<?php
include_lan(e_PLUGIN . "kbase/languages/" . e_LANGUAGE . ".php");
// $TOP_PLUGIN_SECTIONS[]=array(,);
// top items by views
// data in the order item name, poster (ID.name),category,date posted,link,additional info
// .
// Top kbase by views
unset($TOP_VIEWS);
global $sql, $top_tc;
global $TOP_PREFS, $top_limitname, $top_limitmode;
$kbase_arg = "select * from #kbase left join #kbase_info on kbase_parent=kbase_info_id where kbase_approved>0 order by kbase_views  desc limit 0," . $top_tc->limit();
$sql->db_Select_gen($kbase_arg, false);
while ($kbase_row = $sql->db_Fetch())
{
    $TOP_VIEWS[] = array($kbase_row['kbase_question'],
        $kbase_row['kbase_author'],
        $kbase_row['kbase_info_title'],
        $kbase_row['kbase_datestamp'],
        e_PLUGIN . "kbase/kbase.php?0.cat." . $kbase_row['kbase_parent'] . "." . $kbase_row['kbase_id'],
        KBASELAN_141 . " " . $kbase_row['kbase_views'] . " Unique (" . $kbase_row['kbase_unique'] . ")");
} // while
// Top by rating
unset($TOP_RATE);
$kbase_arg = "select r.*,m.*, r.rate_rating/rate_votes as rating from #rate as r
left join #kbase as m on rate_itemid=kbase_id
left join #kbase_info on kbase_parent=kbase_info_id
where rate_table='kbase' and kbase_approved > 0
order by rating desc
limit 0," . $top_tc->limit();
$sql->db_Select_gen($kbase_arg, false);
while ($kbase_row = $sql->db_Fetch())
{
    $TOP_RATE[] = array($kbase_row['kbase_question'],
        $kbase_row['kbase_author'],
        $kbase_row['kbase_info_title'],
        $kbase_row['kbase_datestamp'],
        e_PLUGIN . "kbase/kbase.php?0.cat." . $kbase_row['kbase_parent'] . "." . $kbase_row['kbase_id'],
        KBASELAN_142 . " " . number_format($kbase_row['rate_rating'] / $kbase_row['rate_votes'], 2) . " " . KBASELAN_143 . " " . $kbase_row['rate_votes'] . " " . KBASELAN_144);
} // while
// Top Poster
unset($TOP_POSTER);
$kbase_arg = "select *,count(kbase_author) as numpost from #kbase where kbase_approved>0 group by kbase_author order by numpost  desc limit 0," . $top_tc->limit();
$sql->db_Select_gen($kbase_arg, false);

while ($kbase_row = $sql->db_Fetch())
{
    $TOP_POSTER[] = array(KBASELAN_145 . " " . $kbase_row['numpost'],
        $kbase_row['kbase_author'],
        "",
        "",
        "",
        ""
        );
} // while
unset($TOP_COMMENT);
$kbase_arg = "select count(c.comment_item_id) as numpost,m.* from #comments as c
left join #kbase as m on comment_item_id =kbase_id
left join #kbase_info on kbase_parent=kbase_info_id
where kbase_approved > 0 and comment_type='3'
group by comment_item_id order by numpost desc limit 0," . $top_tc->limit();

$sql->db_Select_gen($kbase_arg, false);
while ($kbase_row = $sql->db_Fetch())
{
    $TOP_COMMENT[] = array($kbase_row['kbase_question'],
        $kbase_row['kbase_author'],
        $kbase_row['kbase_info_title'],
        $kbase_row['kbase_datestamp'],
        e_PLUGIN . "kbase/kbase.php?0.cat." . $kbase_row['kbase_parent'] . "." . $kbase_row['kbase_id'],
        KBASELAN_146 . " " . $kbase_row['numpost']);
} // while
unset($TOP_CAT_KBASE);

$kbase_arg = "select COUNT(kbase_id) as numpost,c.*,r.* from #kbase_info as c
left join #kbase as r on kbase_info_id=kbase_parent
where kbase_approved > 0
group by kbase_info_id
order by numpost desc
limit 0," . $top_tc->limit();
$sql->db_Select_gen($kbase_arg, false);
while ($kbase_row = $sql->db_Fetch())
{
    $TOP_CAT_KBASE[] = array($kbase_row['kbase_info_title'],
        "",
        "",
        "",
        e_PLUGIN . "kbase/kbase.php?0.cat." . $kbase_row['kbase_info_id'] . ".0",
        KBASELAN_145 . " " . $kbase_row['numpost']);
} // while
unset($TOP_CAT_VIEWS);
$kbase_arg = "select sum(r.kbase_views) as numpost,c.*,r.* from #kbase_info as c
left join #kbase as r on kbase_info_id=kbase_parent
where kbase_approved>0 and kbase_views>0
group by kbase_info_id
order by numpost desc
limit 0," . $top_tc->limit();
$sql->db_Select_gen($kbase_arg, false);
while ($kbase_row = $sql->db_Fetch())
{
    $TOP_CAT_VIEWS[] = array($kbase_row['kbase_info_title'],
        "",
        "",
        "",
        e_PLUGIN . "kbase/kbase.php?0.cat." . $kbase_row['kbase_info_id'] . ".0",
        KBASELAN_141 . " " . $kbase_row['numpost']);
} // while
$TOP_MENU_DATA[] = array(
    KBASELAN_120 => $TOP_VIEWS,
    KBASELAN_128 => $TOP_RATE,
    KBASELAN_125 => $TOP_POSTER,
    KBASELAN_129 => $TOP_COMMENT,
    KBASELAN_133 => $TOP_CAT_KBASE,
    KBASELAN_134 => $TOP_CAT_VIEWS);

?>