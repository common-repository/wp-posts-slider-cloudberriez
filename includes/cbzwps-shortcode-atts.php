<?php 
/**
 * If no Wordpress, go home
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$atts = array(
	'id' => array(
		'required' 	=> __( 'Yes', CBZWPS_TXTDOMAIN ),
		'default' 	=> '',
		'desc' 		=> __( 'This is a required attribute, which lets you show the content and settings of specific slider.', CBZWPS_TXTDOMAIN ),
	),
	'slide_content_length' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> 20,
		'desc' 		=> __( 'This attributes can be used for managing the content length of each slides. If not applied in shortcode manually then the setting\'s value will be used.', CBZWPS_TXTDOMAIN ),
	),
	'number_of_posts' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> '-1 (All posts)',
		'desc' 		=> __( 'This attributes can be used for managing the number of post to show in a slider. If not applied in shortcode manually then the setting\'s value will be used.', CBZWPS_TXTDOMAIN ),
	),
	'slide_image' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> 'enable',
		'desc' 		=> __( 'This is a boolean attribute, which lets you enable/disable the slide image option. If set to `disable` mode, image of posts will not be shown.', CBZWPS_TXTDOMAIN ),
	),
	'slide_title' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> 'enable',
		'desc' 		=> __( 'This is a boolean attribute, which lets you enable/disable the slide title option. If set to `disable` mode, title of posts will not be shown.', CBZWPS_TXTDOMAIN ),
	),
	'slide_content' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> 'enable',
		'desc' 		=> __( 'This is a boolean attribute, which lets you enable/disable the slide content option. If set to `disable` mode, content of posts will not be shown.', CBZWPS_TXTDOMAIN ),
	),
	'slider_heading' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> 'enable',
		'desc' 		=> __( 'This is a boolean attribute, which lets you enable/disable the slider heading option. If set to `disable` mode, slider heading will not be shown.', CBZWPS_TXTDOMAIN ),
	),
	'slide_content_font' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> 14,
		'desc' 		=> __( 'This attribute lets you control the font-size of the slide\'s content.', CBZWPS_TXTDOMAIN ),
	),
	'slide_autoplay' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> 'enable',
		'desc' 		=> __( 'This is a boolean attribute means you can either eanble or disable it. This attribute lets you control the slider autoplay feature.', CBZWPS_TXTDOMAIN ),
	),
	'width' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> 500,
		'desc' 		=> __( 'This attribute lets you control the slider width.', CBZWPS_TXTDOMAIN ),
	),
	'height' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> 300,
		'desc' 		=> __( 'This attribute lets you control the slider height.', CBZWPS_TXTDOMAIN ),
	),
	'animation' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> '',
		'desc' 		=> __( 'This attribute lets you control the slider animation effects. <strong>Please make sure that you\'re not using more than 1 slides, otherwise animation effects will not be worked.</strong> Only slide effect will be ther if more than 1 slide. Also the default value is <strong>null</strong> that does means <i><code>slide effect</code></i> rather than <i><code>no effect</code></i>. If you navigate the next/previous slides, no animation effects will work, only the slide effect. You can see all the values of animation effect in settings.', CBZWPS_TXTDOMAIN ),
	),
	'visible_slide' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> '1',
		'desc' 		=> __( 'This lets you manage the number of slides to show at once. If more than 1 slide is set no animation effects will work.', CBZWPS_TXTDOMAIN ),
	),
	'scroll_slide' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> '1',
		'desc' 		=> __( 'This lets you manage the number of slides to scroll at once. This is applicable only on autoscroll.', CBZWPS_TXTDOMAIN ),
	),
	'slide_layout' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> 'layout_1',
		'desc' 		=> __( 'This lets you contolr the slider layout and view. There are total 4 layouts in slider. Values are <code>layout_1</code>, <code>layout_2</code>, <code>layout_3</code>, <code>layout_4</code>. <code>layout_4</code> is specially suitable for slides having no image.', CBZWPS_TXTDOMAIN ),
	),
	'slider_bg' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> '#ffffff',
		'desc' 		=> __( 'This atribute lets you control the slider background color. Default color is white. You can enter the hexcode of color to change it. Or can choose the color from color pallet in settings.', CBZWPS_TXTDOMAIN ),
	),
	'slides_bg' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> '#ffffff',
		'desc' 		=> __( 'This atribute lets you control the each slide\'s background color. Default color is white. You can enter the hexcode of color to change it. Or can choose the color from color pallet in settings.', CBZWPS_TXTDOMAIN ),
	),
	'slider_navigator_bg' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> '#ffffff',
		'desc' 		=> __( 'This atribute lets you control slider\'s navigator background color. Default color is white. You can enter the hexcode of color to change it. Or can choose the color from color pallet in settings.', CBZWPS_TXTDOMAIN ),
	),
	'slider_heading_color' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> '#333333',
		'desc' 		=> __( 'This atribute lets you control the slider\'s heading text color. Default color is metalic black. You can enter the hexcode of color to change it. Or can choose the color from color pallet in settings.', CBZWPS_TXTDOMAIN ),
	),
	'slide_title_color' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> '#333333',
		'desc' 		=> __( 'This atribute lets you control the each slide\'s title text color. Default color is metalic black. You can enter the hexcode of color to change it. Or can choose the color from color pallet in settings.', CBZWPS_TXTDOMAIN ),
	),
	'slide_content_color' => array(
		'required' 	=> __( 'No', CBZWPS_TXTDOMAIN ),
		'default' 	=> '#ffffff',
		'desc' 		=> __( 'This atribute lets you control the each slide\'s content text color. Default color is white. You can enter the hexcode of color to change it. Or can choose the color from color pallet in settings.', CBZWPS_TXTDOMAIN ),
	)
);
?>
<div class="wrap">
	<h2><?php _e( 'CBz Slider Shortcode Attributes', '$domain' ); ?></h2>
	<div class="cbzwps_notice_info notice notice-info">
		<span class=""><b><u><?php _e( 'Note:', '$domain' ); ?></u></b></span>
		<strong>
			<tt><?php _e( ' There are settings(While creating slider) for all these listed attributes here. But you can overwrite them you wish by applying these attributes in shortcode.<br> Like: <code>[cbz_slider id="id_of_slider" slide_content_length="25" slide_content_color="#000000"]</code>', '$domain' ); ?></tt>
		</strong>
	</div>
	<div class="cbzwps_atts_wrap">
		<table class="widefat striped">
			<thead>
				<tr>
					<th><?php _e( 'Attribute Name', CBZWPS_TXTDOMAIN ); ?></th>
					<th><?php _e( 'Required', CBZWPS_TXTDOMAIN ); ?></th>
					<th><?php _e( 'Default', CBZWPS_TXTDOMAIN ); ?></th>
					<th><?php _e( 'Description', CBZWPS_TXTDOMAIN ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				foreach ( $atts as $attr => $options ) {
					if ( empty( $attr ) ) {
						continue;
					}

					?>
					<tr>
						<td class="cbzwps_attr"><?php echo $attr; ?></td>
						<td class="cbzwps_required_attr"><?php echo $options[ 'required' ]; ?></td>
						<td class="cbzwps_default_attr"><?php echo $options[ 'default' ]; ?></td>
						<td class="cbzwps_desc_attr"><?php echo $options[ 'desc' ]; ?></td>
					</tr>
					<?php 
				}

				?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php _e( 'Attribute Name', CBZWPS_TXTDOMAIN ); ?></th>
					<th><?php _e( 'Required', CBZWPS_TXTDOMAIN ); ?></th>
					<th><?php _e( 'Default', CBZWPS_TXTDOMAIN ); ?></th>
					<th><?php _e( 'Description', CBZWPS_TXTDOMAIN ); ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>