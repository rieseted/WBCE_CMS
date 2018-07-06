<?php header("Location: ../index.php",true,301);
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */
?>

<!-- begin settings_modify.html -->
<!-- BEGIN main_block -->
<!-- BEGIN success_block -->
<p class="mod_preferences_success">
	{SUCCESS_VALUE}
</p>
<!-- END success_block -->
<!-- BEGIN error_block -->
<p class="mod_preferences_error">
	{ERROR_VALUE}
</p>
<!-- END error_block -->
<div style="margin: 1em auto;">
	<button type="button" value="cancel" onClick="javascript: window.location = '{HTTP_REFERER}';">{TEXT_CANCEL}</button>
</div>
<hr />
<form name="details" action="" method="post">
    {FTAN}
	<h3>{HEADING_MY_SETTINGS}</h3>
	<table summary="" cellpadding="5" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="160">{TEXT_DISPLAY_NAME}:</td>
			<td>
				<input type="text" name="display_name" value="{DISPLAY_NAME}" style="width: 98%;" readonly="readonly" />
			</td>
		</tr>
		<tr>
			<td>{TEXT_LANGUAGE}:</td>
			<td>
				<select name="language" id="language">
					<!-- BEGIN language_list_block -->
						<option value="{CODE}"{SELECTED} style="background: url({FLAG}.png) no-repeat center left; padding-left: 20px;">{NAME} ({CODE})</option>
					<!-- END language_list_block -->
				</select>
			</td>
		</tr>
		<tr>
			<td>{TEXT_TIMEZONE}:</td>
			<td>
				<select name="timezone" style="width: 98%;">
					<option value="-20">{MOD_PREFERENCE_PLEASE_SELECT}</option>
<!-- BEGIN timezone_list_block -->
					<option value="{VALUE}" {SELECTED}>{NAME}</option>
<!-- END timezone_list_block -->
				</select>
			</td>
		</tr>
		<tr>
			<td>{TEXT_DATE_FORMAT}:</td>
			<td>
				<select name="date_format" style="width: 98%;">
					<option value="">{MOD_PREFERENCE_PLEASE_SELECT}</option>
<!-- BEGIN date_format_list_block -->
					<option value="{VALUE}" {SELECTED}>{NAME}</option>
<!-- END date_format_list_block -->
				</select>
			</td>
		</tr>
		<tr>
			<td>{TEXT_TIME_FORMAT}:</td>
			<td>
				<select name="time_format" style="width: 98%;">
					<option value="">{MOD_PREFERENCE_PLEASE_SELECT}</option>
<!-- BEGIN time_format_list_block -->
					<option value="{VALUE}" {SELECTED}>{NAME}</option>
<!-- END time_format_list_block -->
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<button type="reset" name="reset" value="reset">{TEXT_RESET}</button>
				<button type="submit" name="action" value="details">{MOD_PREFERENCE_SAVE_SETTINGS}</button>
			</td>
		</tr>
	</table>
</form>
<hr />
<form name="email" action="" method="post">
    {FTAN}
	<h3>{HEADING_MY_EMAIL}</h3>
	<table summary="" cellpadding="5" cellspacing="0" border="0" width="100%">
		<tr>
			<td>{TEXT_EMAIL}:</td>
			<td>
				<input type="text" name="email" value="{EMAIL}" style="width: 98%;" />
			</td>
		</tr>
		<tr>
			<td width="160">{TEXT_CURRENT_PASSWORD}:</td>
			<td>
				<input type="password" name="current_password" style="width: 98%;" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<button type="reset" name="reset" value="reset">{TEXT_RESET}</button>
				<button type="submit" name="action" value="email">{MOD_PREFERENCE_SAVE_EMAIL}</button>
			</td>
		</tr>
	</table>
</form>
<hr />
<form name="password" action="" method="post">
    {FTAN}
	<h3>{HEADING_MY_PASSWORD}</h3>
	<table summary="" cellpadding="5" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="160">{TEXT_CURRENT_PASSWORD}:</td>
			<td>
				<input type="password" name="current_password" style="width: 98%;" />
			</td>
		</tr>
		<tr>
			<td width="160">{TEXT_NEW_PASSWORD}:</td>
			<td>
				<input type="password" name="new_password" style="width: 98%;" />
			</td>
		</tr>
		<tr>
			<td width="160">{TEXT_RETYPE_NEW_PASSWORD}:</td>
			<td>
				<input type="password" name="new_password2" style="width: 98%;" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<button type="reset" name="reset" value="reset">{TEXT_RESET}</button>
				<button type="submit" name="action" value="password">{MOD_PREFERENCE_SAVE_PASSWORD}</button>
			</td>
		</tr>
	</table>
</form>
<div style="margin: 1em auto;">
	<button type="button" value="cancel" onClick="javascript: window.location = '{HTTP_REFERER}';">{TEXT_CANCEL}</button>
</div>
<!-- END main_block -->
<!-- end settings_modify.html -->
