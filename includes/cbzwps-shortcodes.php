<?php 
/**
 * If no Wordpress, go home
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders sliders' shortcode content
 * @param  array $atts    
 * @param  string $content 
 * @param  string $name    
 * @return string Returns shortcode html
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_shortcode_definition( $atts, $content, $name ) {
	if ( empty( $atts ) or empty( $atts[ 'id' ] ) ) {
		return ;
	}

	$slider_id 		= sanitize_text_field( $atts[ 'id' ] );
    $attributes 	= shortcode_atts( cbzwps_get_shortcode_attributes( $slider_id ), $atts );
    $width 			= sanitize_text_field( $attributes[ 'width' ] );
    $height 		= sanitize_text_field( $attributes[ 'height' ] );
    $layout 		= sanitize_text_field( $attributes[ 'slide_layout' ] );
    $font_size 		= sanitize_text_field( $attributes[ 'slide_content_font' ] );
    $slider_bg 		= sanitize_text_field( $attributes[ 'slider_bg' ] );
    $slides_bg 		= sanitize_text_field( $attributes[ 'slides_bg' ] );
    $navigator_bg 	= sanitize_text_field( $attributes[ 'slider_navigator_bg' ] );
    $heading_color 	= sanitize_text_field( $attributes[ 'slider_heading_color' ] );
    $heading_bg 	= sanitize_text_field( $attributes[ 'slider_heading_bg' ] );
    $title_color 	= sanitize_text_field( $attributes[ 'slide_title_color' ] );
    $content_color 	= sanitize_text_field( $attributes[ 'slide_content_color' ] );
    $content_length = sanitize_text_field( intval( $attributes[ 'slide_content_length' ] ) );
    $slide_image 	= sanitize_text_field( $attributes[ 'slide_image' ] );
    $slide_title 	= sanitize_text_field( $attributes[ 'slide_title' ] );
    $slide_content 	= sanitize_text_field( $attributes[ 'slide_content' ] );
    $slider_heading = sanitize_text_field( $attributes[ 'slider_heading' ] );
    $title_bg 		= sanitize_text_field( isset( $attributes[ 'layout_4_title_bg' ] ) ? $attributes[ 'layout_4_title_bg' ] : '' );
    $content_bg 	= sanitize_text_field( isset( $attributes[ 'layout_4_content_bg' ] ) ? $attributes[ 'layout_4_content_bg' ] : '' );
	
    $heading_style 	= "style='color: ". esc_attr( $heading_color ) ."; background-color: ". esc_attr( $heading_bg ) .";'";
	$title_style 	= $layout != 'layout_4' ? "style='color: ". esc_attr( $title_color ) .";'" : "style='color: ". esc_attr( $title_color ) ."; background-color: ". esc_attr( $title_bg ) .";'";
    $content_style 	= $layout != 'layout_4' ? "style='color: ". esc_attr( $content_color ) ."; font-size: ". esc_attr( $font_size ) ."px;'" : "style='color: ". esc_attr( $content_color ) ."; background-color: ". esc_attr( $content_bg ) ."; font-size: ". esc_attr( $font_size ) ."px;'";

    extract( $attributes );

    $slider_data 	= "data-id='". esc_attr( $slider_id ) ."' data-width='". esc_attr( $width ) ."' data-autoplay='". esc_attr( $slide_autoplay ) ."' data-animation='". esc_attr( $animation ) ."' data-visible_slide='". esc_attr( $visible_slide ) ."' data-scroll_slide='". esc_attr( $scroll_slide )."'";

	$settings		= get_post_meta( $slider_id, 'cbzwps_slider_settings', true );
	
	$exclude_by 	= $settings[ 'exlude_by' ];
	if ( $exclude_by == 'id' and !empty( $settings[ 'exlude_by_id' ] ) ) {
		$settings[ "exlude_by_{$exclude_by}" ] = explode( ',', str_replace( ' ', '', $settings[ "exlude_by_{$exclude_by}" ] ) )	;
	}

	/**
	 * Build query to fetch posts according to user settings.
	 * @var array
	 * 
	 * @category function
 	 * @author CloudBerriez <support@cloudberriez.com>
	 */
	$query_args = array(
		'posts_per_page' 	=> $settings[ 'number_of_posts' ],
		'post_type' 		=> $settings[ 'cbzwps_post_type' ],
		'post__not_in' 		=> $exclude_by == 'none'? array() : $settings[ "exlude_by_{$exclude_by}" ]
	);
	$query = new WP_Query( $query_args );
	$posts = $query->get_posts();
	if ( empty( $posts ) ) {
		return;
	}


	/**
	 * Setting wrapper class.
	 * @var string
	 * @category function
 	 * @author CloudBerriez <support@cloudberriez.com>
	 */
    $wrapper_class 	= "cbzwps_slider_{$slider_id} cbzwps_slider_wrapper cbzwps_slider_{$layout}";
    
    $wrapper_id 	= "cbzwps_slider_{$slider_id}";

    $wrapper_style 	= "background-color: {$slider_bg}";

    $shortcode_html = '<div id="'. esc_attr( $wrapper_id ) .'" class="'. esc_attr( $wrapper_class ) .'" style="'. esc_attr( $wrapper_style ) .'">';
		if ( $slider_heading == 'enable' ) {
			$shortcode_html .= '<div class="cbzwps_header">';
				$shortcode_html .= '<h2 '. $heading_style .'>'. get_the_title( $slider_id ) .'</h2>';
			$shortcode_html .= '</div>';
		}

		/**
		 * Applies filters for extending the shortcode html before slider applied. 
		 * Can be used by developers.
		 * @var string
		 * @category function
 		 * @author CloudBerriez <support@cloudberriez.com>
		 */
		$shortcode_html = apply_filters( 'cbzwps_cbz_slider_before_slider_appplied', $shortcode_html );

	    $shortcode_html .= '<div class="cbzwps_slides_wrapper" id="cbzwps_slider_container_'.$slider_id .'" '. $slider_data .' style="position: relative; top: 0px; left: 0px; width: '. $width .'px; min-height: '. $height .'px;">';
	    
			$shortcode_html .= '<div class="cbzwps_slides_inner_wrapper" u="slides" style="cursor: move; position: absolute; overflow: hidden; left: 0px; top: 0px; width: '. $width .'px; min-height: '. $height .'px;">';

				/**
				 * Applies filters for extending the shortcode html before slides appearance. 
				 * Can be used by developers.
				 * @var string
				 * @category function
 			 	 * @author CloudBerriez <support@cloudberriez.com>
				 */
				$shortcode_html = apply_filters( 'cbzwps_cbz_slider_before_slides_appearance', $shortcode_html );


				foreach ( $posts as $key => $post ) {
					if ( empty( $post ) ) {
						continue;
					}

					$post_id = $post->ID;
					$content = apply_filters( 'the_content', $post->post_content );
		    		$shortcode_html .= '<div class="cbzwps_slide_wrapper">';
			    		$shortcode_html .= '<div class="cbzwps_slider_content" style="background-color: '. esc_attr( $slides_bg ) .'">';

			    			$shortcode_html .= '<div class="cbzwps_slide_thumb_wrapper">';

			    				/**
								 * Applies filters for extending the shortcode html before slide image appearance. 
								 * Can be used by developers.
								 * @var string
								 * @category function
 			 					 * @author CloudBerriez <support@cloudberriez.com>
								 */
								$shortcode_html = apply_filters( 'cbzwps_cbz_slider_before_slide_image', $shortcode_html, $post );

								if ( $slide_image == 'enable' ) {
					    			if ( has_post_thumbnail( $post_id ) ) {
					    				/**
										 * Applies filters for extending the shortcode html after slides appearance. 
										 * Can be used by developers.
										 * @var string
										 * @category function
 			 					 		 * @author CloudBerriez <support@cloudberriez.com>
										 */
										$thumb_uri 		= apply_filters( 'cbzwps_cbz_slider_slide_image_uri', get_the_post_thumbnail_url( $post_id ), $post );

					    				$shortcode_html .= '<img u="image" class="cbzwps_post_thumb" src="'. esc_attr( esc_url( $thumb_uri ) ) .'">';
					    			}
								}

				    			/**
								 * Applies filters for extending the shortcode html after slide image appearance. 
								 * Can be used by developers.
								 * @var string
								 * @category function
 			 					 * @author CloudBerriez <support@cloudberriez.com>
								 */
								$shortcode_html = apply_filters( 'cbzwps_cbz_slider_after_slide_image', $shortcode_html, $post );


			    			$shortcode_html .= '</div>';

							$shortcode_html .= '<div class="cbzwps_slide_content">';

								/**
								 * Applies filters for extending the shortcode html before slide image appearance. 
								 * Can be used by developers.
								 * @var string
								 * @category function
 			 					 * @author CloudBerriez <support@cloudberriez.com>
								 */
								$shortcode_html = apply_filters( 'cbzwps_cbz_slider_before_slide_title', $shortcode_html, $post );

								if ( $slide_title == 'enable' ) {
				    				$shortcode_html .= '<a class="cbzwps_post_title" '. $title_style .' href="'. esc_attr( esc_url( get_the_permalink( $post_id ) ) ) .'">'. get_the_title( $post_id ) .'</a>';
								}

								/**
								 * Applies filters for extending the shortcode html between slide title and content appearance. 
								 * Can be used by developers.
								 * @var string
								 * @category function
 			 					 * @author CloudBerriez <support@cloudberriez.com>
								 */
								$shortcode_html = apply_filters( 'cbzwps_cbz_slider_between_slide_title_n_content', $shortcode_html, $post );

								if ( $slide_content == 'enable' ) {
					    			$shortcode_html .= '<div class="cbzwps_post_content" '. $content_style .'>'. wp_trim_words( $content, $content_length ) .'</div>';
								}
				    			
								/**
								 * Applies filters for extending the shortcode html between slide title and content appearance. 
								 * Can be used by developers.
								 * @var string
								 * @category function
 			 					 * @author CloudBerriez <support@cloudberriez.com>
								 */
								$shortcode_html = apply_filters( 'cbzwps_cbz_slider_after_slide_content', $shortcode_html, $post );

			    			$shortcode_html .= '</div>';

			    		$shortcode_html .= '</div>';

			    	$shortcode_html .= '</div>';

			    	/**
					 * Applies filters for extending the shortcode html after slides appearance. 
					 * Can be used by developers.
					 * @var string
					 * @category function
 			 		 * @author CloudBerriez <support@cloudberriez.com>
					 */
					$shortcode_html = apply_filters( 'cbzwps_cbz_slider_after_slide', $shortcode_html, $post );

				}

			/**
			 * Applies filters for extending the shortcode html after slides appearance. 
			 * Can be used by developers.
			 * @var string
			 * @category function
 			 * @author CloudBerriez <support@cloudberriez.com>
			 */
			$shortcode_html = apply_filters( 'cbzwps_cbz_slider_after_slides_appearance', $shortcode_html );

				
			$shortcode_html .= '</div>';

			/**
			 * Applies filters for extending the shortcode html after slides appearance. 
			 * Can be used by developers.
			 * @var string
			 * @category function
 			 * @author CloudBerriez <support@cloudberriez.com>
			 */
			$shortcode_html = apply_filters( 'cbzwps_cbz_slider_before_navigator', $shortcode_html );

			/**
			 * Slider navigation is going on here.
			 * @var string
			 * @category function
 			 * @author CloudBerriez <support@cloudberriez.com>
			 */
			$shortcode_html .= '<span u="arrowleft" class="cbzwps_slider_navigator_left" style="top: 123px; left: 8px; background-color: '. esc_attr( $navigator_bg ) .'"> &#x2039; </span>';
			$shortcode_html .= '<span u="arrowright" class="cbzwps_slider_navigator_right" style="top: 123px; right: 8px; background-color: '. esc_attr( $navigator_bg ) .'"> &#x203A; </span>';

			/**
			 * Applies filters for extending the shortcode html after slides appearance. 
			 * Can be used by developers.
			 * @var string
			 *
			 * @category function
 			 * @author CloudBerriez <support@cloudberriez.com>
			 */
			$shortcode_html = apply_filters( 'cbzwps_cbz_slider_after_navigator', $shortcode_html );

		$shortcode_html .= '</div>';
	$shortcode_html .= '</div>';

	/**
	 * Enqueue css if not enqueued yet.
	 */
	if ( ! wp_style_is( 'cbzwps_slider_css' ) ) {
		wp_enqueue_style( 'cbzwps_slider_css' );
	}

	/**
	 * If css is enqueued.
	 */
	if ( wp_style_is( 'cbzwps_slider_css' ) ) {
		/**
		 * Fetch inline custom css
		 * @var string
		 * @category function
 		 * @author CloudBerriez <support@cloudberriez.com>
		 */
		$inline_css 	= apply_filters( 'cbzwps_custom_slider_css', $settings[ 'cbzwps_custom_css' ], $slider_id );
		
		/**
		 * Custom css sanitization.
		 * @var string
		 * @category function
 		 * @author CloudBerriez <support@cloudberriez.com>
		 */
		$inline_css 	= preg_replace( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", wp_strip_all_tags( $inline_css ) );
		
		wp_add_inline_style( 'cbzwps_slider_css', $inline_css );
	}

	/**
	 * If jssor slider js is not enqueued.
	 */
	if ( ! wp_script_is( 'cbzwps_jssor' ) ) {
		wp_enqueue_script( 'cbzwps_jssor' );
	}

	/**
	 * If slider js is not enqueued.
	 */
	if ( ! wp_script_is( 'cbzwps_slider' ) ) {
		/**
		 * Enqueue slider script.
		 */
		wp_enqueue_script( 'cbzwps_slider' );
	}

	return apply_filters( 'cbzwps_cbz_slider_final_html', $shortcode_html );
}
add_shortcode( "cbz_slider", 'cbzwps_shortcode_definition' );

/**
 * Gets shortcode attributes
 * @param  int $post_id 
 * @return array          
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_get_shortcode_attributes( $post_id ) {
	if ( empty( $post_id ) ) {
		return;
	}

	return $slider_settings = apply_filters( 'cbzwps_filter_shortcode_attributes', get_post_meta( $post_id, 'cbzwps_slider_settings', true ) );
}

/**
 * Filters shortcode attributes by preventing some options from settings.
 * @param  array $settings 
 * @return array
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_filter_shortcode_attributes( $settings ) {
	if ( empty( $settings ) ) {
		return $settings;
	}

	$prevented_attributes = apply_filters( 
		'cbzwps_prevented_attributes', 
		array( 
			'exlude_by', 
			'exlude_by_id', 
			'exlude_by_search', 
			'enable_posts_column', 
			'cbzwps_post_type',
			'layout_4_title_bg',
			'layout_4_content_bg'
		) 
	);

	if( empty( $prevented_attributes ) ) {
		return;
	}

	foreach ( $prevented_attributes as $attr ) {
		if ( empty( $attr ) ) {
			continue;
		}

		if ( array_key_exists( $attr, $settings ) ) {
			unset( $settings[ $attr ] );
		}
	}

	return array_filter( $settings );
}
add_filter( 'cbzwps_filter_shortcode_attributes', 'cbzwps_filter_shortcode_attributes' );
