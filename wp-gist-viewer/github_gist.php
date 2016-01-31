<?php
/*
Plugin Name: WP Gist Viewer
Plugin URI: http://aligoren.com
Description: Github's Gist Viewer
Version: 0.1
Author: Ali GOREN
Author URI: http://aligoren.com
*/


add_action( 'admin_menu', 'gist_menu' );
add_action( 'admin_init', 'wpGist_settings_update' );


function gist_menu() {
	add_options_page( 'Gist Settings', 'WpGist Options', 'manage_options', 'gist-wp', 'gist_options' );
}

function wpGist_settings_update(){
	register_setting('wpGist_settings', 'client_id');
	register_setting('wpGist_settings', 'client_secret_id');
}

function gist_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	if($_GET["code"])
	{
		$code = $_GET['code'];
		$data = 'client_id=' . get_option("client_id") . '&' .
				'client_secret=' . get_option("client_secret_id") . '&' .
				'code=' . urlencode($code);
		$ch = curl_init('https://github.com/login/oauth/access_token');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		preg_match('/access_token=([0-9a-f]+)/', $response, $out);
		$token =  $out[1];
		curl_close($ch);

		echo $token;
	}
?>
<h2>WpGist Ayarları</h2>
<form method="post" action="options.php">
	<?php settings_fields( 'wpGist_settings' ); ?>
	<?php do_settings_sections( 'wpGist_settings_update' ); ?>
	<label>Client Id <input type="text" name="client_id" id="client_id" value="<?php echo get_option("client_id"); ?>"/></label>
	<label>Client Secret Id <input type="text" name="client_secret_id" id="client_secret_id" value="<?php echo get_option("client_secret_id"); ?>" /></label><br>
		<?php submit_button(); ?>
</form>

<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<h2>Login Github</h2>
<button id="login">Login</button>
<input type="hidden" value="<?php echo $token; ?>" id="acces_token"  />
<script>
// Step 2
$('#login').click(function () {
	window.open('https://github.com' +
		'/login/oauth/authorize' +
		'?client_id=' + $('#client_id').val() +
		'&scope=gist');
	window.close();
});
</script>
	<?php

}


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
