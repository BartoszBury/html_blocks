<?php
/*
Plugin Name: HTML Bloks with VC 
Description: Make shortcodes. Compatible with Visual Composer.
Author: Bartosz Bury
Version: 0.1
*/

function bb_html_blocks() {
	register_post_type( 'bb_html_blocks',
		array(
			'labels'              => array(
				'name'          => __( 'HTML Bloki' ),
				'singular_name' => __( 'HTML Bloki' ),
			),
			'public'              => true,
			'publicly_queryable'  => true,
			'show_in_menu'        => true,
			'show_ui'             => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => false,
			'rewrite'             => false,
			'menu_position'       => 102,
			'menu_icon'           => 'dashicons-schedule',
		)

	);
}

add_action( 'init', 'bb_html_blocks' );


add_filter( 'manage_edit-holis_html_blocks_columns', 'my_edit_holis_html_blocks_columns' );

function my_edit_holis_html_blocks_columns( $columns ) {

	$columns = array(
		'cb'        => '<input type="checkbox" />',
		'title'     => __( 'HTML Bloki' ),
		'shortcode' => __( 'ShortCode' ),
	);

	return $columns;
}

add_action( 'manage_holis_html_blocks_posts_custom_column', 'my_manage_holis_html_blocks_columns', 10, 2 );

function my_manage_holis_html_blocks_columns( $column, $post_id) {
	switch ($column){
		case 'shortcode':
			echo '<b>';
			echo '[html_block id="' . $post_id . '"]';
			echo '</b>';
	}

}

function bb_html_block_shortcode( $atts ) {
	$a  = shortcode_atts( array(
		'id' => 0
	), $atts );
	$id = $a["id"];

	$content_id = get_post_field( 'post_content', $id );

	$content = do_shortcode( $content_id );

	$shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
	if ( ! empty( $shortcodes_custom_css ) ) {
		$content .= '<style type="text/css" data-type="vc_shortcodes-custom-css">';
		$content .= $shortcodes_custom_css;
		$content .= '</style>';
	}

	return $content;
}

add_shortcode( 'html_block', 'bb_block_shortcode' );