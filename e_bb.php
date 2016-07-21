<?php

$kbaseimg['name'] = 'kbaseimg';
$kbaseimg['onclick'] = "expandit";
$kbaseimg['onclick_var'] = "prekbaseimage_selector";
$kbaseimg['icon'] = e_PLUGIN . "kbase/images/kbasebutton.png";
$kbaseimg['helptext'] = KBASELAN_147;
$kbaseimg['function'] = 'kbaseimage_Select';
# $kbaseimg['function_var'] = $myfunction_vars;
// only show the kbase button on those pages related to the KBASEs
# print $_SERVER['HTTP_REFERER'];
if (strpos($_SERVER['HTTP_REFERER'], "/kbase") > 0 || strpos($_SERVER['HTTP_REFERER'], "edit.entries") > 0 || strpos($_SERVER['HTTP_REFERER'], "edit.record") > 0)
{
    $BBCODE_TEMPLATE .= "{BB=kbaseimg}";
    # $BBCODE_TEMPLATE_NEWSPOST .= "{BB=kbaseimg}";
    $BBCODE_TEMPLATE_ADMIN .= "{BB=kbaseimg}";
    $BBCODE_TEMPLATE_CPAGE .= "{BB=kbaseimg}";
}

$eplug_bb[] = $kbaseimg; // add to the global list - Very Important!

// *
// *	The function to get the list of images and display them in a pop up
// *
if (!function_exists("kbaseimage_Select"))
{
    function kbaseimage_Select($formid)
    {
        global $fl, $tp, $bbcode_imagedir;

        $path = e_PLUGIN . "kbase/graphics/";
        $formid = ($formid) ? ($formid) : "prekbaseimage_selector";

        if (!is_object($fl))
        {
            require_once(e_HANDLER . "file_class.php");
            $fl = new e_file;
        }

        $rejecthumb = array('$.', '$..', '/', 'CVS', 'thumbs.db', '*._$', 'index', 'null*');
        $imagelist = $fl->get_files($path, "", $rejecthumb, 2);
        sort($imagelist);

        $text = "
		<!-- Start of PreKBASEImage selector -->
		<div style='margin-left:0px;margin-right:0px; position:relative;z-index:1000;float:right;display:none' id='{$formid}'>";
        $text .= "<div style='position:absolute; bottom:30px; right:100px'>";
        $text .= "
			<table class='fborder' style='background-color: #fff'>
				<tr>
					<td class='forumheader3' style='white-space: nowrap'>";

        if (!count($imagelist))
        {
            $text .= LANHELP_46 . "<b>" . str_replace("../", "", $path) . "</b>";
        }
        else
        {
            $text .= "<select class='tbox' name='preimagkbaseeselect' onchange=\"addtext(this.value); expandit('{$formid}')\">
				<option value='' selected='selected'>" . LANHELP_42 . "</option>";
            foreach($imagelist as $image)
            {
                $e_path = $tp->createConstants($image['path'], 1);
                $showpath = str_replace($path, "", $image['path']);

                $text .= "<option value=\"[kbaseimg]" . $image['fname'] . "[/kbaseimg]\">" . $showpath . $image['fname'] . "</option>\n";
            }
            $text .= "</select>";
        }
        $text .= "</td></tr>\n
		</table></div>
	</div>\n<!-- End of PreKBASEImage selector -->\n";
        return $text;
    }
}

?>