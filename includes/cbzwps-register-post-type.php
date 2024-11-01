<?php 
/**
 * If no Wordpress, go home
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers CBz Slider post type.
 * Attched to `init` hook for registering post type.
 *
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_register_post_type() {
	/**
	 * Register `CBz Sliders` post type
	 * @var array
	 */
	$slidersArgs = apply_filters( 'cbzwps_register_post_type_cbz_slider',
		array(
			'labels'             => array(
				'name'               => _x( 'CBz Sliders', 'CBz Sliders', CBZWPS_TXTDOMAIN ),
				'singular_name'      => _x( 'CBz Slider', 'CBz Slider', CBZWPS_TXTDOMAIN ),
				'menu_name'          => _x( 'CBz Post Slider', 'CBz Slider', CBZWPS_TXTDOMAIN ),
				'name_admin_bar'     => _x( 'CBz Slider', 'add new on admin bar', CBZWPS_TXTDOMAIN ),
				'add_new'            => _x( 'Add New CBz Slider', 'CBz Slider', CBZWPS_TXTDOMAIN ),
				'add_new_item'       => __( 'Add New CBz Slider', CBZWPS_TXTDOMAIN ),
				'new_item'           => __( 'New CBz Slider', CBZWPS_TXTDOMAIN ),
				'edit_item'          => __( 'Edit CBz Slider', CBZWPS_TXTDOMAIN ),
				'view_item'          => __( 'View CBz Slider', CBZWPS_TXTDOMAIN ),
				'all_items'          => __( 'All CBz Sliders', CBZWPS_TXTDOMAIN ),
				'search_items'       => __( 'Search CBz Sliders', CBZWPS_TXTDOMAIN ),
				'parent_item_colon'  => __( 'Parent CBz Sliders:', CBZWPS_TXTDOMAIN ),
				'not_found'          => __( 'No cbz sliders found.', CBZWPS_TXTDOMAIN ),
				'not_found_in_trash' => __( 'No cbz sliders found in Trash.', CBZWPS_TXTDOMAIN ),
				'set_featured_image' => __( 'Set cbz slider image', CBZWPS_TXTDOMAIN )
			),
			'description'        	=> __( 'This is where you can add new CBz Sliders to your site.', CBZWPS_TXTDOMAIN ),
			'public'             	=> false,
			'publicly_queryable' 	=> true,
			'show_ui'            	=> true,
			'show_in_menu'       	=> true,
			'exclude_from_search'  	=> true,
			'show_in_nav_menus' 	=> false,
			'menu_icon'       	 	=> 'dashicons-slides',
			'query_var'          	=> true,
			'rewrite'            	=> false,
			'capability_type'    	=> 'post',
			'has_archive'        	=> false,
			'hierarchical'       	=> false,
			'menu_position'      	=> null,
			'supports'           	=> array( 'title' ),
			'register_meta_box_cb'	=> 'cbzwps_slider_metaboxes'
		)
	);
	register_post_type( 'cbz_slider', $slidersArgs );
}
add_action( 'init', 'cbzwps_register_post_type', 10 );

/**
 * Slider metaboxes definition invoking. 
 * Attched to `register_post_type` function as arguments.
 * 
 * @param  object $post 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_slider_metaboxes( $post ) {
	add_meta_box( 'cbzwps-slider-settings', __( 'CBz Slider Settings', CBZWPS_TXTDOMAIN ), 'cbzwps_render_slider_settings' );
	add_meta_box( 'cbzwps-shortcode', __( 'CBz Slider Shortcode', CBZWPS_TXTDOMAIN ), 'cbzwps_render_shortcode', '', 'side' );
}

/**
 * Call for rendering slider settings at slider edit page. 
 * Function attached to add_meta_box fucntion invoking.
 * 
 * @param  	object $post 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_render_slider_settings( $post ) {
	if ( empty( $post ) ) {
		return;
	}

	/**
	 * Does action for `cbzwps_render_settings` hook.
	 *
	 * @param object $post
	 */
	do_action( 'cbzwps_render_settings', $post );
}

/**
 * Call for rendering shortcode at slider edit page. 
 * Function attached to add_meta_box fucntion invoking.
 * 
 * @param  object $post 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_render_shortcode( $post ) {
	if ( empty( $post ) ) {
		return;
	}

	$post_id 			= $post->ID;
	$slider_settings 	= get_post_meta( $post_id, 'cbzwps_slider_settings', true );
	if ( ! $slider_settings or empty( $slider_settings ) ) {
		return ;
	}

	/**
	 * Does action for `cbzwps_display_slider_shortcode` hook.
	 *
	 * @param int $post_id 
	 */
	do_action( 'cbzwps_display_slider_shortcode', $post_id );
}

/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_save_slider_settings( $post_id, $post, $update ) {

    /**
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $post_type = get_post_type( $post_id );

    // If this isn't a 'cbz_slider' post, don't update it.
    if ( "cbz_slider" != $post_type ) 
    	return;

	do_action( 'cbzwps_handle_slider_settings', $_POST, $post_id );

}
add_action( 'save_post', 'cbzwps_save_slider_settings', 10, 3 );

/**
 * Handles saving process of cbz slider settings.
 * 
 * @param  array 	$post_data The POsted data through form.
 * @param  int 		$post_id  
 *  
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_handle_slider_settings( $post_data, $post_id ) {
	if ( empty( $post_data ) ) {
		return;
	}

	$slider_setting 		= array();

	/**
	 * Fetches the setting tabs to show in post edit page.
	 * @var array
	 */
	$slider_setting_tabs 	= cbzwps_get_setting_tabs();

	if ( empty( $slider_setting_tabs ) ) {
		return ;
	}

	foreach ( $slider_setting_tabs as $tab => $tab_name ) {
		if ( empty( $tab ) ) {
			continue;
		}

		$slider_setting_contents 	= cbzwps_get_tab_content( $tab );
		if ( empty( $slider_setting_contents ) ) {
			continue;
		}

		foreach ( $slider_setting_contents as $name => $setting ) {
			if ( empty( $setting ) or empty( $name ) ) {
				continue;
			}

			$slider_setting[ $name ] = ( isset( $post_data[ $name ] ) and ! empty( $post_data[ $name ] ) ) ? $post_data[ $name ] : $setting[ 'default' ];
		}
	}

	/**
	 * Applies filter to extract the settings array by developers.
	 * 
	 * @var array
	 * @category function
 	 * @author CloudBerriez <support@cloudberriez.com>
	 */
	$slider_setting = apply_filters( 'cbzwps_slider_post_data', $slider_setting, $post_data, $post_id );

	/**
	 * Updating settings to db.
	 * 
	 * @var bool
	 * @category function
 	 * @author CloudBerriez <support@cloudberriez.com>
	 */
	$updated 		= update_post_meta( $post_id, 'cbzwps_slider_settings', $slider_setting );
}
add_action( 'cbzwps_handle_slider_settings', 'cbzwps_handle_slider_settings', 10, 2 );


/**
 * Renders the shortcode at required place.
 * 
 * @param  int  	$post_id 
 * @param  boolean 	$tooltip 
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_display_slider_shortcode( $post_id, $tooltip = true ) {
	if ( ! $post_id or empty( $post_id ) ) {
		return ;
	}
	
	?>
	<div class="cbzwps_post_edit_shortcode_wrap">
		<div class="cbzwps_row">
			<div class="cbzwps_left_col">
				<label for=""></label>
			</div>
			<div class="cbzwps_right_col">
				<?php 
				if ( $tooltip ) {
					$tip = __( 'Use this shortcode anywhere you need, to render the slider.', CBZWPS_TXTDOMAIN );
					cbzwps_render_tooltip( $tip );
				}
				?>
				<span><?php echo cbzwps_get_slider_shortcode( $post_id );?></span>
			</div>
		</div>
	</div>
<?php 
}
add_action( 'cbzwps_display_slider_shortcode', 'cbzwps_display_slider_shortcode', 10, 2 );

/**
 * Provides the shortcode for given post id.
 * 
 * @param  int 		$post_id 
 * @return string   Returns shortcode when post id is given.
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_get_slider_shortcode( $post_id ) {
	if ( ! $post_id or empty( $post_id ) ) {
		return;
	}

	return '[cbz_slider id="'. esc_attr( $post_id ) .'"]';
}