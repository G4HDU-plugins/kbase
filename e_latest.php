<?php
if (!defined('e107_INIT')) { exit; }
require_once("includes/kbase_class.php");
if (!is_object($kbase_obj)) {
	$kbase_obj=new KBASE;
}
$kbase_approve = $sql->db_Count('kbase', '(*)', "WHERE kbase_approved='0'");
$text .= "<div style='padding-bottom: 2px;'>
<img src='" . e_PLUGIN . "kbase/images/icon_16.gif' style='width: 16px; height: 16px; vertical-align: bottom;border:0;' alt='' /> ";
if (empty($kbase_approve))
{
    $kbase_approve = 0;
}
if ($kbase_approve)
{
    $text .= "<a href='" . e_PLUGIN . "kbase/admin_approve.php'>" . KBASE_ADLAN_94 . ": " . $kbase_approve . "</a>";
}
else
{
    $text .= KBASE_ADLAN_94 . ': ' . $kbase_approve;
}

$text .= '</div>';

?>