<?php
class Yetience
{
	private $product_label,$plugin_title,$plugin_folder,$product_snake;
	private $deployment,$admin_js, $client_js;

	function __construct($product_label,$plugin_title,$plugin_folder,$include_path,$deployment,$yetience_version){


		set_include_path($include_path);

		include "yetience_config.php";
		

		$this->product_label = $product_label;
		$this->product_snake = str_replace("-","_",$product_label);
		$this->plugin_title = $plugin_title;
		$this->plugin_folder = $plugin_folder;
		$this->deployment = $deployment;
		$this->plugin_version = $yetience_version;
		$this->admin_js = $this->get_asset_json("admin","admin-interface/loader")->directjs;
		$this->client_js = $this->get_asset_json("client","client/loader")->directjs;

		//this hook is called when wordpress initializes its dashboard menu
		add_action( 'admin_menu', array($this, 'add_to_settings_menu') );

		//this hook is called after a published page is rendered
		add_action( 'wp_footer', array($this, 'add_client_page') );


		//add_action( 'the_content', array($this, 'add_before_content') );
		
	}

	function add_before_content($content){
		$custom_content = '<img src="http://placehold.it/350x150">';
		$custom_content = $content.$custom_content;
		return $custom_content;
	}


	function initialize_id(){
		//get setup and check if it has website id
		//print_r("in initialize_id<br>");
		//print_r("inside initialize_id");
		$setup = $this->get_decoded_setup();
		if($setup && property_exists($setup, "id")){
			//id exists
			//print_r("id exists- ".($setup["id"]));
			//print_r($setup);
		}else{
			//there is no id, call the API and generate a new id
			//print_r("id does not exist<br>");

			$admin_config = $this->get_admin_config();
			$server = $admin_config->server;
			//print_r("server-> ".$server);

			$website_creation_url = $server.("/api/Websites/new_website");
			//print_r("website_creation_url- ".($website_creation_url));

			$website_data = array('platform' => 'wordpress', 'initial_domain' => (get_option("siteurl")),'product_id' => ($this->product_label),'createdVersion' => ($this->plugin_version),'source'=>'server');
			$website_request = array('website' => $website_data);
			//print_r("website creation url- ".$website_creation_url."<br>");
			//print_r($website_data);
			
			//print_r("website_data");
			//print_r($website_data);

			$response_body = Yetience::httpPost($website_creation_url,$website_request);

			if(property_exists($response_body, 'website')){
				$website_response = $response_body->website;

				//print_r("website_response");
				//print_r($website_response);

				if($website_response){
					$website = json_decode($website_response);
					//print_r($website);
					if(property_exists($website,"id")){
						//print_r("Website ID- ".$website->id);
						$website->saved = true;
						$website->widgets = array();
						$this->save_setup($website);
						//print_r("saved website");
					}else{
						//print_r("website does not have id");
						//print_r($website);
					}
				}else{
					//unable to create a website record, just continue
				}
			}
			
			
			
			
		}

	}

	function add_to_settings_menu(){
 		//add_options_page($this->plugin_title+' Settings', $this->plugin_title, 'manage_options', 'yetience-'.($this->product_label),  array($this, 'add_admin_page'));

 		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

		add_menu_page(
			$this->plugin_title,
			$this->plugin_title,
			'manage_options',
			'yetience-'.($this->product_label),
			array($this, "add_admin_page"),
			plugins_url(($this->plugin_folder).'/admin-interface/images/ypp_icon.jpg' )
			);

 		//this hook is called before the admin page is initialized. 
 		//We want to initialize the options that are used for saving data
		add_action( 'admin_init', array($this, 'register_settings') );
	}

	function add_admin_page(){
 		//register and enque admin scripts
 		//include admin php files
 		/*
 		wp_register_script('yetience-admin-wordpress',plugins_url(($this->plugin_folder).'/wordpress/admin-wordpress.js'));
 		wp_register_script('yetience-admin-loader',plugins_url(($this->plugin_folder).'/common/loader.js'));
		*/
		//register the directjs files listed in the admin_*.json

 		$this->register_js($this->admin_js);	
 		wp_register_style('yetience-loader-wordpress',plugins_url(($this->plugin_folder).'/common/loader.css'));
 		include "admin-load.php";
 	}

 	

 	function add_client_page(){
 		//register and enque client scripts
 		$setup = $this->get_decoded_setup();
 		//json_decode(urldecode(base64_decode($this->get_encoded_setup())),true);
 		
 		if(property_exists($setup, 'widgets') && count($setup->widgets) > 0){
 			/*
 			wp_register_script('yetience-client-wordpress',plugins_url(($this->plugin_folder).'/wordpress/client-wordpress.js'));
 			wp_register_script('yetience-client-loader',plugins_url(($this->plugin_folder).'/common/loader.js'));
			*/
 			$this->register_js($this->client_js);
 			include "client-load.php";
 		}
 		
 	}

 	function enque_js($js_list){
 		foreach ($js_list as $js_file) {
 			wp_enqueue_script($js_file);
 		}
 	}

 	function register_js($js_list){
 		foreach ($js_list as $js_file) {
 			wp_register_script($js_file, plugins_url(($this->plugin_folder)).$js_file.".js");
 		}
 	}

 	function register_settings(){

 		register_setting( 'yetience-'.($this->product_label).'-options', 'yetience_'.($this->product_snake).'_setup' );
 	}

 	function get_encoded_setup(){
 		$option_key = "yetience_".($this->product_snake)."_setup";

 		return get_option($option_key);
 	}

 	function get_asset_json($side,$path){
 		//side is admin or client
 		//deployment is dev, minified or production
 		$file_path = $path.("/").$side."_".($this->deployment).".json";
 		$asset_json = json_decode(file_get_contents($file_path, true));

 		return $asset_json;
 	}

 	function print_files_to_load($tag, $path){
 		$file_path = $path.("/").$tag."_".($this->deployment).".json"; 		
 		$files_to_load = readfile($file_path,1);
 		//echo $files_to_load;
 	}

 	function get_admin_config(){
 		$file_path = ("admin-interface/loader/admin_").($this->deployment).".json";
 		return json_decode(file_get_contents($file_path, true));
 	}

 	function get_decoded_setup(){ 		
 		$encoded_setup = $this->get_encoded_setup();
 		//print_r("encoded_setup<br>");
 		//print_r($encoded_setup);
 		$base64_decoded = base64_decode($encoded_setup);
 		//print_r("base64_decoded<br>");
 		//print_r($base64_decoded);
 		$url_decoded = urldecode($base64_decoded);
 		//print_r("url_decoded<br>");
 		//print_r($url_decoded);
 		$json_decoded = json_decode($url_decoded);
 		//print_r("json_decoded<br>");
 		//print_r($json_decoded);
 		return $json_decoded;
 	}

 	function save_setup($setup){
 		update_option("yetience_".($this->product_snake)."_setup",base64_encode(urlencode(json_encode($setup)) ));
 	}

 	function print_initializations(){

 		$setup = $this->get_decoded_setup();

 		//Iterate through setup.widgets
 		//check if the widget is to be shown on this page
 		//if it is, then print all the initializations corresponding to the widget
 		

 		if($setup){
 			$widgets = $setup->widgets;
 			$widget_count = count($widgets);
 			foreach($widgets as $widget){
 				$initialization_exists = false;
 				//check if there is any initialization corresponding to the widget and print it
 				if($this->is_shown_on_current_page($widget))
 				{

 					$initialization = $widget->initialization;
 					if($initialization){
 						foreach ($initialization as $tag => $text) {
 							echo $text;
 							$initialization_exists = true;
 						}
 					}
 					if($initialization_exists){
	 						//display the widget content in a div
 						echo "<div id='".($widget->code)."' class='yel-popup-main-wrapper' style='visibility:hidden;display:none'>".(do_shortcode(urldecode(base64_decode($widget->rendered)),false))."</div>";
 						}//if widget is shown on the current page

 						else
 						{
		 					//echo "-Not Shown on this page";
 						}
 					}
 				}
 			}
 		}

 		function is_shown_on_current_page($widget){
 			global $post;

 			$where = $widget->configuration->where;

 			if (is_home()) {
 				return $where->home;
 			}
 			//ISSUE_HERE
 			if (isset($post) && is_object($post) && $post->post_name == 'checkout') {
 				return $where->checkout;
 			}

 			switch ($where->other) {
 				case 'all':
 				return true;
 				case 'none':
 				return false;
 				case 'specific':
 				switch ($where->specific->selector) {
 					case 'pageType':
 					switch (get_post_type()) {
 						case 'post':
 						return $where->pageTypes->posts;
 						case 'product':
 						return $where->pageTypes->products;
 						case 'page':
 						return $where->pageTypes->pages;
 					}
 					break;
 					case 'category':
 					$where_categories = $widget->configuration->where_categories;
 					foreach($this->getPostCategories() as $category){
 						$cat = $category->cat_ID;
 						$cat_string = strval($cat);

 						if(in_array($cat, $where_categories) || in_array($cat_string, $where_categories)){
 							return true;
 						}
 					}

 					return false;
 					break;
 					case 'title':
 					$where_titles = $widget->configuration->where_titles;
 					return in_array($this->current_post_id(), $where_titles);                    
 					break;
 				}

 			}

 			return true;
 		}


 		public static function httpPost($url, $data)
 		{
		//print_r("Initializing curl ");
 			if(function_exists("curl_init")){
 				$curl = curl_init($url);
		    //print_r("Created curl");

 				curl_setopt($curl, CURLOPT_POST, true);
 				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
 				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		    //print_r("Executing curl");
 				$response = curl_exec($curl);

		    //print_r("Executed curl");

 				curl_close($curl);
		    //print_r("Close curl");
 				return $response;
 			}else{
 				print_r("curl_init is not defined");
 			}

 		}


 	//STATIC FUNCTIONS BELOW
 		public static function getAllPages(){

 			$post_types = array('post','page','product');
 			$post_type_count = count($post_types);

 			$pages_array = array();
 		//echo "post type count- ".$post_type_count;

 			for($i = 0;$i<$post_type_count;$i++){
 				$pages_of_this_type = get_posts(array('numberposts'=> -1,'post_type' => $post_types[$i]));
 				$page_count = count($pages_of_this_type);
 			//echo "pages of type ".$post_types[$i]." ".$page_count."\n"; 			
 			//print_r($pages_of_this_type);

 				for($j=0; $j<$page_count;$j++){
 					$page = (object) array('ID' => ($pages_of_this_type[$j]->ID),'post_title' => ($pages_of_this_type[$j]->post_title),'post_name' => ($pages_of_this_type[$j]->post_name));
 					array_push($pages_array, $page);
 				}

 			}

 			return json_encode($pages_array);
 		}   


 		public static function getCategories(){

 			return Yetience::beautify_categories(get_categories());
 		}

 		public static function getPostCategories(){

 			return Yetience::beautify_categories(get_the_category());
 		}

 		private static function beautify_categories($categories){
 		//function for returning only cat_ID and name instead of all the attributes
 			$cat_array = array();
 			foreach ($categories as $value) {
	 		# code...
 				array_push($cat_array, (object) array("cat_ID"=> ($value->cat_ID), "name" => ($value->name)));
 			}
 			return json_encode($cat_array);
 		}

 		public static function current_post_id(){

 			$post_id = get_the_ID();

 			if(!is_numeric($post_id)){
 				if(isset($wp_query) && is_object($wp_query)){
 					$post_id = $wp_query->post->ID;  //ISSUE HERE
 				}
 			}

 			if(!is_numeric($post_id)){
 				$post_id= 0;	
 			}

 			return $post_id;
 		}

 		public static function console_log($message)
 		{
 			echo "<script type='text/javascript'>console.log('".$message."')</script>";
 		}
 	}?>