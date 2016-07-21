<?php
/*
 *
 *
 * Copyright (C) 2008-2015 G4HDU)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * kbase Plugin Administration UI
 *
 * $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.8/e107_plugins/release/includes/admin.php $
 * $Id: admin.php 12212 2011-05-11 22:25:02Z e107coders $
*/

if (!defined('e107_INIT')){
    exit;
}
error_reporting(E_ALL);
/**
* plugin_kbase_admin
*
* @package
* @author barry
* @copyright Copyright (c) 2015
* @version $Id$
* @access public
*/
class plugin_kbase_admin extends e_admin_dispatcher{
    /**
    * Format: 'MODE' => array('controller' =>'CONTROLLER_CLASS'[, 'index' => 'list', 'path' => 'CONTROLLER SCRIPT PATH', 'ui' => 'UI CLASS NAME child of e_admin_ui', 'uipath' => 'UI SCRIPT PATH']);
    * Note - default mode/action is autodetected in this order:
    * - $defaultMode/$defaultAction (owned by dispatcher - see below)
    * - $adminMenu (first key if admin menu array is not empty)
    * - $modes (first key == mode, corresponding 'index' key == action)
    *
    * @var array
    */
    protected $modes = array (
        'articles' => array (
            'controller' => 'kbase_articles_admin_ui',
            'path' => null,
            'ui' => 'kbase_articles_admin_form_ui',
            'uipath' => null
            ),
        'category' => array (
            'controller' => 'kbase_category_admin_ui',
            'path' => null,
            'ui' => 'kbase_category_admin_form_ui',
            'uipath' => null
            ),
        'images' => array (
            'controller' => 'kbase_images_admin_ui',
            'path' => null,
            'ui' => 'kbase_images_admin_form_ui',
            'uipath' => null
            ),
        );

    /* Both are optional
	protected $defaultMode = null;
	protected $defaultAction = null;
	*/

    /**
    * Format: 'MODE/ACTION' => array('caption' => 'Menu link title'[, 'url' => '{e_PLUGIN}release/admin_config.php', 'perm' => '0']);
    * Additionally, any valid e107::getNav()->admin() key-value pair could be added to the above array
    *
    * @var array
    */
    protected $adminMenu = array(
        'articles/list' => array('caption' => 'Articles', 'perm' => 'P'),
        'articles/create' => array('caption' => 'Create', 'perm' => 'P'),

        'other0' => array('divider' => true),

        'category/categorylist' => array('caption' => 'Categories', 'perm' => 'P'),
        'category/categorycreate' => array('caption' => 'Create', 'perm' => 'P'),

        'other1' => array('divider' => true),

        'articles/settings' => array('caption' => "Preferences", 'perm' => 'P'),
        'articles/images' => array('caption' => "Images", 'perm' => 'P'),
        );

    /**
    * Optional, mode/action aliases, related with 'selected' menu CSS class
    * Format: 'MODE/ACTION' => 'MODE ALIAS/ACTION ALIAS';
    * This will mark active articles/list menu item, when current page is articles/edit
    *
    * @var array
    */
    protected $adminMenuAliases = array(
        'articles/edit' => 'articles/list'
        );

    /**
    * Navigation menu title
    *
    * Dsiplays at top of admin menu
    *
    * @var string
    */
    protected $menuTitle = "Knowledge Base";
}

/**
* kbase_articles_admin_ui
*
* @package
* @author barry
* @copyright Copyright (c) 2015
* @version $Id$
* @access public
*/
class kbase_articles_admin_ui extends e_admin_ui{
    // required
    protected $pluginTitle = "Knowledge Base";

    /**
    * plugin name or 'core'
    * IMPORTANT: should be 'core' for non-plugin areas because this
    * value defines what CONFIG will be used. However, I think this should be changed
    * very soon (awaiting discussion with Cam)
    * Maybe we need something like $prefs['core'], $prefs['blank'] ... multiple getConfig support?
    *
    * @var string
    */
    protected $pluginName = 'kbase';

    /**
    * DB Table, table alias is supported
    * Example: 'r.blank'
    *
    * @var string
    */
    protected $table = "kbase";

    /**
    * If present this array will be used to build your list query
    * You can link fileds from $field array with 'table' parameter, which should equal to a key (table) from this array
    * 'leftField', 'rightField' and 'fields' attributes here are required, the rest is optional
    * Table alias is supported
    * Note:
    * - 'leftTable' could contain only table alias
    * - 'leftField' and 'rightField' shouldn't contain table aliases, they will be auto-added
    * - 'whereJoin' and 'where' should contain table aliases e.g. 'whereJoin' => 'AND u.user_ban=0'
    *
    * @var array [optional] table_name => array join parameters
    */
    protected $tableJoin = array(
        // 'u.user' => array('leftField' => 'comment_author_id', 'rightField' => 'user_id', 'fields' => '*'/*, 'leftTable' => '', 'joinType' => 'LEFT JOIN', 'whereJoin' => '', 'where' => ''*/)
        );

    /**
    * This is only needed if you need to JOIN tables AND don't wanna use $tableJoin
    * Write your list query without any Order or Limit.
    *
    * @var string [optional]
    */
    protected $listQry = "";
    // optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
    // protected $editQry = "SELECT * FROM #blank WHERE blank_id = {ID}";
    // required - if no custom model is set in init() (primary id)
    protected $pid = "kbase_id";
    // optional
    protected $perPage = 20;
    // default - true - TODO - move to displaySettings
    protected $batchDelete = true;
    // UNDER CONSTRUCTION
    protected $displaySettings = array();
    // UNDER CONSTRUCTION
    protected $disallowPages = array('articles/create', 'articles/prefs');
    // TODO change the blank_url type back to URL before blank.
    // required
    /**
    * (use this as starting point for wiki documentation)
    * $fields format  (string) $field_name => (array) $attributes
    *
    * $field_name format:
    * 	'table_alias_or_name.field_name.field_alias' (if JOIN support is needed) OR just 'field_name'
    * NOTE: Keep in mind the count of exploded data can be 1 or 3!!! This means if you wanna give alias
    * on articles table field you can't omit the table (first key), alternative is just '.' e.g. '.field_name.field_alias'
    *
    * $attributes format:
    * 	- title (string) Human readable field title, constant name will be accpeted as well (multi-language support
    *
    *      - type (string) null (means system), number, text, dropdown, url, image, icon, datestamp, userclass, userclasses, user[_name|_loginname|_login|_customtitle|_email],
    *        boolean, method, ip
    *      	full/most recent reference list - e_form::renderTableRow(), e_form::renderElement(), e_admin_form_ui::renderBatchFilter()
    *      	for list of possible read/writeParms per type see below
    *
    *      - data (string) Data type, one of the following: int, integer, string, str, float, bool, boolean, model, null
    *        Default is 'str'
    *        Used only if $dataFields is not set
    *      	full/most recent reference list - e_admin_model::sanitize(), db::_getFieldValue()
    *      - dataPath (string) - xpath like path to the model/posted value. Example: 'dataPath' => 'prefix/mykey' will result in $_POST['prefix']['mykey']
    *      - primary (boolean) primary field (obsolete, $pid is now used)
    *
    *      - help (string) edit/create table - inline help, constant name will be accpeted as well, optional
    *      - note (string) edit/create table - text shown below the field title (left column), constant name will be accpeted as well, optional
    *
    *      - validate (boolean|string) any of accepted validation types (see e_validator::$_required_rules), true == 'required'
    *      - rule (string) condition for chosen above validation type (see e_validator::$_required_rules), not required for all types
    *      - error (string) Human readable error message (validation failure), constant name will be accepted as well, optional
    *
    *      - batch (boolean) list table - add current field to batch actions, in use only for boolean, dropdown, datestamp, userclass, method field types
    *        NOTE: batch may accept string values in the future...
    *      	full/most recent reference type list - e_admin_form_ui::renderBatchFilter()
    *
    *      - filter (boolean) list table - add current field to filter actions, rest is same as batch
    *
    *      - forced (boolean) list table - forced fields are always shown in list table
    *      - nolist (boolean) list table - don't show in column choice list
    *      - noedit (boolean) edit table - don't show in edit mode
    *
    *      - width (string) list table - width e.g '10%', 'auto'
    *      - thclass (string) list table header - th element class
    *      - class (string) list table body - td element additional class
    *
    *      - readParms (mixed) parameters used by core routine for showing values of current field. Structure on this attribute
    *        depends on the current field type (see below). readParams are used articlesly by list page
    *
    *      - writeParms (mixed) parameters used by core routine for showing control element(s) of current field.
    *        Structure on this attribute depends on the current field type (see below).
    *        writeParams are used articlesly by edit page, filter (list page), batch (list page)
    *
    * $attributes['type']->$attributes['read/writeParams'] pairs:
    *
    * - null -> read: n/a
    * 		  -> write: n/a
    *
    * - dropdown -> read: 'pre', 'post', array in format posted_html_name => value
    * 			  -> write: 'pre', 'post', array in format as required by e_form::selectbox()
    *
    * - user -> read: [optional] 'link' => true - create link to user profile, 'idField' => 'author_id' - tells to renderValue() where to search for user id (used when 'link' is true and current field is NOT ID field)
    * 				   'nameField' => 'comment_author_name' - tells to renderValue() where to search for user name (used when 'link' is true and current field is ID field)
    * 		  -> write: [optional] 'nameField' => 'comment_author_name' the name of a 'user_name' field; 'currentInit' - use currrent user if no data provided; 'current' - use always current user(editor); '__options' e_form::userpickup() options
    *
    * - number -> read: (array) [optional] 'point' => '.', [optional] 'sep' => ' ', [optional] 'decimals' => 2, [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY'
    * 			-> write: (array) [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY', [optional] 'maxlength' => 50, [optional] '__options' => array(...) see e_form class description for __options format
    *
    * - ip		-> read: n/a
    * 			-> write: [optional] element options array (see e_form class description for __options format)
    *
    * - text -> read: (array) [optional] 'htmltruncate' => 100, [optional] 'truncate' => 100, [optional] 'pre' => '', [optional] 'post' => ' px'
    * 		  -> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 255), [optional] '__options' => array(...) see e_form class description for __options format
    *
    * - textarea 	-> read: (array) 'noparse' => '1' default 0 (disable toHTML text parsing), [optional] 'bb' => '1' (parse bbcode) default 0,
    * 								[optional] 'parse' => '' modifiers passed to e_parse::toHTML() e.g. 'BODY', [optional] 'htmltruncate' => 100,
    * 								[optional] 'truncate' => 100, [optional] 'expand' => '[more]' title for expand link, empty - no expand
    * 		  		-> write: (array) [optional] 'rows' => '' default 15, [optional] 'cols' => '' default 40, [optional] '__options' => array(...) see e_form class description for __options format
    * 								[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
    *
    * - bbarea -> read: same as textarea type
    * 		  	-> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 0),
    * 				[optional] 'size' => [optional] - medium, small, large - default is medium,
    * 				[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
    *
    * - image -> read: [optional] 'title' => 'SOME_LAN' (default - LAN_PREVIEW), [optional] 'pre' => '{e_PLUGIN}myplug/images/',
    * 				'thumb' => 1 (true) or number width in pixels, 'thumb_urlraw' => 1|0 if true, it's a 'raw' url (no sc path constants),
    * 				'thumb_aw' => if 'thumb' is 1|true, this is used for Adaptive thumb width
    * 		   -> write: (array) [optional] 'label' => '', [optional] '__options' => array(...) see e_form::imagepicker() for allowed options
    *
    * - icon  -> read: [optional] 'class' => 'S16', [optional] 'pre' => '{e_PLUGIN}myplug/images/'
    * 		   -> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
    *
    * - datestamp  -> read: [optional] 'mask' => 'long'|'short'|strftime() string, default is 'short'
    * 		   		-> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
    *
    * - url	-> read: [optional] 'pre' => '{ePLUGIN}myplug/'|'http://somedoarticles.com/', 'truncate' => 50 default - no truncate, NOTE:
    * 			-> write:
    *
    * - method -> read: optional, passed to given method (the field name)
    * 			-> write: optional, passed to given method (the field name)
    *
    * - hidden -> read: 'show' => 1|0 - show hidden value, 'empty' => 'something' - what to be shown if value is empty (only id 'show' is 1)
    * 			-> write: same as readParms
    *
    * - upload -> read: n/a
    * 			-> write: Under construction
    *
    * Special attribute types:
    * - method (string) field name should be method from the current e_admin_form_ui class (or its extension).
    * 		Example call: field_name($value, $render_action, $parms) where $value is current value,
    * 		$render_action is on of the following: read|write|batch|filter, parms are currently used paramateres ( value of read/writeParms attribute).
    * 		Return type expected (by render action):
    * 			- read: list table - formatted value only
    * 			- write: edit table - form element (control)
    * 			- batch: either array('title1' => 'value1', 'title2' => 'value2', ..) or array('singleOption' => '<option value="somethig">Title</option>') or rendered option group (string '<optgroup><option>...</option></optgroup>'
    * 			- filter: same as batch
    *
    * @var array
    */
    protected $fields = array(
        'checkboxes' => 	array('title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => true, 'class' => 'center', 'toggle' => 'e-multiselect'),
        'kbase_id' => 		array('title' => 'ID', 'type' => 'number', 'data' => 'int', 'width' => '5%', 'thclass' => '', 'class' => 'center', 'forced' => true, 'primary' => true/*, 'noedit'=>TRUE*/), // Primary ID is not editable
        'kbase_parent' =>   array('title' => 'Category', 'type' => 'dropdown',	'data'=> 'int', 'inline'=>true,'width' => '10%', 'filter'=>TRUE, 'batch'=>TRUE),
        'kbase_question' => array('title' => 'Question', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'batch' => true, 'filter' => true),
        'kbase_answer' => 	array('title' => 'Answer', 'type' => 'bbarea',	'width' => '30%', 'readParms' => 'expand=1&truncate=50&bb=1'),
        'kbase_comment' => 	array('title' => 'Comments', 'type' => 'boolean', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'batch' => true, 'filter' => true),
        'kbase_author' => 	array('title' => 'Author', 'type' => 'user', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'batch' => true, 'filter' => true),
        'kbase_datestamp' =>array('title' => 'Date stamp', 'type' => 'datestamp', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'batch' => true, 'filter' => true),
        'kbase_thing' => 	array('title' => 'Thing', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => '', 'batch' => true, 'filter' => true),
        'kbase_order' => 	array('title' => 'Order', 'type' => 'dropdown', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
        'kbase_approved' => array('title' => 'Approved', 'type' => 'boolean', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
        'kbase_views' => 	array('title' => 'Views', 'type' => 'number', 'data' => 'int', 'width' => 'auto', 'thclass' => ''),
        'kbase_viewer' => 	array('title' => 'Viewers', 'type' => 'textarea', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
        'kbase_unique' => 	array('title' => 'Unique views', 'type' => 'number', 'data' => 'int', 'width' => 'auto', 'thclass' => ''),
        'kbase_updated' => 	array('title' => 'Updated', 'type' => 'dropdown', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
        'options' => 		array('title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => true)
        );
    // required - default column user prefs
    protected $fieldpref = array('checkboxes', 'kbase_parent', 'kbase_question', 'kbase_answer', 'kbase_approved' , 'kbase_order', 'options');
    // FORMAT field_name=>type - optional if fields 'data' attribute is set or if custom model is set in init()
    /*protected $dataFields = array();*/
    // optional, could be also set directly from $fields array with attributes 'validate' => true|'rule_name', 'rule' => 'condition_name', 'error' => 'Validation Error message'
    /*protected  $validationRules = array(
	   'blank_url' => array('required', '', 'blank URL', 'Help text', 'not valid error message')
	   );*/
    // optional, if $pluginName == 'core', core prefs will be used, else e107::getPluginConfig($pluginName);
    protected $prefs = array(
        'pref_type' => array('title' => 'type', 'type' => 'text', 'data' => 'string', 'validate' => true),
        'pref_folder' => array('title' => 'folder', 'type' => 'boolean', 'data' => 'integer'),
        'pref_name' => array('title' => 'name', 'type' => 'text', 'data' => 'string', 'validate' => 'regex', 'rule' => '#^[\w]+$#i', 'help' => 'allowed characters are a-zA-Z and underscore')
        );
    // optional
    public function init(){
        $this->prefs = e107::getPlugPref('kbase');
    //	print"Load<br />";
     //   var_dump($this->prefs);
        if ($this->prefs['kbase_installed'] !== true){
      //  	print"defs<br />";
            $this->defaultPrefs();
        }
    	$sql = e107::getDb();
    	if($sql->select('kbase_info'))
    	{
    		while ($row = $sql->fetch())
    		{
    			$this->categories[$row['kbase_info_id']] = $row['kbase_info_title'];
    		}
    	}
    			$this->fields['kbase_parent']['writeParms'] = $this->categories;
    	var_dump($this->categories);
        $this->observe();
    }

    public function customPage(){
        // $ns = e107::getRender();
        // $text = "Hello World!";
        // $ns->tablerender("Hello",$text);
    }
    /**
    * kbase_articles_admin_ui::observe()
    *
    * Watch for this being triggered. If it is then do something
    *
    * @return
    */
    public function observe(){
        if (isset($_POST['updatekbaseoptions'])){ // Save prefs.
            $this->save_prefs();
        }
        // if (isset($_POST)){
        // e107::getCache()->clear( "download_cat" );
        // }
    }
    function settingsPage(){
        $this->show_kbase_options();
    }

    function articlestPage(){
    }

    function save_prefs(){
       // e107::getPlugPref('kbase');
    //	print "save<br />";
    //	var_dump($this->prefs);
        $tp = e107::getParser();
        foreach($_POST AS $key => $val){
            if (stripos($key, "kbase_") === 0){
                $temp[$key] = $tp->toDB($val);
            }
        }
   // 	print "temp<br />";
   // 	var_dump($temp);
        // var_dump($temp);
        e107::getConfig('kbase')->setPref($temp)->save(false);
    //	print "done<br />";
        $this->prefs = e107::getPlugPref('kbase');
    //		var_dump($this->prefs);
    }
    protected function defaultPrefs(){
        // e107::getPlugPref('kbase'); // essential to make it work
        $options['kbase_installed'] = true; // so we know it is set up
        $options['kbase_user'] = 255;
        $options['kbase_add'] = 255;
        $options['kbase_approve'] = 255;
        $options['kbase_defcomments'] = 255;
        $options['kbase_allowcomments'] = 0;
        $options['kbase_ownedit'] = 0;
        $options['kbase_super'] = 255;
        $options['kbase_description'] = "Knowledgebase";
        $options['kbase_keywords'] = "Knowledgebase";
        $options['kbase_showposter'] = 1;
        $options['kbase_picupload'] = 1;
        $options['kbase_sendto'] = 1;
        $options['kbase_title'] = "Knowledgebase";
        $options['kbase_mtext'] = "Knowledgebase";
        $options['kbase_perpage'] = 10;
        $options['kbase_showrand'] = 0;
        $options['kbase_top'] = 0;
        $options['kbase_log'] = 0;
        $options['kbase_stats'] = 0;
        $options['kbase_rating'] = 1;
        $options['kbase_simple'] = 0;
        $options['kbase_seo'] = 1;
        $this->prefs = e107::getConfig('kbase')->setPref($options)->save(false);
    }
    /**
    * kbase_articles_admin_ui::show_kbase_options()
    *
    * @return
    */
    /**
    * kbase_articles_admin_ui::show_kbase_options()
    *
    * @return
    */
    protected function show_kbase_options(){
        global $ns;
        $pref = e107::getPlugPref('kbase');
        require_once(e_HANDLER . "form_handler.php");
        $frm = new e_form(true); //enable inner tabindex counter
        $tab1active = '';
        $tab1Class == '';
        $tab2active = '';
        $tab2Class == '';
        $tab3active = '';
        $tab3Class == '';
        $tab4active = '';
        $tab4Class == '';
        $tab5active = '';
        $tab5Class == '';
        $tab6active = '';
        $tab6Class == '';
        $activeTab = $_COOKIE['kbaseLastTab'];
        $tabTime = $_COOKIE['kbaseLastTabTime'];

        if (time() - $tabTime > 180){
            $activeTab = 1;
            $tabTime = time();
            setcookie("kbaseLastTab", 1, 0, '/');
            setcookie("kbaseLastTabTime", $tabTime, 0, '/');
        }

        switch ($activeTab){
/*
            case 6:
                $tab6active = ' active' ;
                $tab6Class = " class='active' ";
                break;
            case 5:
                $tab5active = ' active' ;
                $tab5Class = " class='active' ";
                break;
            case 4:
                $tab4active = ' active' ;
                $tab4Class = " class='active' ";
                break;
*/
            case 3:
                $tab3active = ' active' ;
                $tab3Class = " class='active' ";
                break;
            case 2:
                $tab2active = ' active' ;
                $tab2Class = " class='active' ";
                break;
            case 1:
            default :
                $tab1active = ' active' ;
                $tab1Class = " class='active' ";
                break;
        }
        // $kbase_perpage = array('10' => '10', '20' => '20', '50' => '50', '100' => '100');
        $kbase_text = "
<div style='text-align:center'>
	<form method='post' action='" . e_SELF . "' id='kbasepref'>
		<table style='" . ADMIN_WIDTH . "' class='fborder'>
			<tr>
				<td class='fcaption' colspan='2'>" . KBASE_PREFLAN_10 . "</td>
			</tr>";
        // $kbase_sub = "<select class='tbox' name='kbase_simple' >";
        // $kbase_sub .= "<option value='0' " . ($this->prefs['kbase_simple'] == 0?"selected='selected'":"") . ">" . KBASE_ADLAN_141 . "</option>";
        // $sql->db_Select("kbase_info", "kbase_info_id,kbase_info_title", "where kbase_info_parent > 0", "nowhere", false);
        // while ($kbase_row = $sql->db_Fetch())
        // {
        // $kbase_sub .= "<option value='" . $kbase_row['kbase_info_id'] . "' " . ($this->prefs['kbase_simple'] == $kbase_row['kbase_info_id']?"selected='selected'":"") . ">" . $tp->toFORM($kbase_row['kbase_info_title']) . "</option>";
        // } // while
        // $kbase_sub .= "</select>";
        $kbase_text .= $kbase_sub;
        /*
		$kbase_text .= "


    	    	</table>
    		</form>
    	</div>";
    	*/
        $text = "

	<ul class='nav nav-tabs'>
		<li {$tab1Class} id='kbaseTab1' ><a data-toggle='tab' href='#core-kbase-kbase1'>" . LAN_CAPTCHA_TAB1 . "vlasses</a></li>
		<li {$tab2Class} id='kbaseTab2' ><a data-toggle='tab' href='#core-kbase-kbase2'>" . LAN_CAPTCHA_TAB2 . "</a></li>
		<li {$tab3Class} id='kbaseTab3' ><a data-toggle='tab' href='#core-kbase-kbase3'>" . LAN_CAPTCHA_TAB3 . "</a></li>
		<!--
		<li {$tab4Class} id='kbaseTab4' ><a data-toggle='tab' href='#core-kbase-kbase4'>" . LAN_CAPTCHA_TAB4 . "</a></li>
		<li {$tab5Class} id='kbaseTab5' ><a data-toggle='tab' href='#core-kbase-kbase5'>" . LAN_CAPTCHA_TAB5 . "</a></li>
		<li {$tab6Class} id='kbaseTab6' ><a data-toggle='tab' href='#core-kbase-kbase6'>" . LAN_CAPTCHA_TAB6 . "</a></li>
	-->
	</ul>
	<form method='post' id='kbasePrefForm' action='" . e_SELF . "?" . e_QUERY . "'>\n
   		<div class='tab-content'>
			<div class='tab-pane {$tab1active}' id='core-kbase-kbase1'>
				<div>
        			<table class='table adminform'>
            			<colgroup>
            				<col style='width:30%'/>
            				<col style='width:70%'/>
            			</colgroup>

        	<tr>
        		<td class='forumheader3'>" . KBASE_ADLAN_128 . ":</td>
        		<td class= 'forumheader3'>" . $frm->userclass("kbase_user", $this->prefs['kbase_user'], "dropdown", "'options=admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
       		 <tr>
        		<td class='forumheader3'>" . KBASE_ADLAN_110 . ":</td>
	        	<td class= 'forumheader3'>" . $frm->userclass("kbase_super", $this->prefs['kbase_super'], "dropdown", "options=nobody,admin,main,member,classes") . "</td>
			</tr>
    	    <tr>
	    	    <td class='forumheader3'>" . KBASE_PREFLAN_02 . ":</td>
	    	    <td class= 'forumheader3'>" . $frm->userclass("kbase_add", $this->prefs['kbase_add'], "dropdown", "options=admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
        	<tr>
        		<td class='forumheader3'>" . KBASE_PREFLAN_09 . ":</td>
        		<td class= 'forumheader3'>" . $frm->userclass("kbase_approve", $this->prefs['kbase_approve'], "dropdown", "options=admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
	        <tr>
    	    	<td class='forumheader3'>" . KBASE_ADLAN_96 . ":</td>
        		<td class= 'forumheader3'>" . $frm->userclass("kbase_allowcomments", $this->prefs['kbase_allowcomments'], "dropdown", "options=admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
        	<tr>
        		<td class='forumheader3'>" . KBASE_ADLAN_95 . ":</td>
        		<td class= 'forumheader3'>" . $frm->userclass("kbase_defcomments", $this->prefs['kbase_defcomments'], "dropdown", "options=admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
        	<tr>
        		<td class='forumheader3'>" . KBASE_ADLAN_121 . ":</td>
        		<td class= 'forumheader3'>" . $frm->userclass("kbase_sendto", $this->prefs['kbase_sendto'], "dropdown", "options=admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
        	<tr>
        		<td class='forumheader3'>" . KBASE_ADLAN_133 . ":</td>
        		<td class= 'forumheader3'>" . $frm->userclass("kbase_stats", $this->prefs['kbase_stats'], "dropdown", "options=admin,main,public,guest,nobody,member,classes") . "</td>
			</tr>
	 		        </table>
		        </div>
		    </div>
		    <div class='tab-pane {$tab2active}' id='core-kbase-kbase2'>
				<div>
					<table class='table adminform'>
            			<colgroup>
            				<col style='width:30%'/>
            				<col style='width:70%'/>
            			</colgroup>
    		        <tr>
    	    	    	<td class='forumheader3'>" . KBASE_ADLAN_144 . ":</td>
    	        		<td class= 'forumheader3'>" . $frm->radio_switch('kbase_seo', $this->prefs['kbase_seo']) . "<br /><i>" . KBASE_PREFLAN_14 . "</i></td>
    				</tr>
    		        <tr>
    	    	    	<td class='forumheader3'>" . KBASE_ADLAN_135 . ":</td>
    	        		<td class= 'forumheader3'>" . $frm->radio_switch('kbase_rating', $this->prefs['kbase_rating']) . "</td>
    				</tr>
    		        <tr>
    	    	    	<td class='forumheader3'>" . KBASE_ADLAN_109 . ":</td>
    	        		<td class= 'forumheader3'>" . $frm->radio_switch('kbase_ownedit', $this->prefs['kbase_ownedit']) . "</td>
    				</tr>
    	        	<tr>
    	       			<td class='forumheader3'>" . KBASE_ADLAN_134 . ":</td>
    	        		<td class= 'forumheader3'>" . $frm->radio_switch('kbase_log', $this->prefs['kbase_log']) . "</td>
    				</tr>
    	        	<tr>
    	        		<td class='forumheader3'>" . KBASE_ADLAN_117 . ":</td>
    	        		<td class= 'forumheader3'>" . $frm->radio_switch('kbase_showposter', $this->prefs['kbase_showposter']) . "</td>
    				</tr>
    	        	<tr>
    	        		<td class='forumheader3'>" . KBASE_ADLAN_118 . ":</td>
    	        		<td class= 'forumheader3'>" . $frm->radio_switch('kbase_picupload', $this->prefs['kbase_picupload']) . "</td>
    				</tr>
    	        	<tr>
    	        		<td class='forumheader3'>" . KBASE_ADLAN_125 . ":</td>
    	        		<td class= 'forumheader3'>" . $frm->radio_switch('kbase_showrand', $this->prefs['kbase_showrand']) . "</td>
    				</tr>
    				</table>
		        </div>
			</div>
			<div class='tab-pane {$tab3active}' id='core-kbase-kbase3'>
				<div>
					<table class='table adminform'>
            			<colgroup>
            				<col style='width:30%'/>
            				<col style='width:70%'/>
            			</colgroup>
   	        	<tr>
    	        		<td class='forumheader3'>" . KBASE_ADLAN_126 . ":</td>
    	        		<td class= 'forumheader3'>" . $frm->text('kbase_top', $this->prefs['kbase_top'], 20) . "</td>
    				</tr>
    				<tr>
    			        <td class='forumheader3'>" . KBASE_ADLAN_122 . ":</td>
    			        <td class= 'forumheader3'>" . $frm->text('kbase_title', $this->prefs['kbase_title'], 20) . "</td>
    				</tr>
    	        	<tr>
    	        		<td class='forumheader3'>" . KBASE_ADLAN_113 . ":</td>
    	        		<td class= 'forumheader3'>" . $frm->text('kbase_description', $this->prefs['kbase_description'], 20) . "<br /><span class='smalltext'>" . KBASE_ADLAN_114 . "</span></td>
    				</tr>
    	        	<tr>
    	        		<td class='forumheader3'>" . KBASE_ADLAN_115 . ":</td>
    	        		<td class= 'forumheader3'>" . $frm->text('kbase_keywords', $this->prefs['kbase_keywords'], 20) . "<br /><span class='smalltext'>" . KBASE_ADLAN_116 . "</span></td>
    				</tr>
    	        	<tr>
    	        		<td class='forumheader3'>" . KBASE_ADLAN_123 . ":</td>
    	        		<td class= 'forumheader3'>" . $frm->text('kbase_mtext', $this->prefs['kbase_mtext'], 20) . "</td>
    				</tr>
    	        	<tr>
    	        		<td class='forumheader3'>" . KBASE_ADLAN_124 . ":</td>
    	        		<td class= 'forumheader3'>" . $frm->number('kbase_perpage', $this->prefs['kbase_perpage'], 20) . "</td>
    				</tr>
		        	</table>
		        </div>
			</div>
			<div class='tab-pane {$tab4active}' id='core-kbase-kbase4'>
				<div>
            		<ul class='bubblewrap'>";

        foreach($listImages as $key => $value){
            $options = array('label' => $value['name']);
            $text .= "	<li>
							<a href='#'>
								<img src='backgrounds/{$value['name']}' title='{$value['name']}' alt='{$value['name']}' />
							</a><br />{$value['width']} x {$value['height']} px<br />" . $frm->checkbox('kbase_background[]', $key, in_array($key, $pref['kbase_background']), $options) . "

						</li>";
            /*
			$img .= "
								<img src='backgrounds/{$value['name']}' style='width:125px; height:20px' alt='Background {$value['name']}' /><br />
								" . $frm->radio('kbase_background', $key, false, $options) . "<br />
									{$value['width']} x {$value['height']} px";
        	*/
        }
        $text .= "
    				</ul>
		        </div>
		    </div>
			<div class='tab-pane {$tab5active}' id='core-kbase-kbase5'>
				<div>
					<table class='table adminform'>
            			<colgroup>
            				<col style='width:30%'/>
            				<col style='width:70%'/>
            			</colgroup>
            			<tr>
		               		<td>" . LAN_CAPTCHA_USENOISE . "</td>
		               		<td>" . $frm->radio('audio_use_noise', array('1' => LAN_CAPTCHA_USENOISE0, '0' => LAN_CAPTCHA_USENOISE1), $pref['audio_use_noise'], array('title' => LAN_CAPTCHA_CASE_HELP)) . "</td>
		                </tr>
            			<tr>
		               		<td>" . LAN_CAPTCHA_DEGRADENOISE . "</td>
		               		<td>" . $frm->radio('degrade_audio', array('1' => LAN_CAPTCHA_DEGRADENOISET, '0' => LAN_CAPTCHA_DEGRADENOISEF), $pref['degrade_audio'], array('title' => LAN_CAPTCHA_CASE_HELP)) . "</td>
		                </tr>

		                <tr>
		               		<td>" . LAN_CAPTCHA_MIXNOISE . "</td>
		               		<td>" . $frm->number('audio_mix_normalization', $pref['audio_mix_normalization'], '3', array('max' => '100', 'min' => '1', 'size' => '3', array('title' => LAN_CAPTCHA_MIXNOISE_HELP))) . "</td>
		                </tr>
		                <tr>
		               		<td>" . LAN_CAPTCHA_MINGAP . "</td>
		               		<td>" . $frm->number('audio_gap_min', $pref['audio_gap_min'], '3', array('max' => '3000', 'min' => '1', 'size' => '3', 'title' => LAN_CAPTCHA_MINGAP_HELP)) . "</td>
		                </tr>
		                <tr>
		               		<td>" . LAN_CAPTCHA_MAXGAP . "</td>
		               		<td>" . $frm->number('audio_gap_max', $pref['audio_gap_max'], '3', array('max' => '3000', 'min' => '1', 'size' => '3', 'title' => LAN_CAPTCHA_MAXGAP_HELP)) . "</td>
		                </tr>

		        	</table>
		        </div>
		    </div>

			<div class='tab-pane {$tab6active}' id='core-kbase-kbase6'>
				<div>
					<table class='table adminform'>
            			<colgroup>
            				<col style='width:30%'/>
            				<col style='width:70%'/>
            			</colgroup>
            			<tr>
		               		<td>" . LAN_CAPTCHA_USEDB . "</td>
		               		<td>" . $frm->radio('use_database', array('1' => LAN_CAPTCHA_USEDB1, '0' => LAN_CAPTCHA_USEDB0), $pref['use_database'], array('title' => LAN_CAPTCHA_USEDB_HELP)) . "</td>
		                </tr>
		    			<tr>
		               		<td>" . LAN_CAPTCHA_SESSIONS . "</td>
		               		<td>" . $frm->radio('no_session', array('0' => LAN_CAPTCHA_USEDB2, '1' => LAN_CAPTCHA_USEDB0), $pref['no_session'], array('title' => LAN_CAPTCHA_SESSIONS_HELP)) . "</td>
		                </tr>
		    			<tr>
		               		<td>" . LAN_CAPTCHA_SESSIONNAME . "</td>
		               		<td>" . $frm->text('session_name', $pref['session_name'], '25', array('size' => '25', 'title' => LAN_CAPTCHA_SESSIONNAME_HELP)) . "</td>
		                </tr>
		        	</table>
		        </div>
			</div>
		</div>
		<div class='buttons-bar center'>
			<input class='btn button' type='submit' name='updatekbaseoptions' value='" . LAN_CAPTCHA_UPDATE . "'/>
		</div>
	</form>";
        echo $text;
        return $text;
    }
}

class kbase_articles_admin_form_ui extends e_admin_form_ui{
	function kbase_approved(){
		return 33;
	}
}

class kbase_category_admin_ui extends e_admin_ui{
    // required
    protected $pluginTitle = "Knowledge Base";

    /**
    * plugin name or 'core'
    *
    * @var string
    */
    protected $pluginName = 'kbase';

    /**
    * DB Table, table alias is supported
    * Example: 'r.blank'
    *
    * @var string
    */
    protected $table = "kbase_info";

    /**
    * If present this array will be used to build your list query
    * You can link fileds from $field array with 'table' parameter, which should equal to a key (table) from this array
    * 'leftField', 'rightField' and 'fields' attributes here are required, the rest is optional
    * Table alias is supported
    * Note:
    * - 'leftTable' could contain only table alias
    * - 'leftField' and 'rightField' shouldn't contain table aliases, they will be auto-added
    * - 'whereJoin' and 'where' should contain table aliases e.g. 'whereJoin' => 'AND u.user_ban=0'
    *
    * @var array [optional] table_name => array join parameters
    */
    protected $tableJoin = array(
        // 'u.user' => array('leftField' => 'comment_author_id', 'rightField' => 'user_id', 'fields' => '*'/*, 'leftTable' => '', 'joinType' => 'LEFT JOIN', 'whereJoin' => '', 'where' => ''*/)
        );

    /**
    * This is only needed if you need to JOIN tables AND don't wanna use $tableJoin
    * Write your list query without any Order or Limit.
    *
    * @var string [optional]
    */
    protected $listQry = "";
    // optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
    // protected $editQry = "SELECT * FROM #blank WHERE blank_id = {ID}";
    // required - if no custom model is set in init() (primary id)
    protected $pid = "kbase_info_id";
    // optional
    protected $perPage = 20;
    // default - true - TODO - move to displaySettings
    protected $batchDelete = true;
    // UNDER CONSTRUCTION
    protected $displaySettings = array();
    // UNDER CONSTRUCTION
    protected $disallowPages = array('category/create', 'articles/prefs');
    // TODO change the blank_url type back to URL before blank.
    // required
    /**
    * (use this as starting point for wiki documentation)
    * $fields format  (string) $field_name => (array) $attributes
    *
    * $field_name format:
    * 	'table_alias_or_name.field_name.field_alias' (if JOIN support is needed) OR just 'field_name'
    * NOTE: Keep in mind the count of exploded data can be 1 or 3!!! This means if you wanna give alias
    * on articles table field you can't omit the table (first key), alternative is just '.' e.g. '.field_name.field_alias'
    *
    * $attributes format:
    * 	- title (string) Human readable field title, constant name will be accpeted as well (multi-language support
    *
    *      - type (string) null (means system), number, text, dropdown, url, image, icon, datestamp, userclass, userclasses, user[_name|_loginname|_login|_customtitle|_email],
    *        boolean, method, ip
    *      	full/most recent reference list - e_form::renderTableRow(), e_form::renderElement(), e_admin_form_ui::renderBatchFilter()
    *      	for list of possible read/writeParms per type see below
    *
    *      - data (string) Data type, one of the following: int, integer, string, str, float, bool, boolean, model, null
    *        Default is 'str'
    *        Used only if $dataFields is not set
    *      	full/most recent reference list - e_admin_model::sanitize(), db::_getFieldValue()
    *      - dataPath (string) - xpath like path to the model/posted value. Example: 'dataPath' => 'prefix/mykey' will result in $_POST['prefix']['mykey']
    *      - primary (boolean) primary field (obsolete, $pid is now used)
    *
    *      - help (string) edit/create table - inline help, constant name will be accpeted as well, optional
    *      - note (string) edit/create table - text shown below the field title (left column), constant name will be accpeted as well, optional
    *
    *      - validate (boolean|string) any of accepted validation types (see e_validator::$_required_rules), true == 'required'
    *      - rule (string) condition for chosen above validation type (see e_validator::$_required_rules), not required for all types
    *      - error (string) Human readable error message (validation failure), constant name will be accepted as well, optional
    *
    *      - batch (boolean) list table - add current field to batch actions, in use only for boolean, dropdown, datestamp, userclass, method field types
    *        NOTE: batch may accept string values in the future...
    *      	full/most recent reference type list - e_admin_form_ui::renderBatchFilter()
    *
    *      - filter (boolean) list table - add current field to filter actions, rest is same as batch
    *
    *      - forced (boolean) list table - forced fields are always shown in list table
    *      - nolist (boolean) list table - don't show in column choice list
    *      - noedit (boolean) edit table - don't show in edit mode
    *
    *      - width (string) list table - width e.g '10%', 'auto'
    *      - thclass (string) list table header - th element class
    *      - class (string) list table body - td element additional class
    *
    *      - readParms (mixed) parameters used by core routine for showing values of current field. Structure on this attribute
    *        depends on the current field type (see below). readParams are used articlesly by list page
    *
    *      - writeParms (mixed) parameters used by core routine for showing control element(s) of current field.
    *        Structure on this attribute depends on the current field type (see below).
    *        writeParams are used articlesly by edit page, filter (list page), batch (list page)
    *
    * $attributes['type']->$attributes['read/writeParams'] pairs:
    *
    * - null -> read: n/a
    * 		  -> write: n/a
    *
    * - dropdown -> read: 'pre', 'post', array in format posted_html_name => value
    * 			  -> write: 'pre', 'post', array in format as required by e_form::selectbox()
    *
    * - user -> read: [optional] 'link' => true - create link to user profile, 'idField' => 'author_id' - tells to renderValue() where to search for user id (used when 'link' is true and current field is NOT ID field)
    * 				   'nameField' => 'comment_author_name' - tells to renderValue() where to search for user name (used when 'link' is true and current field is ID field)
    * 		  -> write: [optional] 'nameField' => 'comment_author_name' the name of a 'user_name' field; 'currentInit' - use currrent user if no data provided; 'current' - use always current user(editor); '__options' e_form::userpickup() options
    *
    * - number -> read: (array) [optional] 'point' => '.', [optional] 'sep' => ' ', [optional] 'decimals' => 2, [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY'
    * 			-> write: (array) [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY', [optional] 'maxlength' => 50, [optional] '__options' => array(...) see e_form class description for __options format
    *
    * - ip		-> read: n/a
    * 			-> write: [optional] element options array (see e_form class description for __options format)
    *
    * - text -> read: (array) [optional] 'htmltruncate' => 100, [optional] 'truncate' => 100, [optional] 'pre' => '', [optional] 'post' => ' px'
    * 		  -> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 255), [optional] '__options' => array(...) see e_form class description for __options format
    *
    * - textarea 	-> read: (array) 'noparse' => '1' default 0 (disable toHTML text parsing), [optional] 'bb' => '1' (parse bbcode) default 0,
    * 								[optional] 'parse' => '' modifiers passed to e_parse::toHTML() e.g. 'BODY', [optional] 'htmltruncate' => 100,
    * 								[optional] 'truncate' => 100, [optional] 'expand' => '[more]' title for expand link, empty - no expand
    * 		  		-> write: (array) [optional] 'rows' => '' default 15, [optional] 'cols' => '' default 40, [optional] '__options' => array(...) see e_form class description for __options format
    * 								[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
    *
    * - bbarea -> read: same as textarea type
    * 		  	-> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 0),
    * 				[optional] 'size' => [optional] - medium, small, large - default is medium,
    * 				[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
    *
    * - image -> read: [optional] 'title' => 'SOME_LAN' (default - LAN_PREVIEW), [optional] 'pre' => '{e_PLUGIN}myplug/images/',
    * 				'thumb' => 1 (true) or number width in pixels, 'thumb_urlraw' => 1|0 if true, it's a 'raw' url (no sc path constants),
    * 				'thumb_aw' => if 'thumb' is 1|true, this is used for Adaptive thumb width
    * 		   -> write: (array) [optional] 'label' => '', [optional] '__options' => array(...) see e_form::imagepicker() for allowed options
    *
    * - icon  -> read: [optional] 'class' => 'S16', [optional] 'pre' => '{e_PLUGIN}myplug/images/'
    * 		   -> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
    *
    * - datestamp  -> read: [optional] 'mask' => 'long'|'short'|strftime() string, default is 'short'
    * 		   		-> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
    *
    * - url	-> read: [optional] 'pre' => '{ePLUGIN}myplug/'|'http://somedoarticles.com/', 'truncate' => 50 default - no truncate, NOTE:
    * 			-> write:
    *
    * - method -> read: optional, passed to given method (the field name)
    * 			-> write: optional, passed to given method (the field name)
    *
    * - hidden -> read: 'show' => 1|0 - show hidden value, 'empty' => 'something' - what to be shown if value is empty (only id 'show' is 1)
    * 			-> write: same as readParms
    *
    * - upload -> read: n/a
    * 			-> write: Under construction
    *
    * Special attribute types:
    * - method (string) field name should be method from the current e_admin_form_ui class (or its extension).
    * 		Example call: field_name($value, $render_action, $parms) where $value is current value,
    * 		$render_action is on of the following: read|write|batch|filter, parms are currently used paramateres ( value of read/writeParms attribute).
    * 		Return type expected (by render action):
    * 			- read: list table - formatted value only
    * 			- write: edit table - form element (control)
    * 			- batch: either array('title1' => 'value1', 'title2' => 'value2', ..) or array('singleOption' => '<option value="somethig">Title</option>') or rendered option group (string '<optgroup><option>...</option></optgroup>'
    * 			- filter: same as batch
    *
    * @var array
    */
    protected $fields = array(
        'checkboxes' => array('title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => true, 'class' => 'center', 'toggle' => 'e-multiselect'),
        'kbase_info_id' => array('title' => 'ID', 'type' => 'number', 'data' => 'int', 'width' => '5%', 'thclass' => '', 'class' => 'center', 'forced' => true, 'primary' => true/*, 'noedit'=>TRUE*/), // Primary ID is not editable
        'kbase_info_title' => array('title' => 'Category name', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
        'kbase_info_about' => array('title' => 'About', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
        'kbase_info_parent' => array('title' => 'Parent', 'type' => 'dropdown', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
        'kbase_info_class' => array('title' => 'Class', 'type' => 'userclass', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
        'kbase_info_order' => array('title' => 'Order', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => '','noedit'=>true),
        'kbase_info_icon' => array('title' => 'Icon', 'type' => 'icon', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
        'options' => array('title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => true)
        );
    // required - default column user prefs
    protected $fieldpref = array('checkboxes', 'kbase_info_id', 'kbase_info_title', 'kbase_info_parent', 'kbase_info_icon', 'options');
    // FORMAT field_name=>type - optional if fields 'data' attribute is set or if custom model is set in init()
    /*protected $dataFields = array();*/
    // optional, could be also set directly from $fields array with attributes 'validate' => true|'rule_name', 'rule' => 'condition_name', 'error' => 'Validation Error message'
    /*protected  $validationRules = array(
	   'blank_url' => array('required', '', 'blank URL', 'Help text', 'not valid error message')
	   );*/
    // optional
    public function init(){
    }

    /**
    * kbase_articles_admin_ui::observe()
    *
    * Watch for this being triggered. If it is then do something
    *
    * @return
    */
    public function observe(){
        var_dump($this);
        if (isset($_POST['updatekbaseoptions'])){ // Save prefs.
            $this->save_prefs();
        }
        // if (isset($_POST)){
        // e107::getCache()->clear( "download_cat" );
        // }
    }
}

class kbase_category_admin_form_ui extends e_admin_form_ui{
}