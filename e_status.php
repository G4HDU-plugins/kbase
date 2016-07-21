<?php
include_lan(e_PLUGIN . "kbase/languages/admin/" . e_LANGUAGE . ".php");
$kbase_posts = $sql->db_Count("kbase", "(*)");
if (empty($kbase_posts))
{
    $kbase_posts = 0;
}
$text .= "<div style='padding-bottom: 2px;'><img src='" . e_PLUGIN . "kbase/images/icon_16.gif' style='width: 16px; height: 16px; vertical-align: bottom;border:0;' alt='' /> " . KBASE_ADLAN_93 . ": " . $kbase_posts . "</div>";

?>