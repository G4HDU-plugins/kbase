<?php

if (!defined('e107_INIT'))
{
    exit;
}
// This is set to the table name you have decided to use.
$e_comment['eplug_comment_ids'] = "kbasefb";
// This is set to the location you'd like the user to return to after replying to a comment.
$e_comment['reply_location'] = e_PLUGIN . "kbase/kbase.php?0.cat.0.{NID}";
// A name for your plugin. It will be used in links to comments, in list_new/new.php.
$e_comment['plugin_name'] = "KBASE";
// The path of the plugin folder
$e_comment['plugin_path'] = "kbase";
// This is the name of the field in your plugin's db table that corresponds to it's name or title.
$e_comment['db_title'] = "kbase_question";
// This is the name of the field in your plugin's db table that correspond to it's unique id number.
$e_comment['db_id'] = "kbase_id";
// qry must be set with a select_gen query.
// the main reason would be to check if a category from another table has a class restriction
// the id of the item should be provided as {NID}
// returned fields should at least contain the 'link_id' and 'db_id' fields set above
$e_comment['qry'] = "
SELECT *
FROM #kbase
left join #kbase_info on kbase_parent=kbase_info_id
WHERE find_in_set(kbase_info_class,'" . USERCLASS_LIST . "') and kbase_id='{NID}' AND kbase_approved > 0 ";

?>