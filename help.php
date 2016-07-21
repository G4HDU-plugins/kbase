<?php
/*
+---------------------------------------------------------------+
|     /help.php
|     For the KBASE Plugin
|
+---------------------------------------------------------------+
*/
include_lan(e_PLUGIN . "kbase/languages/help/" . e_LANGUAGE . ".php");
$kbase_qry = explode(".", e_QUERY);
$kbase_qry = "?" . $kbase_qry[0];
$kbase_haction = basename($_SERVER['PHP_SELF'], ".php") . $kbase_qry;
// print $kbase_action;
$kbase_text = "<table width='100%' class='fborder'>";
switch ($kbase_haction)
{
    case "admin_config?delparent" :
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H17 . "</b></td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H72 . "</b><br />" . KBASE_H73 . "</td></tr>";
        break;
    case "admin_config.php?mvdn.category" :
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H124 . "</b></td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H124 . "</b><br />" . KBASE_H125 . "</td></tr>";
        break;
    case "admin_config.php?edit.entries?" :
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H124 . "</b></td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H124 . "</b><br />" . KBASE_H125 . "</td></tr>";
        break;
    case "admin_config?" :
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H17 . "</b></td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H18 . "</b><br />" . KBASE_H19 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H20 . "</b><br />" . KBASE_H21 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H22 . "</b><br />" . KBASE_H23 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H24 . "</b><br />" . KBASE_H25 . "</td></tr>";
        break;
    case "admin_config?info" :
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H26 . "</b></td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H27 . "</b><br />" . KBASE_H28 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H29 . "</b><br />" . KBASE_H30 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H31 . "</b><br />" . KBASE_H32 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H33 . "</b><br />" . KBASE_H34 . "</td></tr>";
        break;
    case "admin_config?edit" :
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H39 . "</b></td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H40 . "</b><br />" . KBASE_H41 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H42 . "</b><br />" . KBASE_H43 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H44 . "</b><br />" . KBASE_H45 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H46 . "</b><br />" . KBASE_H47 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H48 . "</b><br />" . KBASE_H49 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H114 . "</b><br />" . KBASE_H115 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H116 . "</b><br />" . KBASE_H117 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H118 . "</b><br />" . KBASE_H119 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H120 . "</b><br />" . KBASE_H121 . "</td></tr>";
        break;
    case "admin_config?sub" :
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H58 . "</b></td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H50 . "</b><br />" . KBASE_H51 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H52 . "</b><br />" . KBASE_H53 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H54 . "</b><br />" . KBASE_H55 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H56 . "</b><br />" . KBASE_H57 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H102 . "</b><br />" . KBASE_H103 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H104 . "</b><br />" . KBASE_H105 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H106 . "</b><br />" . KBASE_H107 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H108 . "</b><br />" . KBASE_H109 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H110 . "</b><br />" . KBASE_H111 . "</td></tr>";
        break;
    case "admin_config?add" :
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H59 . "</b></td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H60 . "</b><br />" . KBASE_H61 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H62 . "</b><br />" . KBASE_H63 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H64 . "</b><br />" . KBASE_H65 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H66 . "</b><br />" . KBASE_H67 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H112 . "</b><br />" . KBASE_H113 . "</td></tr>";
        break;
    case "admin_settings?" :
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H1 . "</b></td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H92 . "</b><br />" . KBASE_H93 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H68 . "</b><br />" . KBASE_H69 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H2 . "</b><br />" . KBASE_H3 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H4 . "</b><br />" . KBASE_H5 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H6 . "</b><br />" . KBASE_H7 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H8 . "</b><br />" . KBASE_H9 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H80 . "</b><br />" . KBASE_H81 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H96 . "</b><br />" . KBASE_H97 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H94 . "</b><br />" . KBASE_H95 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H70 . "</b><br />" . KBASE_H71 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H98 . "</b><br />" . KBASE_H99 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H84 . "</b><br />" . KBASE_H85 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H82 . "</b><br />" . KBASE_H83 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H88 . "</b><br />" . KBASE_H89 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H90 . "</b><br />" . KBASE_H91 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H78 . "</b><br />" . KBASE_H79 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H74 . "</b><br />" . KBASE_H75 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H76 . "</b><br />" . KBASE_H77 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H100 . "</b><br />" . KBASE_H101 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H86 . "</b><br />" . KBASE_H87 . "</td></tr>";
        break;
    case "admin_approve?" :

        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H11 . "</b></td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H12 . "</b><br />" . KBASE_H13 . "</td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H14 . "</b><br />" . KBASE_H15 . "</td></tr>";
        break;
    case "admin_readme?" :
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H126 . "</b></td></tr>";
        $kbase_text .= "<tr><td class='forumheader3'><b>" . KBASE_H126 . "</b><br />" . KBASE_H127 . "</td></tr>";
        break;
    default:
     $kbase_text .= "<tr><td class='forumheader3'><b>&nbsp;</b></td></tr>";
}
$kbase_text .= "</table>";
$ns->tablerender(KBASE_H10, $kbase_text);
?>