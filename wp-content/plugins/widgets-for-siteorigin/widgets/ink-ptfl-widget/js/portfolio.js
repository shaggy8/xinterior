( function($) {

	$(document).ready( function() {

		$(function() {
			if( $( '.iw-so-folio-grid' ).length ) {
				$( '.iw-so-folio-grid' ).each( function() {
					$(this).find( '.iw-so-project-article' ).matchHeight({
						byRow: false,
						property: 'height',
						target: null,
						remove: false
					});
				} );
			}
		});

	});

})( jQuery );
