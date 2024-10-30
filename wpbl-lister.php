<?php
/*
Plugin Name: Lister for The WP Blog List
Plugin URI: https://wpbl.org
Description: List your blog automatically on wpbl.org, the webs only plugin powered WP blogs list.
Version: 0.1.0
Author: Sam Wright
Author URI: https://www.patreon.com/samwrightwebdev
Text Domain: wpbl
Domain Path: /languages
License:     GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

// activation 
// send the blog for inclusion in the list

register_activation_hook( __FILE__, 'wpblx51_on_activation' );

function wpblx51_on_activation() {
	
	$key = get_option('wpblx51_key');
	
	if ($key) {
		delete_option('wpblx51_key');
		$key = false;
	}
	
	if (!$key) {
		$new_key = uniqid('', true);
		add_option('wpblx51_key', $new_key);
	}

	$name = urlencode(get_bloginfo('name')); 
	$description = urlencode(get_bloginfo('description')); 
	$url = urlencode(get_site_url());
	$new_key = urlencode($new_key);

	$get = 'https://wpbl.org/add/?xname=' . $name . '&xdescription=' . $description . '&xurl=' . $url . '&xkey=' . $new_key;

	wp_remote_get($get, array('timeout' => 30));

}

// deactivation
// remove the blog from the list 

register_deactivation_hook( __FILE__, 'wpblx51_on_deactivation' );

function wpblx51_on_deactivation() {
	$key = urlencode(get_option('wpblx51_key'));
	delete_option( 'wpblx51_key' );
	wp_remote_get('https://wpbl.org/remove/?xurl=' . $url . '&xkey=' . $key, array('timeout' => 30));
}