<?php

//Administrador 
add_action( 'admin_menu', 'cf7cc_plugin_menu' );
function cf7cc_plugin_menu() {
	add_options_page( __('ChatGPT', 'cf7cc'), __('ChatGPT', 'cf7cc'), 'manage_options', 'cf7cc', 'cf7cc_page_settings');
}

function cf7cc_page_settings() { 
	?><h1><?php _e("Configuration", 'cf7cc'); ?></h1><?php 
	if(isset($_REQUEST['send']) && $_REQUEST['send'] != '') { 
		?><p style="border: 1px solid green; color: green; text-align: center;"><?php _e("Data saved OK!!!!!", 'cf7cc'); ?></p><?php
		update_option('_cf7cc_chatgpt_api_key', $_POST['_cf7cc_chatgpt_api_key']);
		update_option('_cf7cc_clientify_api_key', $_POST['_cf7cc_clientify_api_key']);
		update_option('_cf7cc_forms_ids', $_POST['_cf7cc_forms_ids']);
		update_option('_cf7cc_field_name', $_POST['_cf7cc_field_name']);
		update_option('_cf7cc_prompt', $_POST['_cf7cc_prompt']);
		update_option('_cf7cc_yes_tag', $_POST['_cf7cc_yes_tag']);
		update_option('_cf7cc_no_tag', $_POST['_cf7cc_no_tag']);
		update_option('_cf7cc_send_emails', $_POST['_cf7cc_send_emails']); 
	} ?>
	<form method="post">
    <h2><?php _e("Main configuration", 'cf7cc'); ?></h2>
		<b><?php _e("ChatGPT API key", 'cf7cc'); ?>:</b><br/>
		<input type="text" name="_cf7cc_chatgpt_api_key" value="<?php echo get_option("_cf7cc_chatgpt_api_key"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Clientify API key", 'cf7cc'); ?>:</b><br/>
		<input type="text" name="_cf7cc_clientify_api_key" value="<?php echo get_option("_cf7cc_clientify_api_key"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("CF7 forms ids", 'cf7cc'); ?><br/><small><?php _e("comma separated", 'cf7cc'); ?></small>:</b><br/>
		<input type="text" name="_cf7cc_forms_ids" value="<?php echo get_option("_cf7cc_forms_ids"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Field name to insert in prompt", 'cf7cc'); ?>:<br/><small><?php _e("comma separated", 'cf7cc'); ?></small></b><br/>
		<input type="text" name="_cf7cc_field_name" value="<?php echo get_option("_cf7cc_field_name"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Prompt", 'cf7cc'); ?>:<br/><small><?php _e("use field name between brackets [your-message]", 'cf7cc'); ?></small></b><br/>
		<input type="text" name="_cf7cc_prompt" value="<?php echo get_option("_cf7cc_prompt"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Clientify Tag if ChatGPT responds YES", 'cf7cc'); ?>:<br/><small><?php _e("use field name between brackets [your-message]", 'cf7cc'); ?></small></b><br/>
		<input type="text" name="_cf7cc_yes_tag" value="<?php echo get_option("_cf7cc_yes_tag"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Clientify Tag if ChatGPT responds NO", 'cf7cc'); ?>:<br/><small><?php _e("use field name between brackets [your-message]", 'cf7cc'); ?></small></b><br/>
		<input type="text" name="_cf7cc_no_tag" value="<?php echo get_option("_cf7cc_no_tag"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Emails to notify", 'cf7cc'); ?>:<br/><small>(<?php _e("comma separated", 'cf7cc'); ?>)</small></b><br/>
		<input type="text" name="_cf7cc_send_emails" value="<?php echo get_option("_cf7cc_send_emails"); ?>" style="width: calc(100% - 20px);" /><br/>
		<br/><input type="submit" name="send" class="button button-primary" value="<?php _e("Save", 'cf7cc'); ?>" />
	</form>
	<?php
}
