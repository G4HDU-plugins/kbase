<?php
if (!defined('e107_INIT'))
{
    exit;
}
require_once("includes/kbase_class.php");
if (!is_object($kbase_obj))
{
    $kbase_obj = new KBASE;
}
global $sql, $tp, $KBASE_PREF,$PLUGIN_DIRECTORY;
if (!$kbase_obj->kbase_read)
{
    exit;
}
$kbase_show = false;
$kbase_list = array();
$kbase_url = SITEURL .$PLUGIN_DIRECTORY . "kbase/";
$kbase_text = "<div class='fborder' style='text-align:center;padding:3px;'>";
// Random menu item
if ($kbase_obj->kbase_random)
{
    $kbase_show = true;
    $kbase_arg = "
	select * from #kbase
	left join #kbase_info on kbase_parent = kbase_info_id
	where kbase_approved > 0 and find_in_set(kbase_info_class,'" . USERCLASS_LIST . "')
	order by rand()
	limit 0,1";

    if ($sql->db_Select_Gen($kbase_arg, false))
    {
        $kbase_row = $sql->db_Fetch();
        // $kbase_text.="<div class='fbroder' style='text-align:center;'>".KBASELAN_85."<br /><strong>". $kbase_row['kbase_question']."</strong><br /><a href='".$kbase_url."kbase.php?cat.".$kbase_row['kbase_parent'].".".$kbase_row['kbase_id']."' >".KBASELAN_86."</a></div>";
        $kbase_text .= "<div class='forumheader3'><strong>" . KBASELAN_104 . "</strong><br /><br /><a href='" . e_PLUGIN . "kbase/kbase.php?0.cat." . $kbase_row['kbase_parent'] . "." . $kbase_row['kbase_id'] . "' >" . $tp->toHTML($kbase_row['kbase_question']) . "</a><br /><br /></div>";
    }
    else
    {
        $kbase_text .= KBASELAN_99;
    }
}
if ($kbase_obj->kbase_topnum>0)
{
    $kbase_show = true;
    if ($kbasedivsl = $e107cache->retrieve("nq_kbase_menu"))
    {
        $kbase_text .= $kbasedivsl;
    }
    else
    {
        $kbase_arg = "select kbase_id,kbase_question,kbase_views,kbase_parent from #kbase left join #kbase_info on kbase_parent = kbase_info_id where kbase_approved>0 and find_in_set(kbase_info_class,'" . USERCLASS_LIST . "') order by kbase_views desc limit 0," . $kbase_obj->kbase_topnum;
        if ($sql->db_Select_Gen($kbase_arg, false))
        {
            $kbase_cache .= "<div class='forumheader3' style='text-align:center;'><strong>" . KBASELAN_98 . " " . $kbase_obj->kbase_topnum . " " . KBASELAN_97 . "</strong><br /><br />";
            while ($kbase_row = $sql->db_Fetch())
            {
                extract($kbase_row);
                $kbase_quest = substr($kbase_question, 0, 20);
                if (strlen($kbase_question) > 20)
                {
                    $kbase_quest .= " ...";
                }
                $kbase_cache .= "<a href='" . e_PLUGIN . "kbase/kbase.php?0.cat." . $kbase_parent . "." . $kbase_id . "' title='" .  htmlentities($tp->toHTML($kbase_question),ENT_QUOTES) . "'>" . $tp->toHTML($kbase_quest) . "</a> ($kbase_views)<br />";
            }
            $kbase_cache .= "</div>";
        }
        else
        {
            $kbase_cache .= KBASELAN_100;
        }

        $e107cache->set("nq_kbase_menu", $kbase_cache);
        $kbase_text.=$kbase_cache;
    }
}
if (!$kbase_show)
{
    $kbase_text .= KBASELAN_101;
}
$kbase_text .= "</div>";

$ns->tablerender($tp->toFORM($KBASE_PREF['kbase_title']), $kbase_text,'kbase_menu');

?>