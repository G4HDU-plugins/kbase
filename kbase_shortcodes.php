<?php
include_once(e_HANDLER . 'shortcode_handler.php');
return;
$kbase_shortcodes = $tp->e_sc->parse_scbatch(__FILE__);
/*

SC_BEGIN KBASE_STATS_LINK
global $kbase_obj;
if ($kbase_obj->kbase_viewstats)
{
	return "<a href='".e_PLUGIN."kbase/kbase_stats.php'>".KBASELAN_135."</a>";
}
else
{
	return "";
}
SC_END

SC_BEGIN KBASE_PDF
global $kbase_id,$sql,$pref;
if ($pref['plug_installed'][pdf])
{
	return "<a href='".e_PLUGIN."pdf/pdf.php?plugin:kbase.$kbase_id'><img src='".e_PLUGIN."pdf/images/pdf_16.png' style='border:0;' alt='pdf' /></a>";
}
else
{
	return "";
}
SC_END

SC_BEGIN KBASE_MESSAGE
global $message;
return $message;
SC_END

SC_BEGIN KBASE_EDIT_USER
return "<input type='text' name='kbase_submittedby' class='tbox' style='width:50%;' />";
SC_END

SC_BEGIN KBASE_LOGO
if (file_exists(e_PLUGIN."kbase/images/kbase_logo.png"))
{
	return "<img src='images/kbase_logo.png' style='border:0;' alt='logo'/>";
}
else
{
	return "";
}
SC_END

SC_BEGIN KBASE_EDIT_CAPTION
global $idx;
return (is_numeric($idx)? KBASELAN_43." ".$idx: KBASELAN_59. KBASELAN_50);
SC_END

SC_BEGIN KBASE_EDIT_SUBMIT
    $retval = "<input class='button' type='submit' name='kbase_submit' value='" . KBASE_ADLAN_53 ." '/>";
    return $retval;
SC_END

SC_BEGIN KBASE_EDIT_COMMENTS
global $kbase_comment,$KBASE_PREF;
require_once(e_HANDLER . "userclass_class.php");
if (!is_numeric($kbase_comment))
{
	$kbase_comment = $KBASE_PREF['kbase_defcomments'];
}
$retval = r_userclass("kbase_comment", $kbase_comment, "", "public,guest,nobody,member,admin,classes");
return $retval;
SC_END

SC_BEGIN KBASE_EDIT_PICTURE
$text ="<a style='cursor: pointer; cursor: hand' onclick='expandit(this);'>".KBASE_IMGLAN_03."</a>
		<div style='display: none;'>
				<div id='up_container' >
					<span id='upline' style='white-space:nowrap'>
						<input class='tbox' type='file' name='kbase_gfile[]' size='70%' />\n
					</span>
				</div>";

			$text .="
				<table style='width:100%'>
					<tr>
						<td><input type='button' class='button' value='".KBASE_IMGLAN_01."' onclick=\"duplicateHTML('upline','up_container');\"  /></td>
						<td><input class='button' type='submit' name='submitupload' value='".KBASE_IMGLAN_02."' /></td>
					</tr>
				</table>
		</div>";
return $text;
SC_END

SC_BEGIN KBASE_EDIT_QUESTION
global $kbase_question,$tp;
return "<input class='tbox' type='text' name='kbase_question' style='width:90%;' value='".$tp->toFORM($kbase_question)."'  />";
SC_END

SC_BEGIN KBASE_EDIT_ANSWER
global $tp,$pref,$KBASE_PREF,$kbase_answer,$e_wysiwyg;
require_once(e_HANDLER . "ren_help.php");
$insertjs = (!$pref['wysiwyg'])?"rows='10' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'":
            "rows='20' style='width:100%' ";
            $kbase_answer = $tp->toForm($kbase_answer);
            $retval .= "<textarea class='tbox' id='data' name='data' cols='80'  style='width:95%' $insertjs>" . (strstr($kbase_answer, "[img]http") ? $kbase_answer : str_replace("[img]../", "[img]", $kbase_answer)) . "</textarea>";
            if (!e_WYSIWYG)
            {
                $retval .= "
				<div style='text-align:left'>" . display_help("helpb","comment"). "</div>";
            }
return $retval;

SC_END

SC_BEGIN KBASE_EDIT_CATEGORY
global $tp,$sql,$kbase_parent,$id,$kbase_action,$kbase_obj;
if ($kbase_action=="new")
{
	$kbase_sl=$id;
}
else
{
	$kbase_sl=$kbase_parent;
}
if ($kbase_obj->kbase_simple)
{
	// in simple mode only give the option for the available category
	$kbase_where="and kbase_info_id = ".$kbase_obj->kbase_simpleid;
}
else
{
	$kbase_where="";
}
$retval = "<select  class='tbox' id='kbase_parent' name='kbase_parent' >";
    $sql->db_Select("kbase_info", "*", "where kbase_info_parent !='0' $kbase_where and find_in_set(kbase_info_class,'" . USERCLASS_LIST . "') ","nowhere",false);
    while ($prow = $sql->db_Fetch())
    {
        extract($row);
        $selected = $prow['kbase_info_id'] == $kbase_sl ? " selected='selected'" : "";
        $retval .= "<option value=\"" . $prow['kbase_info_id'] . "\" $selected >" . $tp->toFORM($prow['kbase_info_title']) . "</option>";
    }
    $retval .= " </select>";

    return $retval;
SC_END

SC_BEGIN KBASE_UPDIR
global $kbase_obj,$kbase_action,$kbase_parent,$kbase_from,$id,$idx,$kbase_simple;
if ($kbase_action=="edit" || $kbase_action=="reedit")
{
	return "<a href='" . e_SELF . "?$kbase_from.cat.$id.$idx.$kbase_parent'><img src='./images/updir.png' alt='" . KBASELAN_46 . "' title='" . KBASELAN_46 . "' style='border:0;' /></a>";
}
if ($idx>0)
{
	return "<a href='" . e_SELF . "?$kbase_from.cat.$kbase_parent'><img src='./images/updir.png' alt='" . KBASELAN_46 . "' title='" . KBASELAN_46 . "' style='border:0;' /></a>";
}
else
{
	if (!$kbase_obj->kbase_simple)
	{
		return "<a href='" . e_SELF . "?0.main.$id'><img src='./images/updir.png' alt='" . KBASELAN_46 . "' title='" . KBASELAN_46 . "' style='border:0;' /></a>";
	}
	else
	{
		return"";
	}
}
SC_END

SC_BEGIN KBASE_ITEM_UNIQUE
global $kbase_unique;
return $kbase_unique;
SC_END

SC_BEGIN KBASE_ITEM_RATE
global $kbase_rate_text;
return $kbase_rate_text;
SC_END

SC_BEGIN KBASE_ITEM_VIEWS
global $kbase_views;
return $kbase_views;
SC_END

SC_BEGIN KBASE_ITEM_POSTED
global $kbase_datestamp;
if(empty($parm))
{
	$parm='short';
}
if($kbase_datestamp>0)
{
require_once(e_HANDLER . "date_handler.php");
$con = new convert;
return $con->convert_date($kbase_datestamp, $parm);
}
else
{
return '';
}
SC_END

SC_BEGIN KBASE_ITEM_UPDATED
global $kbase_updated,$kbase_datestamp;
if(empty($parm))
{
	$parm='short';
}
if($kbase_updated>0)
{
	require_once(e_HANDLER . "date_handler.php");
	$con = new convert;
	return $con->convert_date($kbase_updated, $parm);
}
elseif($kbase_datestamp>0)
{
	require_once(e_HANDLER . "date_handler.php");
	$con = new convert;
	return $con->convert_date($kbase_datestamp, $parm);
}
{
return '';
}
SC_END

SC_BEGIN KBASE_ITEM_AUTHOR
global $kbase_author;
$kbase_tmp = explode(".", $kbase_author,2);
if ($kbase_tmp[0]>0)
{
	return "<a href='../../user.php?id.".$kbase_tmp[0]."' >".$kbase_tmp[1]."</a>";
}
else
{
	return $kbase_tmp[1];
}
SC_END

SC_BEGIN KBASE_ITEM_QUESTION
global $tp, $kbase_question;
return $tp->toHTML($kbase_question, false, "no_make_clickable no_replace emotes_off");
SC_END

SC_BEGIN KBASE_ITEM_ANSWER
global $tp, $kbase_answer;
return $tp->toHTML($kbase_answer, true,"constants body");
SC_END
SC_BEGIN KBASE_ITEM_QICON
return "<img src='" . e_PLUGIN . "kbase/images/q.png' alt='' />";
SC_END

SC_BEGIN KBASE_ITEM_AICON
return"<img src='" . e_PLUGIN . "kbase/images/a.png' alt='' />";
SC_END

SC_BEGIN KBASE_ITEM_PRINT
global $idx;
return "<a href='../../print.php?plugin:kbase.$idx' ><img src='" . e_IMAGE . "generic/" . IMODE . "/printer.png' style='border:0;' title='" . KBASELAN_60 . "' alt='" . KBASELAN_60 . "' /></a>";
SC_END

SC_BEGIN KBASE_ITEM_CAPTION
global $kbase_id;
return "&nbsp;KBASE #" . $kbase_id;
SC_END

SC_BEGIN KBASE_ITEM_EDIT
global $kbase_obj,$kbase_authid, $kbase_from, $kbase_parent, $kbase_id;
if ($kbase_obj->kbase_super || ($kbase_obj->kbase_ownedit && $kbase_authid == USERID))
{
    return "<a href='" . e_SELF . "?$kbase_from.edit.$kbase_parent." . $kbase_id . "'><img src='" . e_IMAGE . "generic/" . IMODE . "/edit.png' style='border:0' alt='" . KBASELAN_43 . "' title='" . KBASELAN_43 . "' /></a>";
}
else
{
    return "";
}
SC_END

SC_BEGIN KBASE_EMAIL
global $kbase_id,$kbase_obj;
if ($kbase_obj->kbase_sendto)
{
    return "<a href='../../email.php?plugin:kbase." . $kbase_id . "'><img src='" . e_IMAGE . "generic/" . IMODE . "/email.png' style='border:0' alt='" . KBASELAN_68 . "' title='" . KBASELAN_68 . "' /></a>";
}
else
{
	return "";
}
SC_END

SC_BEGIN KBASE_NEXTPREV
global $kbase_count, $KBASE_PREF, $kbase_from, $tp, $id;
$kbase_action = "cat.$id.";
$parms = $kbase_count . "," . $KBASE_PREF['kbase_perpage'] . "," . $kbase_from . "," . e_SELF . '?' . "[FROM]." . $kbase_action;

$kbase_nextprev = $tp->parseTemplate("{NEXTPREV={$parms}}");
if (!empty($kbase_nextprev))
{
    $retval = $kbase_nextprev;
}
else
{
    $retval = "";
}
return $retval;

SC_END

SC_BEGIN KBASE_LIST_RATE
global $kbase_view_rating;
return $kbase_view_rating;
SC_END

SC_BEGIN KBASE_CAPTION
global $tp, $kbase_cat_name;
$caption = KBASELAN_51 . ": " .$tp->toHTML($kbase_cat_name,false, "no_make_clickable no_replace emotes_off") . "&nbsp;" ;
return $caption;
SC_END

SC_BEGIN KBASE_LIST_KBASE
global $res, $tp, $kbase_question, $id, $kbase_id, $kbase_from;
if ($res > 0)
{
    return "<a href='" . e_SELF . "?$kbase_from.cat.$id.$kbase_id'>" . $tp->toHTML($kbase_question, false, "no_make_clickable no_replace emotes_off") . "</a>";
}
else
{
    return KBASELAN_103;
}
SC_END

SC_BEGIN KBASE_LIST_ICON
global $res;
if ($res > 0)
{
    return "<img src='" . e_PLUGIN . "kbase/images/kbase.png' style='border:0;' alt='' />";
}
else
{
    return "";
}
SC_END

SC_BEGIN KBASE_NEW
global $id, $KBASE_PREF,$kbase_from;
if (check_class($KBASE_PREF['add_kbase']))
{
    return "<a href=\"". e_PLUGIN ."kbase/kbase.php?$kbase_from.new.{$id}\"><img src='./images/new.gif' style='border:0;' alt='" . KBASELAN_47 . "' title='" . KBASELAN_47 . "' /></a>";
}
else
{
    return "";
}
SC_END

SC_BEGIN KBASE_PARENT_TITLE
global $tp, $kbase_info_title, $kbase_info_about;

return ($kbase_info_title ? $tp->toHTML($kbase_info_title,false, "no_make_clickable no_replace emotes_off") : "[" . KBASELAN_102 . "]") ;

SC_END

SC_BEGIN KBASE_PARENT_CATICON
global $kbase_info_icon,$kbase_info_title,$tp;

if (empty($kbase_info_icon))
{
	$kbase_info_icon="kbase.png";
}
return "<img src='" . e_PLUGIN . "kbase/images/caticons/".$tp->toFORM($kbase_info_icon)."' alt='".$tp->toFORM($kbase_info_title)."' title='".$tp->toFORM($kbase_info_title)."' />";

SC_END

SC_BEGIN KBASE_PARENT_ICON
global $kbase_info_icon,$kbase_info_title,$tp,$kbase_info_id;

if (empty($kbase_info_icon))
{
	$kbase_info_icon="kbase.png";
}
if ($parm=="link")
{
    return "<a href='".e_SELF."?0.cat.$kbase_info_id' ><img src='" . e_PLUGIN . "kbase/images/caticons/".$tp->toFORM($kbase_info_icon)."' style='border:0;' alt='".$tp->toFORM($kbase_info_title)."' title='".$tp->toFORM($kbase_info_title)."' /></a>";
}
else
{
    return "<img src='" . e_PLUGIN . "kbase/images/caticons/".$tp->toFORM($kbase_info_icon)."' style='border:0;' alt='".$tp->toFORM($kbase_info_title)."' title='".$tp->toFORM($kbase_info_title)."' />";
}
SC_END

SC_BEGIN KBASE_PARENT_ABOUT
global $tp, $kbase_from, $kbase_info_id, $kbase_info_title, $kbase_info_about, $subparents;
if ($subparents)
{
    return $tp->toHTML($kbase_info_about,false, "no_make_clickable no_replace emotes_off " );
}
else
{
	return "&nbsp;";
}

SC_END
SC_BEGIN KBASE_PARENT_KBASE
global $tp, $kbase_from, $kbase_info_id, $kbase_info_title, $kbase_info_about, $subparents,$cnt;
if ($subparents>0)
{
	if ($cnt==0)
	{
    	return $tp->toHTML($kbase_info_title,false, "no_make_clickable no_replace emotes_off");
	}
	else
	{
    	return "<a href='" . e_SELF . "?0.cat.$kbase_info_id' title='" . KBASELAN_78 . " " . $cnt . " " . KBASELAN_79 . "'  >" . ($kbase_info_title ? $tp->toHTML($kbase_info_title,false, "no_make_clickable no_replace emotes_off") : "[" . KBASELAN_102 . "]") . "</a>";
	}
}
else
{
    return KBASE_ADLAN_75;
}
SC_END

SC_BEGIN KBASE_PARENT_ABOUT
	global $kbase_info_about;
	return $kbase_info_about;
SC_END

SC_BEGIN KBASE_PARENT_COUNT
global $cnt, $subparents;
if ($subparents)
{
    return "$cnt";
}
else
{
    return "&nbsp;";
}
SC_END
*/
?>