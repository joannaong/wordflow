<?php

/*
 * Plugin Name: Wordflow
 * Description: Custom functionality for Wordflow. 2014
 * Version: 0.1
 * Author: Joanna Ong
 * Author URI: http://joannaong.ca
 *
 */

// -----------
// INCLUDES
// -----------

// Uncomment if you need to add custom post types
// @include_once plugin_dir_path( __FILE__ )."custom-post-type.php";
@include_once plugin_dir_path( __FILE__ )."site-build.php";

// -----------
// FILTERS
// -----------

// Remove sidebar from the dashboard sidebar
add_action('admin_menu', 'remove_sidebar_dashboard');

// Extend json api
add_filter('json_api_controllers', 'add_wordflow_controller');
add_filter('json_api_wordflow_controller_path', 'set_wordflow_controller_path');

// add js
add_action('init','add_script');

// remove preview for each post/page
add_action('admin_print_styles', 'posttype_admin_css');

// remove dashboard widgets
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );

// add global vars to admin area
add_action('admin_footer', 'global_vars');


// -----------
// FUNCTIONS
// -----------

function add_script() {
	wp_enqueue_script('wordflow-js', plugins_url('/js/wordflow.js', __FILE__));
	wp_enqueue_style('wordflow-css', plugins_url('/css/wordflow.css', __FILE__));
}

function add_wordflow_controller($controllers) {
  $controllers[] = 'wordflow';
  return $controllers;
}

function set_wordflow_controller_path() {
  return plugin_dir_path( __FILE__ )."json.php";
}

function remove_sidebar_dashboard() {
  remove_menu_page('edit-comments.php');
  remove_menu_page('edit.php');         
}

function posttype_admin_css() {
	echo '<style type="text/css">
		#wp-admin-bar-site-name, #acf-col-right .footer, #cpt_info_box, #edit-slug-box, #minor-publishing-actions {
			display:none;
		}
	</style>';
}

function remove_dashboard_widgets() {
	global $wp_meta_boxes;

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
}

function global_vars(){
  // echo '<script> var VOTE_API = "'.VOTE_API.'"</script>';
}

?>