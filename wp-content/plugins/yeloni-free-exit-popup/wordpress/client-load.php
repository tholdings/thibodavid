<?php $this->print_initializations();?>
<script type="text/javascript">
	// console.log('inside scripts of client-load.php')
	// console.log(window.yetience)
	var yetience  =<?php $this->print_files_to_load("client","client/loader");?>;

	if(!window.yetience){
		//console.log('assigning window.yetience to yetience')
		window.yetience = yetience;
	}else{
		//console.log('extending window.yetience from yetience')
		//console.log(window.yetience)

		window.yetience.scripts = yetience.scripts;
		window.yetience.styles = yetience.styles;
		window.yetience.server = yetience.server;
		window.yetience.static = yetience.static;
		
		
	}

	
	window.yetience.path = '<?php echo plugins_url()."/".($this->plugin_folder);?>';

	window.yetience.version = '<?php echo ($this->plugin_version);?>';

	window.yetience.all_scripts_loaded = false;
	
</script>

<script type="text/javascript">
	window.autience_post_id =<?php echo Yetience::current_post_id();?>;

	
	var autience_is_single =<?php echo (is_single() ? 'true' : 'false');?>;

	window.autience_is_home =<?php echo (is_home() ? 'true' : 'false');?>;

	var autience_path = "<?php echo plugins_url($this->plugin_folder); ?>";

	window.autience_page_name = "<?php global $post;echo $post->post_name; ?>";

	window.autience_post_type = "<?php echo get_post_type(); ?>";

	window.autience_categories =<?php echo Yetience::getPostCategories(); ?>;

	
	window.autience_listen = function(obj, evt, fn,obj_name) {
        //some browsers support addEventListener, and some use attachEvent
        try{

        	if (obj) {	        	
	            if (obj.addEventListener) {
	                obj.addEventListener(evt, function(e) {
	                    fn(e, evt, obj);
	                }, false);
	            } else if (obj.attachEvent) {
	                obj.attachEvent("on" + evt, function(e) {
	                    //pass event as an additional parameter to the input function
	                    fn(e, evt, obj);
	                })
	            }
	        }        	
        }catch(err){
        	console.log('TRY CATCH error while binding event listener for '+evt+' on '+obj_name);

        	if(obj_name == 'window' && evt == 'hashchange'){
        		console.log('Attaching using window.hashchange');
        		window.hashchange = fn;
        	}
        }
        
    };

    window.autience_setup = "<?php echo $this->get_encoded_setup(); ?>";
</script>
<script type="text/javascript">
	//call the specified function with the callback as input when it is defined on the object
	//if the function is not defined , try after 500ms
	window.yetienceCallWhenDefined = function(obj, fn, cb){
		//console.log('request for '+fn);
		if(obj[fn]){
			//console.log('found and calling '+fn);
			obj[fn](cb)
			
		}else{
			//console.log('could not find '+fn+' waiting for 500ms')
			setTimeout(function(){
				//console.log('Sending another request for '+fn)
				window.yetienceCallWhenDefined(obj, fn, cb)
			},500)
		}
		//console.log('out of else');
	};

	var autience_sequence = ['defineYetience','loadYetience','defineAutience','defineAutienceClose','defineAutienceHow','defineAutienceWhen','defineAutienceWhere','defineAutienceWhom','defineAutienceChat','defineAutienceEmail','defineAutienceRedirect','defineAutienceSocial','defineAutienceBack','defineAutienceActionButton'];

	//call the index function in the sequence of functions defined in the above array
	//once all functions are complete, call the final callback
	window.yetienceCallback = function(index, final_cb){
		return function(){
			//console.log('inside yetienceCallback for index '+index)
			if(index < autience_sequence.length){
				//console.log('Sending request for '+autience_sequence[index])
				window.yetienceCallWhenDefined(window, autience_sequence[index],window.yetienceCallback(index+1,final_cb))
			}else{
				if(final_cb){
					final_cb()
				}
			}
		}
	};

	//Initiate the sequence of function calls.
	//Once it is complete, call runLifeycles 
	/*
	window.yetienceCallWhenDefined(window, autience_sequence[0],window.yetienceCallback(0,function(){
		Autience.executors.runLifecycles()
	}));*/
	window.yetienceCallback(0,function(){
		Autience.executors.runLifecycles()
	})()

</script>
<!-- to check whether user is loggedin or not on client side -->
<script type="text/javascript">
    var is_logged_in = "<?php  echo is_user_logged_in()?true:false; ?>";
</script>
<?php
	/*
	wp_enqueue_script('yetience-client-loader');
	wp_enqueue_script('yetience-client-wordpress');
	*/
	$this->enque_js($this->client_js);
?>