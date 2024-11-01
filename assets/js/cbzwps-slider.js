jQuery( document ).ready( function( $ ) {
    var cbz_slider      = {},
    width               = '',
    slider_shortcodes   = $( '.cbzwps_slides_wrapper' ),
    transitions         = {
        off             : {},
        fade            : { $Duration : 1200, $Opacity : 2},
        fade_in_L       : { $Duration : 1200, x : 0.3, $During:{$Left:[0.3,0.7]},$Easing:{$Left:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2},
        fade_in_R       : { $Duration : 1200, x : -0.3, $During:{$Left:[0.3,0.7]},$Easing:{$Left:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2},
        fade_in_corners : { $Duration : 1200, x : 0.3, y:0.3,$Cols:2,$Rows:2,$During:{$Left:[0.3,0.7],$Top:[0.3,0.7]},$ChessMode:{$Column:3,$Row:12},$Easing:{$Left:$Jease$.$InCubic,$Top:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2},
        rotate_hdbl_in  : { $Duration : 1200, x : 0.5, y:0.3,$Cols:2,$Zoom:1,$Rotate:1,$ChessMode:{$Column:15},$Easing:{$Left:$Jease$.$InCubic,$Top:$Jease$.$InCubic,$Zoom:$Jease$.$InCubic,$Opacity:$Jease$.$OutQuad,$Rotate:$Jease$.$InCubic},$Assembly:2049,$Opacity:2,$Round:{$Rotate:0.7}},
        doors           : { $Duration : 1500, x : 0.5, $Cols:2,$ChessMode:{$Column:3},$Easing:{$Left:$Jease$.$InOutCubic},$Opacity:2,$Brother:{ $Duration : 1500, $Opacity : 2}},
        extrud_in_strip : { $Duration : 1000, x : 0.2, $Delay:40,$Cols:12,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Easing:{$Left:$Jease$.$InOutExpo,$Opacity:$Jease$.$InOutQuad},$Assembly:260,$Opacity:2,$Outside:true,$Round:{$Top:0.5}},
        jump_in_straight: { $Duration : 1500, x : -1, y:-0.5,$Delay:50,$Cols:8,$Rows:4,$Formation:$JssorSlideshowFormations$.$FormationStraight,$Easing:{$Left:$Jease$.$Swing,$Top:$Jease$.$InJump},$Assembly:513,$Round:{$Top:1.5}},
        bounce_right    : { $Duration : 1000, x : 1, $Easing:$Jease$.$InBounce},
        bounce_down     : { $Duration : 1000, y : 1, $Easing:$Jease$.$InBounce},
    };
    if ( slider_shortcodes.length > 0 ) {
        slider_shortcodes.each( function( index, el ) {
            var $this       = $( this );
            width           = parseInt( $this.data( 'width' ) );

            var slider_id   = $this.data( 'id' ),
            visible_slide   = parseInt( $this.data( 'visible_slide' ) ),
            scroll_slide    = parseInt( $this.data( 'scroll_slide' ) ),
            options         = {
                $AutoPlay             : $this.data( 'autoplay' ) == 'disable' ? false : true,
                $SlideshowOptions     : {
                    $Class              : $JssorSlideshowRunner$,
                    $Transitions        : [ transitions[ $this.data( 'animation' ) ] ],
                    $TransitionsOrder   : 1,
                    $ShowLink           : true
                },
                $ArrowNavigatorOptions: {
                    $Class          : $JssorArrowNavigator$,
                    $ChanceToShow   : 1
                },
                $AutoPlaySteps        : scroll_slide,
                $PauseOnHover         : 3,
                $Opacity              : 2,
                $SlideWidth           : width / visible_slide,
                $Cols                 : visible_slide,
            };
            go_for_cbz_slider( slider_id, options );
        });
    }

    function go_for_cbz_slider( slider_id, options ) {
        if ( !slider_id || ( Object.keys( options ).length === 0 && options.constructor === Object ) ) {
            return false;
        }

        cbz_slider[ slider_id ] = new $JssorSlider$( 'cbzwps_slider_container_' + slider_id, options );
    }
    


	/*responsive code begin*/
    /*remove responsive code if you don't want the slider scales while window resizing*/
    function ScaleSlider() {
        for( var i in cbz_slider ) {
            if ( !cbz_slider[i] ) {
                continue;
            }

            var refSize = cbz_slider[i].$Elmt.parentNode.parentNode.parentNode.clientWidth;
            if ( refSize && refSize < width ) {
                refSize = Math.min( refSize, $( window ).width() );
                cbz_slider[i].$ScaleWidth( refSize );
            } else {
                window.setTimeout( ScaleSlider, 30 );
            }
        }
    }
    ScaleSlider();

    $( window ).bind( "load", ScaleSlider );
    $( window ).bind( "resize", ScaleSlider );
    $( window ).bind( "orientationchange", ScaleSlider );
    /*responsive code end*/
});
