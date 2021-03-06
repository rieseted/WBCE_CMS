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
// Create new admin object
require('../../config.php');
require_once(WB_PATH . '/framework/class.admin.php');

$admin = new admin('Pages', 'pages_modify');

// Get page id
if (!isset($_REQUEST['page_id']) || !is_numeric($_REQUEST['page_id'])) {
    header("Location: index.php");
    exit(0);
} else {
    $page_id = (int) $_REQUEST['page_id'];
}

if (!$page_id || !is_numeric($page_id)) { // double check doesn't hurt ;D
    $admin->print_error('Invalid arguments passed - script stopped.');
}

// Get perms
if (!$admin->get_page_permission($page_id, 'admin')) {
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}

$sectionId = isset($_GET['wysiwyg']) ? htmlspecialchars($admin->get_get('wysiwyg')) : NULL;

// Get page details
$results_array = $admin->get_page_details($page_id);

// Get display name of person who last modified the page
$user = $admin->get_user_details($results_array['modified_by']);

// Convert the unix ts for modified_when to human a readable form

$modified_ts = ($results_array['modified_when'] != 0) ? $modified_ts = date(TIME_FORMAT . ', ' . DATE_FORMAT, $results_array['modified_when'] + TIMEZONE) : 'Unknown';
// $ftan_module = $GLOBALS['ftan_module'];
// Setup template object, parse vars to it, then parse it
// Create new template object
$pageModifyTemplate = new Template(dirname($admin->correct_theme_source('pages_modify.htt')));
						
// Disable removing of unknown vars to prevent the deleting of JavaScript arrays [] or {}
$pageModifyTemplate->set_unknowns('keep');

// $pageModifyTemplate->debug = true;
$pageModifyTemplate->set_file('page', 'pages_modify.htt');
$pageModifyTemplate->set_block('page', 'main_block', 'main');
$pageModifyTemplate->set_var('FTAN', $admin->getFTAN());

$pageModifyTemplate->set_var(array(
    'PAGE_ID' => $results_array['page_id'],
    // 'PAGE_IDKEY' => $admin->getIDKEY($results_array['page_id']),
    'PAGE_IDKEY' => $results_array['page_id'],
    'PAGE_TITLE' => ($results_array['page_title']),
    'MENU_TITLE' => ($results_array['menu_title']),
    'ADMIN_URL' => ADMIN_URL,
    'WB_URL' => WB_URL,
    'THEME_URL' => THEME_URL
));

$pageModifyTemplate->set_var(array(
    'MODIFIED_BY' => $user['display_name'],
    'MODIFIED_BY_USERNAME' => $user['username'],
    'MODIFIED_WHEN' => $modified_ts,
    'LAST_MODIFIED' => $MESSAGE['PAGES_LAST_MODIFIED'],
));

$pageModifyTemplate->set_block('main_block', 'show_modify_block', 'show_modify');
if ($modified_ts == 'Unknown') {
    $pageModifyTemplate->set_block('show_modify', '');
    $pageModifyTemplate->set_var('CLASS_DISPLAY_MODIFIED', 'hide');
} else {
    $pageModifyTemplate->set_var('CLASS_DISPLAY_MODIFIED', '');
    $pageModifyTemplate->parse('show_modify', 'show_modify_block', true);
}

// Work-out if we should show the "manage sections" link
$sql = 'SELECT COUNT(*) FROM `' . TABLE_PREFIX . 'sections` '
        . 'WHERE `page_id`=' . (int) $page_id . ' AND `module`=\'menu_link\'';
$query_sections = $database->get_one($sql);

$pageModifyTemplate->set_block('main_block', 'show_section_block', 'show_section');
if ($query_sections) {
    $pageModifyTemplate->set_block('show_section', '');
    $pageModifyTemplate->set_var('DISPLAY_MANAGE_SECTIONS', 'display:none;');
} elseif (MANAGE_SECTIONS == 'enabled') {
    $pageModifyTemplate->set_var('TEXT_MANAGE_SECTIONS', $HEADING['MANAGE_SECTIONS']);
    $pageModifyTemplate->parse('show_section', 'show_section_block', true);
} else {
    $pageModifyTemplate->set_block('show_section', '');
    $pageModifyTemplate->set_var('DISPLAY_MANAGE_SECTIONS', 'display:none;');
}

// Insert language TEXT
$pageModifyTemplate->set_var(array(
    'TEXT_CURRENT_PAGE' => $TEXT['CURRENT_PAGE'],
    'TEXT_CHANGE_SETTINGS' => $TEXT['CHANGE_SETTINGS'],
    'HEADING_MODIFY_PAGE' => $HEADING['MODIFY_PAGE']
));

$pageModifyTemplate->set_block('main_block', 'section_module_block', 'section_module');

// get template used for the displayed page (for displaying block details)
if (SECTION_BLOCKS) {
    $sql = 'SELECT `template` FROM `' . TABLE_PREFIX . 'pages` '
            . 'WHERE `page_id`=' . (int) $page_id;
    if (($sTemplate = $database->get_one($sql)) !== null) {
        $page_template = ($sTemplate == '') ? DEFAULT_TEMPLATE : $sTemplate;

        // include template info file if exists
        if (is_readable(WB_PATH . '/templates/' . $page_template . '/info.php')) {
            include_once(WB_PATH . '/templates/' . $page_template . '/info.php');
        }
    }
}

// Get sections for this page
$module_permissions = $_SESSION['MODULE_PERMISSIONS'];
// workout for edit only one section for faster pageloading
// Constant later set in wb_settings, in meantime defined in framework/initialize.php
$sql = 'SELECT * FROM `' . TABLE_PREFIX . 'sections` ';
$sql .= (defined('EDIT_ONE_SECTION') && EDIT_ONE_SECTION && is_numeric($sectionId)) ? 'WHERE `section_id` = ' . $sectionId : 'WHERE `page_id` = ' . intval($page_id);
$sql .= ' ORDER BY position ASC';
$query_sections = $database->query($sql);
if ($query_sections->numRows() > 0) {
	
    while ($section = $query_sections->fetchRow()) {
        $section_id = $section['section_id'];
        $module = $section['module'];

        //Have permission?
        if (!is_numeric(array_search($module, $module_permissions))) {
            // Include the modules editing script if it exists
            if (file_exists(WB_PATH . '/modules/' . $module . '/modify.php')) {

                    // Define section block name
                    if (isset($block[$section['block']]) && trim(strip_tags(($block[$section['block']]))) != '') {
                        $blockName = htmlentities(strip_tags($block[$section['block']]));
                    } else {
                        $blockName = '#' . (int) $section['block'];
                        if ($section['block'] == 1) {
                            $blockName = $TEXT['MAIN'];
                        }
                    }

                    // Define section anchor
                    $sectionAnchor = (defined('SEC_ANCHOR') && SEC_ANCHOR ? SEC_ANCHOR . $section['section_id'] : '');

                    // Set template vars
                    $pageModifyTemplate->set_var('SECTION_ANCHOR', $sectionAnchor);
                    $pageModifyTemplate->set_var('TEXT_BLOCK', $TEXT['BLOCK']);
                    $pageModifyTemplate->set_var('BLOCK_NAME', $blockName);
                    $pageModifyTemplate->set_var('SECTION_ID', $section['section_id']);
                    $pageModifyTemplate->set_var('SECTION_MODULE', $section['module']);
                    $pageModifyTemplate->set_var('SECTION_BLOCK', $section['block']);
                    $pageModifyTemplate->set_var('SECTION_NAME', $section['namesection']);

                // Set section modify output as template var				
                ob_start();
                require(WB_PATH . '/modules/' . $module . '/modify.php');
                $pageModifyTemplate->set_var('SECTION_MODIFY_OUTPUT', ob_get_clean());

                // Parse section module
                $pageModifyTemplate->parse('section_module', 'section_module_block', true);
			
            }
        }
    }
		
}

// Parse and print header template
$pageModifyTemplate->parse('main', 'main_block', false);
$pageModifyTemplate->pparse('output', 'page');


// Print admin footer
$admin->print_footer();
