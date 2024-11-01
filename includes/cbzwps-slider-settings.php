<?php 
/**
 * If no Wordpress, go home
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders sliders' settings.
 * @param  array $option_name(optional) Specific settings name.
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_slider_settings( $post, $slider_setting_opts, $slider_settings = array(), $option_name = array() ) {
	if ( empty( $slider_setting_opts ) and ! is_array( $slider_setting_opts ) ) {
		return ;
	}

	foreach ( $slider_setting_opts as $name => $option ) {
		if ( empty( $option ) or empty( $name ) ) {
			continue;
		}

		if ( !empty( $option_name ) ) {
			if ( !in_array( $name, $option_name ) ) {
				continue;
			}
		}

		$wrapper_class 	= isset( $option[ 'wrapper_class' ] ) ? $option[ 'wrapper_class' ] : '';
		$depends_on 	= isset( $option[ 'depends_on' ] ) ? ( isset( $slider_settings[ $name ] ) and $option[ 'depends_on' ] .'_' . $slider_settings[ $option[ 'depends_on' ] ] == $name ) ? 'cbzwps_' . $option[ 'depends_on' ] . '_val' : 'cbzwps_' . $option[ 'depends_on' ] . '_val cbzwps_hide' : '';
		$depends_on 	= "{$wrapper_class} {$depends_on}";
		$tag 			= $option[ 'tag' ];
		$description 	= ( isset( $option[ 'description' ] ) and $option[ 'description' ] != '' ) ? $option[ 'description' ] : '&nbsp;';
		$placeholder 	= ( !isset( $option[ 'placeholder' ] ) or $option[ 'placeholder' ] == '' ) ? '' : 'placeholder="'. esc_attr( $option[ 'placeholder' ] ) .'"';
		$id 			= ( !isset( $option[ 'id' ] ) or $option[ 'id' ] == '' ) ? '' : 'id="'. esc_attr( $option[ 'id' ] ) .'"';
		
		echo '<div class="cbzwps_row '. esc_attr( $depends_on ) .'" id="'. esc_attr( "cbzwps_{$name}" ) .'">';

			echo '<div class="cbzwps_left_col">';
				echo '<label for="">'. $description .'</label>';
			echo '</div>';

			switch ( $tag ) {

				case 'input':
					$value 		= isset( $slider_settings[ $name ] ) ? $slider_settings[ $name ] : '';
					$type 		= isset( $option[ 'type' ] ) ? $option[ 'type' ] : 'text';
					$attribs 	= ( isset( $option[ 'attribs' ] ) and ! is_array( $option[ 'attribs' ] ) ) ? $option[ 'attribs' ] : '';

					echo '<div class="cbzwps_right_col">';

						if ( isset( $option[ 'hint' ] ) ) {
							cbzwps_render_tooltip( $option[ 'hint' ] );
						}


						echo '<input type="'. esc_attr( $type ) .'" '. $id .' class="'. esc_attr( $option[ 'class' ] ) .'" name="'. esc_attr( $name ) .'" value="'. esc_attr( $value ) .'" '. $placeholder .' '. $attribs .'>';
						
					echo '</div>';

					break;
			
				case 'textarea':
					$value 		= isset( $slider_settings[ $name ] ) ? $slider_settings[ $name ] : '';
					$type 		= isset( $option[ 'type' ] ) ? $option[ 'type' ] : 'text';
					$attribs 	= ( isset( $option[ 'attribs' ] ) and ! is_array( $option[ 'attribs' ] ) ) ? $option[ 'attribs' ] : '';

					echo '<div class="cbzwps_right_col">';

						if ( isset( $option[ 'hint' ] ) ) {
							cbzwps_render_tooltip( $option[ 'hint' ] );
						}


						echo '<textarea '. $id .' class="'. esc_attr( $option[ 'class' ] ) .'" name="'. esc_attr( $name ) .'" '. $attribs .' '. $placeholder .'>'. $value .'</textarea>';
						
					echo '</div>';

					break;
				
				case 'select':
					$attribs 	= ( isset( $option[ 'attribs' ] ) and ! is_array( $option[ 'attribs' ] ) ) ? $option[ 'attribs' ] : '';
					
					echo '<div class="cbzwps_right_col">';
						
						if ( isset( $option[ 'hint' ] ) ) {
							cbzwps_render_tooltip( $option[ 'hint' ] );
						}

						echo '<select '. $id .' class="'. esc_attr( $option[ 'class' ] ) .'" name="'. esc_attr( $name ) .'" '. $placeholder .' '. $attribs .'>';
							
							foreach ( $option[ 'options' ] as $opt_val => $opt_name ) {
								if ( empty( $opt_name ) ) {
									continue;
								}

								$selected = '';
								if ( isset( $slider_settings[ $name ] ) and $slider_settings[ $name ] == $opt_val ) {
									$selected = 'selected';
								}

								echo '<option value="'. esc_attr( $opt_val ) .'" '. $selected .'>'. $opt_name .'</option>';
							}

						echo '</select>';

					echo '</div>';

					break;
				
				case 'checkbox':
					$value 		= isset( $slider_settings[ $name ] ) ? $slider_settings[ $name ] : '';
					$attribs 	= ( isset( $option[ 'attribs' ] ) and ! is_array( $option[ 'attribs' ] ) ) ? $option[ 'attribs' ] : '';

					echo '<div class="cbzwps_right_col">';
						
						if ( isset( $option[ 'hint' ] ) ) {
							cbzwps_render_tooltip( $option[ 'hint' ] );
						}

						echo '<input type="checkbox" '. $id .' class="'. esc_attr( $option[ 'class' ] ) .'" name="'. esc_attr( $name ) .'" value="'. esc_attr( $value ) .'" '. $attribs .'>';
						
						if ( isset( $option[ 'hint' ] ) ) {
							echo $option[ 'hint' ];
						}

					echo '</div>';

					break;
				
				case 'multi_select':
					$value 		= isset( $slider_settings[ $name ] ) ? $slider_settings[ $name ] : '';
					$attribs 	= ( isset( $option[ 'attribs' ] ) and ! is_array( $option[ 'attribs' ] ) ) ? $option[ 'attribs' ] : '';
					$datas 		= ( isset( $option[ 'data' ] ) and is_array( $option[ 'data' ] ) ) ? $option[ 'data' ] : array();
					$data 		= '';

					if ( !empty( $datas ) ) {
						foreach ( $datas as $data_name => $data_val ) {
							if ( empty( $data_name ) or empty( $data_val ) ) {
								continue;
							}

							if ( isset( $slider_settings[ $data_val ] ) ) {
								$data .= $data == '' ? "data-{$data_name}='{$slider_settings[ $data_val ]}'" : " data-{$data_name}='{$slider_settings[ $data_val ]}'";
							}
						}
					}

					echo '<div class="cbzwps_right_col">';
						$placeholder = $placeholder != '' ? "data-{$placeholder}" : $placeholder;
						
						if ( isset( $option[ 'hint' ] ) ) {
							cbzwps_render_tooltip( $option[ 'hint' ] );
						}

						echo '<select '. $id .' class="'. esc_attr( $option[ 'class' ] ) .'" name="'. esc_attr( $name ) .'[]" '. $placeholder .' '. $data .' '. $attribs .' multiple>';
							
							if ( isset( $slider_settings[ $name ] ) and ! empty( $slider_settings[ $name ] ) ) {
								
								foreach ( $slider_settings[ $name ] as $post_id ) {
									if ( empty( $post_id ) ) {
										continue;
									}

									$post = get_post( $post_id );
									if ( ! is_object( $post ) or empty( $post ) ) {
										continue;
									}

									$selected = 'selected="selected"';

									echo '<option value="'. esc_attr( $post_id ) .'" '. $selected .'>'. $post->post_title .'</option>';
								}
							}

						echo '</select>';
						
						
					echo '</div>';

					break;
				
				default:
					/**
					 * Default settings can be handled by developers.
					 */
					do_action( 'cbzwps_default_slider_setting_options' );

					break;
			}
		echo '</div>';
	}
	
	do_action( 'cbzwps_extend_slider_setting_options' );
}
add_action( 'cbzwps_slider_settings', 'cbzwps_slider_settings', 10, 4 );

/**
 * Get tab content
 * @param  string $tab [description]
 * @return array
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_get_tab_content( $tab = '' ) {
	$setting_contents = cbzwps_get_slider_setting_options();
	if ( empty( $tab ) ) {
		return $setting_contents;
	}

	return $setting_contents[ $tab ];
}

/**
 * Get slider setting options.
 * @return array
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_get_slider_setting_options() {
	return $slider_setting_opts = apply_filters( 
		'cbzwps_get_slider_setting_options', 
		array(
			'general' => array(
				'cbzwps_post_type' 	=> array(
					'tag' 			=> 'select',
					'id'			=> 'cbzwps_post_types',
					'description' 	=> __( 'Post Type', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_select2',
					'hint' 			=> __( 'Choose post type for slides. Default is post.', CBZWPS_TXTDOMAIN ),
					'options'		=> cbzwps_get_allowed_post_types(),
					'default'		=> 'post'
				),
				'slide_content_length' 	=> array(
					'tag' 			=> 'input',
					'type'			=> 'number',
					'description' 	=> __( 'Post content length', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_form_input medium-text',
					'placeholder' 	=> __( 'Enter the length of post content.', CBZWPS_TXTDOMAIN ),
					'hint' 			=> __( 'The length of content shown in each slides. The unit of length is words. Like 20 words is the default length.', CBZWPS_TXTDOMAIN ),
					'attribs'		=> 'min="0"',
					'default'		=> '20'
				),
				'number_of_posts' 	=> array(
					'tag' 			=> 'input',
					'type'			=> 'number',
					'description' 	=> __( 'Number of posts', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_form_input medium-text',
					'placeholder' 	=> __( 'Enter number of post to show in slider.', CBZWPS_TXTDOMAIN ),
					'hint' 			=> __( 'How much slides you want to show. Default is all posts or chosen post type.', CBZWPS_TXTDOMAIN ),
					'attribs'		=> 'min="-1"',
					'default'		=> '-1'
				),
				'exlude_by' 	=> array(
					'tag' 			=> 'select',
					'id'			=> 'cbzwps_exlude_by',
					'description' 	=> __( 'Exlude posts by', CBZWPS_TXTDOMAIN ),
					'class' 		=> '',
					'hint' 			=> __( 'Exclude posts either by post id or by searching specific posts.', CBZWPS_TXTDOMAIN ),
					'options'		=> array(
						'none' 		=> __( 'None', CBZWPS_TXTDOMAIN ),
						'id' 		=> __( 'Post Id', CBZWPS_TXTDOMAIN ),
						'search' 	=> __( 'Search Post', CBZWPS_TXTDOMAIN ),
					),
					'default'		=> ''
				),
				'exlude_by_id' 	=> array(
					'tag' 			=> 'input',
					'placeholder' 	=> __( 'Enter comma separated ids', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_form_input',
					'depends_on'	=> 'exlude_by',
					'default'		=> ''
				),
				'exlude_by_search' 	=> array(
					'tag' 			=> 'multi_select',
					'id'			=> 'exlude_by_search',
					'placeholder' 	=> __( 'Search for posts to exclude', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_ajax_select2',
					'data'			=> array(
						'post_type' => 'cbzwps_post_type'
					),
					'depends_on'	=> 'exlude_by',
					'default'		=> ''
				)
			),
			'slider' => array(				
				'slide_image' 	=> array(
					'tag' 			=> 'select',
					'description' 	=> __( 'Slides Image', CBZWPS_TXTDOMAIN ),
					'class' 		=> '',
					'hint' 			=> __( 'Select to enable/disable post image in each slide', CBZWPS_TXTDOMAIN ),
					'options'		=> array(
						'enable' 	=> __( 'Enable', CBZWPS_TXTDOMAIN ),
						'disable' 	=> __( 'Disable', CBZWPS_TXTDOMAIN )
					),
					'default'		=> 'enable'
				),
				'slide_title' 	=> array(
					'tag' 			=> 'select',
					'description' 	=> __( 'Slides Title', CBZWPS_TXTDOMAIN ),
					'class' 		=> '',
					'hint' 			=> __( 'Select to enable/disable post title in each slide', CBZWPS_TXTDOMAIN ),
					'options'		=> array(
						'enable' 	=> __( 'Enable', CBZWPS_TXTDOMAIN ),
						'disable' 	=> __( 'Disable', CBZWPS_TXTDOMAIN )
					),
					'default'		=> 'enable'
				),
				'slide_content' 	=> array(
					'tag' 			=> 'select',
					'description' 	=> __( 'Slides Content', CBZWPS_TXTDOMAIN ),
					'class' 		=> '',
					'hint' 			=> __( 'Select to enable/disable post content in slides', CBZWPS_TXTDOMAIN ),
					'options'		=> array(
						'enable' 	=> __( 'Enable', CBZWPS_TXTDOMAIN ),
						'disable' 	=> __( 'Disable', CBZWPS_TXTDOMAIN )
					),
					'default'		=> 'enable'
				),
				'slider_heading' 	=> array(
					'tag' 			=> 'select',
					'description' 	=> __( 'Slider Heading', CBZWPS_TXTDOMAIN ),
					'class' 		=> '',
					'hint' 			=> __( 'Select to enable/disable slider heading, title of this slider will be slider heading.', CBZWPS_TXTDOMAIN ),
					'options'		=> array(
						'enable' 	=> __( 'Enable', CBZWPS_TXTDOMAIN ),
						'disable' 	=> __( 'Disable', CBZWPS_TXTDOMAIN )
					),
					'default'		=> 'enable'
				),
				'slide_content_font' => array(
					'tag' 			=> 'input',
					'type'			=> 'number',
					'description' 	=> __( 'Font size for slide content', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_form_input medium-text',
					'placeholder' 	=> __( 'Enter font size', CBZWPS_TXTDOMAIN ),
					'hint'			=> __( 'You can adjust font size of slides content.', CBZWPS_TXTDOMAIN ),
					'attribs'		=> 'min="1"',
					'default'		=> '14'
				),
				'slide_autoplay' 	=> array(
					'tag' 			=> 'select',
					'description' 	=> __( 'Autoplay Slider', CBZWPS_TXTDOMAIN ),
					'class' 		=> '',
					'hint' 			=> __( 'Select to enable/disable slider autoplay', CBZWPS_TXTDOMAIN ),
					'options'		=> array(
						'enable' 	=> __( 'Enable', CBZWPS_TXTDOMAIN ),
						'disable' 	=> __( 'Disable', CBZWPS_TXTDOMAIN ),
					),
					'default'		=> 'enable'
				),
				'width' 		=> array(
					'tag' 			=> 'input',
					'type'			=> 'number',
					'description' 	=> __( 'Slider width', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_form_input medium-text',
					'placeholder' 	=> __( 'Slider width', CBZWPS_TXTDOMAIN ),
					'hint'			=> __( 'The width you set here will be width of slider in frontend.', CBZWPS_TXTDOMAIN ),
					'attribs'		=> 'min="1"',
					'default'		=> '500'
				),
		        'height' 		=> array(
					'tag' 			=> 'input',
					'type'			=> 'number',
					'description' 	=> __( 'Slider height', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_form_input medium-text',
					'placeholder' 	=> __( 'Slider height', CBZWPS_TXTDOMAIN ),
					'attribs'		=> 'min="1"',
					'hint'			=> __( 'The height you set here will be minimum height of slider.', CBZWPS_TXTDOMAIN ),
					'default'		=> '300'
				),
		        'animation' 	=> array(
					'tag' 			=> 'select',
					'description' 	=> __( 'Slider animation', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_select2',
					'placeholder' 	=> __( 'Slider animation', CBZWPS_TXTDOMAIN ),
					'hint'			=> __( 'These animation effects will be applied only if 1 slide at once is visible and autplay is enabled. On previous/next button only the default (slide) effect will be working.', CBZWPS_TXTDOMAIN ),
					'options'		=> cbzwps_get_slider_animations(),
					'default'		=> ''
				),
		        'visible_slide'	=> array(
					'tag' 			=> 'input',
					'type'			=> 'number',
					'description' 	=> __( 'Total slides at once', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_form_input medium-text',
					'placeholder' 	=> __( 'Totals number of slides visible in slider', CBZWPS_TXTDOMAIN ),
					'hint'			=> __( 'Minimum 1, Maximum 5, Also according to width and height', CBZWPS_TXTDOMAIN ),
					'attribs'		=> 'min="1" max="5"',
					'default'		=> '1'
				),
		        'scroll_slide'	=> array(
					'tag' 			=> 'input',
					'type'			=> 'number',
					'description' 	=> __( 'Scroll slides at once', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_form_input medium-text',
					'placeholder' 	=> __( 'Totals number of slides should be scrolled at once', CBZWPS_TXTDOMAIN ),
					'hint'			=> __( 'Minimum 1, Maximum 5, also depends on above field', CBZWPS_TXTDOMAIN ),
					'attribs'		=> 'min="1" max="5"',
					'default'		=> '1'
				),
		        'slide_layout'	=> array(
					'tag' 			=> 'select',
					'description' 	=> __( 'Choose slider layout', CBZWPS_TXTDOMAIN ),
					'class' 		=> '',
					'placeholder' 	=> __( 'Choose slider layout', CBZWPS_TXTDOMAIN ),
					'options'		=> array(
						'layout_1' => __( 'Layout 1', CBZWPS_TXTDOMAIN ),
						'layout_2' => __( 'Layout 2', CBZWPS_TXTDOMAIN ),
						'layout_3' => __( 'Layout 3', CBZWPS_TXTDOMAIN ),
						'layout_4' => __( 'Layout 4', CBZWPS_TXTDOMAIN )
					),
					'hint'			=> __( 'The 4 given layouts for slider. The `Layout 4` is specially for without image slides.', CBZWPS_TXTDOMAIN ),
					'default'		=> 'layout_1'
				),
				'layout_4_title_bg' 	=> array(
					'tag' 			=> 'input',
					'id'			=> 'layout_4_title_bg',
					'description' 	=> __( 'Choose title background color', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_colorpicker',
					'wrapper_class' => 'layout_4_option cbzwps_hide',
					'default'		=> '#42A5F5'
				),
				'layout_4_content_bg' 	=> array(
					'tag' 			=> 'input',
					'id'			=> 'layout_4_content_bg',
					'description' 	=> __( 'Choose content background color', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_colorpicker',
					'wrapper_class' => 'layout_4_option cbzwps_hide',
					'default'		=> '#81D4FA'
				)
			),
			'colors' => array(
				'slider_bg' 	=> array(
					'tag' 			=> 'input',
					'description' 	=> __( 'Slider Background Color', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_colorpicker',
					'hint' 			=> __( 'Choose background color for slider.', CBZWPS_TXTDOMAIN ),
					'default'		=> '#ffffff'
				),
				'slides_bg' 	=> array(
					'tag' 			=> 'input',
					'description' 	=> __( 'Slides Background Color', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_colorpicker',
					'hint' 			=> __( 'Choose background color for each slides inside the slider.', CBZWPS_TXTDOMAIN ),
					'default'		=> '#ffffff'
				),
				'slider_navigator_bg' 	=> array(
					'tag' 			=> 'input',
					'description' 	=> __( 'Slider Navigator Background Color', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_colorpicker',
					'hint' 			=> __( 'Choose background color for slider navigation arrow.', CBZWPS_TXTDOMAIN ),
					'default'		=> '#FFFFFF'
				),
				'slider_heading_color' 	=> array(
					'tag' 			=> 'input',
					'description' 	=> __( 'Slider Heading Color', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_colorpicker',
					'hint' 			=> __( 'Choose slider heading text color.', CBZWPS_TXTDOMAIN ),
					'default'		=> '#333333'
				),
				'slider_heading_bg' 	=> array(
					'tag' 			=> 'input',
					'description' 	=> __( 'Slider heading background color', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_colorpicker',
					'hint' 			=> __( 'Choose slider heading text color.', CBZWPS_TXTDOMAIN ),
					'default'		=> '#FFFFFF'
				),
				'slide_title_color' 	=> array(
					'tag' 			=> 'input',
					'description' 	=> __( 'Slides Title Color', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_colorpicker',
					'hint' 			=> __( 'Choose slides title text color.', CBZWPS_TXTDOMAIN ),
					'default'		=> '#333333'
				),
				'slide_content_color' 	=> array(
					'tag' 			=> 'input',
					'description' 	=> __( 'Slides Content Color', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_colorpicker',
					'hint' 			=> __( 'Choose slides content text color.', CBZWPS_TXTDOMAIN ),
					'default'		=> '#FFFFFF'
				)
			),
			'extra' => array(
				'cbzwps_custom_css' => array(
					'tag' 			=> 'textarea',
					'id'			=> 'cbzwps_custom_css',
					'description' 	=> __( 'Add Custom Css', CBZWPS_TXTDOMAIN ),
					'class' 		=> 'cbzwps_textarea',
					'hint' 			=> __( 'This option is only for developers. If they need some modification through css in this slider, they can write css for that here.', CBZWPS_TXTDOMAIN ),
					'placeholder'	=> __( 'Please inherit from the given shortcode wrapper id.', CBZWPS_TXTDOMAIN ),
					'attribs'		=> 'rows="5"',
					'default'		=> ''
				)
			)			
		)
	);
}

/**
 * Get settings tabs.
 * @return array
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_get_setting_tabs() {
	return $tabs = apply_filters( 
		'cbzwps_setting_tabs', 
		array( 
			'general' 	=> __( 'General Settings', CBZWPS_TXTDOMAIN ),
			'slider' 	=> __( 'Slider Settings', CBZWPS_TXTDOMAIN ),
			'colors' 	=> __( 'Colors Settings', CBZWPS_TXTDOMAIN ),
			'extra' 	=> __( 'Extra Settings', CBZWPS_TXTDOMAIN )
		) 
	);
}

/**
 * Renders admin settings.
 * @param  object $post
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_render_settings( $post ) {
	$tabs = cbzwps_get_setting_tabs();
	if ( empty( $tabs ) ) {
		return;
	}

	echo '<div class="cbzwps_tabs_wrapper">';
		
		$count = 0;
		echo '<div class="cbzwps_tabs">';
			foreach ( $tabs as $tab_val => $tab_name ) {
				if ( empty( $tab_val ) or empty( $tab_name ) ) {
					continue;
				}

				$active = $count > 0 ? '' : 'cbzwps_active_tab';
				echo '<a class="cbzwps_tab_anchor '. esc_attr( $active ) .'" href="javascript:void(0);" data-tab="'. esc_attr( $tab_val ) .'">'. $tab_name .'</a>';
				$count++;
			}
		echo '</div>';

		$count = 0;
		echo '<div class="cbzwps_tabs_content">';
			foreach ( $tabs as $tab_val => $tab_name ) {
				if ( empty( $tab_val ) or empty( $tab_name ) ) {
					continue;
				}

				$content_class = $count > 0 ? "cbzwps_{$tab_val}_settings_content cbzwps_settings_tab_content cbzwps_hide" : "cbzwps_{$tab_val}_settings_content cbzwps_settings_tab_content" ;
				
				echo '<div class="'. esc_attr( $content_class ) .'">';
				
					$slider_settings 		= get_post_meta( $post->ID, 'cbzwps_slider_settings', true );

					$slider_setting_opts 	= cbzwps_get_tab_content( $tab_val );

					/**
					 * Add slider settings
					 */
					do_action( 'cbzwps_slider_settings', $post, $slider_setting_opts, $slider_settings ); 

				echo '</div>';

				$count++;
			}
		echo '</div>';
	echo '</div>';
}
add_action( 'cbzwps_render_settings', 'cbzwps_render_settings', 10, 1 );

/**
 * Renders tootltip
 * @param  string $tip
 * 
 * @category function
 * @author CloudBerriez <support@cloudberriez.com>
 */
function cbzwps_render_tooltip( $tip = '' ) {
	echo '<div class="cbzwps_tooltip dashicons dashicons-editor-help">';
		echo '<span class="cbzwps_tooltiptext cbzwps_tooltip-bottom">'. sanitize_text_field( $tip ) .'</span>';
	echo '</div>';
}