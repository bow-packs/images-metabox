<?php 
	/**
	* add meta box for Ablum 
	*/
	class AlbumMetaBox {
		
		public $options;
		public $dir;

		function __construct($dir) 
		{
			$this->dir 			= $dir;
			$this->options 	= get_option( 'multi_image_meta' );

			add_action( 'admin_enqueue_scripts', array($this, 'multi_enqueue_scripts') );
			add_action( 'add_meta_boxes', array($this, 'add_meta_box') );
			add_action( 'save_post', array($this, 'save_post') );
		}

		public function multi_enqueue_scripts() 
		{
			global $pagenow;
			//$current_screen = get_current_screen();
			$current_post_type = get_post_type(); //$current_screen->post_type;

			$post_types  = $this->options['post-types'];
			if ( ( $pagenow === 'post.php' || $pagenow === 'post-new.php' ) && in_array($current_post_type, $post_types) ) {
				wp_enqueue_style('thickbox');

				wp_enqueue_media();
				wp_enqueue_script( 
					'img-mb', 
					'/wp-content/plugins/images-metabox/assets/js/get-images.js',
					array( 'jquery','media-upload','thickbox','set-post-thumbnail' ) 
				);
				wp_enqueue_style( 
					'alb-style', 
					'/wp-content/plugins/images-metabox/assets/css/style.css',
					array()
				);
			}
		}

		public function add_meta_box() 
		{
			$multi_pts 		= $this->options['post-types'];

			if( !empty($multi_pts) ) {
				foreach($multi_pts as $k => $post_type ) {
					add_meta_box( 
						'_multiimages',
						'Add Images',
						//'صور الألبوم',
						array($this, 'multi_image_template'), 
						$post_type, 
						'normal', 
						'core'
					);
				}
			}
		}

		public function multi_image_template($post)
		{
			$img_nums	= ( !empty($this->options['img-num']) ) ? (int)$this->options['img-num'] : 1; // Get images number
			$meta 		= get_post_meta($post->ID, '_multiimages', true); // get meta values
			include $this->dir . 'templates/album-meta-template.php' ;
		}

		public function save_post($post_id) 
		{
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
      	return $post_id;

			$post_types = $this->options['post-types'];
	    $post_type 	= get_post_type( $post_id );
	    if( in_array($post_type, $post_types) ) {
		    if( isset( $_POST['multi_images'] ) ) {
					// check_admin_referer( 'multi-image-save_'.$post_id, 'multi-image-save' );
					update_post_meta( $post_id, '_multiimages', $_POST['multi_images']); 
		    }
	    }
	}
}