<?php
if (!defined('e107_INIT')) { exit; }
include_lan(e_PLUGIN . "kbase/languages/" . e_LANGUAGE . ".php");
$config_category = KBASELAN_88;
$config_events = array('kbasepost' => KBASELAN_87);

if (!function_exists('notify_kbasepost'))
{
    function notify_kbasepost($kbasedata)
    {
        global $nt;
        $message = "<strong>" . KBASELAN_89 . ': </strong>' . $kbasedata['user'] . '<br />';
        $message .= "<strong>" . KBASELAN_90 . ':</strong> ' . $kbasedata['itemtitle'] . "<br /><br />" . KBASELAN_91 ;
        $message .= " " . KBASELAN_92 . " " . $kbasedata['catid'] . "<br /><br />";
        $nt->send('kbasepost', KBASELAN_88, $message);
    }
}

?>