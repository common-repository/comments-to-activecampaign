<?php
function ctac_InitiateAC($url='',$key=''){
	if ($url == '' || $key == ''){
		$url = get_option( 'ctac_api_access' )['url'];
		$key = get_option( 'ctac_api_access' )['key'];
	}
	
	if ($url != '' && $key !=''){
		if (!class_exists('ActiveCampaign'))
			require_once(plugin_dir_path(__FILE__)."ac/ActiveCampaign.class.php");
		
		$ac = new ActiveCampaign($url,$key);
		return $ac;
	}else{
		return false;
	}
}

function ctac_GetListsFromAC(){
	$selected = (int) $_POST['selected'];
	$name = esc_attr($_POST['name']);
	
	if ($name != ''){
		$out = array();
		$list_exists = false;
		
		if ( $ac = ctac_InitiateAC() ){
			$response = $ac->api("list/list?ids=all");
			//print_r($response); die;
			$select = "<select name='$name'><option value=''>Please select</option>";
			if ((int)$response->success) {
				foreach ($response as $r){
					if (!isset($r->id) || $r->id == '') continue;
					$isselected = $selected == $r->id ? 'selected' : '';
					$select .= "<option value='$r->id' $isselected>$r->name</option>";
					$list_exists = true;
				}
			}
			$select .= '<select>';
			$out['select'] = $list_exists ? $select : 'Please create a list in Active Campaign';
		}else{
			$out['select'] = 'Internal Error: Might be related with your Active Campaign credentials';
		}
	}else{
		$out['select'] = 'Internal Error: Unknown';
	}
	print json_encode($out);
	wp_die();
}
add_action( 'wp_ajax_ctac_GetListsFromAC', 'ctac_GetListsFromAC' );
add_action( 'wp_ajax_nopriv_ctac_GetListsFromAC', 'ctac_GetListsFromAC' );

function ctac_ACSyncUser($params){
	if ( $ac = ctac_InitiateAC() ){
		$response = $ac->api("contact/sync", $params);
		//TODO Logging, try catch, fallbacks etc..
	}
}