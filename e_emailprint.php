<?php

if (!defined('e107_INIT'))
{
    exit;
}
function print_item($id)
{
    global $tp, $KBASE_PREF, $sql;
    require_once("includes/kbase_class.php");
    if (!is_object($kbase_obj))
    {
        $kbase_obj = new KBASE;
    }
    if (e_LANGUAGE != "English" && file_exists(e_PLUGIN . "kbase/languages/" . e_LANGUAGE . ".php"))
    {
        include_once(e_PLUGIN . "kbase/languages/" . e_LANGUAGE . ".php");
    }
    else
    {
        include_once(e_PLUGIN . "kbase/languages/English.php");
    }
    require_once(e_HANDLER . "date_handler.php");
    $con = new convert;
    $kbase_arg = "select * from #kbase left join #kbase_info on kbase_parent=kbase_info_id where kbase_id='" . intval($id) . "' and find_in_set(kbase_info_class,'" . USERCLASS_LIST . "')";
    if ($sql->db_Select_Gen($kbase_arg))
    {
        $row = $sql->db_Fetch();
        extract($row);
        $kbase_question = $tp->toHTML($kbase_question, false);
        $kbase_answer = $tp->toHTML($kbase_answer, true);
        // Check if new format
        $kbase_tmp = explode(".", $kbase_author, 2);
        $kbase_userid = $kbase_tmp[0];
        $kbase_posted = $con->convert_date($kbase_datestamp, "long");
        $text = "
<span style=\"font-size: 12px; color: black; font-family: Tahoma, Verdana, Arial, Helvetica; text-decoration: none\">
	<b>" . KBASELAN_62 . "</b>
	<br /><br /><b>" . KBASELAN_55 . "</b>
	<br />$kbase_question
	<br /><br /><b>" . KBASELAN_56 . "</b>
	<br />$kbase_answer</span><span style=\"font-size: 10px; color: black; font-family: Tahoma, Verdana, Arial, Helvetica; text-decoration: none\">";
        if ($KBASE_PREF['kbase_showposter'] > 0)
        {
            $text .= "<br /><br /><em><b>" . KBASELAN_64 . "</b><br />" . KBASELAN_65 . " " . $kbase_posted . " " . KBASELAN_63 . " " . $kbase_tmp[1] . " " . $kbase_nlr . "</em><br /><br />";
        }
        $text .= "<br />
	<hr />
	" . SITENAME . "
	</span>";
    }
    else
    {
        $text = KBASELAN_66;
    }
    // $text = str_replace("graphics/", e_PLUGIN . "kbase/graphics/", $text);
    // require_once(e_HANDLER . 'bbcode_handler.php');
    // $kbase_bb = new e_bbcode;
    // $text = $kbase_bb->parseBBCodes($text, '');
    return $text;
}

function email_item($id)
{
    global $tp, $sql;
    require_once("includes/kbase_class.php");
    if (!is_object($kbase_obj))
    {
        $kbase_obj = new KBASE;
    }

    $kbase_arg = "select * from #kbase left join #kbase_info on kbase_parent=kbase_info_id where kbase_id='" . intval($id) . "'";
    $sql->db_Select_gen($kbase_arg);
    $row = $sql->db_Fetch();

    $kbase_message = KBASELAN_70 . "\n\n<a href='" . SITEURL . e_PLUGIN . "kbase/kbase.php?0.cat." . $row['kbase_parent'] . "." . $id . "'>" . SITEURL . e_PLUGIN . "kbase/kbase.php?0.cat." . $row['kbase_parent'] . "." . $id . "</a>\n\n";
    $kbase_message .= KBASELAN_71 . "\"" . $tp->toHTML($row['kbase_question']) . "\"\n\n" ;

    return $kbase_message;
}

function print_item_pdf($id)
{
    global $tp, $content_shortcodes;
    global $tp, $KBASE_PREF, $sql;
    require_once("includes/kbase_class.php");
    if (!is_object($kbase_obj))
    {
        $kbase_obj = new KBASE;
    }
    require_once(e_HANDLER . "date_handler.php");
    $con = new convert;
    $kbase_arg = "select * from #kbase left join #kbase_info on kbase_parent=kbase_info_id where kbase_id='" . intval($id) . "' and find_in_set(kbase_info_class,'" . USERCLASS_LIST . "')";
    if ($sql->db_Select_Gen($kbase_arg))
    {
        $row = $sql->db_Fetch();
        extract($row);
        $kbase_question = $tp->toHTML($kbase_question, false);
        $kbase_answer = $tp->toHTML($kbase_answer, true);
        // Check if new format
        $kbase_tmp = explode(".", $kbase_author, 2);
        $kbase_userid = $kbase_tmp[0];

        $kbase_posted = $con->convert_date($kbase_datestamp, "long");
        $text = "
<span style=\"font-size: 12px; color: black; font-family: Tahoma, Verdana, Arial, Helvetica; text-decoration: none\">
	<b>" . KBASELAN_62 . "</b>
	<br /><br /><b>" . KBASELAN_55 . "</b>
	<br />$kbase_question
	<br /><br /><b>" . KBASELAN_56 . "</b>
	<br />$kbase_answer</span><span style=\"font-size: 10px; color: black; font-family: Tahoma, Verdana, Arial, Helvetica; text-decoration: none\">";
        if ($KBASE_PREF['kbase_showposter'] > 0)
        {
            $text .= "<br /><hr><br /><em><b>" . KBASELAN_64 . "</b><br />" . KBASELAN_65 . " " . $kbase_posted . " " . KBASELAN_63 . " " . $kbase_tmp[1] . " " . $kbase_nlr . "</em><br /><br />";
        }
        $text .= "<br />
	<hr />
	" . SITENAME . "
	</span>";
    }
    else
    {
        $text = KBASELAN_66;
    }
    // the following defines are processed in the document properties of the pdf file
    // Do NOT add parser function to the variables, leave them as raw data !
    // as the pdf methods will handle this !
    $text = $text; //define text
    $creator = SITENAME; //define creator
    $author = $kbase_tmp[1]; //define author
    $title = "KBASE" . $id; //define title
    $subject = "Question"; //define subject
    $keywords = "test"; //define keywords

    // define url to use in the header of the pdf file
    $url = SITEURLBASE . e_PLUGIN_ABS . "kbase/kbase.php?0.cat." . $row['kbase_id'];
    // always return an array with the following data:
    return array($text, $creator, $author, $title, $subject, $keywords, $url);
}

?>