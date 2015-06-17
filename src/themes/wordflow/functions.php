<?php

// MENU
function theme_setup() {
	register_nav_menus( array(
		'primary'   => __('Top primary menu', 'visicons')
	));
}
add_action('after_setup_theme', 'theme_setup');

// Include scripts and styles
function theme_scripts() {
	wp_enqueue_style( 'style-name', get_stylesheet_uri() );
	// wp_enqueue_script( 'script-name', get_template_directory_uri( . '/js/example.js', array(), '1.0.
}
add_action('wp_enqueue_scripts', 'theme_scripts');

?>