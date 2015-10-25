<?php
/*
	Plugin Name: Multi Image Metabox
	Description: Add a multi-image metabox to your posts, pages and custom post types
	Version:1.3.5
	Author: Shreif Ashraf
*/

include 'multi-image-meta-admin.php';

Class MultiImageMeta extends MultiImageMetaBoxAdmin {

	public $options;

	public function __construct() {
		parent::__construct();

		$this->options = get_option( 'multi_image_meta' );

		add_action( 'admin_enqueue_scripts', array($this, 'multi_enqueue_scripts') ); // enqueue scripts
		add_action(	'add_meta_boxes', array($this, 'add_image_metabox') ); // add meta box
		add_action( 'save_post', array($this, 'save_image_metabox') ); // save meta box
	}

	public function multi_enqueue_scripts() {
		global $pagenow;
		$current_post_type = get_current_screen();
		$current_post_type = $current_post_type->post_type;

		$post_types  = $this->options['post-types'];
		// if ( ( $pagenow === 'post.php' || $pagenow === 'post-new.php' ) && in_array($current_post_type, $post_types) ) {
		// 	wp_enqueue_style('thickbox');

		// 	wp_enqueue_media();
		// 	wp_enqueue_script( 
		// 		'img-mb', 
		// 		plugins_url('/js/get-images.js', __FILE__), 
		// 		array( 'jquery','media-upload','thickbox','set-post-thumbnail' ) 
		// 	);
		// }
	}

	public function add_image_metabox() {
		$multi_pts 		= $this->options['post-types'];
		if( !empty($multi_pts) ) {
			foreach($multi_pts as $k => $post_type ) {
				add_meta_box( 
					'multiimages', 
					__('Add Photos'), 
					array($this, 'multi_image'), 
					$post_type, 
					'normal', 
					'core'
				);
			}
		}
	}

	public function multi_image($post) {
		$img_nums	= ( !empty($this->options['img-num']) ) ? (int)$this->options['img-num'] : 1;

		// get meta values
		$meta 	= get_post_meta($post->ID, 'multi_images', true);

		wp_nonce_field( 'multi-image-save_'.$post->ID, 'multi-image-save' );

		echo '<div id="droppable">';
		for ($i=0; $i < $img_nums; $i++) {

			$img 		= ( !empty($meta[$i]) ) ? wp_get_attachment_image_src( $meta[$i], 'thumbnail' )[0]: '';
			$meta[$i] 	= ( !isset($meta[$i]) || empty($meta[$i]) ) ? '' : $meta[$i];

			if( !empty($meta[$i]) ) {
				$add_class 	= 'hidden';
				$rm_class 	= '';
			} else {
				$add_class 	= '';
				$rm_class 	= 'hidden';
			}

			echo <<<END
			<div class="image-entry" draggable="true">
				<input type="hidden" name="multi_images[{$i}]" id="multi_images[{$i}]" class="id_img" data-num="{$i}" value="{$meta[$i]}">
				<p>
					<div class="img-preview" data-num="multi_images[{$i}]">
						<img src="{$img}" width="100" height="100" alt="" draggable="false">
					</div>
				</p>

				<a href="javascript:void(0);" class="get-image button-secondary {$add_class}" data-num="multi_images[{$i}]">إضافه جديد</a>
				<a href="javascript:void(0);" class="del-image button-secondary {$rm_class}" data-num="multi_images[{$i}]">مسح</a>
			</div>		
END;
		}
		echo '</div>';
		echo '<div class="clearfix"></div>';
	}

	public function save_image_metabox($post_id) {
		
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
	        return $post_id;

		$post_types = $this->options['post-types'];
	    $post_type 	= get_post_type( $post_id );
	    if( in_array($post_type, $post_types) ) {
		    if( isset( $_POST['multi_images'] ) ) {
				check_admin_referer( 'multi-image-save_'.$post_id, 'multi-image-save' );
				update_post_meta( $post_id, 'multi_images', $_POST['multi_images']); 
		    }
	    }
	}

	public static function get_image_count( $post_id = null ) {
		if( $post_id == null )
			$post_id = get_the_id();

		$multi_images = get_post_meta( $post_id, 'multi_images', true );
		return count(array_filter($multi_images));
	}

	public static function get_image_array( $post_id = null, $thumbnail = false ) {
		if( $post_id == null )
			$post_id = get_the_id();

		$image_meta = get_post_meta( $post_id, 'multi_images', true );
		$image_array = array(); // initialize returning value
		if( !empty($image_meta) ) {
			$image_meta = array_filter($image_meta); // clear empty fields

			if( $thumbnail ) {
				foreach ($image_meta as $key => $img_id) {
					$image_array[] = wp_get_attachment_image_src( $img_id, 'thumbnail' )[0];
				}
			} else {
				foreach ($image_meta as $key => $img_id) {
					$image_array[] = wp_get_attachment_image_src( $img_id, 'full' )[0];
				}
			}
		}

		return $image_array;
	}
}

new MultiImageMeta;