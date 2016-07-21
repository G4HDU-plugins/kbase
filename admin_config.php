<?php
/**
 * Main administration configuration.
 *
 * @package REPEATER
 * @copyright 2008-2015 Barry Keal G4HDU
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Barry Keal G4HDU <www.g4hdu.co.uk>
 * @version 1.0.1
 */
require_once( "../../class2.php" );
if (!defined('e107_INIT')){
	exit;
}

//error_reporting(E_ALL);
if ( !getperms( "P" ) ) {
    header( "location:" . e_BASE . "index.php" );
    exit;
}

$eplug_admin = true;
if ( !getperms( "P" ) || !e107::isInstalled( 'kbase' ) ) {
	header( "location:" . e_BASE . "index.php" );
	exit() ;
}
e107::lan('kbase','English_admin',true); //load the admin language file
e107::js('kbase','js/kbase.js','jquery');	// Load Plugin javascript and include jQuery framework
e107::css('kbase','css/kbase.css');		// load css file



//include_lan(e_PLUGIN . 'kbase/languages/' . e_LANGUAGE . '_global.php');
require_once( 'includes/kbase_class.php' );
$rep=new kbase;
//$rep->parseCsv();
require_once( e_HANDLER . "form_handler.php" );

require_once( "handlers/admin.php" );
new plugin_kbase_admin();

require_once( e_ADMIN . "auth.php" );;
e107::getAdminUI()->runPage();
require_once( e_ADMIN . "footer.php" );
?>