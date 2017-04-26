<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright       Ryan Djurovich (2004-2009)
 * @copyright       WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-2017)
 * @category        opffilter
 * @package         OPF Droplets
 * @version         1.0.1
 * @authors         Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.2.x
 * @requirements    OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */


/**
 * execute droplets
**/

function opff_mod_opf_droplets (&$content, $page_id, $section_id, $module, $wb) {
    if(!class_exists('Settings')
        || (Settings::Get('opf_droplets', true) && ($page_id != 'backend'))
        || (Settings::Get('opf_droplets'.'_be', true) && ($page_id == 'backend'))){
        if(file_exists(WB_PATH .'/modules/droplets/droplets.php')) {
            include_once(WB_PATH .'/modules/droplets/droplets.php');
            if(function_exists('evalDroplets')) {
                $content = evalDroplets($content);
            }
        }
    }
    return(TRUE);
}

