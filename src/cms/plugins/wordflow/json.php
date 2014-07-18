<?php

/*
 * Controller name: Wordflow
 * Controller description: Custom json calls for Wordflow.
 *
 * Note:
 *  - This class is an extention from the JSON API plugin for wordpress.
 *  - To activate, go to WP Dashboard > Settings > JSON API and activate 'wordflow'.
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
 *  - http://localhost/visi-cons/deploy/local/wp/?json=wordflow.[function name]
 *  - i.e. http://localhost/visi-cons/deploy/local/wp/?json=wordflow.get_menu
 *
 */

class JSON_API_Wordflow_Controller {

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
	    $array["menu"][$counter]["ID"] = $value->ID;
	    $array["menu"][$counter]["title"] = $value->post_title;
	    $array["menu"][$counter]["description"] = $value->description;
	    $array["menu"][$counter]["target"] = $value->target;
	    $array["menu"][$counter]["url"] = ($value->target == '') ? str_replace('http://', '', $value->url) : $value->url;
	    $array["menu"][$counter]["classes"] = $value->classes;
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
	public function get_sample_page() {
		$array = array();
		$args = array(
			'name' => 'sample-page',
			'post_type' => 'page'
		);
		$loop = new WP_Query($args);
		
		while ($loop->have_posts()) : $loop->the_post();
			$array['sample']['post_id'] = get_the_id();
			$array['sample']['wp_title'] = get_the_title();
			$array['sample']['copy'] = get_the_content();
			$array['sample']['hi'] = get_field('hi');
		endwhile;

		// write it yo
		@mkdir(JSON_DATAPATH, 0777, true);
		$u = umask(0777);
		$fp = fopen(JSON_DATAPATH.'sample.json', 'w+');
		fwrite($fp, json_encode($array));
		fclose($fp);
		@chmod(JSON_DATAPATH.'sample.json',0777);

		return $array;
	}

}

?>