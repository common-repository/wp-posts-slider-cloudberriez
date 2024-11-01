<?php
/**
 * If no Wordpress, go home
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get prevented the post types.
 * @return array
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_get_prevented_post_types() {
	return $preventedPostTypes = apply_filters( 
		'cbzwps_prevented_post_types', 
		array( 
			'attachment', 
			'revision', 
			'custom_css', 
			'customize_changeset', 
			'product_variation', 
			'shop_order', 
			'shop_order_refund', 
			'shop_webhook',
			'nav_menu_item',
			'cbz_slider'
		)
	);
}

/**
 * Get allowed post types for cbz slider in the plugin.
 * @return array 
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_get_allowed_post_types() {
	$preventedPostTypes = cbzwps_get_prevented_post_types();
	if ( empty( $preventedPostTypes ) ) {
		return ;
	}

	$post_types = apply_filters( 'cbzwps_post_types_for_slider', get_post_types() );

	$allowed_post_types = array_diff( $post_types, $preventedPostTypes );
	if ( empty( $allowed_post_types ) ) {
		return $allowed_post_types;
	}

	$formatted_allowed_post_types = array();
	foreach ( $allowed_post_types as $post_type_slug => $post_type ) {
		if ( empty( $post_type_slug ) or empty( $post_type ) ) {
			continue;
		}

		$post_obj 	= get_post_type_object( $post_type );
		if ( empty( $post_obj ) ) {
			continue;
		}

		$formatted_allowed_post_types[ $post_type ] = $post_obj->labels->singular_name;
	}

	return $formatted_allowed_post_types;
}

/**
 * Get meta query for woocommerce products. 
 * Please don't use this function for other posts' meta queries.
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 * 
 * @param  string $term       
 * @param  string $post_types 
 * @return string Returns meta query for woocommerce products, returns empty string for other post types.
 */
function cbzwps_get_meta_query( $term, $post_types = "product" ) {
	if ( ! cbzwps_is_active_woocommerce() ) {
		return '';
	}

	global $wpdb;
	$meta_query = "";
	$like_term 	= '%' . $wpdb->esc_like( $term ) . '%';
	if ( $post_types == 'product' ) {
		$meta_query = "OR ( postmeta.meta_key = '_sku' AND postmeta.meta_value LIKE {$like_term} )";
	}

	return apply_filters( 'cbzwps_search_meta_query', $meta_query );
}

/**
 * Make searched array useful, by filtering ans sanitizing it.
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 * 
 * @param  array  $posts      Found posts' id
 * @param  string $post_type Traversing post type
 * @return array
 */
function cbzwps_get_searched_post_json_array( $posts, $post_type = "product" ) {
	$found_products = '';

	/**
	 * If the post type is woocommerce's product
	 */
	if ( $post_type == 'product' and cbzwps_is_active_woocommerce() ) {
		if ( ! empty( $posts ) ) {

			/**
			 * Traverse found posts
			 */
			foreach ( $posts as $post ) {

				/**
				 * Get product informations
				 * @var object
				 */
				$product = wc_get_product( $post );

				if ( ! current_user_can( 'read_product', $post ) ) {
					continue;
				}

				if ( ! $product || ( $product->is_type( 'variation' ) && empty( $product->parent ) ) ) {
					continue;
				}

				$found_products[ $post ] = rawurldecode( $product->get_formatted_name() );
			}
		}
	} else {

		/**
		 * If no woocommerce
		 */
		if ( ! empty( $posts ) ) {

			/**
			 * Traverse found posts
			 */
			foreach ( $posts as $post ) {

				/**
				 * Fetch posts' informations
				 * @var object
				 */
				$post_obj = get_post( $post );

				if ( ! $post_obj or empty( $post_obj ) ) {
					continue;
				}

				$found_products[ $post ] = rawurldecode( $post_obj->post_title );
			}
		}
	}

	return $found_products;
}

/**
 * Checks if woocommerce is active
 *
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 * 
 * @return bool return true if woocommerce is installed and active, and false if not active.
 */
function cbzwps_is_active_woocommerce() {
	if ( in_array ( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option ( 'active_plugins' ) ) ) ) {
		return true;
	}

	return false;
}

/**
 * Get all sliders animations effects.
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 * 
 * @return array Returns array of all available slider animation types.
 */
function cbzwps_get_slider_animations() {
	return $animations = apply_filters( 
		'cbzwps_slider_animations', 
		array(
			'off' 				=> __( 'No Animation', CBZWPS_TXTDOMAIN ),
			'fade' 				=> __( 'Fade', CBZWPS_TXTDOMAIN ),
			'fade_in_L' 		=> __( 'Fade in left', CBZWPS_TXTDOMAIN ),
			'fade_in_R' 		=> __( 'Fade in right', CBZWPS_TXTDOMAIN ),
			'fade_in_corners' 	=> __( 'Fade in corners', CBZWPS_TXTDOMAIN ),
			'rotate_hdbl_in' 	=> __( 'Fade H Double in', CBZWPS_TXTDOMAIN ),
			'doors' 			=> __( 'Doors', CBZWPS_TXTDOMAIN ),
			'extrud_in_strip' 	=> __( 'Extrud in strip', CBZWPS_TXTDOMAIN ),
			'jump_in_straight' 	=> __( 'Jump in straight', CBZWPS_TXTDOMAIN ),
			'bounce_right' 		=> __( 'Bounce Right', CBZWPS_TXTDOMAIN ),
			'bounce_down' 		=> __( 'Bounce Down', CBZWPS_TXTDOMAIN ),
		)
	);
}
