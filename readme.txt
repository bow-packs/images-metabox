=== Images Metabox ===
Contributors: shreif ashraf, Michael Behr
Tags: images, metabox, multiple, pictures, multiple post thumbnail, thumbnail, gallery
Requires at least: 3.0
Stable tag: trunk
License: GPLv2 or later

Add a multi-image metabox to your posts, pages and custom post types


== Description ==

This plugin add a metabox which allox to upload and link multiple images to one post.

== Installation ==

1. Upload the Images Metabox plugin to your blog and Activate it.
2. Adjust under Settings Multi Images Metabox
3. Use in your template :


get_album_images_count( $post_id = null, $feature_img = false )

get_album_images_array( $post_id = null, $thumbnail = false, $feature_img = false )