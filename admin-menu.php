<?php
function comments_to_activecampaign_menu() {
	add_options_page( 
		'Comments To ActiveCampaign',
		'Comments To ActiveCampaign',
		'manage_options',
		'comments-to-activecampaign.php',
		'comments_to_activecampaign_page'
	);
	add_action( 'admin_init', 'register_comments_to_activecampaign_settings' );
}
add_action( 'admin_menu', 'comments_to_activecampaign_menu' );

function register_comments_to_activecampaign_settings() {
	register_setting( 'comments-to-activecampaign-settings-group', 'ctac_api_access', 'ctac_validate_API' );
	register_setting( 'comments-to-activecampaign-settings-group', 'ctac_activate_globally' );
        register_setting( 'comments-to-activecampaign-settings-group', 'ctac_list_id' );
}

function ctac_validate_API($inputs){
	//error_log(print_r($inputs,1));
	//print_r($inputs);
	$type = 'error';
	$message = 'Unknown Error';
	if ($inputs["url"] == '' || $inputs["key"] == ''){
		$type = 'error';
		$message = 'You can\'t leave the fields blank. Please fill and submit again.';
	}else{
		if ( $ac = ctac_InitiateAC($inputs["url"], $inputs["key"]) ){
			$response = $ac->api("account/view");
			//print_r($response); die;
			if ( is_object($response) && $response->success ){
				//success!
				$type = 'updated';
				$message = 'Successfully saved';
			}else{
				$type = 'error';
				$message = 'We couldn\'t authorize your account. Please check "Active Campaign API Access URL" or "Active Campaign API Access Key" and try again.';
			}
		}else{
			$type = 'error';
			$message = 'We couldn\'t authorize your account. Please check "Active Campaign API Access URL" or "Active Campaign API Access Key" and try again.';
		}
	}
	
	if ($type == 'error'){
		add_settings_error(
			'ctac_api_access_url',
			'settings_updated',
			$message,
			$type
		);
	}else{
		return $inputs;
	}
}

function comments_to_activecampaign_page(){
?>
	<div class='wrap'>
		<h2><span class="dashicons dashicons-admin-settings" style='line-height: 1.1;font-size: 30px; padding-right: 10px;'></span> Comments To ActiveCampaign</h2>
		<form method='post' action='options.php'>
			<?php settings_fields( 'comments-to-activecampaign-settings-group' ); ?>
			<?php do_settings_sections( 'comments-to-activecampaign-settings-group' ); ?>
			<table class='form-table'>
				<tr>
					<th scope='row'>Active Campaign API Access URL</th>
					<td>
						<fieldset>
							<legend class='screen-reader-text'>
								<span>Active Campaign API Access URL</span>
							</legend>
							<label for='ctac_api_access_url'>
								<input name='ctac_api_access[url]' id='ctac_api_access_url' type='text' class="regular-text" value="<?php print get_option( 'ctac_api_access' )['url']; ?>" />
							</label>
							<p class='description'>See <a href="https://help.activecampaign.com/hc/en-us/articles/207317590-Getting-started-with-the-API?utm_campaign=Wordpress+Plugin+Comments+To+ActiveCampaign">"Obtain API URL and key"</a>.</p>
						</fieldset>
					</td>
					
				</tr>
				<tr>
					<th scope='row'>Active Campaign API Access Key</th>
					<td>
						<fieldset>
							<legend class='screen-reader-text'>
								<span>Active Campaign API Access Key</span>
							</legend>
							<label for='ctac_api_access_key'>
								<input name='ctac_api_access[key]' id='ctac_api_access_key' type='password' class="regular-text" value="<?php print get_option( 'ctac_api_access' )['key']; ?>" />
							</label>
							<p class='description'>See <a href="https://help.activecampaign.com/hc/en-us/articles/207317590-Getting-started-with-the-API?utm_campaign=Wordpress+Plugin+Comments+To+ActiveCampaign">"Obtain API URL and key"</a>.</p>
						</fieldset>
					</td>
					
				</tr>
				<?php if ( $ac = ctac_InitiateAC($inputs["url"], $inputs["key"]) ) : ?>
				<tr>
					<th scope='row'>Default List</th>
					<td>
						<fieldset>
							<legend class='screen-reader-text'>
								<span>Default List</span>
							</legend>
							<label for='ctac_ac_lists' class='ctac_GetListsFromAC' data-selected-list-id='<?php print get_option( 'ctac_list_id' );?>' data-select-name='ctac_list_id'>
								Loading...
							</label>
							<p class='description'>You can still overwrite this setting in the individual post.</p>
						</fieldset>
					</td>
					
				</tr>
				<?php endif; ?>
				<tr>
					<th scope='row'>Activate Globally</th>
					<td>
						<fieldset>
							<legend class='screen-reader-text'>
								<span>Activate Globally</span>
							</legend>
							<label for='ctac_activate_globally'>
								<input name='ctac_activate_globally' id='ctac_activate_globally' type='checkbox' value='1' <?php print checked( '1', get_option( 'ctac_activate_globally' ) ) ?> />
								Click if you want to use the plugin for ALL your posts.
							</label>
							<p class='description'>One click to activate the plugin for all your posts. You can still overwrite this setting in the individual post.</p>
						</fieldset>
					</td>
					
				</tr>
			</table>

			<p>
			<?php submit_button(); ?>
		</form>
	
	</div>
<?php
}