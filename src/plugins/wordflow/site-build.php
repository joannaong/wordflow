<?php

/*
 * Site Build
 * added in dashboard sidebar
 */

class Site_Build {

	function __construct() {
		// add publish site into sidebar
		add_action('admin_menu', array($this,'admin_menu_reg'));

		// add metabox inside page/post
		add_action('load-post.php', array($this,'metabox_setup'));
		add_action('load-post-new.php', array($this,'metabox_setup'));

		// add dashboard widget
		add_action('wp_dashboard_setup', array($this,'add_dashboard_widgets'));
	}
	
	function admin_menu_reg(){
		add_menu_page('Publish Site', 'Publish Site', 'manage_options', 'publish_site', array($this,'build_init'), 'dashicons-upload', 50);
	}

	function metabox_setup() {
	  add_action('add_meta_boxes', array($this,'metabox_add'));

	  if(isset($_GET['vpreview']) == true) {
	    $this->call_shell("preview");
		}
		if(isset($_GET['vpublish']) == true) {
	    $this->call_shell("publish");
		}
	}

	function add_dashboard_widgets() {
		wp_add_dashboard_widget(
			'site_urls',
			'Site URLs',
			array($this,'site_urls')
    );	
	}

	function site_urls() {
		// Display whatever it is you want to show.
		echo '<p>Preview URL: <a target="_blank" href="'.PREVIEW_SITEURL.'">'.PREVIEW_SITEURL.'</a></p><p>Live URL: <a target="_blank" href="'.MAIN_SITEURL.'">'.MAIN_SITEURL.'</a></p>';
	}

	function metabox_add() {
		// params: add_meta_box(Unique ID, Title of metabox, Callback function, Admin page (or post type), Context, Priority)

		// Page (about / sponsors)
		add_meta_box('singlepage-build', 'Preview / Publish Live', array($this,'single_build'), 'page', 'side', 'core');

  	// Current episode
  	add_meta_box('current_episode-build', 'Single Post Build', array($this,'single_build'), 'current_episode', 'side', 'core');
  	
  	// Past episode
  	add_meta_box('past_episode-build', 'Single Post Build', array($this,'single_build'), 'past_episode','side', 'core');
  	
  	// Podcast
  	add_meta_box('podcast-build', 'Single Post Build', array($this,'single_build'), 'podcast', 'side', 'core');
	}

	function single_build($object, $box) {
		global $post;
		$post_id = $post->ID;
		$post_name = $post->post_name;
		$post_type = $post->post_type;

		// sample page
		if ($post_name == "sample") {
			$page_url = PAGE_SAMPLE;
			$get_function = "sample_page";
		}
		
	  echo '
	  	<h4>Preview your changes</h4>
	  	<a data-postid="'.$post_id.'" data-siteurl="'.site_url().'" data-getfunction="'.$get_function.'" id="single-page-preview" class="button button-primary wordflow-build" href="post.php?post='.$post_id.'&action=edit&vpreview=true&redirect='.$page_url.'">Preview</a>

	  	<br><br><hr><h4>Publish</h4><p>Click below to push the site live. Please make sure your changes have been previewed and double-checked.</p>
	  	<a data-postid="'.$post_id.'" data-siteurl="'.site_url().'" data-getfunction="'.$get_function.'" class="button button-primary wordflow-build" id="single-page-build" href="post.php?post='.$post_id.'&action=edit&vpublish=true&redirect='.$page_url.'">Publish Live</a>';
	}

	
	function build_init() {
	  if(isset($_GET['vpreview']) == true) {
	  	$this->build_html();
	  	$this->call_shell("preview");
		} else if(isset($_GET['vpublish']) == true) {
	  	$this->build_html();
	    $this->call_shell("publish");
		} else {
			$this->build_html();
		}
	}

	function build_html(){
		$html = '
			<div class="wrap">
				<h2>Publish Site</h2>
				<div class="postbox">
					<div class="inside">
						<h3>Preview Site</h3>
						<a data-siteurl="'.site_url().'" href="admin.php?page=publish_site&vpreview=true" class="wordflow-build-all button button-primary">Preview Site</a>
						<h3><br>Publish Site</h3>
						<p>Click below to push the site live. Please make sure your changes have been previewed and double-checked.</p>
						<a data-siteurl="'.site_url().'" href="admin.php?page=publish_site&vpublish=true" class="wordflow-build-all button button-primary">Publish Site</a>
					</div>
				</div>
			</div>';
	  echo $html;
	}

	/*
	 * param: "preview" / "publish"
	 * $this->call_shell("preview");
	 */
	function call_shell($call) {
		if ($call == "preview") {
			$url = PREVIEW_SITEURL;
			$script = PREVIEW_SCRIPT;
		} 
		if ($call == "publish") {
			$url = MAIN_SITEURL;
			$script = PUBLISH_SCRIPT;
		}

		$redirect = isset($_GET['redirect']) ? $url.$_GET["redirect"] : $url;
		$workpath = LOCKFILE;
		$sworkpath = escapeshellarg(LOCKFILE);

		$u = umask(0777);
		@mkdir(DOCROOT."log", 0777, true);
		$fp = fopen($workpath.".lck", "w+");

		$block = 0;
		if (flock($fp, LOCK_EX|LOCK_NB, $block)) { // do an exclusive lock
			exec($script, $output, $status);
			$json['output'][0] = 'Generating Published Site';
			$i = 1;
			$log = "";
			foreach( $output as $line ) {
				$json['output'][$i] = $line;
				$log .= $line . "\r\n";
				$i++;
			}

			$u = umask(0777);
			$logfp = fopen(LOG_FILE, 'w+');
			
			$output['log_timestamp'] = "Timestamp: " . date( "Y/m/d H:i:s T" );
			$json['debug']['log_file_written'] = fputs($logfp, $log);
			$json['debug']['log_file_written'] .= " " . LOG_FILE;
			fclose($logfp);

			@chmod(LOG_FILE,0777);

			$json['output'][$i] = 'Published Site generated, redirecting to Published Site URL.';
			$json['status'] = true;

			// print_r($output);
			echo "<div class='updated'><p><b>SUCCESS!</b></p><p> ".$call." link: <a target='_blank' href='".$redirect."'>".$redirect."</a></p></div>";

		}else{
	   	$json['output'][0] = 'Unable to publish site. Another publish process is being processed currently. You may try again once the first publish action is completed.';
			$json['status'] = false;
			echo "<div class='error'><p>FAIL</p></div>";
		}
		fclose($fp);
		@chmod($workpath.".lck",0777);
	}

}

new Site_Build();

?>