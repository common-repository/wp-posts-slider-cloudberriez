jQuery( document ).ready( function( $ ) {
    var transitions = {
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

    var _SlideshowTransitions = [{$Duration:1800,x:1,y:0.2,$Delay:30,$Cols:10,$Rows:5,$Clip:15,$During:{$Left:[0.3,0.7],$Top:[0.3,0.7]},$SlideOut:true,$Reverse:true,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Easing:{$Left:$Jease$.$InOutSine,$Top:$Jease$.$OutWave,$Clip:$Jease$.$InOutQuad},$Assembly:2050,$Round:{$Top:1.3}}];
	var options = {
		$AutoPlay: cbzwps_preview.slider_autoplay,
		$SlideshowOptions: {
            $Class: $JssorSlideshowRunner$,
            $Transitions: [ transitions[ cbzwps_preview.slider_animation ] ],
            $TransitionsOrder: 1,
            $ShowLink: true
        },
        $ArrowNavigatorOptions: {
            $Class: $JssorArrowNavigator$,
            $ChanceToShow: 2
        },
        $AutoPlaySteps : cbzwps_preview.scroll_slide,
		$PauseOnHover : 3,
		$Opacity: 2,
        $SlideWidth: cbzwps_preview.slider_width / cbzwps_preview.visible_slide,
        $Cols : cbzwps_preview.visible_slide,
    };

    if ( $( '#slider1_container' ).length > 0 ) {
    	var jssor_slider1 = new $JssorSlider$( 'slider1_container', options );

    	/*responsive code begin*/
        /*remove responsive code if you don't want the slider scales while window resizing*/
        function ScaleSlider() {
            var refSize = jssor_slider1.$Elmt.parentNode.clientWidth;
            if ( refSize ) {
                refSize = Math.min( refSize, $( window ).width() );
                jssor_slider1.$ScaleWidth( refSize );
            } else {
                window.setTimeout( ScaleSlider, 30 );
            }
        }
        /*ScaleSlider();
        $( window ).bind( "load", ScaleSlider );
        $( window ).bind( "resize", ScaleSlider );
        $( window ).bind( "orientationchange", ScaleSlider );*/
        /*responsive code end*/
    }

    $( document ).on( 'change', '#cbzwps_preview_animation', function() {
        var $this           = $( this ),
        chosen_animation    = $this.val();

        jssor_slider1.$SetSlideshowTransitions([ transitions[ chosen_animation ] ] );
    });
});
