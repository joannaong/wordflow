<?php

/*
 * Controller name: Custom
 * Controller description: Custom json calls for Conspiracy Show.
 *
 * Note:
 *  - This class is an extention from the JSON API plugin for wordpress.
 *  - To activate, go to WP Dashboard > Settings > JSON API and activate 'custom'.
 *  - Plugin link: http://wordpress.org/plugins/json-api
 *  - Documentation: http://wordpress.org/plugins/json-api/other_notes/
 *  - my custom functions auto writes to a json file when called upon
 *
 * USAGE: Sample functions usage
 *   public function hello_world() {
 *     return array( "message" => "Hello, world" );
 *   }
 *
 *   public function hello_person() {
 *     global $json_api;
 *     $name = $json_api->query->name;
 *     return array( "message" => "Hello, $name." );
 *   }
 *
 * CALLS:
 *  - http://localhost/visi-cons/deploy/local/wp/?json=custom.[function name]
 *  - i.e. http://localhost/visi-cons/deploy/local/wp/?json=custom.get_menu
 *
 */

class JSON_API_Custom_Controller {

	// Menu which calls upon wp function wp_get_nav_menu_items
	public function get_menu() {
		$array = array();
		$args = array(
			'order'                  => 'ASC',
			'orderby'                => 'menu_order',
			'post_type'              => 'nav_menu_item',
			'post_status'            => 'publish',
			'output_key'             => 'menu_order'
		);
		$m = wp_get_nav_menu_items('Primary', $args);

		$counter = 0;
		foreach ($m as $value) {
	    $array["menuNav"][$counter]["ID"] = $value->ID;
	    $array["menuNav"][$counter]["title"] = $value->post_title;
	    $array["menuNav"][$counter]["description"] = $value->description;
	    $array["menuNav"][$counter]["target"] = $value->target;
	    $array["menuNav"][$counter]["url"] = ($value->target == '') ? str_replace('http://', '', $value->url) : $value->url;
	    $array["menuNav"][$counter]["classes"] = $value->classes;
	    $counter++;
		}

		@mkdir(JSON_DATAPATH, 0777, true);
		$u = umask(0777);
		$fp = fopen(JSON_DATAPATH.'nav.json', 'w+');
		fwrite($fp, json_encode($array));
		fclose($fp);
		@chmod(JSON_DATAPATH.'nav.json',0777);

		return $array;
	}

	// PAGE with custom fields
	public function get_privacy_page() {
		$array = array();
		$args = array(
			'name' => 'privacy-policy',
			'post_type' => 'page'
		);
		$loop = new WP_Query($args);
		
		while ($loop->have_posts()) : $loop->the_post();
			$array['privacy']['post_id'] = get_the_id();
			$array['privacy']['wp_title'] = get_the_title();
			$array['privacy']['title_bolded'] = get_field('title_bolded');
			$array['privacy']['title_normal'] = get_field('title_normal');
			$array['privacy']['show_title'] = get_field('show_title');
			$array['privacy']['copy'] = get_the_content();
		endwhile;

		// write it yo
		@mkdir(JSON_DATAPATH, 0777, true);
		$u = umask(0777);
		$fp = fopen(JSON_DATAPATH.'privacy_data.json', 'w+');
		fwrite($fp, json_encode($array));
		fclose($fp);
		@chmod(JSON_DATAPATH.'privacy.json',0777);

		return $array;
	}

	// Custom Post Type with custom fields
	public function get_media_page() {
		$array = array();

		// past_episodes
		$cat_args = array(
		  'order' => 'ASC',
		  'taxonomy'=> 'season_number',
		  'type' => 'past_episode'
   	);
		$categories = get_categories($cat_args);
		
		$counter = 0;
		foreach($categories as $category) { 
	    $args = array(
	      'posts_per_page' => -1,
	      'season_number' => $category->slug,
	      'post_type' => 'past_episode'
	    );
	    $loop = new WP_Query($args);
    	$counter2 = 0;

    	$array['past_episode'][$counter]['key'] = $category->slug;
    	$array['past_episode'][$counter]['season'] = $category->name;

    	while ($loop->have_posts()) : $loop->the_post();
    		$array['past_episode'][$counter]['data'][$counter2]['post_id'] = get_the_id();
	    	$array['past_episode'][$counter]['data'][$counter2]['wp_title'] = get_the_title();
	    	$array['past_episode'][$counter]['data'][$counter2]['ep_title'] = get_field('episode_title');
	    	$array['past_episode'][$counter]['data'][$counter2]['ep_thumbnail'] = get_field('episode_thumbnail');
	    	$array['past_episode'][$counter]['data'][$counter2]['ep_synopsis'] = htmlentities(get_field('episode_synopsis'));
	    	$array['past_episode'][$counter]['data'][$counter2]['ep_link'] = get_field('episode_link');

	    	$counter2++;
	    endwhile;

		  $counter++;
    }

		// write it yo
		@mkdir(JSON_DATAPATH, 0777, true);
		$u = umask(0777);
		$fp = fopen(JSON_DATAPATH.'media.json', 'w+');
		fwrite($fp, json_encode($array));
		fclose($fp);
		@chmod(JSON_DATAPATH.'media.json',0777);

		return $array;
	}

}

?>