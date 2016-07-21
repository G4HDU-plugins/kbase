<?php
require_once("includes/kbase_class.php");
if (!is_object($kbase_obj)) {
	$kbase_obj=new KBASE;
}
global $sql, $tp, $KBASE_PREF;
if (!$kbase_obj->kbase_read)
{
    exit;
}
require_once(e_HANDLER . "cache_handler.php");


if ($kbasedivsl = $e107cache->retrieve("nq_kbasetop_menu"))
{
    echo $kbasedivsl;
}
else
{
    $kbasedivsl = "
<script type='text/javascript'>
<!--
function kbaseDiv(kbasedivid) {

	if (document.getElementById('kbasemenu'+kbasedivid).style.display == 'block')
	{
		document.getElementById('kbasemenu'+kbasedivid).style.display = 'none';

	}
	else
	{
		document.getElementById('kbasemenu'+kbasedivid).style.display = 'block';
	}
}
-->
</script>
<table style='width:100%;border:0;'>
<tr>
<td style='width:100%'>
	<div id='kbasedivsl0' class=\"fborder\">";

    $kbase_gen = new convert;
    $kbase_imode = (IMODE == "lite"?"lite/":"dark/");
    $kbase_dlurl = e_BASE;
    // ********************************************************************
    // ********************************************************************
    // Top 10 by views
    // ********************************************************************
    // ********************************************************************
    $kbasemenu_kbasedivid = 1;
    $kbasedivsl .= "
		<div class='forumheader3' >
			<div style='width:100%'>
				<div onclick=\"kbaseDiv(" . $kbasemenu_kbasedivid . ");\" style='cursor:pointer;float:left;width:80%; '>

						<img src='" . THEME . "images/bullet2.gif' alt='bullet' style='border:0;' />
							<span class='smalltext'>" . KBASELANTOP_01 . "</span>&nbsp;&nbsp;

				</div>
				<div onclick=\"kbaseDiv(" . $kbasemenu_kbasedivid . ");\" style='cursor:pointer;float:right;width:19%;text-align:right;'>

						<img id='kbaseexpdr" . $kbasemenu_kbasedivid . "' src='" . e_PLUGIN . "kbase/images/expand.png' title='expand/close' alt='expand/close' style='border:0;'/>

				</div>
				<div style='clear:both;'></div>
			</div>
			<div id='kbasemenu" . $kbasemenu_kbasedivid . "' style='display:none' >
				<div style='padding:10px' >";
    // Get top 10 KBASE
    $kbase_arg="select * from #kbase
    left join #kbase_info on kbase_parent=kbase_info_id
    where kbase_approved>0 and find_in_set(kbase_info_class,'".USERCLASS_LIST."') order by kbase_views desc limit 0," . $kbase_obj->kbase_topnum;
    $sql->db_Select_gen($kbase_arg, false);
    while ($kbase_row = $sql->db_Fetch())
    {
        $kbasedivsl .= "<span class='smallblacktext'><a href='" . e_PLUGIN . "kbase/kbase.php?0.cat." . $kbase_row['kbase_parent'] . "." . $kbase_row['kbase_id'] . "'><strong>" . $tp->toFORM($kbase_row['kbase_question']) . "</strong></a> (" . $kbase_row['kbase_views'] . ")</span><br />";
    } // while
    $kbasedivsl .= "
				</div>
			</div>
		</div>";
    // end // Top 10 by views
    // ********************************************************************
    // ********************************************************************
    // Top 10 posters
    // ********************************************************************
    // ********************************************************************
    $kbasemenu_kbasedivid = 2;
    $kbasedivsl .= "
		<div  class='forumheader3' >
			<div style='width:100%'>
				<div onclick=\"kbaseDiv(" . $kbasemenu_kbasedivid . ");\" style='cursor:pointer;float:left;width:80%; '>

						<img src='" . THEME . "images/bullet2.gif' alt='bullet' style='border:0;' />
							<span class='smalltext'>" . KBASELANTOP_02 . "</span>&nbsp;&nbsp;

				</div>
				<div onclick=\"kbaseDiv(" . $kbasemenu_kbasedivid . ");\" style='cursor:pointer;float:right;width:19%;text-align:right;'>
						<img id='kbaseexpdr" . $kbasemenu_kbasedivid . "' src='" . e_PLUGIN . "kbase/images/expand.png' title='expand/close' alt='expand/close' style='border:0;'/>
				</div>
				<div style='clear:both;'></div>
			</div>
			<div id='kbasemenu" . $kbasemenu_kbasedivid . "' style='display:none' >
				<div style='padding:10px' >";
    // Get top 10 authors
    $sql->db_Select("kbase", "kbase_author, count(kbase_author) as numpost", "where kbase_approved > 0 group by kbase_author order by numpost desc limit 0," . $kbase_obj->kbase_topnum, "nowhere", false);
    while ($kbase_row = $sql->db_Fetch())
    {
        $kbase_tmp = explode(".", $kbase_row['kbase_author'], 2);

        $kbasedivsl .= "<span class='smallblacktext'><a href='" . "../../user.php?id." . $kbase_tmp[0] . "'><strong>" . $tp->toFORM($kbase_tmp[1]) . "</strong>" . "</a> (" . $kbase_row['numpost'] . ")</span><br />";
    } // while
    $kbasedivsl .= "
				</div>
			</div>
		</div>";
    // end // Top 10 posters
    // ********************************************************************
    if ($kbase_obj->kbase_rating)
    {
        // ********************************************************************
        // Top 10 by rating
        // ********************************************************************
        // ********************************************************************
        $kbasemenu_kbasedivid = 3;
        $kbasedivsl .= "
		<div  class='forumheader3' >
			<div style='width:100%'>
				<div onclick=\"kbaseDiv(" . $kbasemenu_kbasedivid . ");\" style='cursor:pointer;float:left;width:80%; '>
						<img src='" . THEME . "images/bullet2.gif' alt='bullet' style='border:0;' />
							<span class='smalltext'>" . KBASELANTOP_03 . "</span>&nbsp;&nbsp;
				</div>
				<div onclick=\"kbaseDiv(" . $kbasemenu_kbasedivid . ");\" style='cursor:pointer;float:right;width:19%;text-align:right;'>
						<img id='kbaseexpdr" . $kbasemenu_kbasedivid . "' src='" . e_PLUGIN . "kbase/images/expand.png' title='expand/close' alt='expand/close' style='border:0;'/>
				</div>
				<div style='clear:both;'></div>
			</div>
			<div id='kbasemenu" . $kbasemenu_kbasedivid . "' style='display:none' >
				<div style='padding:10px' >";
        // Get top 10 authors
        $kbase_arg = "select r.*,m.*, r.rate_rating/rate_votes as rating from #rate as r
        left join #kbase_info on kbase_parent=kbase_info_id
left join #kbase as m on rate_itemid=kbase_id
where rate_table='kbase' and kbase_approved > 0 and find_in_set(kbase_info_class,'".USERCLASS_LIST."')
order by rating desc
limit 0," . $kbase_obj->kbase_topnum;
        $sql->db_Select_gen($kbase_arg, false);
        while ($kbase_row = $sql->db_Fetch())
        {
            $kbasedivsl .= "<span class='smallblacktext'><a href='" . e_PLUGIN . "kbase/kbase.php?0.cat." . $kbase_row['kbase_parent'] . "." . $kbase_row['kbase_id'] . "'><strong>" . $tp->toFORM($kbase_row['kbase_question']) . "</strong></a> (" . $kbase_row['rating'] . ")</span><br />";
        } // while
        $kbasedivsl .= "
				</div>
			</div>
		</div>";
        // end // Top 10 by rating
        // ********************************************************************
    }
    // Top 10 by comments
    // ********************************************************************
    // ********************************************************************
    $kbasemenu_kbasedivid = 4;
    $kbasedivsl .= "
		<div  class='forumheader3' >
			<div style='width:100%'>
				<div onclick=\"kbaseDiv(" . $kbasemenu_kbasedivid . ");\" style='cursor:pointer;float:left;width:80%; '>
						<img src='" . THEME . "images/bullet2.gif' alt='bullet' style='border:0;' />
							<span class='smalltext'>" . KBASELANTOP_04 . "</span>&nbsp;&nbsp;
				</div>
				<div onclick=\"kbaseDiv(" . $kbasemenu_kbasedivid . ");\" style='cursor:pointer;float:right;width:19%;text-align:right;'>
						<img id='kbaseexpdr" . $kbasemenu_kbasedivid . "' src='" . e_PLUGIN . "kbase/images/expand.png' title='expand/close' alt='expand/close' style='border:0;'/>
				</div>
				<div style='clear:both;'></div>
			</div>
			<div id='kbasemenu" . $kbasemenu_kbasedivid . "' style='display:none' >
				<div style='padding:10px' >";
    // Get top 10 kbase by comments
    $kbase_arg = "select count(c.comment_item_id) as numpost,m.* from #comments as c
left join #kbase as m on comment_item_id =kbase_id
where kbase_approved > 0 and comment_type='3' group by comment_item_id order by numpost desc limit 0," . $kbase_obj->kbase_topnum;

    $sql->db_Select_gen($kbase_arg, false);
    while ($kbase_row = $sql->db_Fetch())
    {
        $kbasedivsl .= "<span class='smallblacktext'><a href='" . e_PLUGIN . "kbase/kbase.php?0.cat." . $kbase_row['kbase_parent'] . "." . $kbase_row['kbase_id'] . "'><strong>" . $tp->toFORM($kbase_row['kbase_question']) . "</strong></a> (" . $kbase_row['numpost'] . ")</span><br />";
    } // while
    $kbasedivsl .= "
				</div>
			</div>
		</div>";
    // end // Top 10 by comments
    // ********************************************************************
    // Top 10 Categories
    // ********************************************************************
    // ********************************************************************
    $kbasemenu_kbasedivid = 5;
    $kbasedivsl .= "
		<div  class='forumheader3' >
			<div style='width:100%'>
				<div onclick=\"kbaseDiv(" . $kbasemenu_kbasedivid . ");\" style='cursor:pointer;float:left;width:80%; '>
						<img src='" . THEME . "images/bullet2.gif' alt='bullet' style='border:0;' />
							<span class='smalltext'>" . KBASELANTOP_05 . "</span>&nbsp;&nbsp;
				</div>
				<div onclick=\"kbaseDiv(" . $kbasemenu_kbasedivid . ");\" style='cursor:pointer;float:right;width:19%;text-align:right;'>
						<img id='kbaseexpdr" . $kbasemenu_kbasedivid . "' src='" . e_PLUGIN . "kbase/images/expand.png' title='expand/close' alt='expand/close' style='border:0;'/>
				</div>
				<div style='clear:both;'></div>
			</div>
			<div id='kbasemenu" . $kbasemenu_kbasedivid . "' style='display:none' >
				<div style='padding:10px' >";
    // Get top 10 authors
    $kbase_arg = "select COUNT(kbase_id) as numpost,c.*,r.* from #kbase_info as c
left join #kbase as r on kbase_info_id=kbase_parent
where kbase_approved > 0  and find_in_set(c.kbase_info_class,'".USERCLASS_LIST."')
group by kbase_info_id
order by numpost desc
limit 0," . $kbase_obj->kbase_topnum;
    $sql->db_Select_gen($kbase_arg, false);
    while ($kbase_row = $sql->db_Fetch())
    {
        $kbasedivsl .= "<span class='smallblacktext'><a href='" . e_PLUGIN . "kbase/kbase.php?0.cat." . $kbase_row['kbase_parent'] . "'><strong>" . $tp->toFORM($kbase_row['kbase_info_title']) . "</strong></a> (" . $kbase_row['numpost'] . ")</span><br />";
    } // while
    $kbasedivsl .= "
				</div>
			</div>
		</div>";
    // end // Top 10 categories
    // ********************************************************************
    // Top 10 Categories by views
    // ********************************************************************
    // ********************************************************************
    $kbasemenu_kbasedivid = 6;
    $kbasedivsl .= "
		<div  class='forumheader3' >
			<div style='width:100%'>
				<div onclick=\"kbaseDiv(" . $kbasemenu_kbasedivid . ");\" style='cursor:pointer;float:left;width:80%; '>
						<img src='" . THEME . "images/bullet2.gif' alt='bullet' style='border:0;' />
							<span class='smalltext'>" . KBASELANTOP_06 . "</span>&nbsp;&nbsp;
				</div>
				<div onclick=\"kbaseDiv(" . $kbasemenu_kbasedivid . ");\" style='cursor:pointer;float:right;width:19%;text-align:right;'>
						<img id='kbaseexpdr" . $kbasemenu_kbasedivid . "' src='" . e_PLUGIN . "kbase/images/expand.png' title='expand/close' alt='expand/close' style='border:0;'/>
				</div>
				<div style='clear:both;'></div>
			</div>
			<div id='kbasemenu" . $kbasemenu_kbasedivid . "' style='display:none' >
				<div style='padding:10px' >";
    // Get top 10 by views
    $kbase_arg = "select sum(r.kbase_views) as numpost,c.*,r.* from #kbase_info as c
left join #kbase as r on kbase_info_id=kbase_parent
where kbase_approved>0 and kbase_views>0 and find_in_set(c.kbase_info_class,'".USERCLASS_LIST."')
group by kbase_info_id
order by numpost desc
limit 0," . $kbase_obj->kbase_topnum;
    $sql->db_Select_gen($kbase_arg, false);
    while ($kbase_row = $sql->db_Fetch())
    {
        $kbasedivsl .= "<span class='smallblacktext'><a href='" . e_PLUGIN . "kbase/kbase.php?0.cat." . $kbase_row['kbase_parent'] . "'><strong>" . $tp->toFORM($kbase_row['kbase_info_title']) . "</strong></a> (" . $kbase_row['numpost'] . ")</span><br />";
    } // while
    $kbasedivsl .= "
				</div>
			</div>
		</div>
	</div>
	</td></tr></table>";
    ob_start();
    $ns->tablerender(KBASELANTOP_00, $kbasedivsl);
    $kbase_cache = ob_get_flush();
    $e107cache->set("nq_kbasetop_menu", $kbase_cache);
}

?>