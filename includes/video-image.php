<?php
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}
/* Manipulate the featured image */
add_action( 'genesis_entry_content', 'gfv_video_image', 0 );

function gfv_video_image( $dimensions ) {
	global $post;

	if ( $dimensions ){
		$width 		= $dimensions['width'];
		$height 	= $dimensions['height'];
		$position 	= $dimensions['align'];
	} else {
		$width 		= absint( get_option( 'gfv_thumb_width' ) );
		$height 	= absint( get_option( 'gfv_thumb_height' ) );
		$position 	= esc_attr( get_option( 'gfv_thumb_position' ) );
	}

	$url = get_post_meta( $post->ID, '_gfv_video_url', true );

	if ( '' != $url && 'video' == get_post_format( $post->ID ) ) {
		remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
		remove_action( 'genesis_post_content', 'genesis_do_post_image' );
		if ( has_action( 'gfv_remove_post_image' ) ){
			do_action( 'gfv_remove_post_image' );
		}
		echo
		'<div class="gfv gfv' . $position . '">' .
		  do_shortcode( '[video height="' . $height . '" width="' . $width . '" src="' . $url . '"]' ) .
		'</div>';
	} else {
		// Need to add back featured image in case theme removed it earlier.
		if ( has_action( 'gfv_add_post_image' ) ){
			do_action( 'gfv_add_post_image' );
		}
	}
}

add_action( 'pre_get_posts', 'gfv_hide_video_on_post' ) ;
function gfv_hide_video_on_post( $query ) {
	if( $query->is_main_query()
		&& ( $query->is_singular()
		|| $query->get( 'post_type' ) )
		&& ! $query->is_page()
		&& ! $query->is_attachment()
	) {
		remove_action( 'genesis_entry_content', 'gfv_video_image', 0 );
	}
}
