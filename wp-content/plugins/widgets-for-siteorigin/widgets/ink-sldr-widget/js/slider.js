(
	function ( $ ) {

	$.fn.iwInitSlider = function(){
		"use strict";

		if( $( '.iw-so-slider-slide' ).length ) {
			$( '.iw-so-slider-slide' ).each( function() {
				var $max_width = $( this ).find( 'img' ).width();
				$( this ).find( '.iw-so-slide-content' ).css( { 'max-width': $max_width, 'margin': '0 auto' } );
				$( this ).find( '.iw-so-slide-caption' ).css( { 'max-width': $max_width, 'margin': '0 auto' } );
			} );
		}
	}

	}
)( jQuery );

jQuery( function ( $ ) {
	$(document).ready( function() {
		$( '.iw-so-slider' ).slick();
	} );
	$( document ).iwInitSlider();
} );
