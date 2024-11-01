<?php 
/**
 * Plugin Name: Wp Posts Slider - CloudBerriez
 * Description: Displays any posts of wordpress in the slider.
 * Author: CloudBerriez
 * Author URI: http://cloudberriez.com/
 * Version: 1.0.0
 * License: GPL2
 * Text Domain: wp-posts-slider
 */

/**
 * If no Wordpress, go home
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Wp_Posts_Slider' ) ) :
	
	/**
	 * Defines class named as `Wp_Posts_Slider`.
	 * 
	 * @category class
	 * @author CloudBerriez <support@cloudberriez.com>
	 */
	class Wp_Posts_Slider {

		/**
		 * Defines variable for constants
		 * @var array
		 */
		public $constants = array();

		/**
		 * Defines contant variable
		 * @var array
		 */
		public $script_suffix = '.min';

		/**
		 * Defines constructor
		 * @author CloudBerriez <support@cloudberriez.com>
		 */
		function __construct() {

			/**
			 * Invokes to initialize the plugin
			 */
			$this->cbzwps_init();
		}

		/**
		 * Initializes the plugin's required resources.
		 * 
		 * @category function
		 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_init() {
			if ( ! session_id() ) {
				session_start();
			}

			$this->constants = apply_filters( 
				'cbzwps_define_constants', 
				array(
					'CBZWPS_PREFIX' 	=> 'cbzwps_',
					'CBZWPS_VERSION' 	=> '1.0.0',
					'CBZWPS_TXTDOMAIN' 	=> 'wp-posts-slider',
					'CBZWPS_BASENAME' 	=> plugin_basename( __FILE__ ),
					'CBZWPS_PLGN_PATH' 	=> plugin_dir_path( __FILE__ ),
					'CBZWPS_PLGN_URL' 	=> plugin_dir_url ( __FILE__ ),
				)
			);

			/**
			 * Invokes to define the constants.
			 */
			$this->cbzwps_define_constants();
			
			require_once( CBZWPS_PLGN_PATH . 'includes/cbzwps-register-post-type.php' );
			require_once( CBZWPS_PLGN_PATH . 'includes/cbzwps-slider-settings.php' );
			require_once( CBZWPS_PLGN_PATH . 'includes/cbzwps-hooks.php' );
			require_once( CBZWPS_PLGN_PATH . 'includes/cbzwps-post-type-settings.php' );
			require_once( CBZWPS_PLGN_PATH . 'includes/cbzwps-shortcodes.php' );

			add_action( 'admin_menu', array( $this, 'cbzwps_admin_menu' ) );

			/**
			 * Enqueue frontend scripts and styles
			 */
			add_action( 'wp_enqueue_scripts', array( $this, 'cbzwps_wp_enqueue_scripts' ) );

			/**
			 * Enqueue Admin scripts and styles
			 */
			add_action( 'admin_enqueue_scripts', array( $this, 'cbzwps_admin_enqueue_scripts' ) );

			add_action( 'wp_ajax_cbzwps_query_posts', array( $this, 'cbzwps_get_query_posts' ) );
			add_action( 'admin_notices', array( $this, 'cbzwps_success' ) );
			add_action( 'admin_notices', array( $this, 'cbzwps_error' ) );
			add_action( 'in_admin_footer', array( $this, 'cbzwps_in_admin_footer' ) );
			add_action( 'admin_footer_text', array( $this, 'cbzwps_admin_footer_text' ) );

			add_action ( 'plugins_loaded', array( $this, 'cbzwps_load_text_domain' ) );
		}

		/**
		 * Enqueues scripts and styles for frontend.
		 *
		 * @category function
	 	 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_wp_enqueue_scripts() {
			$suffix = (string) $this->script_suffix;

			wp_register_style( 'cbzwps_slider_css', CBZWPS_PLGN_URL . 'assets/css/cbzwps-slider'. $suffix .'.css', '', CBZWPS_VERSION, 'all' );

			wp_enqueue_script( 'cbzwps_jssor' );
			wp_register_script( 'cbzwps_jssor', CBZWPS_PLGN_URL . 'assets/js/slider/jssor.slider.mini.js', array( 'jquery' ), CBZWPS_VERSION, true );
			wp_register_script( 'cbzwps_slider', CBZWPS_PLGN_URL . 'assets/js/cbzwps-slider'. $suffix .'.js', array( 'jquery' ), CBZWPS_VERSION, true );

			$slider_l10n = array(
				'ajaxUrl' 	=> admin_url( 'admin-ajax.php' ),
			);
			wp_localize_script( 'cbzwps_slider', 'cbzwps_slider', $slider_l10n );
		}

		/**
		 * Enqueues scripts and styles for admin area.
		 * 
		 * @category function
	 	 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_admin_enqueue_scripts() {
			if ( ! is_admin() ) {
				return ;
			}

			$suffix 	= (string) $this->script_suffix;

			$ajax_nonce = wp_create_nonce( "cbzwps_seurity_nonce" );
			$l10n 		= array(
				'ajaxUrl' 		=> admin_url( 'admin-ajax.php' ),
				'ajax_nonce' 	=> $ajax_nonce
			);

			$screen = get_current_screen();
			if ( $screen->post_type != 'cbz_slider' ) {
				return ;
			}

			/**
			 * Enqueue styles
			 */
			wp_enqueue_style( 'cbzwps_styles_css', CBZWPS_PLGN_URL . 'assets/css/cbzwps-styles'. $suffix .'.css', array(), CBZWPS_VERSION, 'all' );
			wp_enqueue_style( 'cbzwps_select2_css', CBZWPS_PLGN_URL . 'assets/css/select2.min.css', array(), CBZWPS_VERSION, 'all' );
			wp_enqueue_style( 'wp-color-picker' );
			
			/**
			 * Enqueue scripts
			 */
			wp_enqueue_script( 'cbzwps_select2_js', CBZWPS_PLGN_URL . 'assets/js/select2.min.js', array( 'jquery' ), CBZWPS_VERSION, true );
			wp_enqueue_script( 'cbzwps_jssor', CBZWPS_PLGN_URL . 'assets/js/slider/jssor.slider.mini.js', array( 'jquery' ), CBZWPS_VERSION, true );
			wp_enqueue_script( 'cbzwps_preview', CBZWPS_PLGN_URL . 'assets/js/cbzwps-slider-preview'. $suffix .'.js', array( 'jquery' ), CBZWPS_VERSION, true );
			wp_enqueue_script( 'cbzwps_main_js', CBZWPS_PLGN_URL . 'assets/js/cbzwps-main'. $suffix .'.js', array( 'jquery', 'wp-color-picker', 'cbzwps_select2_js' ), CBZWPS_VERSION, true );
			
			/**
			 * Scripts localization.
			 */
			wp_localize_script( 'cbzwps_main_js', 'cbzwps', $l10n );
			
			$slider_l10n 		= array(
				'ajaxUrl' 			=> admin_url( 'admin-ajax.php' )
			);
			wp_localize_script( 'cbzwps_preview', 'cbzwps_preview', $slider_l10n );
		}

		/**
		 * Adds submenu for shortcode attributes.
		 * 
		 * @category function
	 	 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_admin_menu() {
			add_submenu_page( 'edit.php?post_type=cbz_slider', __( 'Shortcode Attributes', CBZWPS_TXTDOMAIN ), __( 'Shortcode Attributes', CBZWPS_TXTDOMAIN ), 'manage_options', 'cbzwps_atts', array( $this, 'cbzwps_shortcode_atts' ) );
		}

		/**
		 * Renders shortcode's attributes.
		 * 
		 * @category function
	 	 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_shortcode_atts() {
			require_once( CBZWPS_PLGN_PATH . 'includes/cbzwps-shortcode-atts.php' );
		}

		/**
		 * Renders success messages on `admin_notices` hook.
		 * 
		 * @param  string $msg 
		 * @category function
	 	 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_success( $msg = '' ) {
			if ( ! isset( $_SESSION[ 'cbzwps_success' ] ) ) {
				return;
			}
			?>

			<div class="updated notice is-dismissible">
				<p><?php echo $msg; ?></p>
			</div>
			<?php
			unset( $_SESSION[ 'cbzwps_success' ] );
		}

		/**
		 * Renders error messages on `admin_notices` hook.
		 * 
		 * @param  string $msg [description]
		 * @category function
	 	 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_error( $msg = '' ) {
			if ( ! isset( $_SESSION[ 'cbzwps_error' ] ) ) {
				return;
			}
			?>

			<div class="error notice is-dismissible">
				<p><?php echo $msg; ?></p>
			</div>
			<?php
			unset( $_SESSION[ 'cbzwps_error' ] );
		}


		/**
		 * Seacrh for posts by query posted by users.
		 * 
		 * @category function
	 	 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_get_query_posts() {
			check_ajax_referer( 'cbzwps_seurity_nonce', 'nonce' );

			$query 		= sanitize_text_field( $_POST[ 'q' ] );
			$post_type 	= sanitize_text_field( $_POST[ 'post_type' ] );

			/**
			 * If no query found, return back.
			 */
			if ( empty( $query ) ) {
				wp_send_json_error( __( 'Please input something first.', CBZWPS_TXTDOMAIN ) );
				wp_die();
			}

			$found_items = $this->cbzwps_search_post( $query, $post_type );
			if ( empty( $found_items ) ) {
				wp_send_json_error( __( 'No match found!!', CBZWPS_TXTDOMAIN ) );
				wp_die();
			}

			wp_send_json_success( $found_items );
			wp_die();
		}

		/**
		 * Searches for posts by query string, posted by users.
		 * 
		 * @param  string $term      
		 * @param  string $post_type 
		 * @return array  Returns found data array on success, Returns empty array when nothing found.
		 * @category function
	 	 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_search_post( $term = '', $post_type = 'post' ) {
			if ( empty( $term ) ) {
				return false;
			}
			
			global $wpdb;
			$like_term 	= '%' . $wpdb->esc_like( $term ) . '%';
			$meta_query = cbzwps_get_meta_query( $term, $post_types );

			if ( is_numeric( $term ) ) {
				$query = $wpdb->prepare( "
					SELECT ID FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
					WHERE posts.post_status = 'publish'
					AND (
						posts.post_parent = %s
						OR posts.ID = %s
						OR posts.post_title LIKE %s
						{$meta_query}
					)
				", $term, $term, $term );
			} else {
				$query = $wpdb->prepare( "
					SELECT ID FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
					WHERE posts.post_status = 'publish'
					AND (
						posts.post_title LIKE %s
						or posts.post_content LIKE %s
						{$meta_query}
					)
				", $like_term, $like_term );
			}

			$query .= " AND posts.post_type IN ('" . implode( "','", array_map( 'esc_sql', array( $post_type ) ) ) . "')";

			$posts          = array_unique( $wpdb->get_col( $query ) );
			$found_products = cbzwps_get_searched_post_json_array( $posts, $post_type );

			$found_products = apply_filters( 'cbzwps_json_search_found_post', $found_products );

			return $found_products;
		}

		/**
		 * Difnes the pugin's constants
		 * 
		 * @category function
		 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_define_constants() {
			if ( empty( $this->constants ) ) {
				return;
			}

			/**
			 * Traverses the constants array.
			 * @category loop
			 */
			foreach ( $this->constants as $name => $value ) {
				if ( empty( $name ) or empty( $value ) ) {
					continue;
				}

				define( $name, $value );
			}
		}

		/**
		 * Adds CBz logo into admin footer
		 * 
		 * @category function
		 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_in_admin_footer() {
			$screen = get_current_screen();
			if ( $screen->post_type != 'cbz_slider' ) {
				return ;
			}

			// echo '<img class="cbzwps_admin_footer_img" src="'. CBZWPS_PLGN_URL . 'assets/images/cbz-logo.png">';
		}

		/**
		 * Changes the admin footer text.
		 * 
		 * @category function
		 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_admin_footer_text( $footer_text ) {
			$screen = get_current_screen();
			if ( $screen->post_type != 'cbz_slider' ) {
				return $footer_text;
			}

			$text = sprintf( __( 'Thanks for using the <strong>Wp Post Slider - CBz</strong>.<br> Build and customize more with <a href="%s" target="_blank">CBz</a>.' ), __( 'http://cloudberriez.com/' ) );

			return '<span id="footer-thankyou">' . $text . '</span>';
		}

		/**
		 * Loads plugin's textdomain.
		 * 
		 * @category function
		 * @author CloudBerriez <support@cloudberriez.com>
		 */
		public function cbzwps_load_text_domain() {

			$locale = apply_filters( 'plugin_locale', get_locale(), CBZWPS_TXTDOMAIN );

			load_textdomain( CBZWPS_TXTDOMAIN, CBZWPS_PLGN_PATH . 'languages/' . CBZWPS_TXTDOMAIN . '-' . $locale . '.mo' );
			
			load_plugin_textdomain( CBZWPS_TXTDOMAIN, false, plugin_basename( dirname ( __FILE__ ) ) . '../languages' );
		}
	}

	$_GLOBALS[ 'Wp_Posts_Slider' ] = new Wp_Posts_Slider();
endif;
