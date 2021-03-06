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

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

// Check Ftan
if (!$wb->checkFTAN()) {
    $wb->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],WB_URL );
}

// Get entered values
$password = $wb->get_post('current_password');
$email    = $wb->get_post('email');

// validate password
$sSql  = "SELECT `user_id` FROM `{TP}users` WHERE `user_id` = ".$wb->get_user_id()." AND `password` = '".md5($password)."'";
// Validate values
if($database->get_one($sSql) == false) {
	$error[] = $MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'];
} else {
	if(!$wb->validate_email($email)) {
		$error[] = $MESSAGE['USERS_INVALID_EMAIL'];
	} else {
		$email = $database->escapeString($email);
		// Update the database
		$aUpdate = array(
			'user_id' => $wb->get_user_id(),
			'email' => $email
		);
		// Update the database
		if ($database->updateRow('{TP}users', 'user_id', $aUpdate)) {
			$success[] = $MESSAGE['PREFERENCES_EMAIL_UPDATED'];
			$_SESSION['EMAIL'] = $email;
		} else {
			$error[] = $database->get_error();
		}
	}
}