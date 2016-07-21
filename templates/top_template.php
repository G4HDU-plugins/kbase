<?php
if (!defined('e107_INIT'))
{
    exit;
}
include_lan(e_PLUGIN . "kbase/languages/" . e_LANGUAGE . ".php");
if (!function_exists("kbase_mtemplate"))
{
    function kbase_mtemplate($top_show_author, $top_show_date, $top_show_category,$top_show_info)
    {
        $top_returnval = "{TOPMENU_ITEM}<br />";
        if ($top_show_info)
        {
            $top_returnval .= "{TOPMENU_INFO}<br />";
        }
        if ($top_show_author)
        {
            $top_returnval .= KBASELAN_138." {TOPMENU_POSTER}<br />";
        }
        if ($top_show_date)
        {
            $top_returnval .= KBASELAN_139." {TOPMENU_DATE}<br />";
        }
        if ($top_show_category)
        {
            $top_returnval .= KBASELAN_140." {TOPMENU_CATEGORY}<br />";
        }
        $top_returnval .= "<br />";
        return $top_returnval;
    }
}
if (!function_exists("kbase_ptemplate"))
{
    function kbase_ptemplate($top_show_author, $top_show_date, $top_show_category,$top_show_info)
    {
        $top_returnval = "{TOPMENU_ITEM}<br />";
        if ($top_show_info)
        {
            $top_returnval .= "{TOPMENU_INFO}<br />";
        }
        if ($top_show_author)
        {
            $top_returnval .= KBASELAN_138." {TOPMENU_POSTER}<br />";
        }
        if ($top_show_date)
        {
            $top_returnval .= KBASELAN_139." {TOPMENU_DATE}<br />";
        }
        if ($top_show_category)
        {
            $top_returnval .= KBASELAN_140." {TOPMENU_CATEGORY}<br />";
        }
        $top_returnval .= "<br />";
        return $top_returnval;
    }
}

?>