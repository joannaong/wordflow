<?php

/*
 * Custom Post Type Class
 */

class Custom_PostType {

	function __construct() {
		add_action('init', array($this,'CPT_past_episode'));
		add_action('init', array($this,'TAX_past_episode'));
  }

	function CPT_past_episode() {
		$labels = array(
			'name'               => 'Past Episodes',
			'singular_name'      => 'Past Episode',
			'menu_name'          => 'Past Episodes',
			'name_admin_bar'     => 'Past Episode',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Past Episode',
			'new_item'           => 'New Past Episode',
			'edit_item'          => 'Edit Past Episode',
			'view_item'          => 'View Past Episode',
			'all_items'          => 'All Past Episodes',
			'search_items'       => 'Search Past Episodes',
			'parent_item_colon'  => 'Parent Past Episodes:',
			'not_found'          => 'No past episodes found.',
			'not_found_in_trash' => 'No past episodes found in Trash.'
		);
		$args = array(
			'labels'             => $labels,
			'description'        => 'Episodes for past season',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug' => 'past_episode'),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'			 		 => 'dashicons-video-alt3',
			'supports'           => array('title', 'thumbnail')
		);
		register_post_type('past_episode', $args);
	}

	function TAX_past_episode() {
		register_taxonomy(
			'season_number',
			'past_episode',
			array(
				'label' => __('Season Number'),
				'hierarchical' => true
			)
		);
	}

}

new Custom_PostType();

?>