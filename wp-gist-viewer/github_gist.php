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


function gist_menu() {
	add_options_page( 'Gist Settings', 'WpGist Options', 'manage_options', 'gist-wp', 'gist_options' );
}


function gist_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	if($_GET["code"])
	{

		$code = $_GET['code'];

		$data = 'client_id=' . '291792fd3b6c20afd5e1' . '&' .
				'client_secret=' . '18a513950f5026ca6b793894dc34c7e7d3085b89' . '&' .
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

<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<h2>Login Github</h2>
<label>Client Id <input type="text" id="client_id" /></label>
<label>code <input type="text" id="code" /></label>
<button id="login">Login</button>
<label>access_token <input type="text" id="access_token" /></label>
<label>username <input type="text" id="username" /></label>
<input type="hidden" value="<?php echo $token; ?>" id="acces_token" />
<script>
// Step 2
$('#login').click(function () {
	window.open('https://github.com' +
		'/login/oauth/authorize' +
		'?client_id=' + $('#client_id').val() +
		'&scope=gist');


});

	function getURLParameter(name) {
	  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
	}

	var code = getURLParameter("code");
	if(code != null)
	{
			var access_token = $('#acces_token').val();
			$.getJSON('https://api.github.com/user?access_token=' + access_token, function (user) {
				alert('print object: ' + JSON.stringify(user));
				$('#username').val(user.login);
			});

	}
</script>
	<?
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
