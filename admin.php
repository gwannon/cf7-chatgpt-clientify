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
		//echo "<pre>"; print_r ($_POST['_cf7cc_forms']); echo "</pre>";
		//Limpiamos los formualrios de CF7
		foreach ($_POST['_cf7cc_forms'] as $key => $form) {
			if ($form['form_id'] == 0) unset($_POST['_cf7cc_forms'][$key]);
		}
		$_POST['_cf7cc_forms'] = array_values($_POST['_cf7cc_forms']);
		//echo "<pre>"; print_r ($_POST['_cf7cc_forms']); echo "</pre>";

		foreach ($_POST as $label => $value) {
			if (strpos($label, "_cf7cc_") !== false) update_option($label, $value);
		}
		/*update_option('_cf7cc_chatgpt_api_key', $_POST['_cf7cc_chatgpt_api_key']);
		update_option('_cf7cc_clientify_api_key', $_POST['_cf7cc_clientify_api_key']);
		update_option('_cf7cc_forms_ids', $_POST['_cf7cc_forms_ids']);
		update_option('_cf7cc_field_name', $_POST['_cf7cc_field_name']);
		update_option('_cf7cc_prompt', $_POST['_cf7cc_prompt']);
		update_option('_cf7cc_yes_tag', $_POST['_cf7cc_yes_tag']);
		update_option('_cf7cc_no_tag', $_POST['_cf7cc_no_tag']);
		update_option('_cf7cc_no_tag', $_POST['_cf7cc_no_tag']);
		update_option('_cf7cc_send_emails', $_POST['_cf7cc_send_emails']); */
	} 
	
	$args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
	$cf7Forms = get_posts( $args );
	
	?>
	<form method="post">
    <h2><?php _e("Main configuration", 'cf7cc'); ?></h2>
		<b><?php _e("ChatGPT API key", 'cf7cc'); ?>:</b><br/>
		<input type="text" name="_cf7cc_chatgpt_api_key" value="<?php echo get_option("_cf7cc_chatgpt_api_key"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Clientify API key", 'cf7cc'); ?>:</b><br/>
		<input type="text" name="_cf7cc_clientify_api_key" value="<?php echo get_option("_cf7cc_clientify_api_key"); ?>" style="width: calc(100% - 20px);" /><br/>
		<table>
				<tr>
					<th>
						<?php _e("Form", 'cf7cc'); ?>
					</th>
					<th>
						<?php _e("Email field name", 'cf7cc'); ?>:<br/><small><?php _e("use field name without brackets (your-email)", 'cf7cc'); ?></small>
					</th>
					<th>
						<?php _e("Prompt", 'cf7cc'); ?>:<br/><small><?php _e("use field name between brackets ([your-message])", 'cf7cc'); ?></small>
					</th>
					<th>
						<?php _e("Response 1", 'cf7cc'); ?>
					</th>
					<th>
						<?php _e("Response 2", 'cf7cc'); ?>
					</th>
					<th>
						<?php _e("Response 3", 'cf7cc'); ?>
					</th>
				</tr>
			<?php 
				$prompts = get_option("_cf7cc_forms"); 
				$max = 3;
				if(count($prompts) >= $max) $max = count($prompts) + 1;
			
				for($i = 0; $i < $max; $i++) { ?>
				<tr>
					<td style="vertical-align: top;">
						<select name="_cf7cc_forms[<?=$i;?>][form_id]">
							<option value="0"><?php _e("Select CF7 form", 'cf7cc'); ?></option>
							<?php foreach($cf7Forms as $form) { ?>
								<option value="<?=$form->ID;?>"<?php echo (isset($prompts[$i]['form_id']) && $form->ID == $prompts[$i]['form_id'] ? " selected='selected'" : ""); ?>><?=$form->post_title;?></option>
							<?php } ?>
						</select>
					</td>
					<td style="vertical-align: top;">
						<input style="width: 100%;" type="text" name="_cf7cc_forms[<?=$i;?>][email_field]" value="<?php echo (isset($prompts[$i]['email_field']) ? $prompts[$i]['email_field'] : ""); ?>" />
					</td>
					<td style="vertical-align: top;">
						<textarea style="width: 100%; height: 100px;" name="_cf7cc_forms[<?=$i;?>][prompt]"><?php echo (isset($prompts[$i]['prompt']) ? stripslashes($prompts[$i]['prompt']) : ""); ?></textarea>
					</td>
					<td style="vertical-align: top;">
						<input style="width: 100%;" type="text" name="_cf7cc_forms[<?=$i;?>][response1]" value="<?php echo (isset($prompts[$i]['response1']) ? $prompts[$i]['response1'] : ""); ?>" placeholder="<?php _e("Response", 'cf7cc'); ?>" /><br/>
						<input style="width: 100%;" type="text" name="_cf7cc_forms[<?=$i;?>][tag1]" value="<?php echo (isset($prompts[$i]['tag1']) ? $prompts[$i]['tag1'] : ""); ?>" placeholder="<?php _e("Clientify Tag", 'cf7cc'); ?>" />
					</td>
					<td style="vertical-align: top;">
						<input style="width: 100%;" type="text" name="_cf7cc_forms[<?=$i;?>][response2]" value="<?php echo (isset($prompts[$i]['response2']) ? $prompts[$i]['response2'] : ""); ?>" placeholder="<?php _e("Response", 'cf7cc'); ?>" /><br/>
						<input style="width: 100%;" type="text" name="_cf7cc_forms[<?=$i;?>][tag2]" value="<?php echo (isset($prompts[$i]['tag2']) ? $prompts[$i]['tag2'] : ""); ?>" placeholder="<?php _e("Clientify Tag", 'cf7cc'); ?>" />
					</td>
					<td style="vertical-align: top;">
						<input style="width: 100%;" type="text" name="_cf7cc_forms[<?=$i;?>][response3]" value="<?php echo (isset($prompts[$i]['response3']) ? $prompts[$i]['response3'] : ""); ?>" placeholder="<?php _e("Response", 'cf7cc'); ?>" /><br/>
						<input style="width: 100%;" type="text" name="_cf7cc_forms[<?=$i;?>][tag3]" value="<?php echo (isset($prompts[$i]['tag3']) ? $prompts[$i]['tag3'] : ""); ?>" placeholder="<?php _e("Clientify Tag", 'cf7cc'); ?>" />
					</td>
				</tr>
			<?php } ?>
		</table>
		
		
		<?php /* <b><?php _e("CF7 forms ids", 'cf7cc'); ?><br/><small><?php _e("comma separated", 'cf7cc'); ?></small>:</b><br/>
		<input type="text" name="_cf7cc_forms_ids" value="<?php echo get_option("_cf7cc_forms_ids"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Field name to email", 'cf7cc'); ?>:</b><br/>
		<input type="text" name="_cf7cc_email" value="<?php echo get_option("_cf7cc_email"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Field name to insert in prompt", 'cf7cc'); ?>:<br/><small><?php _e("comma separated", 'cf7cc'); ?></small></b><br/>
		<input type="text" name="_cf7cc_field_name" value="<?php echo get_option("_cf7cc_field_name"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Prompt", 'cf7cc'); ?>:<br/><small><?php _e("use field name between brackets [your-message]", 'cf7cc'); ?></small></b><br/>
		<input type="text" name="_cf7cc_prompt" value="<?php echo get_option("_cf7cc_prompt"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Clientify Tag if ChatGPT responds YES", 'cf7cc'); ?>:</b><br/>
		<input type="text" name="_cf7cc_yes_tag" value="<?php echo get_option("_cf7cc_yes_tag"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Clientify Tag if ChatGPT responds NO", 'cf7cc'); ?>:</b><br/> 
		<input type="text" name="_cf7cc_no_tag" value="<?php echo get_option("_cf7cc_no_tag"); ?>" style="width: calc(100% - 20px);" /><br/> */ ?>
		
		
		
		
		<b><?php _e("Emails to notify", 'cf7cc'); ?>:<br/><small>(<?php _e("comma separated", 'cf7cc'); ?>)</small></b><br/>
		<input type="text" name="_cf7cc_send_emails" value="<?php echo get_option("_cf7cc_send_emails"); ?>" style="width: calc(100% - 20px);" /><br/>
		<br/><input type="submit" name="send" class="button button-primary" value="<?php _e("Save", 'cf7cc'); ?>" />
	</form>
	<?php
}
