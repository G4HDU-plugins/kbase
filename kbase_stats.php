<?php
require_once("../../class2.php");
if (!defined('e107_INIT'))
{
    exit;
}
require_once("includes/kbase_class.php");
if (!is_object($kbase_obj)) {
	$kbase_obj=new KBASE;
}
if (!$kbase_obj->kbase_viewstats)
{
    require_once(HEADERF);
    print "Not permitted to view stats";
    require_once(FOOTERF);
    exit;
}
$barl = (file_exists(THEME . "images/barl.png") ? THEME . "images/barl.png" : e_PLUGIN . "poll/images/barl.png");
$barr = (file_exists(THEME . "images/barr.png") ? THEME . "images/barr.png" : e_PLUGIN . "poll/images/barr.png");
$bar = (file_exists(THEME . "images/bar.png") ? THEME . "images/bar.png" : e_PLUGIN . "poll/images/bar.png");

require_once(HEADERF);
// Top Items
// Top 10 popular authors
// top 10 popular books
// top 10 rated books
// top 10 rated authors
$kbase_gen = new convert;
$kbase_dlurl = e_BASE;

$numkbases = $sql->db_Count("kbase", "(*)", "where kbase_approved > 0", false);

$numcats = $sql->db_Count("kbase_info", "(*)", "where kbase_info_parent>0", false);

$sql->db_Select("kbase", "sum(kbase_views) as numviews", "where kbase_approved>0", false);
$kbase_row = $sql->db_Fetch();
$numviews = $kbase_row['numviews'];

$sql->db_Select("kbase", "sum(kbase_unique) as numunique", "where kbase_approved>0", false);
$kbase_row = $sql->db_Fetch();
$numunique = $kbase_row['numunique'];

$kbase_arg = "select count(c.comment_item_id) as numcom from #comments as c
left join #kbase as m on comment_item_id =kbase_id
where kbase_approved > 0 and comment_type='3'";

$sql->db_Select_gen($kbase_arg, false);
$kbase_row = $sql->db_Fetch();
$numcom = $kbase_row['numcom'];

$latedl .= "
<table class='fborder' style='width:100%;'>
	<tr>
		<td class='fcaption' colspan='4'>" . KBASELAN_110 . "</td>
	</tr>";
if (file_exists("./images/kbase_logo.png"))
{
    $latedl .= "<tr><td class='forumheader3' colspan='4' style='text-align:center;'><img src='./images/kbase_logo.png' alt='logo' title='' /></td></tr>";
}
$latedl .= "

	<tr>
		<td class='forumheader' colspan='4'>" . KBASELAN_109 . "</td>
	</tr>
	<tr>
		<td class='forumheader3' colspan='2'>" . KBASELAN_107 . "</td>
		<td class='forumheader3' colspan='2'>" . $numkbases . "</td>
	</tr>
	<tr>
		<td class='forumheader3' colspan='2'>" . KBASELAN_108 . "</td>
		<td class='forumheader3' colspan='2'>" . $numcats . "</td>
	</tr>
	<tr>
		<td class='forumheader3' colspan='2'>" . KBASELAN_111 . "</td>
		<td class='forumheader3' colspan='2'>" . $numviews . "</td>
	</tr>
	<tr>
		<td class='forumheader3' colspan='2'>" . KBASELAN_112 . "</td>
		<td class='forumheader3' colspan='2'>" . $numunique . "</td>
	</tr>

	<tr>
		<td class='forumheader3' colspan='2'>" . KBASELAN_113 . "</td>
		<td class='forumheader3' colspan='2'>" . $numcom . "</td>
	</tr>			";
// ********************************************************************
// ********************************************************************
// Top 10 by views
// ********************************************************************
// ********************************************************************
$latedl .= "

	<tr>
		<td class='forumheader' colspan='4'>" . KBASELAN_120 . "</td>
	</tr>
	<tr>
		<td class='forumheader2' style='width:45%;'>" . KBASELAN_121 . "</td>
		<td class='forumheader2' style='width:5%;'>" . KBASELAN_122 . "</td>
		<td class='forumheader2' style='width:10%;'>" . KBASELAN_123 . "</td>
		<td class='forumheader2' style='width:40%;'>&nbsp;</td>
	</tr>";
// Get top 10 by views
$sql->db_Select("kbase", "kbase_id,kbase_question,kbase_views,kbase_parent", "where kbase_approved>0 order by kbase_views desc limit 0,10", "nowhere", false);
while ($kbase_row = $sql->db_Fetch())
{
    $percentage = round((($kbase_row['kbase_views'] / $numviews) * 100), 2);
    $latedl .= "
    <tr>
    	<td class='forumheader3' ><a href='" . e_PLUGIN . "kbase/kbase.php?0.cat.".$kbase_row['kbase_parent']."." . $kbase_row['kbase_id'] . "'><strong>" . $tp->toHTML($kbase_row['kbase_question']) . "</strong></a></td>
		<td class='forumheader3' >" . $kbase_row['kbase_views'] . "</td>
		<td class='forumheader3' >" . $percentage . "</td>
		<td class='forumheader3'>
			<div style='background-image: url($barl); width: 5px; height: 14px; float: left;'></div>
			<div style='background-image: url($bar); width: " . intval($percentage) / 2 . "%; height: 14px; float: left;'></div>
			<div style='background-image: url($barr); width: 5px; height: 14px; float: left;'></div>
		</td>
	</tr>";
} // while
// end // Top 10 by views
// ********************************************************************
// ********************************************************************
// Top 10 by unique views
// ********************************************************************
// ********************************************************************
$latedl .= "

	<tr>
		<td class='forumheader' colspan='4'>" . KBASELAN_124 . "</td>
	</tr>
	<tr>
		<td class='forumheader2' style='width:45%;'>" . KBASELAN_121 . "</td>
		<td class='forumheader2' style='width:5%;'>" . KBASELAN_122 . "</td>
		<td class='forumheader2' style='width:10%;'>" . KBASELAN_123 . "</td>
		<td class='forumheader2' style='width:40%;'>&nbsp;</td>
	</tr>";
// Get top 10 by unique views
$sql->db_Select("kbase", "kbase_id,kbase_question,kbase_unique,kbase_parent", "where kbase_approved>0 order by kbase_unique desc limit 0,10", "nowhere", false);
while ($kbase_row = $sql->db_Fetch())
{
    $percentage = round((($kbase_row['kbase_unique'] / $numunique) * 100), 2);
    $latedl .= "
    <tr>
    	<td class='forumheader3' ><a href='" . e_PLUGIN . "kbase/kbase.php?0.cat.".$kbase_row['kbase_parent']."." . $kbase_row['kbase_id'] . "'><strong>" . $tp->toHTML($kbase_row['kbase_question']) . "</strong></a></td>
		<td class='forumheader3' >" . $kbase_row['kbase_unique'] . "</td>
		<td class='forumheader3' >" . $percentage . "</td>
		<td class='forumheader3'>
			<div style='background-image: url($barl); width: 5px; height: 14px; float: left;'></div>
			<div style='background-image: url($bar); width: " . intval($percentage) / 2 . "%; height: 14px; float: left;'></div>
			<div style='background-image: url($barr); width: 5px; height: 14px; float: left;'></div>
		</td>
	</tr>";
} // while
// end // Top 10 by views
// ********************************************************************
// ********************************************************************
// Top 10  posters
// ********************************************************************
// ********************************************************************
$latedl .= "
	<tr>
		<td class='forumheader' colspan='4'>" . KBASELAN_125 . "</td>
	</tr>
	<tr>
		<td class='forumheader2' style='width:45%;'>" . KBASELAN_127 . "</td>
		<td class='forumheader2' style='width:5%;'>" . KBASELAN_126 . "</td>
		<td class='forumheader2' style='width:10%;'>" . KBASELAN_123 . "</td>
		<td class='forumheader2' style='width:40%;'>&nbsp;</td>
	</tr>";
// Get top 10 authors
$sql->db_Select("kbase", "kbase_author, count(kbase_author) as numpost", "where kbase_approved > 0 group by kbase_author order by numpost desc limit 0,10", "nowhere", false);
while ($kbase_row = $sql->db_Fetch())
{
    $percentage = round((($kbase_row['numpost'] / $numkbases) * 100), 2);
    $kbase_tmp = explode(".", $kbase_row['kbase_author'], 2);
    $latedl .= "
	<tr>
		<td class='forumheader3' ><a href='" . "../../user.php?id." . $kbase_tmp[0] . "'><strong>" . $tp->toFORM($kbase_tmp[1]) . "</strong></a></td>
		<td class='forumheader3' >" . $kbase_row['numpost'] . "</td>
		<td class='forumheader3' >" . $percentage . "</td>
		<td class='forumheader3'>
			<div style='background-image: url($barl); width: 5px; height: 14px; float: left;'></div>
			<div style='background-image: url($bar); width: " . intval($percentage) / 2 . "%; height: 14px; float: left;'></div>
			<div style='background-image: url($barr); width: 5px; height: 14px; float: left;'></div>
		</td>
	</tr>";
} // while
// end // Top 10  posters
// ********************************************************************
if ($KBASE_PREF['kbase_rating'] > 0)
{
    // ********************************************************************
    // Top 10  by rating
    // ********************************************************************
    // ********************************************************************
    $latedl .= "
	<tr>
		<td class='forumheader' colspan='4'>" . KBASELAN_128 . "</td>
	</tr>
	<tr>
		<td class='forumheader2' style='width:45%;'>" . KBASELAN_121 . "</td>
		<td class='forumheader2' style='width:5%;'>" . KBASELAN_130 . "</td>
		<td class='forumheader2' style='width:10%;'>" . KBASELAN_123 . "</td>
		<td class='forumheader2' style='width:40%;'>&nbsp;</td>
	</tr>";
    // Get top 10 authors
    $kbase_arg = "select r.*,m.*, r.rate_rating/r.rate_votes as rating from #rate as r
left join #kbase as m on rate_itemid=kbase_id
where rate_table='kbase' and kbase_approved > 0
order by rating desc
limit 0,10";
    $sql->db_Select_gen($kbase_arg, false);
    while ($kbase_row = $sql->db_Fetch())
    {
        $percentage = round((($kbase_row['rating'] / 10) * 100), 2);
        $latedl .= "
    <tr>
        <td class='forumheader3' ><a href='" . e_PLUGIN . "kbase/kbase.php?0.cat.".$kbase_row['kbase_parent']."." . $kbase_row['kbase_id'] . "' ><strong>" . $tp->toFORM($kbase_row['kbase_question']) . "</strong></a></td>
		<td class='forumheader3' >" . $kbase_row['rating'] . "</td>
		<td class='forumheader3' >" . $percentage . "</td>
		<td class='forumheader3'>
			<div style='background-image: url($barl); width: 5px; height: 14px; float: left;'></div>
			<div style='background-image: url($bar); width: " . intval($percentage) / 2 . "%; height: 14px; float: left;'></div>
			<div style='background-image: url($barr); width: 5px; height: 14px; float: left;'></div>
		</td>
	</tr>";
    } // while
    // end // Top 10 by rating
    // ********************************************************************
}
// Top 10 by comments
// ********************************************************************
// ********************************************************************
$latedl .= "
	<tr>
		<td class='forumheader' colspan='4'>" . KBASELAN_129 . "</td>
	</tr>
	<tr>
		<td class='forumheader2' style='width:45%;'>" . KBASELAN_121 . "</td>
		<td class='forumheader2' style='width:5%;'>" . KBASELAN_132 . "</td>
		<td class='forumheader2' style='width:10%;'>" . KBASELAN_123 . "</td>
		<td class='forumheader2' style='width:40%;'>&nbsp;</td>
	</tr>";
// Get top 10 recipes by comments
$kbase_arg = "select count(c.comment_item_id) as numpost,m.* from #comments as c
left join #kbase as m on comment_item_id =kbase_id
where kbase_approved > 0 and comment_type='3' group by comment_item_id order by numpost desc limit 0,10";

$sql->db_Select_gen($kbase_arg, false);
while ($kbase_row = $sql->db_Fetch())
{
    $percentage = round((($kbase_row['numpost'] / $numcom) * 100), 2);
    $latedl .= "
	<tr>
        <td class='forumheader3' ><a href='" . e_PLUGIN . "kbase/kbase.php?0.cat.".$kbase_row['kbase_parent']."." . $kbase_row['kbase_id'] . "' ><strong>" . $tp->toFORM($kbase_row['kbase_question']) . "</strong></a></td>
		<td class='forumheader3' >" . $kbase_row['numpost'] . "</td>
		<td class='forumheader3' >" . $percentage . "</td>
		<td class='forumheader3'>
			<div style='background-image: url($barl); width: 5px; height: 14px; float: left;'></div>
			<div style='background-image: url($bar); width: " . intval($percentage) / 2 . "%; height: 14px; float: left;'></div>
			<div style='background-image: url($barr); width: 5px; height: 14px; float: left;'></div>
		</td>
	</tr>";
} // while
// end // Top 10 by comments
// ********************************************************************
// Top 10 Categories by KBASE
// ********************************************************************
// ********************************************************************
$latedl .= "
	<tr>
		<td class='forumheader' colspan='4'>" . KBASELAN_133 . "</td>
	</tr>
	<tr>
		<td class='forumheader2' style='width:45%;'>" . KBASELAN_131 . "</td>
		<td class='forumheader2' style='width:5%;'>" . KBASELAN_126 . "</td>
		<td class='forumheader2' style='width:10%;'>" . KBASELAN_123 . "</td>
		<td class='forumheader2' style='width:40%;'>&nbsp;</td>
	</tr>";
// Get top categories by number of recipes
$kbase_arg = "select COUNT(r.kbase_id) as numpost,c.*,r.* from #kbase_info as c
left join #kbase as r on kbase_info_id=kbase_parent
where kbase_approved > 0
group by kbase_info_id
order by numpost desc
limit 0,10";
$sql->db_Select_gen($kbase_arg, false);
while ($kbase_row = $sql->db_Fetch())
{
    $percentage = round((($kbase_row['numpost'] / $numkbases) * 100), 2);

    $latedl .= "
	<tr>
        <td class='forumheader3' ><a href='" . e_PLUGIN . "kbase/kbase.php?0.cat.".$kbase_row['kbase_parent'].".0' ><strong>" . $tp->toFORM($kbase_row['kbase_info_title']) . "</strong></a></td>
		<td class='forumheader3' >" . $kbase_row['numpost'] . "</td>
		<td class='forumheader3' >" . $percentage . "</td>
		<td class='forumheader3'>
			<div style='background-image: url($barl); width: 5px; height: 14px; float: left;'></div>
			<div style='background-image: url($bar); width: " . intval($percentage) / 2 . "%; height: 14px; float: left;'></div>
			<div style='background-image: url($barr); width: 5px; height: 14px; float: left;'></div>
		</td>
	</tr>";
} // while
// end // top categories by number of kbases
// ********************************************************************
// Top 10 Categories by views
// ********************************************************************
// ********************************************************************
$latedl .= "
	<tr>
		<td class='forumheader' colspan='4'>" . KBASELAN_134 . "</td>
	</tr>
	<tr>
		<td class='forumheader2' style='width:45%;'>" . KBASELAN_131 . "</td>
		<td class='forumheader2' style='width:5%;'>" . KBASELAN_122 . "</td>
		<td class='forumheader2' style='width:10%;'>" . KBASELAN_123 . "</td>
		<td class='forumheader2' style='width:40%;'>&nbsp;</td>
	</tr>";
// Get top 10 categories by views
$kbase_arg = "select sum(kbase_views) as numpost,c.*,r.* from #kbase_info as c
left join #kbase as r on kbase_info_id=kbase_parent
where kbase_approved>0 and kbase_views>0
group by kbase_info_id
order by numpost desc
limit 0,10";
$sql->db_Select_gen($kbase_arg, false);
while ($kbase_row = $sql->db_Fetch())
{
    $percentage = round((($kbase_row['numpost'] / $numviews) * 100), 2);
    $latedl .= "
	<tr>
        <td class='forumheader3' ><a href='" . e_PLUGIN . "kbase/kbase.php?0.cat.".$kbase_row['kbase_parent'].".0' ><strong>" . $tp->toFORM($kbase_row['kbase_info_title']) . "</strong></a></td>
		<td class='forumheader3' >" . $kbase_row['numpost'] . "</td>
		<td class='forumheader3' >" . $percentage . "</td>
		<td class='forumheader3'>
			<div style='background-image: url($barl); width: 5px; height: 14px; float: left;'></div>
			<div style='background-image: url($bar); width: " . intval($percentage) / 2 . "%; height: 14px; float: left;'></div>
			<div style='background-image: url($barr); width: 5px; height: 14px; float: left;'></div>
		</td>
	</tr>";
} // while
$latedl .= "</table>";

$ns->tablerender(KBASELAN_110, $latedl);
require_once(FOOTERF);

?>