<?php

if (!defined('e107_INIT'))
{
    exit;
}

require_once(e_PLUGIN . "kbase/includes/kbase_class.php");
if (!is_object($kbase_obj))
{
    $kbase_obj = new kbase;
}
if (!is_object($kbase_gen))
{
    $kbase_gen = new convert;
}
global $e107;
// ##### e_rss.php ---------------------------------------------
// get all the categories
$feed['name'] = KBASELAN_RSS01;
$feed['url'] = "kbase";
$feed['topic_id'] = '';
$feed['path'] = 'kbase';
$feed['text'] = KBASELAN_RSS02 ;
$feed['class'] = '0';
$feed['limit'] = '9';
$eplug_rss_feed[] = $feed;
// ##### --------------------------------------------------------
// ##### create rss data, return as array $eplug_rss_data -------
$rss = array();
global $KBASE_PREF, $sql, $tp;
 // if ($kbase_obj->articulate_reader)
{
    // get kbases which are approved and are visible to this class viz everybody
    $kbase_args = "
    select s.*,c.kbase_info_title from #kbase as s
left join #kbase_info as c on s.kbase_parent=c.kbase_info_id
where s.kbase_approved>0 and find_in_set(kbase_info_class,'" . USERCLASS_LIST . "')
order by s.kbase_datestamp desc LIMIT 0," . $this->limit;

    if ($items = $sql->db_Select_gen($kbase_args, false))
    {
        $i = 0;
        while ($rowrss = $sql->db_Fetch())
        {
            $rss[$i]['author'] = $tp->toRss(substr($rowrss['kbase_author'], strpos($rowrss['kbase_author'], ".") + 1), false);

            $rss[$i]['author_email'] = '';
            $rss[$i]['link'] = $e107->base_path . $PLUGINS_DIRECTORY . "kbase/kbase.php?0.cat." . $rowrss['kbase_parent'] . "." . $rowrss['kbase_id'] ;
            $rss[$i]['linkid'] = $tp->toRss($rowrss['kbase_id'], false);
            $rss[$i]['title'] = $tp->toRss($rowrss['kbase_question'], false);;
            $rss[$i]['description'] = $tp->toRss($rowrss['kbase_answer'], false);

            $rss[$i]['category_name'] = $tp->toRss($rowrss['kbase_info_title'], false);
            $rss[$i]['category_link'] = $e107->base_path . $PLUGINS_DIRECTORY . "kbase/kbase.php?0.cat." . $rowrss['kbase_parent'] ;
            $rss[$i]['datestamp'] = $rowrss['kbase_datestamp'];
            $rss[$i]['enc_url'] = "";
            $rss[$i]['enc_leng'] = "";
            $rss[$i]['enc_type'] = "";
            $i++;
        }
    }
    else
    {
        $rss[$i]['author'] = "" ;
        $rss[$i]['author_email'] = '';
        $rss[$i]['link'] = $e107->base_path . $PLUGINS_DIRECTORY . "kbase/kbase.php";
        $rss[$i]['linkid'] = '';
        $rss[$i]['title'] = "";
        $rss[$i]['description'] = "none";
        $rss[$i]['category_name'] = "";
        $rss[$i]['category_link'] = '';
        $rss[$i]['datestamp'] = "";
        $rss[$i]['enc_url'] = "";
        $rss[$i]['enc_leng'] = "";
        $rss[$i]['enc_type'] = "";
    }
}
$eplug_rss_data[] = $rss;

?>