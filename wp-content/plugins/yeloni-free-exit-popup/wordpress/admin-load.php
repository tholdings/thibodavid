<!-- this file is called when the admin side of yeloni is loaded -->

<script type="text/javascript">
var yetience  =<?php $this->print_files_to_load("admin","admin-interface/loader");?>;

window.yetience = yetience;


	//finding wordpress version
	yetience.wordpress_version = "<?php bloginfo('version'); ?>";
	

	yetience.path = '<?php echo plugins_url()."/".($this->plugin_folder);?>';

	yetience.adminPath = '<?php echo plugins_url()."/".($this->plugin_folder)."/admin-interface";?>';
	</script>

	<script type="text/javascript">
	yetience.pageList =<?php echo Yetience::getAllPages() ?>;
	</script>
	<script type="text/javascript">
	yetience.categories =<?php echo Yetience::getCategories(); ?>;
	
	</script>
	<script type="text/javascript">
	yetience.product= '<?php echo $this->product_label ?>';
	yetience.title = '<?php echo $this->plugin_title ?>';
	yetience.version = '<?php echo $this->plugin_version ?>';

	</script><?php
	//$this->initialize_id();//initializ_id commented because php makes two post calls instead of one
	$this->enque_js($this->admin_js);
	wp_enqueue_style('yetience-loader-wordpress');
	?>


	<div class="yetience-container"><?php
		include "admin-interface/src/platform_index.html";
		?><!-- below part contains a hidden textbox and a submit button
		 which loads the setup data into the wordpress settings textbox
		-->
		<div class="row">
			<div class="yel-last-screen col-md-8 col-md-offset-2">

				<form method="post" action="options.php"><?php
					settings_fields( 'yetience-'.($this->product_label).'-options' );
					do_settings_sections( 'yetience-'.($this->product_label).'-options' );
					?><table class="form-table" style="display:none">
						<tr valign="top">   
							<td>
								<input id="yetience_setup" type="input" name="yetience_<?php echo ($this->product_snake)?>_setup" value="<?php echo $this->get_encoded_setup(); ?>"?>
							</td>
							
						</tr>
					</table>
					

					<div id="yetience_submit_button" class="" style="display:none">
						

						<div>
							<center>
								<div id="autience-save-message"></div><?php submit_button();?><span id="autience-undo-message" style="display:none">To undo your changes, Refresh the Browser.</span>
							</center>
							
						</div>
					</div>
				</form>

			</div>
			
		</div>
	</div>
	<script  type="text/javascript">
	yetience.encoded_setup = document.getElementById('yetience_setup').value;

	window.yetienceCallWhenDefined = function(obj, fn, cb){
		if(obj[fn]){
			obj[fn](cb)
		}else{
			setTimeout(function(){
				window.yetienceCallWhenDefined(obj, fn, cb)
			},500)
		}
	}

	window.yetienceCallWhenDefined(window, 'defineYetience', function(){
		console.log('in callback of defineYetience')

		window.yetienceCallWhenDefined(window, 'defineAdminYetience', function(){
			console.log('in callback of defineAdminYetience')
		})
	})

	</script>

