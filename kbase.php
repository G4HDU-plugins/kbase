<?php

require_once("../../class2.php");
if (!defined('e107_INIT'))
{
    exit;
}
require_once(e_HANDLER . "comment_class.php");
require_once("includes/kbase_class.php");
if (!is_object($kbase_obj))
{
    $kbase_obj = new KBASE;
}
if (is_readable(THEME . "kbase_template.php"))
{
    define(KBASE_THEME, THEME . "kbase_template.php");
}
else
{
    define(KBASE_THEME, e_PLUGIN . "kbase/templates/kbase_template.php");
}
require_once(e_PLUGIN . "kbase/includes/kbase_shortcodes.php");
$e_wysiwyg = "data";
if (!$kbase_obj->kbase_read)
{
    require_once(KBASE_THEME);
    $kbase_text .= $tp->parseTemplate($KBASE_NO_ACCESS, true, $kbase_shortcodes);
    require_once(HEADERF);
    $kbase_obj->tablerender(KBASELAN_KBASE , $kbase_text, 'kbase');
    require_once(FOOTERF);
    exit;
}
if (!is_object($kbase_rater))
{
    $kbase_rater = new rater;
}
if (e_QUERY)
{
    $kbase_qs = explode(".", e_QUERY);
    if (is_numeric($kbase_qs[0]))
    {
        $kbase_from = intval($kbase_qs[0]);
        $kbase_action = $kbase_qs[1];
        $id = intval($kbase_qs[2]);
        $idx = intval($kbase_qs[3]);
    }
    else
    {
        $kbase_from = 0;
        $kbase_action = $kbase_qs[0];
        $id = intval($kbase_qs[1]);
        $idx = intval($kbase_qs[2]);
    }
}
else
{
    $kbase_action = $_POST['action'];
    $id = intval($_POST['id']);
    $kbase_from = intval($_POST['kbase_from']);

    $idx = intval($_POST['idx']);
}
// *
// *
// experimental work on auto meta tag for description and keyworms
// *
// *
if ($idx > 0 && $kbase_action == 'cat' && $sql->db_Select('kbase', 'kbase_question,kbase_answer', 'where kbase_id=' . $idx, 'nowhere', false))
{
    extract($sql->db_Fetch());

    define("e_PAGETITLE", KBASELAN_150 . $idx . ' : ' . $tp->toFORM($kbase_question));
    $search = array ('@<script[^>]*?>.*?</script>@si', // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
        '@{[\/\!]*?[^{}]*?}@si', // Strip out e107 tags
        '@&(quot|#34);@i', // Replace HTML entities
        '@&(amp|#38);@i',
        '@&(lt|#60);@i',
        '@&(gt|#62);@i',
        '@&(nbsp|#160);@i',
        '@&(iexcl|#161);@i',
        '@&(cent|#162);@i',
        '@&(pound|#163);@i',
        '@&(copy|#169);@i',
        '@&#(\d+);@e',
        '|[[\/\!]*?[^\[\]]*?]|si', // strip out bbcode tags
        '@([\t\r\n])[\s]+@', // Strip out white space
        );
    // evaluate as php
    $replace = array (' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ');
    $kbase_nanswer = preg_replace($search, $replace, strip_tags($kbase_answer));
    $kbase_nanswer = str_replace(',,', '', $kbase_nanswer);

    $kbase_keynames = explode(' ', $kbase_nanswer);
    $kbase_cleaned = array_filter($kbase_keynames, kbase_clean);
    $kbase_keylist = array_unique($kbase_cleaned);
    $kbase_keywords = implode(',', $kbase_keylist);
    define("META_KEYWORDS", $kbase_keywords);

    define("META_DESCRIPTION", KBASELAN_149 . $kbase_question . ' : ' . $tp->text_truncate($kbase_nanswer, 100,'[...]'));

}
else
{
    if (!empty($KBASE_PREF['kbase_description']))
    {
        define("META_DESCRIPTION", $KBASE_PREF['kbase_description']);
    }
    if (!empty($KBASE_PREF['kbase_keywords']))
    {
        define("META_KEYWORDS", $KBASE_PREF['kbase_keywords']);
    }

    if (!empty($KBASE_PREF['kbase_title']))
    {
        define("e_PAGETITLE", $KBASE_PREF['kbase_title']);
    }
}

require_once(HEADERF);
$kbase_cobj = new comment;
// print $kbase_action;
if (isset($_POST['commentsubmit']))
{
    $tmp = explode(".", e_QUERY);
    $kbase_kbase_from = intval($tmp[0]);
    $kbase_action = "cat";
    $idz = intval($tmp[2]);
    $idx = intval($tmp[3]);
    $pid = (isset($_POST['pid']) ? $_POST['pid'] : 0);
    $kbase_cobj->enter_comment($_POST['author_name'], $_POST['comment'], "kbasefb", $idx, $pid, $_POST['subject']);
    $kbase_obj->kbase_cache_clear();
}
// Upload pictures
if (isset($_FILES['kbase_gfile']['name'][0]))
{
    unset($_POST['kbase_submit']);

    require_once(e_HANDLER . "upload_handler.php");
    $kbase_fileoptions = array('file_mask' => 'jpg,gif,png', 'file_array_name' => 'kbase_gfile', 'overwrite' => true);
    $kbase_upresult = process_uploaded_files(e_PLUGIN . "kbase/graphics", false, $kbase_fileoptions);
    if (isset($_POST['submitupload']))
    {
        $kbase_action = 'reedit';
    }
    else
    {
        $kbase_action = 'save';
    }
}
// print $kbase_action;
if ($kbase_action == "save")
{
    // print "here";
    if ($idx > 0)
    {
        // an existing record so we save it
        if ($_POST['kbase_question'] != "" && $_POST['data'] != "")
        {
            if ($sql->db_Select("kbase", "kbase_id", "where kbase_id<>'" . intval($idx) . "' and kbase_question ='" . $tp->toDB($_POST['kbase_question']) . "' and kbase_parent=" . intval($_POST['kbase_parent']) . " ", "nowhere", false))
            {
                $message = KBASELAN_105;
                $kbase_action = "reedit";
            }
            else
            {
                $kbase_question = $tp->toDB($_POST['kbase_question']);
                $data = $tp->toDB($_POST['data']);
                $sql->db_Update("kbase", "kbase_parent='" . intval($_POST['kbase_parent']) . "', kbase_question ='$kbase_question', kbase_answer='$data', kbase_comment='" . intval($_POST['kbase_comment']) . "',kbase_updated='" . time() . "'  WHERE kbase_id='" . intval($idx) . "' ", false, "KBASE", "KBASE Updated " . intval($idx));
                $message = KBASE_ADLAN_29;
                $kbase_action = "cat";
                // unset($kbase_question, $data);
            }
        }
        else
        {
            $message = KBASE_ADLAN_30;
            $kbase_action = "reedit";
        }
    }
    else
    {
        // a new record so we create it
        $message = "";
        $kbase_action = "cat";
        if ($sql->db_Select("kbase", "kbase_id", "where kbase_question ='" . $tp->toDB($_POST['kbase_question']) . "' and kbase_parent=" . intval($_POST['kbase_parent']) . " ", "nowhere", false))
        {
            $message = KBASELAN_105;
            $kbase_action = "reedit";
        }
        else
        {
            if ($_POST['kbase_question'] != "" && $_POST['data'] != "")
            {
                if (USER)
                {
                    $kbase_poster = USERID . "." . USERNAME;
                }
                else
                {
                    $kbase_poster = "0." . $_POST['kbase_submittedby'];
                }
                $kbase_appr = ($kbase_obj->kbase_autoapprove?1:0);
                $kbase_question = $tp->toDB($_POST['kbase_question']);
                $data = $tp->toDB($_POST['data']);
                $count = ($sql->db_Count("kbase", "(*)", "WHERE kbase_parent='" . $_POST['kbase_parent'] . "' ") + 1);
                $kbase_newid = $sql->db_Insert("kbase", " 0, '" . $_POST['kbase_parent'] . "', '$kbase_question', '$data', '" . intval($_POST['kbase_comment']) . "', '" . time() . "', '" . $kbase_poster . "', '" . $count . "','" . $kbase_appr . "',0,'',0, ".time(), false, "KBASE", "KBASE Submitted");
                if ($kbase_obj->kbase_autoapprove)
                {
                    $message = KBASE_ADLAN_32;
                }
                else
                {
                    $message = KBASE_ADLAN_132;
                }
                $edata_sn = array("user" => USERNAME, "itemtitle" => $_POST['kbase_question'], "catid" => intval($kbase_newid));
                $e_event->trigger("kbasepost", $edata_sn);
                // unset($kbase_question, $data);
                $kbase_action = 'cat';
            }
            else
            {
                $message = KBASE_ADLAN_30;
                $kbase_action = "reedit";
            }
        }
        $id = $_POST['kbase_parent'];
    }
    $kbase_obj->kbase_cache_clear();
}
// Actions +++++++++++++++++++++++++++++
if ($kbase_obj->kbase_simple && ($kbase_action == "" || $kbase_action == "main"))
{
    $kbase_action = "cat";
    // $sql->db_Select("kbase_info", "*", "where kbase_info_parent>0 and find_in_set(kbase_info_class,'" . USERCLASS_LIST . "') order by kbase_info_id", "nowhere", false);
    // $kbase_row = $sql->db_Fetch();
    // unset($idx);
    // $id = $kbase_row['kbase_info_id'];
    $id = $kbase_obj->kbase_simpleid;
}
if ($kbase_action == "" || $kbase_action == "main")
{
    require_once(KBASE_THEME);
    $kbase_text = $kbase_obj->show_parents($kbase_action, $sub_action, $id, $kbase_from, $amount);
    $kbase_obj->tablerender(KBASELAN_KBASE, $kbase_text, 'kbase');
}

if ($kbase_action == "cat")
{
    require_once(KBASE_THEME);
    if ($idx > 0)
    {
        $kbase_text = $kbase_obj->view_kbase($idx) ;
    }
    else
    {
        $kbase_text = $kbase_obj->view_list($kbase_action, $id) ;
    }
    $kbase_obj->tablerender(KBASELAN_KBASE, $kbase_text, 'kbase');
}

if (($kbase_action == "edit" || $kbase_action == "new" || $kbase_action == "reedit") && (($kbase_obj->kbase_ownedit && USER) || $kbase_obj->kbase_add))
{
    require_once(KBASE_THEME);
    $kbase_text = $kbase_obj->add_kbase($kbase_action, $id, $idx);
    $kbase_obj->tablerender(KBASELAN_67, $kbase_text, 'kbase');
}

require_once(FOOTERF);

function kbase_clean($value)
{
    return strlen($value) > 3;
}
