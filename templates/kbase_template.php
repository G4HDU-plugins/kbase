<?php
if (!defined("USER_WIDTH"))
{
    define(USER_WIDTH, "width:100%");
}
// *
// * KBASEs list. This part is the front opening screen of the KBASE Plugin
// *
if (!isset($KBASE_LISTPARENT_HEADER))
{
    // Start of KBASE list
    // This is sent first
    $KBASE_LISTPARENT_HEADER = "
	<div class='fborder' style='" . USER_WIDTH . "'>
		<table style='" . USER_WIDTH . "'>
        	<tr>
            	<td colspan='2' style='text-align:left;' class='fcaption'>" . KBASE_ADLAN_76 . "</td>
                <td class='fcaption' style='text-align:center;'>" . KBASELAN_42 . "</td>
            </tr>
			<tr>
				<td colspan='3' style='text-align:left;' class='forumheader3'>{KBASE_NEW}&nbsp;</td>
			</tr>
			<tr>
				<td class='forumheader3' style='text-align:center;'  colspan='3'>{KBASE_LOGO}</td>
			</tr>";
}
// *
if (!isset($KBASE_LISTPARENT_TABLE))
{
    // The main heading for the KBASEs list
    // This displays the parent category
    $KBASE_LISTPARENT_TABLE = "
			<tr>
				<td class='fcaption' style='text-align:center;' >{KBASE_PARENT_CATICON}</td>
				<td class='fcaption' colspan='2' >{KBASE_PARENT_TITLE}<br /><span class='smalltext'>{KBASE_PARENT_ABOUT}</span></td>
			</tr>";
}
// *
if (!isset($KBASE_LISTPARENT_DETAIL))
{
    // Display each of the categories in this parent category
    $KBASE_LISTPARENT_DETAIL = "
			<tr>
				<td style='width:5%;text-align:center;' class='forumheader2'>{KBASE_PARENT_ICON=link}</td>
				<td style='width:75%' class='forumheader2'>{KBASE_PARENT_KBASE}<br /><span class='smalltext'>{KBASE_PARENT_ABOUT}</span></td>
				<td style='width:20%; text-align:center' class='forumheader2'>{KBASE_PARENT_COUNT}</td>
			</tr>";
}
// *
if (!isset($KBASE_LISTPARENT_FOOTER))
{
    // Footer for the page
    $KBASE_LISTPARENT_FOOTER .= "
			<tr>
				<td class='forumheader3' colspan='3' >{KBASE_STATS_LINK}&nbsp;</td>
			</tr>
    		<tr>
				<td class='fcaption' colspan='3' >&nbsp;</td>
			</tr>
		</table>
	</div>";
}
// *
// * This is the list of KBASEs in a particular category
// *
if (!isset($KBASE_LIST_HEADER))
{
    // Start of KBASE list
    // This is sent first
    $KBASE_LIST_HEADER = "
	<div class='fborder' style='" . USER_WIDTH . "'>
		<table style='" . USER_WIDTH . "'>
        	<tr>
				<td class='fcaption' colspan='2'>{KBASE_PARENT_CATICON} {KBASE_CAPTION}</td>
			</tr>
			<tr>
				<td class='forumheader3' colspan='2'>{KBASE_UPDIR}&nbsp;{KBASE_NEW} <strong>{KBASE_MESSAGE}</strong></td>
			</tr>
						<tr>
				<td class='forumheader3' style='text-align:center;'  colspan='2'>{KBASE_LOGO}</td>
			</tr>";
}
// *
if (!isset($KBASE_LIST_DETAIL))
{
    // The main heading for the KBASEs list
    // displayed second
    $KBASE_LIST_DETAIL = "
			<tr>
				<td style='width:5%;text-align:center;' class='forumheader2'>{KBASE_LIST_ICON}</td>
				<td style='width:95%' class='forumheader2'>{KBASE_LIST_KBASE}";
    if ($kbase_obj->kbase_rating)
    {
        $KBASE_LIST_DETAIL .= "<br /><span class='smalltext'>{KBASE_LIST_RATE}</span>";
    }
    $KBASE_LIST_DETAIL .= "
				</td>
			</tr>";
}
// *
if (!isset($KBASE_LIST_FOOTER))
{
    // The list of KBASEs number of KBASEs is set in admin config
    $KBASE_LIST_FOOTER = "
    		<tr>
				<td class='forumheader3' colspan='2' >{KBASE_NEXTPREV} {KBASE_STATS_LINK}&nbsp;</td>
			</tr>
			<tr>
    			<td class='fcaption' colspan='2' >&nbsp;</td>
    		</tr>
		</table>
	</div>";
}
// *
// * Displays the individual KBASE
// *
if (!isset($KBASE_ITEM_HEADER))
{
    // Start of KBASE list
    // This is sent first
    $KBASE_ITEM_HEADER = "
	<div class='fborder' style='" . USER_WIDTH . "'>
		<table style='" . USER_WIDTH . "'>
        	<tr>
				<td class='fcaption' colspan='2'>{KBASE_ITEM_CAPTION}</td>
			</tr>
			<tr>
				<td class='forumheader3' colspan='2'>{KBASE_UPDIR}&nbsp;&nbsp;{KBASE_ITEM_EDIT}&nbsp;&nbsp;{KBASE_ITEM_PRINT}&nbsp;&nbsp;{KBASE_EMAIL}&nbsp;{KBASE_PDF}</td>
			</tr>
			";
}
// *
if (!isset($KBASE_ITEM_DETAIL))
{
    // The main heading for the KBASEs list
    // displayed second
    $KBASE_ITEM_DETAIL = "
			<tr>
				<td class='forumheader3' style='text-align:center;vertical-align:top;width:20%'>{KBASE_ITEM_QICON}</td>
        		<td class='forumheader3' style='vertical-align:top'>{KBASE_ITEM_QUESTION}</td>
			</tr>
        	<tr>
				<td class='forumheader3' style='text-align:center;vertical-align:top;width:20%'>{KBASE_ITEM_AICON}</td>
        		<td class='forumheader3'>{KBASE_ITEM_ANSWER}</td>
			</tr>
			<tr>
				<td class='forumheader3' >" . KBASELAN_96 . "</td>
				<td class='forumheader3' >{KBASE_ITEM_VIEWS} (" . KBASELAN_106 . " {KBASE_ITEM_UNIQUE})</td>
			</tr>";
    if ($kbase_obj->kbase_rating)
    {
        $KBASE_ITEM_DETAIL .= "
			<tr>
				<td class='forumheader3' >" . KBASELAN_119 . "</td>
				<td class='forumheader3' >{KBASE_ITEM_RATE}</td>
			</tr>";
    }
    // Only show poster details if set in admin config
    if ($kbase_obj->kbase_showposter)
    {
        $KBASE_ITEM_DETAIL .= "
			<tr>
				<td class='forumheader3' >" . KBASELAN_76 . "</td>
				<td class='forumheader3' >{KBASE_ITEM_AUTHOR}</td>
			</tr>
			<tr>
				<td class='forumheader3' >" . KBASELAN_65 . "</td>
				<td class='forumheader3' >".KBASELAN_151." {KBASE_ITEM_POSTED=long}<br />".KBASELAN_152." {KBASE_ITEM_UPDATED=long}</td>
			</tr>";
    }
}
// *
if (!isset($KBASE_ITEM_FOOTER))
{
    // The list of KBASEs number of KBASEs is set in admin config
    $KBASE_ITEM_FOOTER = "
		    <tr>
				<td class='fcaption' colspan='2'>&nbsp;</td>
			</tr>
		</table>
	</div>";
}
// *
// *
// *
if (!isset($KBASE_EDIT_HEADER))
{
    // Start of KBASE list
    // This is sent first
    $KBASE_EDIT_HEADER = "
	<div class='fborder' style='" . USER_WIDTH . "'>
		<table style='" . USER_WIDTH . "'>
        	<tr>
				<td class='fcaption' colspan='2'>{KBASE_EDIT_CAPTION}</td>
			</tr>
			<tr>
				<td class='forumheader3' colspan='2'>{KBASE_UPDIR} <strong>{KBASE_MESSAGE}</strong></td>
			</tr>";
}
// *
if (!isset($KBASE_EDIT_DETAIL))
{
    // The main heading for the KBASEs list
    // displayed second
    $KBASE_EDIT_DETAIL = "
    		<tr>
				<td class='forumheader3' style='width:20%;'>" . KBASE_ADLAN_78 . "</td>
        		<td class='forumheader3' style='width:80%;'>{KBASE_EDIT_CATEGORY}</td>
        	</tr>";
    if (!USER)
    {
        $KBASE_EDIT_DETAIL .= "
			<tr>
				<td class='forumheader3' style='width:20%;'>" . KBASE_ADLAN_131 . "</td>
        		<td class='forumheader3' style='width:80%;'>{KBASE_EDIT_USER}</td>
        	</tr>";
    }
    $KBASE_EDIT_DETAIL .= "
			<tr>
        		<td class='forumheader3' style='width:20%;'>" . KBASE_ADLAN_51 . "</td>
        		<td class='forumheader3' style='width:80%;'>{KBASE_EDIT_QUESTION}</td>
			</tr>
			<tr>
        		<td class='forumheader3' style='width:20%;vertical-align:top;'>" . KBASE_ADLAN_60 . "</td>
        		<td class='forumheader3' style='width:80%;'>{KBASE_EDIT_ANSWER}</td>
			</tr>";
    // If pictures can be uploaded
    if ($kbase_obj->kbase_picupload)
    {
        $KBASE_EDIT_DETAIL .= "
			<tr>
				<td class='forumheader3' >" . KBASELAN_81 . "</td>
				<td class='forumheader3' >{KBASE_EDIT_PICTURE}</td>
			</tr>";
    }
    if (check_class($pref['kbase_allowcomments']))
    {
        $KBASE_EDIT_DETAIL .= "
			<tr>
          		<td class='forumheader3'  style=\"width:20%; vertical-align:top\">" . KBASE_ADLAN_52 . "</td>
		  		<td class='forumheader3' >{KBASE_EDIT_COMMENTS}</td>
			</tr>";
    }
    $KBASE_EDIT_DETAIL .= "
    		<tr>
				<td class='forumheader3' colspan='2'>{KBASE_EDIT_SUBMIT}</td>
        	</tr>";
}
// *
if (!isset($KBASE_EDIT_FOOTER))
{
    // The list of KBASEs number of KBASEs is set in admin config
    $KBASE_EDIT_FOOTER = "
		</table>
	</div>";
}

if (!isset($KBASE_NO_ACCESS))
{
    // Not permitted access
    $KBASE_NO_ACCESS = "
<table class='fborder' style='" . USER_WIDTH . "' >
   	<tr>
   		<td class='fcaption'>" . KBASELAN_KBASE . "</td>
   	</tr>
   	<tr>
   		<td class='forumheader3'>" . KBASELAN_148 . "</td>
   	</tr>
</table>";
}

?>