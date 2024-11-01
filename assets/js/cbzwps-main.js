jQuery( document ).ready( function( $ ) {


	if ( $( '.cbzwps_select2' ).length > 0 ) {
		/**
		 * Apply select2 on `cbzwps_select2` class
		 */
		$( '.cbzwps_select2' ).select2({
			allowClear: true
		});
	}

	function apply_select2_ajax() {
		if ( $( document ).find( '.cbzwps_ajax_select2' ).length > 0 ) {
			$( document ).find( '.cbzwps_ajax_select2' ).select2({
				width: 'resolve',
				allowClear: true,
				ajax: {
					url 	: cbzwps.ajaxUrl,
					dataType: 'json',
					delay 	: 250,
					type 	: 'POST',
					data 	: function( params ) {
						var data = {
							q 			: params.term,
							nonce 		: cbzwps.ajax_nonce,
							post_type 	: $( this ).data( 'post_type' ),
							action 		: 'cbzwps_query_posts'
						};
						return data;
					},
					processResults: function( data, params ) {
						if ( data.success ) {
							var terms = [];
							$.each( data.data, function( id, text ) {
								terms.push({
									id: id,
									text: text
								});
							});
							return { results: terms };
						} else {
							return {
								results: []
							};
						}
					},
					cache: true
				},
				escapeMarkup: function( markup ) { 
					return markup; 
				},
				minimumInputLength 	: 3
			});
		}
	}
	apply_select2_ajax();

	if ( $( '.cbzwps_colorpicker' ).length > 0 ) {

		/**
		 * Apply colorpicker to class `cbzwps_colorpicker`
		 */
		$( '.cbzwps_colorpicker' ).wpColorPicker({
		    /**
		     * you can declare a default color here,
		     * or in the data-default-color attribute on the input
		     */
		    defaultColor: 'FFFFFF',

		    // a callback to fire whenever the color changes to a valid color
		    change: function(event, ui){},

		    // a callback to fire when the input is emptied or an invalid color
		    clear: function() {},

		    // hide the color picker controls on load
		    hide: true,

		    /**
		     * show a group of common colors beneath the square
		     * or, supply an array of colors to customize further
		     */
		    palettes: true
		});
	}

	$( document ).on( 'change', '#cbzwps_post_types', function() {
		var $this 			= $( this ),
		post_type 			= $this.val(),
		search_post_type 	= $( '#exlude_by_search' ).data( 'post_type' );

		if ( post_type == '' || post_type === '' || post_type === 'undefined' || post_type == 'undefined' ) {
			return false;
		}

		if ( ( $( '#exlude_by_search' ).val() != '' && $( '#exlude_by_search' ).val() != null ) || ( $( 'input[name="exlude_by_id"]' ).val() != '' && $( 'input[name="exlude_by_id"]' ).val() != null ) ) {
			if ( search_post_type != post_type ) {
				$confirm = confirm( 'If you change the post type setting the previous excluded items will be lost. Would you like to continue ?' );
				if ( ! $confirm ) {
					return false;
				}
			}
		}

		$( 'input[name="exlude_by_id"]' ).val( '' );
		$( '#exlude_by_search option:selected' ).prop( 'selected', false );
		$( '#exlude_by_search' ).data( 'post_type', post_type );
		apply_select2_ajax();
	});

	$( document ).on( 'change', '#cbzwps_exlude_by', function() {
		var $this = $( this ),
		post_type = $this.val();

		if ( post_type == '' || post_type === '' || post_type === 'undefined' || post_type == 'undefined' ) {
			return false;
		}

		$( '.cbzwps_exlude_by_val' ).hide();
		$( '#cbzwps_exlude_by_' + post_type ).show();
	});

	/**
	 * Switches to sliders setting tabs
	 */
	$( document ).on( 'click', '.cbzwps_tab_anchor', function() {
		var $this 	= $( this ),
		tab_name  	= $this.data( 'tab' );

		if ( !tab_name ) {
			return false;
		}

		/**
		 * Fetch element related to tab_name
		 * @type object
		 */
		var to_show = $( '.cbzwps_'+ tab_name +'_settings_content' );

		/**
		 * If element exicbz at document 
		 */
		if ( to_show.length > 0 ) {
			/**
			 * Make tabs active/deactive
			 */
			$( '.cbzwps_tab_anchor' ).removeClass( 'cbzwps_active_tab' );
			$this.addClass( 'cbzwps_active_tab' );
			
			/**
			 * Show/hide tabs content
			 */
			$( '.cbzwps_settings_tab_content' ).addClass( 'cbzwps_hide' );
			to_show.removeClass( 'cbzwps_hide' );
		}
	});

	if ( $( '#cbzwps_slide_layout select option:selected' ).val() == 'layout_4' ) {
		if ( $( '.layout_4_option' ).hasClass( 'cbzwps_hide' ) ) {
			$( '.layout_4_option' ).removeClass( 'cbzwps_hide' );
		}
	}

	$( '#cbzwps_slide_layout select' ).on( 'change', function() {
		var $this 	= $( this ),
		selected 	= $this.val();

		if ( $this.val() == 'layout_4' ) {
			if ( $( '.layout_4_option' ).hasClass( 'cbzwps_hide' ) ) {
				$( '.layout_4_option' ).removeClass( 'cbzwps_hide' );
			}
		} else {
			if ( ! $( '.layout_4_option' ).hasClass( 'cbzwps_hide' ) ) {
				$( '.layout_4_option' ).addClass( 'cbzwps_hide' );
			}
		}
	});
});