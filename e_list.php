<?php
if (!defined('e107_INIT'))
{
    exit;
}
global $pref;
if (!$pref['plug_installed']['kbase'])
{
    return;
}
require_once("includes/kbase_class.php");
global $kbase_obj;
if (!is_object($kbase_obj))
{
    $kbase_obj = new KBASE;
}
$LIST_CAPTION = $arr[0];
$LIST_DISPLAYSTYLE = ($arr[2] ? "" : "none");

$todayarray = getdate();
$current_day = $todayarray['mday'];
$current_month = $todayarray['mon'];
$current_year = $todayarray['year'];
$current = mktime(0, 0, 0, $current_month, $current_day, $current_year);

if ($mode == "new_page" || $mode == "new_menu")
{
    $lvisit = $this->getlvisit();
    $qry = "find_in_set(kbase_info_class,'" . USERCLASS_LIST . "') and kbase_approved > 0 and kbase_datestamp>" . $lvisit ;
}
else
{
    $qry = "find_in_set(kbase_info_class,'" . USERCLASS_LIST . "') and kbase_approved > 0 and kbase_id>0 " ;
}

$bullet = $this->getBullet($arr[6], $mode);

$qry = "
	SELECT r.*, c.kbase_info_title,c.kbase_info_id
	FROM #kbase AS r
	LEFT JOIN #kbase_info AS c ON r.kbase_parent = c.kbase_info_id
	WHERE " . $qry . "
	ORDER BY r.kbase_datestamp ASC LIMIT 0," . $arr[7];
if (!$eclassf_items = $sql->db_Select_gen($qry))
{
    $LIST_DATA = KBASELAN_95;
}
else
{
    while ($row = $sql->db_Fetch())
    {
        $tmp = explode(".", $row['kbase_author'], 2);
        if ($tmp[0] == "0")
        {
            $AUTHOR = $tmp[1];
        } elseif (is_numeric($tmp[0]) && $tmp[0] != "0")
        {
            $AUTHOR = (USER ? "<a href='" . e_BASE . "user.php?id." . $tmp[0] . "'>" . $tmp[1] . "</a>" : $tmp[1]);
        }
        else
        {
            $AUTHOR = "";
        }
        $rowheading = $this->parse_heading($tp->toHTML($row['kbase_question'], false), $mode);
        $ICON = $bullet;
        $HEADING = "<a href='" . e_PLUGIN . "kbase/kbase.php?0.cat." . $row['kbase_info_id'] . "." . $row['kbase_id'] . "' title='" . $tp->toHTML($row['kbase_question'], false) . "'>" . $rowheading . "</a>";
        $CATEGORY = $row['kbase_info_title'];
        $DATE = ($arr[5] ? ($row['kbase_datestamp'] ? $this->getListDate($row['kbase_datestamp'], $mode) : "") : "");
        $INFO = "";
        $LIST_DATA[$mode][] = array($ICON, $HEADING, $AUTHOR, $CATEGORY, $DATE, $INFO);
    }
}

?>