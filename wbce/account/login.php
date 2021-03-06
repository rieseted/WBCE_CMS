<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require_once realpath('../config.php');

// Make sure the login is enabled
if(!FRONTEND_LOGIN) {
	header('Location: '.WB_URL.((INTRO_PAGE) ? PAGES_DIRECTORY : '').'/index.php');
	exit(0);
}


require_once __DIR__ .'/functions/functions.php';


$page_id = !empty($_SESSION['PAGE_ID']) ? $_SESSION['PAGE_ID'] : 0;

// Required page details
// $page_id = 0;
$page_description = '';
$page_keywords = '';
define('PAGE_ID', $page_id);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $TEXT['PLEASE_LOGIN']);
define('MENU_TITLE', $TEXT['PLEASE_LOGIN']);
define('VISIBILITY', 'public');
// Set the page content include file
define('PAGE_CONTENT', WB_PATH.'/account/login_form.php');

// Create new frontend object
$wb = new frontend();

// Create new login app
$requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
$redirect      = strip_tags(isset(${$requestMethod}['redirect']) ? ${$requestMethod}['redirect'] : '');
$redirect      = ((isset($_SERVER['HTTP_REFERER']) && empty($redirect)) ?  $_SERVER['HTTP_REFERER'] : $redirect);

$_SESSION['HTTP_REFERER'] = str_replace(WB_URL,'',$redirect);

$loginUrl  = WB_URL.'/account/login.php'.(!empty($redirect) ? '?redirect=' .$_SESSION['HTTP_REFERER'] : '');

$ThemeUrl  = WB_URL.$wb->correct_theme_source('warning.html');
// Setup template object, parse vars to it, then parse it
$ThemePath = realpath(WB_PATH.$wb->correct_theme_source('login.htt'));

$thisApp = new login(
	array(
		"MAX_ATTEMPS"           => "3",
		"WARNING_URL"           => $ThemeUrl."/templates/warning.html",
		"USERNAME_FIELDNAME"    => 'username',
		"PASSWORD_FIELDNAME"    => 'password',
		"REMEMBER_ME_OPTION"    => SMART_LOGIN,
		"MIN_USERNAME_LEN"      => "2",
		"MIN_PASSWORD_LEN"      => "3",
		"MAX_USERNAME_LEN"      => "30",
		"MAX_PASSWORD_LEN"      => "30",
		"LOGIN_URL"             => $loginUrl,
		"DEFAULT_URL"           => WB_URL.PAGES_DIRECTORY."/index.php",
		"TEMPLATE_DIR"          => $ThemePath,
		"TEMPLATE_FILE"         => "login.htt",
		"FRONTEND"              => true,
		"FORGOTTEN_DETAILS_APP" => WB_URL."/account/forgot.php",
		"USERS_TABLE"           => TABLE_PREFIX."users",
		"GROUPS_TABLE"          => TABLE_PREFIX."groups",
		"REDIRECT_URL"          => $redirect
	)
);

// Set extra outsider var
$globals[] = 'thisApp';

// Include the index (wrapper) file
require WB_PATH.'/index.php';
