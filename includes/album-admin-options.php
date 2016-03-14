<?php
	Class MultiImageMetaBoxAdmin {

		public $general_data;

		function __construct() {
			$this->general_data = get_option( 'multi_image_meta' );

			add_action( 'admin_menu', array($this, 'multi_image_add_menu_page') );
			add_action( 'admin_init', array($this, 'multi_image_register_settings') );
		}

		public function multi_image_add_menu_page() {
			global $pagenow;
			// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );
			add_options_page( 
				'Multi Image Meta', 
				'Multi Image Meta', 
				'administrator', 
				'multi-image-meta', 
				array($this, 'display_multi_image_options')
			);
			
			if( $pagenow == 'options-general.php' ) {
				wp_enqueue_style( 
					'multi-image-admin', 
					plugins_url( '/style.css', __FILE__ )
				);
			}
		}

		public function display_multi_image_options() {
			?>
				<div class="wrap">
					<form method="post" action="options.php"> 
						<?php settings_fields( 'multi_image_meta' ); ?>
						<?php do_settings_sections('multi-image-meta'); ?>
						<?php submit_button(); ?>
				    </form>
				</div>
			<?php
		}

		public function multi_image_register_settings() {
			// register settings it take the id where it will be saved in database
			register_setting( 
				'multi_image_meta', 
				'multi_image_meta' 
			);
			// register sections for post types 
			add_settings_section( 
				'multi-image-custom-post-types', 
				'Multi Image Metabox General Settings', 
				array($this, 'display_custom_post_types'), 
				'multi-image-meta' 
			);
		}

		public function display_custom_post_types() {

			$general_data = $this->general_data;

			add_settings_field( 
				'custom-post-types-multi', 
				'Custom Post Types', 
				function() use ( $general_data ) {
					$post_types = get_post_types( array( 'public' => true ), 'names' ); 
					?>
                        <ul class="multi-image-list">
                            <?php
                                foreach ($post_types as $post_type) {
                                	$class = ( isset($general_data['post-types']) && in_array($post_type, $general_data['post-types']) ) ? 'checked' : '';

                                    echo '<li>';
                                        echo '<label><input name="multi_image_meta[post-types]['.$post_type.']" type="checkbox" value="'.$post_type.'" '.$class.'>'.$post_type.'</label>';
                                    echo '</li>';
                                }
                            ?>
                        </ul>
                        <p class="description">Activate Multi Image metabox for custom post type.</p>
					<?php
				},
				'multi-image-meta', 
				'multi-image-custom-post-types'
			);

			add_settings_field( 
				'images-number-multi', 
				'Number Of Multi Images', 
				function() use( $general_data ) {
					?>
						<input 
							type 		= "number"
							class 		= ""
							name 		= "multi_image_meta[img-num]"
							id 			= "multi_image_meta[img-num]"
							value 		= "<?php echo ( isset( $general_data['img-num'] )  && !empty($general_data['img-num']) ) ? $general_data['img-num'] : '1'; ?>" 
							onkeypress 	= 'return event.charCode >= 48 && event.charCode <= 57'
							min 		= "1"
						>
					<?php
				}, 
				'multi-image-meta', 
				'multi-image-custom-post-types'
			);
		}
	}
