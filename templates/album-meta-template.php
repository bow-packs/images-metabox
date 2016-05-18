<?php wp_nonce_field( 'multi-image-save_'.$post->ID, 'multi-image-save' ); ?>

<div id="droppable">
<?php  
	for ($i=0; $i < $img_nums; $i++) {
		$img 				= ( !empty($meta[$i]) ) ? wp_get_attachment_image_src( $meta[$i], 'thumbnail' )[0]: '';
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

			<a href="javascript:void(0);" class="get-image button-secondary {$add_class}" data-num="multi_images[{$i}]">Add Image</a>
			<a href="javascript:void(0);" class="del-image button-secondary {$rm_class}" data-num="multi_images[{$i}]">Remove Image</a>
		</div>		
END;
	}
?>
</div>
<div class="clearfix"></div>