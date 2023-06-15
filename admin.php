<?php

//Administrador 
add_action( 'admin_menu', 'cf7cc_plugin_menu' );
function cf7cc_plugin_menu() {
	add_options_page( __('ChatGPT', 'cf7cc'), __('ChatGPT', 'cf7cc'), 'manage_options', 'cf7cc', 'cf7cc_page_settings');
}

function cf7cc_page_settings() { 
	?><h1><?php _e("Configuración WP A tu gusto", 'cf7cc'); ?></h1><?php 
	if(isset($_REQUEST['send']) && $_REQUEST['send'] != '') { 
		?><p style="border: 1px solid green; color: green; text-align: center;"><?php _e("Datos guardados correctamente.", 'cf7cc'); ?></p><?php
		update_option('_cf7cc_api_key', $_POST['_cf7cc_api_key']);
		update_option('_cf7cc_forms_ids', $_POST['_cf7cc_forms_ids']);
		update_option('_cf7cc_field_name', $_POST['_cf7cc_field_name']);
		update_option('_cf7cc_prompt', $_POST['_cf7cc_prompt']);
		update_option('_cf7cc_send_emails', $_POST['_cf7cc_send_emails']); 
	} ?>
	<form method="post">
    <h2><?php _e("Configuración", 'cf7cc'); ?></h2>
		<b><?php _e("ChatGPT Api key", 'cf7cc'); ?>:</b><br/>
		<input type="text" name="_cf7cc_api_key" value="<?php echo get_option("_cf7cc_api_key"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("IDs de los formularios de CF7 (separados por comas)", 'cf7cc'); ?>:</b><br/>
		<input type="text" name="_cf7cc_forms_ids" value="<?php echo get_option("_cf7cc_forms_ids"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Nombres de los campo del formularios para meter en el prompt<br/><small>(separados por comas)</small>", 'cf7cc'); ?>:</b><br/>
		<input type="text" name="_cf7cc_field_name" value="<?php echo get_option("_cf7cc_field_name"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Prompt<br/><small>(mete el nombre del campo como lo metes en el email de notificación [your-message])</small>", 'cf7cc'); ?>:</b><br/>
		<input type="text" name="_cf7cc_prompt" value="<?php echo get_option("_cf7cc_prompt"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Emails de aviso<br/><small>(separados por comas)</small>", 'cf7cc'); ?>:</b><br/>
		<input type="text" name="_cf7cc_send_emails" value="<?php echo get_option("_cf7cc_send_emails"); ?>" style="width: calc(100% - 20px);" /><br/>
		<br/><input type="submit" name="send" class="button button-primary" value="<?php _e("Guardar", 'cf7cc'); ?>" />
	</form>
	<?php
}