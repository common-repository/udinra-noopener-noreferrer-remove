<?php
/*
Plugin Name: Udinra Noopener Noreferrer Remove
Plugin URI: https://udinra.com/downloads/udinra-noopener-noreferrer-remove-pro
Description: Automatically removes Noopener and Noreferrer from links
Author: Udinra
Version: 1.2
Author URI: https://udinra.com

*/
/*
********************************************************************************
* Change Log                                                                   *
********************************************************************************
*  Version 1.2 - Rewrites the Complete plugin
*/

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

function Udinra_Links() {
	include 'lib/udinra_html_links.php';
}

function udinra_links_sitemap_admin() {
	if (function_exists('add_options_page')) {
		add_options_page('Udinra Links', 'Udinra Links', 'manage_options', basename(__FILE__), 'Udinra_Links');
	}
}

function udinra_links_admin_notice() {
	global $current_user ;
	$user_id = $current_user->ID;
	if ( ! get_user_meta($user_id, 'udinra_links_admin_notice') ) {
		echo '<div class="notice notice-info"><p>'; 
		printf(__('Remove Noopener Noreferrer from existing content. <a href="https://udinra.com/downloads/udinra-noopener-noreferrer-remove-pro">Know More</a> | <a href="%1$s">Hide Notice</a>'), '?udinra_links_admin_ignore=0');
		echo "</p></div>";
	}
}

function udinra_links_admin_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	if ( isset($_GET['udinra_links_admin_ignore']) && '0' == $_GET['udinra_links_admin_ignore'] ) {
		add_user_meta($user_id, 'udinra_links_admin_notice', 'true', true);
	}
}

function udinra_links_settings_plugin_link( $links, $file ) 
{
    if ( $file == plugin_basename(dirname(__FILE__) . '/udinra-noopener-noreferrer-remove.php') ) 
    {
        $in = '<a href="options-general.php?page=udinra-noopener-noreferrer-remove">' . __('Settings','udlinks') . '</a>';
        array_unshift($links, $in);
   }
    return $links;
}

function udinra_links_admin_style($hook) {
	if($hook == 'settings_page_udinra-noopener-noreferrer-remove') {
		wp_enqueue_style( 'udinra_links_pure_style', plugins_url('css/udstyle.css', __FILE__) );	
    }
}


function udinra_remove_link_target( $mceInit ) {
    $mceInit['allow_unsafe_link_target']=true;
    return $mceInit;
}

add_filter('tiny_mce_before_init','udinra_remove_link_target');

add_action('admin_menu','udinra_links_sitemap_admin');	
add_action('admin_notices', 'udinra_links_admin_notice');
add_action('admin_init', 'udinra_links_admin_ignore');
add_filter( 'plugin_action_links', 'udinra_links_settings_plugin_link', 10, 2 );
add_action( 'admin_enqueue_scripts', 'udinra_links_admin_style' );

?>
