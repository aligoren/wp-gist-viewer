<?php
/*
Plugin Name: WP Gist Shower
Plugin URI: http://aligoren.com
Description: Github's Gist Shower
Version: 0.1
Author: Ali GOREN
Author URI: http://aligoren.com
*/

// Add TinyMCE Plugin

function gist_add_tinymce_plugin( $plugin_array ) {
	
	$plugin_array['gist_add'] = plugins_url( '/js/gist_button.js', __FILE__ );


	return $plugin_array;
}

// Add Button

function add_tinymce_gist_button( $buttons ) {
	array_push( $buttons, 'gist_add_open_button' );

	return $buttons;
}

// Return TinyMCE button

function add_tinymce_gist_button_r( $buttons ) {

	return $buttons;
}


// Button for TinyMCE Editor

function add_gist_tinymce() {
	global $typenow;
	
	if( ! in_array( $typenow, array( 'post', 'page' ) ) )
		return ;
	
	add_filter( 'mce_external_plugins', 'gist_add_tinymce_plugin' );

	add_filter( 'mce_buttons', 'add_tinymce_gist_button' );
	add_filter( 'mce_buttons_2', 'add_tinymce_gist_button_r' );
}
add_action( 'admin_head', 'add_gist_tinymce' );


// Shortcode for Post Page

function gist_code( $atts ) {

	extract( shortcode_atts(
		array(
			'source' => 'source',
		), $atts )
	);


    $rtn = '<script src=https://gist.github.com/'.$source.'.js'.'></script>';
    return $rtn;
}
add_shortcode( 'gist', 'gist_code' );
?>