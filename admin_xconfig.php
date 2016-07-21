<?php
/*
+---------------------------------------------------------------+
|        e107 website system
|
|
|
+---------------------------------------------------------------+
*/
require_once("../../class2.php");
if (!defined('e107_INIT'))
{
    exit;
}
if (!getperms("P"))
{
    header("location:" . e_BASE . "index.php");
}

$e_wysiwyg = "data";
$eplug_css = 'includes/js/ajax.css';
require_once(e_ADMIN . "auth.php");
if (!defined('ADMIN_WIDTH'))
{
    define(ADMIN_WIDTH, "width:100%;");
}
global $KBASE_PREF;
require_once("includes/kbase_class.php");
if (!is_object($kbase_obj))
{
    $kbase_obj = new KBASE;
}

$kbasepost = new kbasepost;
// $action
// $subaction
// $category
// $kbase
if (e_QUERY)
{
    list($action, $sub_action, $id, $from, $parent, $order) = explode(".", e_QUERY);
    unset($tmp);
}
else
{
    $action = "main";
    $sub_action = "main";
    $id = 0;
    $from = 0;
    $parent = 0;
}
$from = ($from ? $from : 0);
$amount = 50;
// _POST Handling....
if (isset($_POST['cancel']))
{
    $message = KBASE_ADLAN_28;
}
if (isset($_FILES['kbase_gfile']['name'][0]))
{
    require_once(e_HANDLER . "upload_handler.php");
    $kbase_fileoptions = array('file_mask' => 'jpg,gif,png', 'file_array_name' => 'kbase_gfile', 'overwrite' => true);
    $kbase_upresult = process_uploaded_files(e_PLUGIN . "kbase/graphics", false, $kbase_fileoptions);
}
if (isset($_POST['kbase_author']))
{
    // we have an author to find
    $sql->db_Select('user', 'concat(user_id,".",user_name) as postername', 'where user_name="' . $tp->toDB($_POST['kbase_author']) . '"', 'nowhere', false);
    extract($sql->db_Fetch()) ;
}
$kbase_poster = $postername;

if (isset($_POST['kbase_update_entry']))
{
    if ($_POST['kbase_question'] != "" || $_POST['data'] != "")
    {
        // kbase_pcomments kbase_prating
        if ($KBASE_PREF['kbase_log'] > 0)
        {
            $kbase_plugin = KBASE_LOG_01;
            $kbase_action = KBASE_LOG_02 . " " . intval($_POST['kbase_id']);
        }
        else
        {
            $kbase_plugin = "";
            $kbase_action = "";
        }
        if (intval($_POST['kbase_pcomments']) == 1)
        {
            $sql->db_Delete("comments", "comment_item_id=" . intval($_POST['kbase_id']) . " and comment_type='kbasefb'", false, $kbase_plugin, $kbase_action);
        }
        if ($KBASE_PREF['kbase_log'] > 0)
        {
            $kbase_plugin = KBASE_LOG_01;
            $kbase_action = KBASE_LOG_03 . " " . intval($_POST['kbase_id']);
        }
        else
        {
            $kbase_plugin = "";
            $kbase_action = "";
        }
        if (intval($_POST['kbase_prating']) == 1)
        {
            $sql->db_Delete("rate", "rate_itemid=" . intval($_POST['kbase_id']) . " and rate_table='kbase'", false, $kbase_plugin, $kbase_action);
        }

        $kbaseneworder = intval(intval($_POST['kbaseoldorder']));
        $kbaseoldorder = $kbaseneworder;
        $kbase_oldparent = intval(intval($_POST['kbaseoldparent']));
        // if parent <> oldparent then it is being moved so sort out the order
        if ($kbase_oldparent <> intval(intval($_POST['kbase_parent'])))
        {
            $sql->db_Update("kbase", "kbase_order=kbase_order-1 WHERE kbase_parent= '$kbase_oldparent' and kbase_order > '$kbaseoldorder' ");
            $kbaseneworder = $sql->db_Count("kbase", "(*)", "WHERE kbase_parent='" . intval($_POST['kbase_parent']) . "' ") + 1;
        }

        $kbase_question = $tp->toDB($_POST['kbase_question']);
        $data = $tp->toDB($_POST['data']);
        if ($KBASE_PREF['kbase_log'] > 0)
        {
            $kbase_plugin = KBASE_LOG_01;
            $kbase_action = KBASE_LOG_04 . " " . intval($_POST['kbase_id']);
        }
        else
        {
            $kbase_plugin = "";
            $kbase_action = "";
        }
        $sql->db_Update("kbase", "kbase_updated=".time().",kbase_parent='" . intval($_POST['kbase_parent']) . "', kbase_question ='$kbase_question', kbase_answer='" . $tp->toDB($data) . "', kbase_comment='" . intval($_POST['kbase_comment']) . "', kbase_author='" . $tp->toDB($kbase_poster) . "', kbase_order='$kbaseneworder', kbase_approved='" . intval($_POST['kbase_approved']) . "' WHERE kbase_id='" . intval($_POST['kbase_id']) . "' ", false, $kbase_plugin , $kbase_action);
        if ($_POST['kbase_pviews'] == 1)
        {
            if ($KBASE_PREF['kbase_log'] > 0)
            {
                $kbase_plugin = KBASE_LOG_01;
                $kbase_action = KBASE_LOG_05 . " " . intval($_POST['kbase_id']);
            }
            else
            {
                $kbase_plugin = "";
                $kbase_action = "";
            }
            $sql->db_Update("kbase", "kbase_views='0', kbase_viewer ='', kbase_unique='0' WHERE kbase_id='" . intval($_POST['kbase_id']) . "' ", false, $kbase_plugin , $kbase_action);
        }
        $message = KBASE_ADLAN_29;
        unset($kbase_question, $data);
        $kbase_obj->kbase_cache_clear();
    }
    else
    {
        $message = KBASE_ADLAN_30;
    }
    $action = "edit";
    $sub_action = "entries";
}

if (isset($_POST['kbase_insert_entry']))
{
    $message = "-";
    if ($_POST['kbase_question'] != "" || $_POST['data'] != "")
    {
        // $kbase_poster = $_POST['kbase_author'];
        $kbase_question = $tp->toDB($_POST['kbase_question']);
        $data = $tp->toDB($_POST['data']);
        $count = ($sql->db_Count("kbase", "(*)", "WHERE kbase_parent='" . intval($_POST['kbase_parent']) . "' ") + 1);
        if ($KBASE_PREF['kbase_log'] > 0)
        {
            $kbase_plugin = KBASE_LOG_01;
            $kbase_action = KBASE_LOG_06;
        }
        else
        {
            $kbase_plugin = "";
            $kbase_action = "";
        }

        $kbase_insid = $sql->db_Insert("kbase", " 0, '" . intval($_POST['kbase_parent']) . "', '" . $kbase_question . "', '" . $data . "', '" . intval($_POST['kbase_comment']) . "', '" . time() . "', '" . $tp->toDB($kbase_poster) . "', '" . $count . "','" . intval($_POST['kbase_approved']) . "',0,'',0,".time(), false, $kbase_plugin, $kbase_action);
        $message = KBASE_ADLAN_32;
        $kbase_obj->kbase_cache_clear();
        unset($kbase_question, $data);
    }
    else
    {
        $message = KBASE_ADLAN_30;
    }
    // $from =$_POST['kbase_parent'];
    $from = $kbase_insid;
    $id = intval($_POST['kbase_parent']);
    $action = "edit";
    $sub_action = "entries";
}

if (isset($_POST['parent_edit']))
{
    $sql->db_Select("kbase", "*", "kbase_id='" . intval($_POST['kbase_parent']) . "' ");
    list($p_id, $parent, $p_question, $p_answer, $p_comment) = $sql->db_Fetch();
    $edit_parent = true;
}
else
{
    $edit_parent = false;
}
// Insert Parent
if (isset($_POST['kbase_info_add']))
{
    if ($_POST['kbase_info_title'] != "" || $_POST['kbase_info_about'] != "")
    {
        $kbase_info_title = $tp->toDB($_POST['kbase_info_title']);
        $kbase_info_about = $tp->toDB($_POST['kbase_info_about']);
        $count = ($sql->db_Count("kbase_info", "(*)", "WHERE kbase_info_parent='" . intval($_POST['kbase_info_parent']) . "' ") + 1);
        if ($KBASE_PREF['kbase_log'] > 0)
        {
            $kbase_plugin = KBASE_LOG_01;
            $kbase_action = KBASE_LOG_07;
        }
        else
        {
            $kbase_plugin = "";
            $kbase_action = "";
        }

        $sql->db_Insert("kbase_info", " 0, '" . $kbase_info_title . "', '" . $kbase_info_about . "', '" . intval($_POST['kbase_info_parent']) . "', '" . intval($_POST['kbase_info_class']) . "', '" . $count . "' , '" . $tp->toDB($_POST['kbase_info_icon']) . "'", false, $kbase_plugin, $kbase_action);
        $message = KBASE_ADLAN_85;
        unset($kbase_title, $kbase_about);
        $kbase_obj->kbase_cache_clear();
    }
    else
    {
        $message = KBASE_ADLAN_30;
    }
    $action = "main";
}
// Edit Parent.
if (isset($_POST['kbase_info_edit']))
{
    if ($_POST['kbase_info_title'] != "" || $_POST['kbase_info_about'] != "")
    {
        $kbase_info_title = $tp->toDB($_POST['kbase_info_title']);
        $kbase_info_about = $tp->toDB($_POST['kbase_info_about']);
        if ($KBASE_PREF['kbase_log'] > 0)
        {
            $kbase_plugin = KBASE_LOG_01;
            $kbase_action = KBASE_LOG_08 . " " . intval($_POST['kbase_info_id']);
        }
        else
        {
            $kbase_plugin = "";
            $kbase_action = "";
        }
        $sql->db_Update("kbase_info", "kbase_info_title ='$kbase_info_title', kbase_info_about='$kbase_info_about', kbase_info_parent='" . intval($_POST['kbase_info_parent']) . "', kbase_info_class='" . intval($_POST['kbase_info_class']) . "', kbase_info_icon='" . $tp->toDB($_POST['kbase_info_icon']) . "' WHERE kbase_info_id='" . intval($_POST['kbase_info_id']) . "' ", false, $kbase_plugin, $kbase_action);
        $message = KBASE_ADLAN_33;
        unset($kbase_info_title, $kbase_info_about);

        $kbase_obj->kbase_cache_clear();
    }
    else
    {
        $message = KBASE_ADLAN_30; // You left a field blank
    }
    $action = "main";
}

if (isset($message))
{
    $ns->tablerender("", "<div style='text-align:center'><b>" . $message . "</b></div>");
}
// ============------=++++++++++++++++++++++++++++++++++++++++++++++
// ACTIONS ===========
// ============-----------
if (isset($_POST['confirm_del_parent']))
{
    $id = intval($_POST['parid']);
    $kbase_parent = intval($_POST['oldkbaseinfoparent']);
    $kbase_info_oldorder = intval($_POST['oldkbaseinfoorder']);
    $kbase_info_oldid = intval($_POST['oldkbaseinfoid']);
    $sql->db_Update("kbase_info", "kbase_info_order=kbase_info_order-1 WHERE kbase_info_order>'$kbase_info_oldorder' AND kbase_info_parent= '$kbase_parent'");
    $sql->db_Delete("kbase_info", "kbase_info_id='$kbase_info_oldid' ", false, "KBASE", "KBASE Category Deleted " . $kbase_info_oldid);
    $message = KBASE_ADLAN_74;
    $kbase_obj->kbase_cache_clear();
    $ns->tablerender("", "<div style='text-align:center'><b>" . $message . "</b></div>");
}

if ($action == "delparent")
{
    $kbasepost->del_parent($sub_action, $id);
}

if ($action == "main")
{
    $kbasepost->show_existing_parents($action, $sub_action, $id, $from, $amount);
}

if ($action == "info")
{
    $kbasepost->edit_parent_info($action, $sub_action, $id, $from, $amount);
}

if ($action == "add")
{
    $kbasepost->edit_parent_info($action, $sub_action, "", $from, $amount);
}

if ($action == "edit")
{
    $kbasepost->edit_entries($action, $sub_action, $id, $from, $amount);
}

if ($action == "sub")
{
    $kbasepost->edit_entries($action, $sub_action, $id, $from, $amount);
}

if ($action == "mvup")
{
    if ($order > 1)
    {
        // print "from $from sub $sub_action id $id<br>";
        $sql->db_Update("kbase_info", "kbase_info_order=kbase_info_order+1 WHERE kbase_info_order='" . ($order-1) . "' AND kbase_info_parent= '" . intval($parent) . "'");
        $sql->db_Update("kbase_info", "kbase_info_order=kbase_info_order-1 WHERE kbase_info_id='" . intval($id) . "' AND kbase_info_parent= '" . intval($parent) . "'");
        $kbase_obj->kbase_cache_clear();
    }
    $action = "main";
    $sub_action = "";
    $kbasepost->show_existing_parents($action, $sub_action, $id, $from, $amount);
}

if ($action == "mvdn")
{
    // get the largest order
    $sql->db_Select("kbase_info", "max(kbase_info_order) as tops", "kbase_info_parent = " . intval($parent));
    $kbase_tops = $sql->db_Fetch();
    $kbase_max = $kbase_tops[0];
    if ($order < $kbase_max)
    {
        $sql->db_Update("kbase_info", "kbase_info_order=kbase_info_order-1 WHERE kbase_info_order='" . ($order + 1) . "' AND kbase_info_parent= '" . intval($parent) . "'");
        $sql->db_Update("kbase_info", "kbase_info_order=kbase_info_order+1 WHERE kbase_info_id='" . intval($id) . "' AND kbase_info_parent= '" . intval($parent) . "'");
    }
    $action = "main";
    $sub_action = "";
    $kbasepost->show_existing_parents($action, $sub_action, $id, $from, $amount);
    $kbase_obj->kbase_cache_clear();
}

if ($action == "entup")
{
    // $order = $from;
    if ($order > 1)
    {
        $sql->db_Update("kbase", "kbase_order=kbase_order+1 WHERE kbase_order='" . ($order-1) . "' AND kbase_parent= '" . intval($parent) . "'");
        $sql->db_Update("kbase", "kbase_order=kbase_order-1 WHERE kbase_id='" . intval($from) . "' AND kbase_parent= '" . intval($parent) . "'");
    }
    $action = "edit";
    $sub_action = "entries";
    $kbasepost->edit_entries($action, $sub_action, $parent, $from, $amount);
    $kbase_obj->kbase_cache_clear();
}

if ($action == "entdn")
{
    // $order = $from;
    $sql->db_Select("kbase", "max(kbase_order) as tops", "kbase_parent = " . intval($parent) . "");
    $kbase_tops = $sql->db_Fetch();
    $kbase_max = $kbase_tops[0];
    if ($order < $kbase_max)
    {
        $sql->db_Update("kbase", "kbase_order=kbase_order-1 WHERE kbase_order='" . ($order + 1) . "' AND kbase_parent= '" . intval($parent) . "'");
        $sql->db_Update("kbase", "kbase_order=kbase_order+1 WHERE kbase_id='" . intval($from) . "' AND kbase_parent= '" . intval($parent) . "'");
    }
    $action = "edit";
    $sub_action = "entries";
    $kbasepost->edit_entries($action, $sub_action, $parent, $from, $amount);
    $kbase_obj->kbase_cache_clear();
}

if ($action == "delentry")
{
    $kbasepost->del_entry($action, $sub_action, $id, $from, $parent, $order);
    $kbase_obj->kbase_cache_clear();
}

if (isset($_POST['confirm_del_entry']))
{
    // move the order up
    // print "p $parent o $order ";
    $sql->db_Update("kbase", "kbase_order=kbase_order - 1 where kbase_order>$order and kbase_parent=" . intval($parent) . "");
    $sql->db_Delete("kbase", " kbase_id='" . intval($from) . "' ", false, "KBASE", "KBASE Deleted " . intval($from));
    // Delete any comments for this kbase
    $sql->db_Delete("comments", " comment_type='kbasefb' and comment_item_id='" . intval($from) . "'");
    $message = KBASE_ADLAN_74;
    $ns->tablerender("", "<span style='text-align:center'><b>" . $message . "</b></span>");
    $action = "edit";
    $sub_action = "entries";
    $kbasepost->edit_entries($action, $sub_action, $id , $from, $amount);
    $kbase_obj->kbase_cache_clear();
}
require_once(e_ADMIN . "footer.php");
// end Main Program
// ++++++++++++ CLASS // FUNCTIONS ++++++++++++++++++++++
class kbasepost
{
    // ********************************************************
    // Delete Entry
    // ********************************************************
    function del_entry($action, $sub_action, $id, $from, $parent, $order)
    {
        global $sql, $ns, $tp;
        $sql->db_Select("kbase", "*", "kbase_id='" . intval($from) . "' ");
        list($del_id, $delp_id, $del_question) = $sql->db_Fetch();
        $kbase_text = "
<form method='post' action='" . e_SELF . "?confirm.delentry.$id.$from.$parent.$order'>
	<div style='text-align:center'>
        " . KBASE_ADLAN_23 . "<br /><b> ('" . $tp->toHTML($del_question, false, "no_make_clickable no_replace emotes_off") . "') </b><br />" . KBASE_ADLAN_24 . "
		<br /><br />
		<input class='button' type='submit' name='cancel' value='" . KBASE_ADLAN_25 . "' />
		<input class='button' type='submit' name='confirm_del_entry' value='" . KBASE_ADLAN_26 . "' />
		<input type='hidden' name='id' value='" . intval($id) . "' />
	</div>
</form>";
        $ns->tablerender(KBASE_ADLAN_27, $kbase_text);
        // exit;
    }

    function del_parent($sub_action, $id)
    {
        global $sql, $ns, $tp;
        $kbase_kbases = $sql->db_Count("kbase", "(*)", "where kbase_parent='" . intval($id) . "'");
        $kbase_cats = $sql->db_Count("kbase_info", "(*)", "where kbase_info_parent='" . intval($id) . "'");
        if ($kbase_kbases > 0 || $kbase_cats > 0)
        {
            $sql->db_Select("kbase_info", "*", "kbase_info_id='" . intval($id) . "' ");
            $row = $sql->db_Fetch();
            $kbase_text .= "
<div class='fborder' style='text-align:left'>
        " . KBASE_ADLAN_111 . " " . $tp->toHTML($row['kbase_info_title'], false, "no_make_clickable no_replace emotes_off") . " " . KBASE_ADLAN_112 . "
</div>";
        }
        else
        {
            $sql->db_Select("kbase_info", "*", "kbase_info_id='" . $id . "' ");
            $row = $sql->db_Fetch();
            $kbase_text = "
<form method='post' action='" . e_SELF . "'>
	<div style='text-align:center'>
		<input type='hidden' name='oldkbaseinfoorder' value='" . intval($row['kbase_info_order']) . "' />
		<input type='hidden' name='oldkbaseinfoparent' value='" . intval($row['kbase_info_parent']) . "' />
		<input type='hidden' name='oldkbaseinfoid' value='" . intval($row['kbase_info_id']) . "' />
        " . KBASE_ADLAN_23 . "<br /><b> ('" . $row['kbase_info_title'] . "') </b><br />" . KBASE_ADLAN_24 . "
		<br /><br />
			<input class='button' type='submit' name='cancel' value='" . KBASE_ADLAN_25 . "' />
			<input class='button' type='submit' name='confirm_del_parent' value='" . KBASE_ADLAN_26 . "' />
			<input type='hidden' name='parid' value='" . intval($id) . "' />
	</div>
</form>";
        }
        $ns->tablerender(KBASE_ADLAN_27, $kbase_text);
        // exit;
    }

    function show_existing_parents($action, $sub_action, $id, $from, $amount)
    {
        // ##### Display scrolling list of existing KBASE items ---------------------------------------------------------------------------------------------------------
        global $sql2, $sql, $ns, $tp;
        if (!is_object($sql3))
        {
            $sql3 = new db;
        }
        $kbase_text = "
<div style='text-align:center'>
<div style='border : solid 1px #000; padding : 0px; width : auto; height : 320px; overflow : auto; '>";
        if ($sql->db_Select("kbase_info", "*", "where kbase_info_parent='0' ORDER BY kbase_info_order ASC", "nowhere", false))
        {
            $kbase_text .= "
	<table class='fborder' style='" . ADMIN_WIDTH . "'>
    	<tr>
			<td colspan='2' style='text-align:left; width:55%' class='fcaption'>" . KBASE_ADLAN_76 . "</td>
            <td style='text-align:center; width:12%' class='fcaption'>" . KBASE_ADLAN_80 . "</td>
            <td colspan='2' style='width:20%; text-align:center' class='fcaption'>" . KBASE_ADLAN_81 . "</td>
        </tr>";
            while ($row = $sql->db_Fetch())
            {
                extract($row);
                $kbase_text .= "
		<tr>
        	<td colspan='3' style='width:65%' class='forumheader'>" . ($kbase_info_title ? $tp->toHTML($kbase_info_title, false, "no_make_clickable no_replace emotes_off") : "[" . NWSLAN_42 . "]") . "</td>
            <td style='width:5%; text-align:center;margin-left:auto;margin-right:auto' class='forumheader'>
            	<a href='" . e_SELF . "?mvup.category.$kbase_info_id.0.$kbase_info_parent.$kbase_info_order'><img title='" . KBASE_ADLAN_91 . "' src='./images/up.png' style='padding:2px;border:0px' alt='" . KBASE_ADLAN_91 . "' /></a><br />
                <a href='" . e_SELF . "?mvdn.category.$kbase_info_id.0.$kbase_info_parent.$kbase_info_order'><img title='" . KBASE_ADLAN_92 . "' src='./images/down.png' style='padding:2px;border:0px' alt='" . KBASE_ADLAN_92 . "' /></a>
            </td>
            <td style='width:25%; text-align:right' class='forumheader'>
            	<input type='button' class='button' id='main_info_{$kbase_info_id}' name='main_info_{$kbase_info_id}' onclick=\"document.location='" . e_SELF . "?info.edit.$kbase_info_id.$from'\" value='" . KBASE_ADLAN_71 . "' />
            	<input type='button' class='button' id='main_delete_{$kbase_info_id}' name='main_delete_{$kbase_info_id}' onclick=\"document.location='" . e_SELF . "?delparent.entries.$kbase_info_id.$from'\" value='" . KBASE_ADLAN_50 . "' />
            </td>
        </tr>";
                $subparents = $sql2->db_Select("kbase_info", "*", "kbase_info_parent='" . $kbase_info_id . "' ORDER BY kbase_info_order ASC");
                if (!$subparents)
                {
                    $kbase_text .= "
		<tr>
			<td colspan='5' style='text-align:center' class='forumheader3'>" . KBASE_ADLAN_75 . "</td>
		</tr>";
                }
                else
                {
                    while ($row = $sql2->db_Fetch())
                    {
                        extract($row);
                        $kbase_text .= "
		<tr>
        	<td style='width:5%;vertical-align:middle' class='forumheader3'><img src='" . e_PLUGIN . "kbase/images/kbase.png' alt='' /></td>
            <td style='width:53%' class='forumheader3'><a href='" . e_PLUGIN . "kbase/kbase.php?cat.$kbase_info_id'>" . ($kbase_info_title ?$tp->toHTML($kbase_info_title, false, "no_make_clickable no_replace emotes_off") : "[" . NWSLAN_42 . "]") . "</a></td>
            <td style='width:12%; text-align:center' class='forumheader3'>";
                        $cnt = $sql3->db_Count("kbase", "(*)", "WHERE kbase_parent = '" . intval($kbase_info_id) . "' ");
                        $kbase_text .= $cnt;
                        $kbase_text .= "
            </td>
            <td style='width:5%; text-align:center;margin-left:auto;margin-right:auto' class='forumheader3'>
            	<a href='" . e_SELF . "?mvup.category.$kbase_info_id.0.$kbase_info_parent.$kbase_info_order'><img title='" . KBASE_ADLAN_91 . "' src='./images/up.png' style='padding:2px;border:0px' alt='" . KBASE_ADLAN_91 . "' /></a><br />
                <a href='" . e_SELF . "?mvdn.category.$kbase_info_id.0.$kbase_info_parent.$kbase_info_order'><img title='" . KBASE_ADLAN_92 . "' src='./images/down.png' style='padding:2px;border:0px' alt='" . KBASE_ADLAN_92 . "' /></a>
            </td>
            <td style='width:25%; text-align:right' class='forumheader3'>
	        	<input type='button' class='button' id='main_edit_{$kbase_info_id}' name='main_edit_{$kbase_info_id}' onclick=\"document.location='" . e_SELF . "?edit.entries.$kbase_info_id.$from'\" value='" . KBASE_ADLAN_142 . "' />
	        	<input type='button' class='button' id='main_info_{$kbase_info_id}' name='main_info_{$kbase_info_id}' onclick=\"document.location='" . e_SELF . "?info.edit.$kbase_info_id.$from'\" value='" . KBASE_ADLAN_71 . "' />
	        	<input type='button' class='button' id='main_delete_{$kbase_info_id}' name='main_delete_{$kbase_info_id}' onclick=\"document.location='" . e_SELF . "?delparent.entries.$kbase_info_id.$from'\" value='" . KBASE_ADLAN_50 . "' />
            </td>
		</tr>";
                    }
                }
            }
            $kbase_text .= "
	</table>";
        }
        else
        {
            $kbase_text .= "
	<div style='text-align:center'>" . KBASE_ADLAN_86 . "<br />" . KBASE_ADLAN_87 . "</div>";
        }
        $kbase_text .= "
</div>
</div>";
        $ns->tablerender("KBASE", $kbase_text);
    }
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    function edit_parent_info($action, $sub_action, $id, $from, $amount)
    {
        global $sql, $ns, $tp, $PLUGINS_DIRECTORY;
        if ($id)
        {
            $sql->db_Select("kbase_info", "*", " kbase_info_id = '" . intval($id) . "' ");
            $row = $sql->db_Fetch();
            extract($row);
        }
        $kbase_text = "
<form method='post' action='" . e_SELF . "?" . e_QUERY . "'>
	<table class='fborder' style='" . ADMIN_WIDTH . "' >";
        if ($kbase_edit == true)
        {
            $kbase_text .= "
		<tr>
        	<td colspan='2' class='border'>
        		<span class='caption'>" . KBASE_ADLAN_38 . "</span>
        	</td>
        </tr>";
        }
        else
        {
            $kbase_text .= "
		<tr>
        	<td class='fcaption' colspan='2' >
        		<span class='caption'>" . KBASE_ADLAN_57 . "</span>
        	</td>
        </tr>";
        }

        $kbase_text .= "
		<tr>
        	<td class='forumheader3' style='width:20%'>" . KBASE_ADLAN_79 . "</td>
        	<td class='forumheader3' style='width:80%'>";
        $kbase_text .= "<select class='tbox' name='kbase_info_parent' ><option value='0'>" . KBASE_ADLAN_77 . "</option>";
        $sql->db_Select("kbase_info", "*", "kbase_info_parent='0'");
        while ($par = $sql->db_Fetch())
        {
            $selected = ($kbase_info_parent == $par['kbase_info_id']) ? " selected='selected'" : "";
            $kbase_text .= "<option value='" . $par['kbase_info_id'] . "' $selected>" . $tp->toFORM($par['kbase_info_title']) . "</option>";
        }
        $kbase_text .= " </select>
			</td>
		</tr>
		<tr>
        	<td class='forumheader3' style='width:20%'>" . KBASE_ADLAN_39 . "</td>
        	<td class='forumheader3' style='width:80%'>
        		<input class='tbox' type='text' name='kbase_info_title' size='60' value='" . $tp->toFORM($kbase_info_title) . "'  />
        	</td>
        </tr>
        <tr>
	        <td class='forumheader3' style='width:20%'>" . KBASE_ADLAN_40 . "</td>
    	    <td class='forumheader3' style='width:80%'>
        		<textarea class='tbox' name='kbase_info_about' cols='70' rows='10'>" . $tp->toFORM($kbase_info_about) . "</textarea>
        	</td>
        </tr>
        <tr>
	        <td class='forumheader3' style='width:40%; vertical-align:top'>" . KBASE_ADLAN_73 . "</td>"; // visible to
        $kbase_text .= "
			<td class='forumheader3'>" . r_userclass("kbase_info_class", $kbase_info_class, "off", "public,guest,nobody,member,admin,main,classes") . "</td>
        </tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . KBASE_ADLAN_129 . "</td>
			<td style='width:70%' class='forumheader3'>
				<input class='tbox' type='text' id='kbase_info_icon' name='kbase_info_icon' size='60' value='" . $tp->toFORM($kbase_info_icon) . "' maxlength='200' /><br />";
        require_once(e_HANDLER . "file_class.php");
        $kbase_fl = new e_file;
        $kbase_list = $kbase_fl->get_files(e_PLUGIN . "kbase/images/caticons/", '');
        if ($id)
        {
            $sql->db_Select("kbase_info", "*", " kbase_info_id = '" . intval($id) . "' ");
            $row = $sql->db_Fetch();
            extract($row);
        }
        foreach($kbase_list as $kbase_catlogo)
        {
            $kbase_text .= "<a href=\"javascript:insertext('" . $kbase_catlogo['fname'] . "','kbase_info_icon')\"><img src='" . $kbase_catlogo['path'] . $kbase_catlogo['fname'] . "' style='border-width:1px' alt='' /></a> ";
        }

        $kbase_text .= "
			</td>
		</tr>
    	<tr>
			<td class='fcaption' colspan='2' style='text-align:left'>";
        if ($id)
        {
            $kbase_text .= " <input class='button' type='submit' name='kbase_info_edit' value='" . KBASE_ADLAN_72 . "' />";
        }
        else
        {
            $kbase_text .= " <input class='button' type='submit' name='kbase_info_add' value='" . KBASE_ADLAN_42 . "' />";
        }
        $kbase_text .= "<input type='hidden' name='kbase_info_id' value='" . intval($id) . "' />
			</td>
    	</tr>
    </table>
</form>";
        $ns->tablerender(KBASE_ADLAN_43, $kbase_text);
    } // end of function.
    function edit_entries($action, $sub_action, $id, $from, $amount)
    {
        global $sql, $ns, $tp, $pref, $KBASE_PREF, $PLUGINS_DIRECTORY;
        $id = intval($id);
        $from = intval($from);
        if ($sub_action == "entries")
        {
            $sql->db_Select("kbase_info", "*", "kbase_info_id='" . intval($id) . "'");
            $kbase_row = $sql->db_Fetch();
            $kbase_cat = $kbase_row['kbase_info_title'];
            $kbase_text .= "\n\n<!-- Sub entry -->\n\n
<div style='text-align:left'>
<table class='fborder' style='" . ADMIN_WIDTH . "' >
    <tr>
        <td colspan='2' class='forumheader3' style='width:80%;'>";
            $sql->db_Select("kbase", "*", "kbase_parent='$id' ORDER BY kbase_order ASC");
            $kbase_text .= "
			<div style='border : solid 1px #000; width : 100%; height : 320px; overflow : auto; '>
        		<table class='fborder' style='width:99%; border:0px'>
        			<tr>
        				<td class='fcaption' style='width:70%'>" . KBASE_ADLAN_49 . " <b>" . $tp->toHTML($kbase_cat, false, "no_make_clickable no_replace emotes_off") . "</b></td>
        				<td class='fcaption' style='text-align:center'>" . KBASE_ADLAN_89 . "</td>
        				<td class='fcaption' style='text-align:center'>" . KBASE_ADLAN_90 . "</td>
					</tr>
        ";
            while (list($pkbase_id, $pkbase_parent, $pkbase_question, $pkbase_answer, $pkbase_comment, $pkbase_datestamp, $pkbase_author, $pkbase_order) = $sql->db_Fetch())
            {
                $pkbase_question = substr($pkbase_question, 0, 50) . " ... ";
                $kbase_text .= "
					<tr>
	                	<td style='width:70%' class='forumheader3'>" . ($pkbase_question ? $tp->toHTML($pkbase_question, false, "no_make_clickable no_replace emotes_off") : "[" . NWSLAN_42 . "]") . "</td>
                  		<td style='width:5%; text-align:center' class='forumheader3'>
                    		<a href='" . e_SELF . "?entup.record.$pkbase_parent.$pkbase_id.$pkbase_parent.$pkbase_order'><img title='" . KBASE_ADLAN_91 . "' src='./images/up.png' style='padding:2px;border:0px' alt='" . KBASE_ADLAN_91 . "' /></a><br />
                    		<a href='" . e_SELF . "?entdn.record.$pkbase_parent.$pkbase_id.$pkbase_parent.$pkbase_order'><img title='" . KBASE_ADLAN_92 . "' src='./images/down.png' style='padding:2px;border:0px' alt='" . KBASE_ADLAN_92 . "' /></a>
                    	</td>
                  		<td style='width:30%; text-align:center' class='forumheader3'>
	                  		<input type='button' class='button' id='entry_edit{$pkbase_id}' name='entry_edit{$pkbase_id}' onclick=\"document.location='" . e_SELF . "?edit.record.$id.$pkbase_id.$pkbase_parent.$pkbase_order'\" value='" . KBASE_ADLAN_45 . "' />
    	              		<input type='button' class='button' id='entry_delete{$pkbase_id}' name='entry_delete{$pkbase_id}' onclick=\"document.location='" . e_SELF . "?delentry.record.$id.$pkbase_id.$pkbase_parent.$pkbase_order'\" value='" . KBASE_ADLAN_50 . "' />
                  		</td>
                  </tr>";
            }
            $kbase_text .= "
				</table>
			</div>
		</td>
	</tr>
</table>
</div>";
        }
        else
        {
            if ($from > 0)
            {
                $sql->db_Select("kbase", "*", " kbase_id = '" . intval($from) . "' ");
                $row = $sql->db_Fetch();
                extract($row);
            }
            $data = $kbase_answer;
            $kbase_text .= "


<div style='text-align:left'>
	<form method='post' action='" . e_SELF . "?edit.entries.$id.$from' id='dataform' enctype='multipart/form-data'>
		<div>
			<input type='hidden' name='kbaseoldid' value='$kbase_id' />
			<input type='hidden' name='kbaseoldparent' value='$kbase_parent' />
			<input type='hidden' name='kbaseoldorder' value='$kbase_order' />
		</div>
	    <table class='fborder' style='" . ADMIN_WIDTH . "' >
    		<tr>
        		<td class='fcaption' colspan='2' style='text-align:center'>";
            $kbase_text .= (is_numeric($from)) ? KBASE_ADLAN_45 : KBASE_ADLAN_82;
            $kbase_text .= " " . KBASE_ADLAN_83 . "
				</td>
			</tr>
        	<tr>
        		<td class='forumheader3' style='width:20%'>" . KBASE_ADLAN_78 . "</td>
        		<td class='forumheader3' style='width:80%'>";
            $kbase_text .= "<select class='tbox' name='kbase_parent' >";
            $sql->db_Select("kbase_info", "*", "kbase_info_parent !='0' ");
            while ($prow = $sql->db_Fetch())
            {
                extract($row);
                $selected = $prow['kbase_info_id'] == $id ? " selected='selected'" : "";
                $kbase_text .= "<option value='" . $prow['kbase_info_id'] . "' $selected>" . $tp->toFORM($prow['kbase_info_title']) . "</option>";
            }
            $kbase_text .= " </select>
            	</td>
            </tr>
        	<tr>
        		<td class='forumheader3' style='width:20%'>" . KBASE_ADLAN_51 . "</td>
        		<td class='forumheader3' style='width:80%'>
			        <input class='tbox' type='text' name='kbase_question' style='width:100%' value='" . $tp->toFORM($kbase_question) . "'  />
        		</td>
        	</tr>
	        <tr>
    		    <td class='forumheader3' style='width:20%;vertical-align:top;' >" . KBASE_ADLAN_60 . "</td>
        		<td class='forumheader3' style='width:80%' >
        			<textarea id='data' class='tbox' name='data' style='width:100%' rows='15' cols='30' onselect=\"storeCaret(this);\" onclick=\"storeCaret(this);\" onkeyup=\"storeCaret(this);\">" . $tp->toDB($data) . "</textarea>";
            if (!$pref['wysiwyg'])
            {
                $kbase_text .= "
					<input  type='text' class='helpbox' id='helpb' name='helpb' size='70' style='width:100%'/><br />" . display_help("helpb");
            }
            $kbase_text .= "
				</td>
			</tr>
			<tr>
				<td class='forumheader3' style='width:20%' >" . KBASE_ADLAN_119 . "</td>
				<td class='forumheader3' style='width:80%'>
					<a style='cursor: pointer; cursor: hand' onclick=\"expandit(this);\">" . KBASE_IMGLAN_03 . "</a>
					<div style='display: none;'>
						<div id='up_container' >
							<span id='upline' style='white-space:nowrap'>
								<input class='tbox' type='file' name='kbase_gfile[]' size='70%' />\n
							</span>
						</div>
						<table style='width:100%'>
							<tr>
								<td>
									<input type='button' class='button' value='" . KBASE_IMGLAN_01 . "' onclick=\"duplicateHTML('upline','up_container');\"  />
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr>
        		<td class='forumheader3'  style='width:20%; vertical-align:top'>" . KBASE_ADLAN_52 . "</td>";
            if (!is_numeric($kbase_comment))
            {
                $kbase_comment = $KBASE_PREF['kbase_defcomments'];
            }
            $kbase_text .= "
				<td class='forumheader3' >" . r_userclass("kbase_comment", $kbase_comment, "off", "public,guest,nobody,member,admin,main,classes") . "</td>
            </tr>";
             $kbase_user = explode(".", $kbase_author, 2);
             $kbase_userid = $kbase_user[0];
            $kbase_text .= "

			<tr>
				<td class='forumheader3'>" . KBASE_ADLAN_127 . "</td>
				<td class='forumheader3'><input type='text' id='kbase_username' name='kbase_author' class='tbox' value='" . $tp->toFORM($kbase_user[1]) . "' onkeyup='ajax_showOptions(this,\"\",event)' /></td>
			</tr>
			<tr>
				<td class='forumheader3'>" . KBASE_ADLAN_130 . "</td>
				<td class='forumheader3'>
					<input type='checkbox' class='tbox' style='border:0;' value='1' name='kbase_approved' " . ($kbase_approved > 0?"checked='checked'":"") . " />
				</td>
			</tr>
			<tr>
			 	<td class='forumheader3'>" . KBASE_ADLAN_138 . "</td>
				<td class='forumheader3'>
					<input type='checkbox' class='tbox' style='border:0;' value='1' name='kbase_pviews'  />
				</td>
			</tr>
			<tr>
			 	<td class='forumheader3'>" . KBASE_ADLAN_136 . "</td>
				 <td class='forumheader3'>
					<input type='checkbox' class='tbox' style='border:0;' value='1' name='kbase_pcomments'  />
				</td>
			</tr>
			<tr>
			 	<td class='forumheader3'>" . KBASE_ADLAN_137 . "</td>
				 <td class='forumheader3'>
					<input type='checkbox' class='tbox' style='border:0;' value='1' name='kbase_prating'  />
				</td>
			</tr>
			<tr>
				<td class='forumheader' colspan='2' style='text-align:center'>";
            if ($action == "sub")
            {
                $kbase_text .= "<input class='button' type='submit' name='kbase_insert_entry' value='" . KBASE_ADLAN_54 . "' />";
            }
            else
            {
                $kbase_text .= "<input class='button' type='submit' name='kbase_update_entry' value='" . KBASE_ADLAN_53 . "$kbase_id' />
            	<input type='hidden' name='kbase_id' value='$from' /> ";
            }

            $kbase_text .= "<input type='hidden' name='kbase' value='$kbase' />
        		</td>
        	</tr>
        </table>
	</form>
</div>";
            $sql->db_Select("kbase_info", "*", "kbase_info_id='" . intval($id) . "'");
            $row = $sql->db_Fetch();
            extract($row);
        }
        $kbase_text.="<script type='text/javascript'>
		var ajaxBox_offsetX = 0;
		var ajaxBox_offsetY =0;
		var ajax_list_externalFile = \"" . SITEURL . $PLUGINS_DIRECTORY . "kbase/getusername.php\"; // Path to external file
		var minimumLettersBeforeLookup = 3; // Number of letters entered before a lookup is performed.
	</script>
	<script type='text/javascript' src='".e_PLUGIN."kbase/includes/js/ajax.js' >	</script>
	<script type='text/javascript' src='".e_PLUGIN."kbase/includes/js/ajax-dynamic-list.js' ></script>";
        $ns->tablerender(" <b>$kbase_info_title</b> " . KBASE_ADLAN_88 . "# $kbase_id", $kbase_text);
    }
} // end class.
