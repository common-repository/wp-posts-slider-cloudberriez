<?php 
/**
 * If no Wordpress, go home
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage slider post type columns.
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_manage_post_types_column() {

	add_action( "manage_cbz_slider_posts_custom_column", 'cbzwps_shortcode_column_content', 10, 2 );
	add_filter( "manage_cbz_slider_posts_columns", 'cbzwps_add_shortcode_column' );
}
add_action( 'init', 'cbzwps_manage_post_types_column' );

/**
 * Add new column to show shortcode per slider
 * @param  array $columns 
 * @return array
 *
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_add_shortcode_column( $columns ) {
	/**
	 * If custom column not allowed
	 */
	if ( empty( $columns ) ) {
		return $columns;
	}

	$slider_column = apply_filters( 'cbzwps_filter_slider_column', array( 'cbzwps_slider_shortcode' => __( 'CBz Slider Shortcode', CBZWPS_TXTDOMAIN ) ) );

	/**
	 * Add custom column
	 */
    return array_merge( $columns, $slider_column );
}

/**
 * Renders shortocode html
 * @param  string 	$column_name
 * @param  int 		$post_id 
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_shortcode_column_content( $column_name, $post_id ) {
	if ( $column_name != 'cbzwps_slider_shortcode' ) {
		return ;
	}

	echo cbzwps_get_slider_shortcode( $post_id );
}
