<?php
// **************************************************************************
// *
// *  KBASE Menu for e107 v7
// *
// **************************************************************************
require_once("../../class2.php");
if (!defined('e107_INIT'))
{
    exit;
}
if (!getperms("P"))
{
    header("location:" . e_HTTP . "index.php");
    exit;
}

require_once(e_ADMIN . "auth.php");
if (!defined('ADMIN_WIDTH'))
{
    define(ADMIN_WIDTH, "width:100%;");
}
require_once("includes/kbase_class.php");
if (!is_object($kbase_obj))
{
    $kbase_obj = new KBASE;
}
if ($_POST['kbase_action'] == "kbase_app")
{
    $kbase_apparray = $_POST['kbase_app'];
    foreach($kbase_apparray as $kbase_element)
    {
        if ($KBASE_PREF['kbase_log'] > 0)
        {
            $kbase_plugin = KBASE_LOG_01;
            $kbase_action = KBASE_LOG_09 . " " . intval($kbase_element);
        }
        else
        {
            $kbase_plugin = "";
            $kbase_action = "";
        }
        $sql->db_Update("kbase", "kbase_approved='1' where kbase_id='" . intval($kbase_element) . "' ", false, $kbase_plugin, $kbase_action);
    }
    $kbase_delarray = $_POST['kbase_del'];
    foreach($kbase_delarray as $kbase_element)
    {
        if ($KBASE_PREF['kbase_log'] > 0)
        {
            $kbase_plugin = KBASE_LOG_01;
            $kbase_action = KBASE_LOG_10 . " " . intval($kbase_element);
        }
        else
        {
            $kbase_plugin = "";
            $kbase_action = "";
        }
        $sql->db_Delete("kbase", "kbase_id='" . intval($kbase_element) . "' ", false, $kbase_plugin, $kbase_action);
    }

    $kbase_msg .= KBASE_ADLAN_107 ;
    $kbase_obj->kbase_cache_clear();
}

$kbase_text .= "
<script type=\"text/javascript\">
<!--
function checkAll(checkWhat) {
  // Find all the checkboxes...
  var inputs = document.getElementsByTagName(\"input\");

  // Loop through all form elements (input tags)
  for(index = 0; index < inputs.length; index++)
  {
    // ...if it's the type of checkbox we're looking for, toggle its checked status
    if(inputs[index].id == checkWhat)
      if(inputs[index].checked == 0)
      {
        inputs[index].checked = 1;
      }
      else if(inputs[index].checked == 1)
      {
        inputs[index].checked = 0;
      }
  }
}
-->
</script>

<form id='kbase_qap' action='" . e_SELF . "' method='post'>
	<div>
		<input type='hidden' name='kbase_action' value='kbase_app' />
	</div>
	<table class='fborder' style='" . ADMIN_WIDTH . "'>
		<tr>
			<td class='fcaption' colspan='5'>" . KBASE_ADLAN_99 . "</td>
		</tr>
				<tr>
			<td class='forumheader2' colspan='5'>" . $kbase_msg . "&nbsp;</td>
		</tr>";

$kbase_text .= "
		<tr>
			<td class='forumheader2' style='width:35%;'><span class='smalltext'>" . KBASE_ADLAN_100 . "</span></td>
			<td class='forumheader2' style='width:35%;'><span class='smalltext'>" . KBASE_ADLAN_101 . "</span></td>
			<td class='forumheader2' style='width:10%;'><span class='smalltext'>" . KBASE_ADLAN_102 . "</span></td>
			<td class='forumheader2' style='width:10%;text-align:center;'><img src='./images/approve.gif' alt='" . KBASE_ADLAN_103 . "' title='" . KBASE_ADLAN_103 . "' /></td>
			<td class='forumheader2' style='width:10%;text-align:center;'><img src='./images/delete.gif' alt='" . KBASE_ADLAN_104 . "' title='" . KBASE_ADLAN_104 . "' /></td>
		</tr>";

if ($sql->db_Select("kbase", "*", "where kbase_approved='0'", "nowhere"))
{
    while ($kbase_row = $sql->db_Fetch())
    {
        extract($kbase_row);
        $kbase_post = explode(".", $kbase_author, 2);
        $kbase_postname = $kbase_post[1];
        $kbase_text .= "
		<tr>
			<td class='forumheader3'>" . $tp->toHTML($tp->html_truncate($kbase_question, 50), false) . "</td>
			<td class='forumheader3'>" . $tp->toHTML($tp->html_truncate($kbase_answer, 50), false) . "</td>
			<td class='forumheader3'>" . $tp->toHTML($kbase_postname, false) . "&nbsp;</td>
			<td class='forumheader3' style='text-align:center;'><input type='checkbox' class='tbox' style='border:0;' name='kbase_app[]' id='app' value='$kbase_id' /></td>
			<td class='forumheader3' style='text-align:center;'><input type='checkbox' class='tbox' style='border:0;' name='kbase_del[]' id='delit' value='$kbase_id' /></td>
		</tr>";
    } // while
    $kbase_text .= "
		<tr>
			<td class='forumheader3' colspan='3' style='text-align:center;'>&nbsp;</td>
			<td class='forumheader3' style='text-align:center;'>
				<input class='button' type='button' name='CheckAlls' value='" . KBASE_ADLAN_105 . "'
onclick=\"checkAll('app');\" /></td>
<td class='forumheader3' style='text-align:center;'>
<input class='button' type='button' name='CheckAll' value='" . KBASE_ADLAN_105 . "'
onclick=\"checkAll('delit');\"  />
			</td>
		</tr>
		<tr>
			<td class='fcaption' colspan='5'><input class='button' type='submit' name='recipeub_app' value='" . KBASE_ADLAN_106 . "' /></td>
		</tr>";
}

else
{
    $kbase_text .= "
		<tr>
			<td class='forumheader3' colspan='5'><b>" . KBASE_ADLAN_108 . "</b></td>
		</tr>";
}
$kbase_text .= "
		<tr>
			<td class='fcaption' colspan='5'>&nbsp;</td>
		</tr>
	</table>
</form>";

$ns->tablerender(KBASE_ADLAN_98, $kbase_text);
require_once(e_ADMIN . "footer.php");

?>
