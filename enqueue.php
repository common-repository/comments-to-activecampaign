<?php
function ctac_enqueue_scripts($hook){
	wp_register_script( 'ctac_backend_js', plugins_url( 'js/backend.js', __FILE__ ), array( 'jquery' ), '', true );
	wp_enqueue_script( 'ctac_backend_js' );
}
add_action( 'admin_enqueue_scripts', 'ctac_enqueue_scripts' );