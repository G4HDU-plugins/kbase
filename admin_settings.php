<?php
/*
+---------------------------------------------------------------+
|		KBASE Plugin
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
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

require_once(e_ADMIN . "auth.php");
if (!defined('ADMIN_WIDTH'))
{
    define(ADMIN_WIDTH, "width:100%;");
}

require_once("includes/kbase_class.php");
if (!is_object($kbase_obj))
{
    $kbase_obj = new KBASE;
}
$kbase_fp = fopen(e_PLUGIN . "kbase/graphics/index.htm", "w+");
if ($kbase_fp === false)
{
    $kbase_message .= KBASE_ADLAN_143 . " ";
}
fclose($kbase_fp);
if (isset($_POST['updatesettings']))
{
    $success = 0;
    if (intval($_POST['kbase_seo']) == 1 )
    {
        // changed to we are using seo
        $kbase_newht = $kbase_obj->regen_htaccess('on');
    } elseif (intval($_POST['kbase_seo']) == 0 )
    {
        // changed to not using seo
        $kbase_newht = $kbase_obj->regen_htaccess('off');
    }
    if ($kbase_newht > 0)
    {
        $kbase_errmsg = explode('~', KBASE_PREFLAN_13);
        $kbase_message .= KBASE_PREFLAN_12 . ' ' . $kbase_errmsg[$kbase_newht] . " ({$kbase_newht}) <br />";
    }
    $KBASE_PREF['kbase_user'] = intval($_POST['kbase_user']);
    $KBASE_PREF['add_kbase'] = intval($_POST['add_kbase']);
    $KBASE_PREF['kbase_approve'] = intval($_POST['kbase_approve']);
    $KBASE_PREF['kbase_defcomments'] = intval($_POST['kbase_defcomments']);
    $KBASE_PREF['kbase_allowcomments'] = intval($_POST['kbase_allowcomments']);
    $KBASE_PREF['kbase_ownedit'] = intval($_POST['kbase_ownedit']);
    $KBASE_PREF['kbase_super'] = intval($_POST['kbase_super']);
    $KBASE_PREF['kbase_description'] = $tp->toDB($_POST['kbase_description']);
    $KBASE_PREF['kbase_keywords'] = $tp->toDB($_POST['kbase_keywords']);
    $KBASE_PREF['kbase_showposter'] = intval($_POST['kbase_showposter']);
    $KBASE_PREF['kbase_picupload'] = intval($_POST['kbase_picupload']);
    $KBASE_PREF['kbase_sendto'] = intval($_POST['kbase_sendto']);
    $KBASE_PREF['kbase_title'] = $tp->toDB($_POST['kbase_title']);
    $KBASE_PREF['kbase_mtext'] = $tp->toDB($_POST['kbase_mtext']);
    $KBASE_PREF['kbase_perpage'] = intval($_POST['kbase_perpage']);
    $KBASE_PREF['kbase_showrand'] = intval($_POST['kbase_showrand']);
    $KBASE_PREF['kbase_top'] = intval($_POST['kbase_top']);
    $KBASE_PREF['kbase_log'] = intval($_POST['kbase_log']);
    $KBASE_PREF['kbase_stats'] = intval($_POST['kbase_stats']);
    $KBASE_PREF['kbase_rating'] = intval($_POST['kbase_rating']);
    $KBASE_PREF['kbase_simple'] = intval($_POST['kbase_simple']);
    $KBASE_PREF['kbase_seo'] = intval($_POST['kbase_seo']);
    $kbase_obj->save_prefs();

    $kbase_message .= KBASE_PREFLAN_01;
    // we've made changes so clear the cache to get rid of old info
    $kbase_obj->kbase_cache_clear();
}

$kbase_text = "
<div style='text-align:center'>
	<form method='post' action='" . e_SELF . "' id='kbasepref'>
		<table style='" . ADMIN_WIDTH . "' class='fborder'>
			<tr>
				<td class='fcaption' colspan='2'>" . KBASE_PREFLAN_10 . "</td>
			</tr>
			<tr>
				<td class='forumheader2' colspan='2'><strong>$kbase_message</strong>&nbsp;</td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_128 . ":</td>
        		<td style='width:70%' class='forumheader3'>" . r_userclass("kbase_user", $KBASE_PREF['kbase_user'], "off", "admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
       		 <tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_110 . ":</td>
	        	<td style='width:70%' class='forumheader3'>" . r_userclass("kbase_super", $KBASE_PREF['kbase_super'], "off", "nobody,admin,main,member,classes") . "</td>
			</tr>
    	    <tr>
	    	    <td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_PREFLAN_02 . ":</td>
	    	    <td style='width:70%' class='forumheader3'>" . r_userclass("add_kbase", $KBASE_PREF['add_kbase'], "off", "admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_PREFLAN_09 . ":</td>
        		<td style='width:70%' class='forumheader3'>" . r_userclass("kbase_approve", $KBASE_PREF['kbase_approve'], "off", "admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
	        <tr>
    	    	<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_96 . ":</td>
        		<td style='width:70%' class='forumheader3'>" . r_userclass("kbase_allowcomments", $KBASE_PREF['kbase_allowcomments'], "off", "admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_95 . ":</td>
        		<td style='width:70%' class='forumheader3'>" . r_userclass("kbase_defcomments", $KBASE_PREF['kbase_defcomments'], "off", "admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_121 . ":</td>
        		<td style='width:70%' class='forumheader3'>" . r_userclass("kbase_sendto", $KBASE_PREF['kbase_sendto'], "off", "admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_133 . ":</td>
        		<td style='width:70%' class='forumheader3'>" . r_userclass("kbase_stats", $KBASE_PREF['kbase_stats'], "off", "admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
	        <tr>
    	    	<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_139 . ":</td>
        		<td style='width:70%' class='forumheader3'>";

$kbase_sub = "<select class='tbox' name='kbase_simple' >";
$kbase_sub .= "<option value='0' " . ($KBASE_PREF['kbase_simple'] == 0?"selected='selected'":"") . ">" . KBASE_ADLAN_141 . "</option>";
$sql->db_Select("kbase_info", "kbase_info_id,kbase_info_title", "where kbase_info_parent > 0", "nowhere", false);
while ($kbase_row = $sql->db_Fetch())
{
    $kbase_sub .= "<option value='" . $kbase_row['kbase_info_id'] . "' " . ($KBASE_PREF['kbase_simple'] == $kbase_row['kbase_info_id']?"selected='selected'":"") . ">" . $tp->toFORM($kbase_row['kbase_info_title']) . "</option>";
} // while
$kbase_sub .= "</select>";
$kbase_text .= $kbase_sub;
$kbase_text .= "
				</td>
			</tr>
	        <tr>
    	    	<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_144 . ":</td>
        		<td style='width:70%' class='forumheader3'>
					<input type='checkbox' class='tbox' value='1' name='kbase_seo' " . ($KBASE_PREF['kbase_seo'] > 0?"checked='checked'":"") . " /><br /><i>".KBASE_PREFLAN_14."</i>
				</td>
			</tr>
	        <tr>
    	    	<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_135 . ":</td>
        		<td style='width:70%' class='forumheader3'><input type='checkbox' class='tbox' value='1' name='kbase_rating' " . ($KBASE_PREF['kbase_rating'] > 0?"checked='checked'":"") . " /></td>
			</tr>
	        <tr>
    	    	<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_109 . ":</td>
        		<td style='width:70%' class='forumheader3'><input type='checkbox' class='tbox' value='1' name='kbase_ownedit' " . ($KBASE_PREF['kbase_ownedit'] > 0?"checked='checked'":"") . " /></td>
			</tr>
        	<tr>
       			<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_134 . ":</td>
        		<td style='width:70%' class='forumheader3'><input type='checkbox' class='tbox' value='1' name='kbase_log' " . ($KBASE_PREF['kbase_log'] > 0?"checked='checked'":"") . " /></td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_117 . ":</td>
        		<td style='width:70%' class='forumheader3'><input type='checkbox' class='tbox' value='1' name='kbase_showposter' " . ($KBASE_PREF['kbase_showposter'] > 0?"checked='checked'":"") . " /></td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_118 . ":</td>
        		<td style='width:70%' class='forumheader3'><input type='checkbox' class='tbox' value='1' name='kbase_picupload' " . ($KBASE_PREF['kbase_picupload'] > 0?"checked='checked'":"") . " /></td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_125 . ":</td>
        		<td style='width:70%' class='forumheader3'><input type='checkbox' class='tbox' value='1' name='kbase_showrand' " . ($KBASE_PREF['kbase_showrand'] > 0?"checked='checked'":"") . " /></td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_126 . ":</td>
        		<td style='width:70%' class='forumheader3'><input type='text' name='kbase_top' style='width:10%' class='tbox' value='" . $tp->toFORM($KBASE_PREF['kbase_top']) . "' /></td>
			</tr>
			<tr>
		        <td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_122 . ":</td>
		        <td style='width:70%' class='forumheader3'><input type='text' name='kbase_title' style='width:80%' class='tbox' value='" . $tp->toFORM($KBASE_PREF['kbase_title']) . "' /></td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_113 . ":</td>
        		<td style='width:70%' class='forumheader3'><input type='text' name='kbase_description' style='width:80%' class='tbox' value='" . $tp->toFORM($KBASE_PREF['kbase_description']) . "' />
					<br /><span class='smalltext'>" . KBASE_ADLAN_114 . "</span>
				</td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_115 . ":</td>
        		<td style='width:70%' class='forumheader3'><input type='text' name='kbase_keywords' style='width:80%' class='tbox' value='" . $tp->toFORM($KBASE_PREF['kbase_keywords']) . "' />
					<br /><span class='smalltext'>" . KBASE_ADLAN_116 . "</span>
				</td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_123 . ":</td>
        		<td style='width:70%' class='forumheader3'><input type='text' name='kbase_mtext' style='width:80%' class='tbox' value='" . $tp->toFORM($KBASE_PREF['kbase_mtext']) . "' /></td>
			</tr>
        	<tr>
        		<td style='width:30%; vertical-align:center' class='forumheader3'>" . KBASE_ADLAN_124 . ":</td>
        		<td style='width:70%' class='forumheader3'><input type='text' name='kbase_perpage' style='width:10%' class='tbox' value='" . $tp->toFORM($KBASE_PREF['kbase_perpage']) . "' /></td>
			</tr>
			<tr>
				<td colspan='2'  style='text-align:left;vertical-align:top' class='fcaption'>
    				<input class='button' type='submit' name='updatesettings' value='" . KBASE_PREFLAN_03 . "' />
    			</td>
    		</tr>
    	</table>
	</form>
</div>";

$ns->tablerender(KBASE_ADLAN_88, $kbase_text);

require_once(e_ADMIN . "footer.php");
