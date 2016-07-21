<?php
if (!defined('e107_INIT'))
{
    exit;
}
include_lan(e_PLUGIN . "kbase/languages/admin/" . e_LANGUAGE . ".php");

$kbase_qry = explode(".", e_QUERY);
$kbase_qry = $kbase_qry[0];

$kbase_action = basename($_SERVER['PHP_SELF'], ".php") . $kbase_qry;

if ($kbase_qry == "info" || $kbase_qry == "edit" || $kbase_qry == "delparent")
{
    $kbase_action = "admin_config";
}

$var['admin_settings']['text'] = KBASE_PREFLAN_08;
$var['admin_settings']['link'] = "admin_settings.php";

$var['admin_config']['text'] = KBASE_PREFLAN_05;
$var['admin_config']['link'] = "admin_config.php";

$var['admin_configadd']['text'] = KBASE_PREFLAN_06;
$var['admin_configadd']['link'] = "admin_config.php?add";

$var['admin_configsub']['text'] = KBASE_PREFLAN_07;
$var['admin_configsub']['link'] = "admin_config.php?sub";

$var['admin_approve']['text'] = KBASE_ADLAN_97;
$var['admin_approve']['link'] = "admin_approve.php";

$var['admin_readme']['text'] = KBASE_ADLAN_140;
$var['admin_readme']['link'] = "admin_readme.php";

$var['admin_vupdate']['text'] = KBASE_PREFLAN_11;
$var['admin_vupdate']['link'] = "admin_vupdate.php";

show_admin_menu(KBASE_PREFLAN_04, $kbase_action, $var);

?>