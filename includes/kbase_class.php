<?php
if (!defined('e107_INIT')){
	exit;
}

require_once(e_HANDLER . "ren_help.php");
require_once(e_HANDLER . "news_class.php");
require_once(e_HANDLER . "form_handler.php");
require_once(e_HANDLER . "userclass_class.php");

require_once(e_HANDLER . "rate_class.php");


e107::js('kbase', 'js/kbase.js', 'jquery'); // Load Plugin javascript and include jQuery framework
e107::css('kbase', 'css/kbase.css'); // load css file
e107::lan('kbase', 'kbase');

class kbase
{
	private $prefs;
    var $kbase_read = false;
    var $kbase_ownedit = false;
    var $kbase_super = false;
    var $kbase_add = false;
    var $kbase_autoapprove = false;
    var $kbase_sendto = false;
    var $kbase_viewstats = false;
    var $kbase_rating = false;
    var $kbase_simple = false;
    var $kbase_simpleid = 0;
    var $kbase_log = false;
    var $kbase_random = false;
    var $kbase_topnum = 0;
    // var $kbase_sendto = false;
    function __construct()
    {
    	$this->mes = e107::getMessage();
    	$this->tp = e107::getParser();
    	$this->frm = e107::getForm();
    	$this->sql = e107::getDB();
    	$this->prefs = e107::getPlugConfig( 'kbase', '', true );

	//	$this->sc = e107::getScBatch( 'kbase', true );
  //  	$this->template = new kbaseTemplate;

        $this->load_prefs();
        $this->kbase_super = check_class($KBASE_PREF['kbase_super']);
        $this->kbase_add = check_class($KBASE_PREF['add_kbase']) || $this->kbase_super;
        $this->kbase_read = check_class($KBASE_PREF['kbase_user']) || $this->kbase_add || $this->kbase_super;
        $this->kbase_autoapprove = check_class($KBASE_PREF['kbase_approve']) || $this->kbase_super;
        $this->kbase_viewstats = check_class($KBASE_PREF['kbase_stats']) || $this->kbase_super;
        $this->kbase_sendto = check_class($KBASE_PREF['kbase_sendto']) || $this->kbase_super;
        $this->kbase_ownedit = $KBASE_PREF['kbase_ownedit'];
        $this->kbase_rating = $KBASE_PREF['kbase_rating'] > 0;
        $this->kbase_showposter = $KBASE_PREF['kbase_showposter'] > 0;
        $this->kbase_picupload = $KBASE_PREF['kbase_picupload'] > 0;
        $this->kbase_simple = $KBASE_PREF['kbase_simple'] > 0;
        $this->kbase_simpleid = $KBASE_PREF['kbase_simple'] ;
        $this->kbase_log = $KBASE_PREF['kbase_log'] > 0 ;
        $this->kbase_random = $KBASE_PREF['kbase_showrand'] > 0 ;
        $this->kbase_topnum = $KBASE_PREF['kbase_top'];
        /*
 		// For debugging
        if ($this->kbase_super)
        {
            print "super ";
        }
        if ($this->kbase_add)
        {
            print "add ";
        }
        if ($this->kbase_read)
        {
            print "read ";
        }
*/
    }
    // ********************************************************************************************
    // *
    // * KBASE load and Save prefs
    // *
    // ********************************************************************************************

	function getdefaultprefs()
    {
        global $KBASE_PREF, $pref;
        if (isset($pref['kbase_user']))
        {
            // if we have prefs set in the old way copy them to the new way and delete those prefs
            $KBASE_PREF['kbase_user'] = $pref['kbase_user'];
            $KBASE_PREF['add_kbase'] = $pref['add_kbase'];
            $KBASE_PREF['kbase_approve'] = $pref['kbase_approve'];
            $KBASE_PREF['kbase_defcomments'] = $pref['kbase_defcomments'];
            $KBASE_PREF['kbase_allowcomments'] = $pref['kbase_allowcomments'];
            $KBASE_PREF['kbase_ownedit'] = $pref['kbase_ownedit'];
            $KBASE_PREF['kbase_super'] = $pref['kbase_super'];
            $KBASE_PREF['kbase_description'] = $pref['kbase_description'];
            $KBASE_PREF['kbase_keywords'] = $pref['kbase_keywords'];
            $KBASE_PREF['kbase_showposter'] = $pref['kbase_showposter'];
            $KBASE_PREF['kbase_picupload'] = $pref['kbase_picupload'];
            $KBASE_PREF['kbase_sendto'] = $pref['kbase_sendto'];
            $KBASE_PREF['kbase_title'] = $pref['kbase_title'];
            $KBASE_PREF['kbase_mtext'] = $pref['kbase_mtext'];
            $KBASE_PREF['kbase_perpage'] = $pref['kbase_perpage'];
            $KBASE_PREF['kbase_showrand'] = $pref['kbase_showrand'];
            $KBASE_PREF['kbase_top'] = $pref['kbase_top'];
            $KBASE_PREF['kbase_log'] = $pref['kbase_log'];
            $KBASE_PREF['kbase_stats'] = $pref['kbase_stats'];
            $KBASE_PREF['kbase_rating'] = $pref['kbase_rating'];
            $KBASE_PREF['kbase_simple'] = $pref['kbase_simple'];

            unset($pref['kbase_user']);
            unset($pref['add_kbase']);
            unset($pref['kbase_approve']);
            unset($pref['kbase_defcomments']);
            unset($pref['kbase_allowcomments']);
            unset($pref['kbase_ownedit']);
            unset($pref['kbase_super']);
            unset($pref['kbase_description']);
            unset($pref['kbase_keywords']);
            unset($pref['kbase_showposter']);
            unset($pref['kbase_picupload']);
            unset($pref['kbase_sendto']);
            unset($pref['kbase_title']);
            unset($pref['kbase_mtext']);
            unset($pref['kbase_perpage']);
            unset($pref['kbase_showrand']);
            unset($pref['kbase_top']);
            unset($pref['kbase_log']);
            unset($pref['kbase_stats']);
            unset($pref['kbase_rating']);
            unset($pref['kbase_simple']);
            $this->save_prefs();
            save_prefs();
        }
        else
        {
            // otherwise create new default prefs
            $KBASE_PREF = array("kbase_user" => 254,
                "add_kbase" => 254,
                "kbase_approve" => 254,
                "kbase_defcomments" => 254,
                "kbase_allowcomments" => 254,
                "kbase_ownedit" => 0,
                "kbase_super" => 254,
                "kbase_keywords" => "Blank",
                "kbase_description" => "Blank",
                "kbase_showposter" => 1,
                "kbase_picupload" => 1,
                "kbase_sendto" => 254,
                "kbase_title" => "KBASE",
                "kbase_mtext" => "Did you know?",
                "kbase_perpage" => 10,
                "kbase_showrand" => 1,
                "kbase_top" => 10,
                "kbase_log" => 1,
                "kbase_stats" => 254,
                "kbase_rating" => 1,
                "kbase_simple" => 0
                );
        }
    }
    function save_prefs()
    {
        global $sql, $eArrayStorage, $KBASE_PREF;
        // save preferences to database
        if (!is_object($sql))
        {
            $sql = new db;
        }
        $tmp = $eArrayStorage->WriteArray($KBASE_PREF);
        $sql->db_Update("core", "e107_value='$tmp' where e107_name='kbase'", false);
        return ;
    }
    function load_prefs()
    {
        global $sql, $eArrayStorage, $KBASE_PREF;
        // get preferences kbase_from database
        if (!is_object($sql))
        {
            $sql = new db;
        }
        $num_rows = $sql->db_Select("core", "*", "e107_name='kbase' ");
        $row = $sql->db_Fetch();
        if (empty($row['e107_value']))
        {
            // insert default preferences if none exist
            $this->getDefaultPrefs();
            $tmp = $eArrayStorage->WriteArray($KBASE_PREF);
            $sql->db_Insert("core", "'kbase', '$tmp' ");
            $sql->db_Select("core", "*", "e107_name='kbase' ");
        }
        else
        {
            $KBASE_PREF = $eArrayStorage->ReadArray($row['e107_value']);
        }
        return;
    }
    function kbase_cache_clear($kbase_menu = false)
    {
        global $e107cache;
        $e107cache->clear("nq_kbasetop_menu");
        $e107cache->clear("nq_kbase_menu");
        $e107cache->clear("nq_kbasenew_menu");
        if (!$kbase_menu)
        {
            // if we're not just clearing the menu
            $e107cache->clear("kbase");
            $e107cache->clear("kbasecat");
        }
    }
    function show_parents($kbase_action, $sub_action, $id, $kbase_from, $amount)
    {
        global $e107, $e107cache, $subparents, $kbase_view_rating, $kbase_info_id, $kbase_info_title, $kbase_info_icon, $kbase_info_about, $cnt, $sql, $sql2, $rs, $tp, $KBASE_PREF, $kbase_from, $kbase_shortcodes, $KBASE_LISTPARENT_FOOTER, $KBASE_LISTPARENT_DETAIL, $KBASE_LISTPARENT_HEADER, $KBASE_LISTPARENT_TABLE;
        $cache_tag = "kbase";
        if ($cacheData = $e107cache->retrieve($cache_tag))
        {
            $kbase_text .= $cacheData;
        }
        else
        {
            $sql3 = new db;
            $kbase_cache .= $tp->parseTemplate($KBASE_LISTPARENT_HEADER, true, $kbase_shortcodes);
            if ($sql->db_Select("kbase_info", "*", "where kbase_info_parent='0' and find_in_set(kbase_info_class,'" . USERCLASS_LIST . "') ORDER BY kbase_info_order ASC", "nowhere", false))
            {
                while ($row3 = $sql->db_Fetch())
                {
                    extract($row3);
                    $subparents = $sql2->db_Select("kbase_info", "*", "where kbase_info_parent='" . intval($kbase_info_id) . "' and find_in_set(kbase_info_class,'" . USERCLASS_LIST . "') ORDER BY kbase_info_order ASC", "nowhere", false);
                    $kbase_cache .= $tp->parseTemplate($KBASE_LISTPARENT_TABLE, true, $kbase_shortcodes);
                    if (!$subparents)
                    {
                        $kbase_cache .= $tp->parseTemplate($KBASE_LISTPARENT_DETAIL, true, $kbase_shortcodes);
                    }
                    else
                    {
                        while ($row = $sql2->db_Fetch())
                        {
                            extract($row);
                            $cnt = $sql3->db_Count("kbase", "(*)", "WHERE kbase_parent = '$kbase_info_id' and kbase_approved > 0 ");
                            $kbase_cache .= $tp->parseTemplate($KBASE_LISTPARENT_DETAIL, true, $kbase_shortcodes);
                        }
                    }
                }
            }
            $kbase_cache .= $tp->parseTemplate($KBASE_LISTPARENT_FOOTER, true, $kbase_shortcodes);
            $e107cache->set($cache_tag, $kbase_cache);
            $kbase_text .= $kbase_cache;
        }

        return $kbase_text;
    }
    function view_list($kbase_action, $id)
    {
        global $e107, $e107cache, $kbase_cat_name, $kbase_view_rating, $kbase_rater, $sql, $kbase_action, $row2, $res, $kbase_count, $kbase_info_icon, $kbase_id, $id, $kbase_from, $kbase_question, $ns, $aj, $KBASE_PREF, $kbase_from, $tp, $KBASE_LIST_HEADER, $KBASE_LIST_DETAIL, $KBASE_LIST_FOOTER, $kbase_shortcodes;
        $cache_tag = "kbasecat";
        if ($cacheData = $e107cache->retrieve($cache_tag))
        {
            $kbase_text .= $cacheData;
        }
        else
        {
            // get the category
            $sql->db_Select("kbase_info", "*", "where kbase_info_id='" . intval($id) . "' ", "nowhere", false);
            $row2 = $sql->db_Fetch();
            $kbase_info_icon = $row2['kbase_info_icon'];
            $kbase_cat_name = $row2['kbase_info_title'];
            $kbase_text .= $tp->parseTemplate($KBASE_LIST_HEADER, true, $kbase_shortcodes);
            // check that this category is permitted for the user
            if (check_class($row2['kbase_info_class']))
            {
                // count the records for the page display
                $kbase_count = $sql->db_Count("kbase", "(*)", "where kbase_parent='$id' and kbase_approved > 0 ");
                $kbase_arg = "select kbase_question,kbase_id,kbase_parent from #kbase
		left join #kbase_info on kbase_info_id=kbase_parent where kbase_parent='$id' and kbase_approved > 0 and find_in_set(kbase_info_class,'" . USERCLASS_LIST . "') order by kbase_order limit $kbase_from," . $KBASE_PREF['kbase_perpage'] . "";
                if ($res = $sql->db_Select_gen($kbase_arg))
                {
                    while ($row = $sql->db_Fetch())
                    {
                        extract($row);
                        $kbase_view_rating = "";
                        if ($kbase_ratearray = $kbase_rater->getrating("kbase", $kbase_id))
                        {
                            if (defined("IMODE"))
                            {
                                $kbase_star = e_IMAGE . "rate/" . IMODE;
                            }
                            else
                            {
                                $image = $kbase_star = e_IMAGE . "rate/lite";
                            }
                            for($c = 1;
                                $c <= $kbase_ratearray[1];
                                $c++)
                            {
                                $kbase_view_rating .= "<img src='{$kbase_star}/star.png' alt='' />";
                            }
                            if ($kbase_ratearray[2])
                            {
                                $kbase_view_rating .= "<img src='{$kbase_star}/" . $kbase_ratearray[2] . ".png'  alt='' />";
                            }
                            if ($kbase_ratearray[2] == "")
                            {
                                $kbase_ratearray[2] = 0;
                            }
                            $kbase_view_rating .= "&nbsp;&nbsp;" . $kbase_ratearray[1] . "." . $kbase_ratearray[2] . " - " . $kbase_ratearray[0] . "&nbsp;";
                            $kbase_view_rating .= ($kbase_ratearray[0] == 1 ? KBASELAN_115 : KBASELAN_114);
                        }
                        else
                        {
                            $kbase_view_rating = "&nbsp;";
                        }
                        $kbase_text .= $tp->parseTemplate($KBASE_LIST_DETAIL, true, $kbase_shortcodes);
                    }
                }
                else
                {
                    $kbase_text .= $tp->parseTemplate($KBASE_LIST_DETAIL, true, $kbase_shortcodes);
                }
            }

            $kbase_text .= $tp->parseTemplate($KBASE_LIST_FOOTER, true, $kbase_shortcodes);
            $kbase_cache .= $tp->parseTemplate($KBASE_LISTPARENT_FOOTER, true, $kbase_shortcodes);
            $e107cache->set($cache_tag, $kbase_text);
        }
        return $kbase_text;
    }
    function view_kbase($idx)
    {
        global $pref, $e107, $kbase_obj, $e107cache, $kbase_rater, $kbase_rate_text, $idx, $sql2, $kbase_datestamp, $kbase_author, $kbase_views,
        $kbase_unique, $kbase_answer, $kbase_question, $kbase_from, $kbase_parent, $kbase_authid,
        $kbase_id, $ns, $sql, $tp, $KBASE_PREF, $kbase_cobj, $kbase_from, $kbase_id, $kbase_shortcodes,
        $KBASE_ITEM_HEADER, $KBASE_ITEM_FOOTER, $KBASE_ITEM_DETAIL,$kbase_updated;
        $kbase_arg = "select f.*,i.* from #kbase as f left join #kbase_info as i on kbase_parent=kbase_info_id where find_in_set(kbase_info_class,'" . USERCLASS_LIST . "') and kbase_id='$idx'";
        if (!$sql->db_Select_Gen($kbase_arg, false))
        {
            $kbase_text .= "<table class='fborder' width='97%'>
		<tr><td class='fcaption'>" . KBASELAN_67 . "</td></tr>
		<tr><td class='forumheader3'>" . KBASELAN_80 . "<br />
		<a href='" . e_PLUGIN . "kbase/kbase.php'>" . KBASELAN_75 . "</a></td></tr></table>";
            $ns->tablerender(KBASELAN_KBASE, $kbase_text);
        }
        else
        {
            $row = $sql->db_Fetch();
            extract($row);
            if (USER)
            {
                $kbase_usercheck = USERID;
            }
            else
            {
                $kbase_usercheck = $e107->getip();
            }
            $kbase_viewer .= $kbase_usercheck . ",";
            $sql->db_Update("kbase", "kbase_unique=kbase_unique+1,kbase_viewer='" . $kbase_viewer . "' where (isnull(kbase_viewer) or not find_in_set('{$kbase_usercheck}',kbase_viewer)) and kbase_id='$idx'", false);
            $sql->db_Update("kbase", "kbase_views=kbase_views+1 where kbase_id='$idx'", false);
            $kbase_obj->kbase_cache_clear(true);
            $sql->db_Select_Gen($kbase_arg, false);
            $row = $sql->db_Fetch();
            extract($row);
            $kbase_tmp = explode(".", $kbase_author, 2);
            $kbase_authid = $kbase_tmp[0];
            // Do rating if turned on
            if ($kbase_obj->kbase_rating)
            {
                // rating
                if ($kbase_ratearray = $kbase_rater->getrating("kbase", $idx))
                {
                    if (defined("IMODE"))
                    {
                        $kbase_star = e_IMAGE . "rate/" . IMODE;
                    }
                    else
                    {
                        $image = $kbase_star = e_IMAGE . "rate/lite";
                    }
                    for($c = 1;
                        $c <= $kbase_ratearray[1];
                        $c++)
                    {
                        $kbase_view_rating .= "<img src='{$kbase_star}/star.png' alt='' />";
                    }
                    if ($kbase_ratearray[2])
                    {
                        $kbase_view_rating .= "<img src='{$kbase_star}/" . $kbase_ratearray[2] . ".png'  alt='' />";
                    }
                    if ($kbase_ratearray[2] == "")
                    {
                        $kbase_ratearray[2] = 0;
                    }
                    $kbase_view_rating .= "&nbsp;&nbsp;" . $kbase_ratearray[1] . "." . $kbase_ratearray[2] . " - " . $kbase_ratearray[0] . "&nbsp;";
                    $kbase_view_rating .= ($kbase_ratearray[0] == 1 ? KBASELAN_115 : KBASELAN_114);
                }
                else
                {
                    $kbase_view_rating .= KBASELAN_116;
                }

                if (!$kbase_rater->checkrated("kbase", $idx) && USER)
                {
                    $kbase_view_rating .= $kbase_rater->rateselect("<br /><b>" . KBASELAN_117 . " ", "kbase", $idx) . "</b>";
                }
                else if (!USER)
                {
                    $kbase_view_rating .= "&nbsp;";
                }
                else
                {
                    $kbase_view_rating .= " (" . KBASELAN_118 . ")";
                }
                $kbase_view_rating .= "&nbsp;";
                // rating
                $kbase_rate_text .= $kbase_view_rating;
            }
            $theanswer = $tp->parseTemplate($KBASE_ITEM_HEADER, true, $kbase_shortcodes);
            $theanswer .= $tp->parseTemplate($KBASE_ITEM_DETAIL, true, $kbase_shortcodes);
            $theanswer .= $tp->parseTemplate($KBASE_ITEM_FOOTER, true, $kbase_shortcodes);

            $kbase_text .= $theanswer;
            $subject = (!$subject ? $tp->toFORM($kbase_question, false, "no_make_clickable no_replace emotes_off") : $subject);
            // If this kbase allows comments
            if (check_class($kbase_comment))
            {
                $kbase_action = "comment";
                $table = "kbasefb";

                $query = ($pref['nested_comments'] ? "where comment_item_id='$idx' AND comment_type='$table' AND comment_pid='0' ORDER BY comment_datestamp" : "where comment_item_id='$idx' AND comment_type='$table' ORDER BY comment_datestamp");
                if ($comment_total = $sql2->db_Select("comments", "*", $query, "nowhere", false))
                {
                    $width = 0;
                    while ($row = $sql2->db_Fetch())
                    {
                        $kbase_text .= $kbase_cobj->render_comment($row, $table, $kbase_action, $idx , $width, $subject);
                    }
                }

                $kbase_text .= $kbase_cobj->form_comment($kbase_action, $table, $idx , $subject, $content_type, true, false, false);
                if (ADMIN && getperms("B"))
                {
                    $kbase_text .= "<div style='text-align:right'><a href='" . e_ADMIN . "modcomment.php?kbasefb.$kbase_id'>moderate comments</a></div><br />";
                }
            } // end of check_class
            return $kbase_text;
        }
    }
    function add_kbase($kbase_action, $id, $idx)
    {
        global $e107, $kbase_obj, $kbase_answer, $message, $kbase_comment, $kbase_parent, $kbase_question, $tp, $sql, $rs, $KBASE_PREF, $kbase_from, $idx, $kbase_shortcodes, $KBASE_EDIT_FOOTER, $KBASE_EDIT_DETAIL, $KBASE_EDIT_HEADER;
        $userid = USERID;
        if ($kbase_action == "new")
        {
            $data = "";
            $kbase_question = "";
        }
        if ($kbase_action == "reedit")
        {
            $kbase_answer = $_POST['data'];
            $kbase_parent = $_POST['kbase_parent'];
            $kbase_question = $_POST['kbase_question'];
            $kbase_comment = $_POST['kbase_comment'];
        }
        if ($kbase_action == "edit")
        {
            $sql->db_Select("kbase", "*", " kbase_id = '$idx' ");
            $row = $sql->db_Fetch();
            extract($row);
            $data = $kbase_answer;
            $sql->db_Select("kbase_info", "*", "kbase_info_id='$kbase'");
            $row = $sql->db_Fetch();
            extract($row);
        }
        $kbase_text .= "<form method='post' action='" . e_SELF . "?' id='dataform' enctype='multipart/form-data'>
	<div>
	<input type='hidden' name='kbase' value='$kbase' />
	<input type='hidden' name='id' value='$id' />
	<input type='hidden' name='idx' value='$idx' />
	<input type='hidden' name='kbase_from' value='$kbase_from' />
	<input type='hidden' name='action' value='save' />
	</div>";
        $kbase_text .= $tp->parseTemplate($KBASE_EDIT_HEADER, true, $kbase_shortcodes);
        $kbase_text .= $tp->parseTemplate($KBASE_EDIT_DETAIL, true, $kbase_shortcodes);
        $kbase_text .= $tp->parseTemplate($KBASE_EDIT_FOOTER, true, $kbase_shortcodes);
        $kbase_text .= "</form>";
        return $kbase_text;
    }
    function tablerender($caption, $text, $mode = "default", $return = false)
    {
        global $ns, $KBASE_PREF;
        // do the mod rewrite steps if installed
        #$modules = apache_get_modules();
        if ( $KBASE_PREF['kbase_seo'] == 1 && file_exists(e_PLUGIN.'kbase/.htaccess'))
        {
            $patterns[0] = '/' . $PLUGINS_DIRECTORY . '\/kbase\/kbase\.php\?([0-9]+).([a-z]+).([0-9]+).([0-9]+)/';
            $patterns[1] = '/' . $PLUGINS_DIRECTORY . '\/kbase\/kbase\.php\?([0-9]+).([a-z]+).([0-9]+)/';
            $patterns[2] = '/' . $PLUGINS_DIRECTORY . '\/kbase\/kbase_stats\.php/';
            $replacements[0] = '/kbase/kbase-$1-$2-$3-$4.html';
            $replacements[1] = '/kbase/kbase-$1-$2-$3.html';
            $replacements[2] = '/kbase/kbase_stats.html';

            $text = preg_replace($patterns, $replacements, $text);
        }
        $ns->tablerender($caption, $text, $mode , $return);
    }
    function regen_htaccess($onoff)
    {
        $hta = '.htaccess';
        $pattern = array("\n", "\r");
        $replace = array("", "");
        if (is_writable($hta) || !file_exists($hta))
        {
            // open the file for reading and get the contents
            $file = file($hta);
            $skip_line = false;
            unset($new_line);
            foreach($file as $line)
            {
                if (strpos($line, '*** KBASE REWRITE BEGIN ***') > 0)
                {
                    // we start skipping
                    $skip_line = true;
                }

                if (!$skip_line)
                {
                    // print strlen($line) . '<br>';
                    $new_line[] = str_replace($pattern, $replace, $line);
                }
                if (strpos($line, '*** KBASE REWRITE END ***') > 0)
                {
                    $skip_line = false;
                }
            }
            if ($onoff == 'on')
            {
                $base_loc = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
                $new_line[] = "#*** KBASE REWRITE BEGIN ***";
                $new_line[] = 'RewriteEngine On';
                $new_line[] = "RewriteBase $base_loc";
                $new_line[] = 'RewriteRule kbase.html kbase.php';
                $new_line[] = 'RewriteRule kbase_stats.html kbase_stats.php';
                $new_line[] = 'RewriteRule kbase-([0-9]*)-([a-z]*)-([0-9]*)\.html(.*)$ kbase.php?$1.$2.$3';
                $new_line[] = 'RewriteRule kbase-([0-9]*)-([a-z]*)-([0-9]*)-([0-9]*)\.html(.*)$ kbase.php?$1.$2.$3.$4';
                $new_line[] = '#*** KBASE REWRITE END ***';
                $outwrite = implode("\n", $new_line);
            }
            else
            {

                $outwrite = implode("\n", $new_line);
            }
            $retval = 0;
            if ($fp = fopen('tmp.txt', 'wt'))
            {
                // we can open the file for reading
                if (fwrite($fp, $outwrite)!==false)
                {
                    fclose($fp);
                    // we have written the new data to temp file OK
                    if (is_readable('old.htaccess'))
                    {
                        // there is an old htaccess file so delete it
                        if (!unlink('old.htaccess'))
                        {
                            $retval = 2;
                        }
                    }
                    if ($retval == 0)
                    {
                        // old one deleted OK so rename the existing to the old one
                        if (is_readable('.htaccess'))
                        {
                            // if there is an old .htaccess then rename it
                            if (!rename('.htaccess', 'old.htaccess'))
                            {
                                $retval = 3;
                            }
                        }
                    }
                    if ($retval == 0)
                    {
                        // successfully renamed existing htaccess to old.htaccess
                        // so rename the temp file to .htaccess
                        if (!rename('tmp.txt', '.htaccess'))
                        {
                            $retval = 4;
                        }
                    }
                }
                else
                {
                    // unable to open temporary file
                    $retval = 5;
                }
            }
            else
            {
                fclose($fp);
                $retval = 1;
            }
            return $retval;
            // unlink('old.htaccess');
            // print_a($new_line);
        }
    }
}
