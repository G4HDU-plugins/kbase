<?php
if (!defined('e107_INIT')) { exit; }
// search module for kbase.
require_once(e_PLUGIN."kbase/includes/kbase_class.php");
if (!is_object($kbase_obj)) {
	$kbase_obj=new KBASE;
}

$return_fields = 't.kbase_question,t.kbase_answer,t.kbase_datestamp,x.kbase_info_title,t.kbase_id,x.kbase_info_id,x.kbase_info_class';
$search_fields = array('t.kbase_question', 't.kbase_answer', "x.kbase_info_title");
$weights = array('2.0', '2.0', '1.0');
$no_results = LAN_198;
$where = " kbase_approved > 0 and find_in_set(kbase_info_class,'".USERCLASS_LIST."') and ";
$order = array('t.kbase_question' => DESC);
$table = "kbase as t left join #kbase_info as x on kbase_parent=kbase_info_id ";
$ps = $sch->parsesearch($table, $return_fields, $search_fields, $weights, 'search_kbase', $no_results, $where, $order);
$text .= $ps['text'];
$results = $ps['results'];

function search_kbase($row)
{
    global $con,$tp;
    $datestamp = $con->convert_date($row['kbase_datestamp'], "long");
    $title = "-" . $row['kbase_info_title'] . "-";
    $cat_id = $row['kbase_info_id'];
    $link_id = $row['kbase_id'];
    $dept = $row['dept_id'];
    $res['link'] = e_PLUGIN . "kbase/kbase.php?0.cat." . $cat_id . "." . $link_id . "";
    $res['pre_title'] = $title ?KBASELAN_57 . " " : "";
    $res['title'] = $title ? $title : LAN_SEARCH_9;
    $res['summary'] = "-- ".KBASELAN_55 . " " . $tp->toHTML($row['kbase_question'],false) . " -- ".KBASELAN_56 . " " . $tp->toHTML($row['kbase_answer'],false);
    $res['detail'] = KBASELAN_58 . " " . $datestamp . " in " . $row['kbase_info_title'];
    return $res;
}

?>