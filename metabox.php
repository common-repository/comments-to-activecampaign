<?php
function ctac_add_meta_box() {
    $screens = array( 'post', 'page' );
    foreach ($screens as $screen) {
        add_meta_box(
            'comments_to_activecampaign_meta_box',
            'Comment To ActiveCampaign',
            'comments_to_activecampaign_meta_box_inner_content',
            $screen,
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'ctac_add_meta_box' );

function comments_to_activecampaign_meta_box_inner_content( $post ) {

	wp_nonce_field( plugin_basename( __FILE__ ), 'comments_to_activecampaign_post_nonce' );
      
?>
	<p class="meta-options">
		<label for="_ctac_status" class="selectit">
			<input name='_ctac_status' id='_ctac_status' type='checkbox' value='1' <?php print checked( '1', get_post_meta( $post->ID, '_ctac_status', true ) ) ?> /> Activate for this post
		</label>
		<?php if (get_option( 'ctac_activate_globally' ) == 1) : ?>
		<p class="howto" id="_ctac_status-desc">This will overwrite the settings <a href='<?php print admin_url("options-general.php?page=comments-to-activecampaign.php"); ?>'>here</a></p>
		<? endif; ?>
	</p>
	<p class="meta-options">
		<label for='_ctac_list_id' class='ctac_GetListsFromAC' data-selected-list-id='<?php print get_post_meta( $post->ID, '_ctac_list_id', true );?>' data-select-name='_ctac_list_id'>
			Loading...
		</label>
		
	</p>
  
<?
}


function comments_to_activecampaign_save_postdata( $post_id ) {
 
	if ( 'page' == $_POST['post_type'] ) {
	  if ( ! current_user_can( 'edit_page', $post_id ) )
	      return;
	} else {
	  if ( ! current_user_can( 'edit_post', $post_id ) )
	      return;
	}
      
	if ( ! isset( $_POST['comments_to_activecampaign_post_nonce'] ) || ! wp_verify_nonce( $_POST['comments_to_activecampaign_post_nonce'], plugin_basename( __FILE__ ) ) )
	    return;

	$_ctac_status = (int) $_POST['_ctac_status'];
	$_ctac_list_id = (int) $_POST['_ctac_list_id'];
  
  	update_post_meta($post_id, '_ctac_status', $_ctac_status);
	update_post_meta($post_id, '_ctac_list_id', $_ctac_list_id);
}
add_action( 'save_post', 'comments_to_activecampaign_save_postdata' );