<?php
function ctac_comment_process($comment_id) {
    $comment_output = get_comment( $comment_id );

    $status_global = get_option( 'ctac_activate_globally' );
    $status_for_page = get_post_meta( $comment_output->comment_post_ID, '_ctac_status', true );
    
    
    if ($status_global == 1 || $status_for_page == 1){
        $list_id = get_option( 'ctac_list_id' );
        
        //overwrite the list id if it is defined in page meta
        if ($status_for_page == 1)
            $list_id = get_post_meta( $comment_output->comment_post_ID, '_ctac_list_id', true );
        
        //ready to sync!        
        $params = array(
            "email" => $comment_output->comment_author_email,
            "first_name" => $comment_output->comment_author,
            "p[$list_id]" => $list_id
        );
        ctac_ACSyncUser($params);
    
    }
}	
add_action('comment_post', 'ctac_comment_process');