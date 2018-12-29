(function( $ ) {
	$( document ).ready(function() {
		if ( $( 'body' ).hasClass( 'post-type-portfolio' ) || $( 'body' ).hasClass( 'post-type-gallery' ) || $( 'body' ).hasClass( 'post-new-php' ) || $( 'body' ).hasClass( 'post-php' ) ) {
			/*	function to send Ajax request to portfolio */
			var rttchr_media_attach = function( post_id, thumb_id, unattachId ) {
				wp.media.post( 'add_attachment_item', {
					post_id:		post_id,
					thumbnail_id:	thumb_id,
					unattachId:		unattachId,
					nonce:			rttchr.nonce
				}).done( function ( html ) {
					$( '#rttchr_metabox_in_posts' ).find( '.inside' ).html( html );
				});
			};
			/*	function to send Ajax request to pgallery */
			var rttchr_gallery_attach = function( post_id, thumb_id, unattachId ) {
				wp.media.post( 'add_attachment_item_gallery', {
					post_id:		post_id,
					thumbnail_id:	thumb_id,
					unattachId:		unattachId,
					nonce:			rttchr.nonce
				}).done( function ( html ) {
					$( '#Upload-File' ).find( '#rttchr_preview_media' ).append( html );
				});
			};
			/*  Initialization windows loader Portfolio */
			var portfolio_uploader = wp.media( {
				id: 		'rttchr_portfolio_frame',
				frame: 		'select',
				title: 		rttchr.uploaderTitle,
				button: 	{ text: rttchr.uploaderButton },
				multiple: 	true
			});
			portfolio_uploader.on( 'select', function() {
				var attachments = [];
				portfolio_uploader.state().get( 'selection' ).forEach(function( i ) {
					attachments.push( i.id );
				} );
				rttchr_media_attach( wp.media.view.settings.post.id, attachments );
			});			
			/*  Initialization windows loader Gallery */
			var gallery_uploader = wp.media( {
				id:			'rttchr_portfolio_frame',
				title:		rttchr.uploaderTitle,
				button:		{ text: rttchr.uploaderButton },
				multiple: true
				} );
			gallery_uploader.on( 'select', function() {
				var attachments = [];
				gallery_uploader.state().get( 'selection' ).forEach(function( i ) {
					attachments.push( i.id );
				} );
				rttchr_gallery_attach( wp.media.view.settings.post.id, attachments );
			});
			/* Call media window for re-attacher in page portfolio */		
			$( '#wp-content-media-buttons' ).on( 'click', '#rttchr-attach-media-item', function( e ) {
				e.preventDefault();
				e.stopPropagation();
				portfolio_uploader.open();	
			} );
			/* Action to click  unattach in metabox on page portfolio */
			$( '#rttchr_metabox_in_posts' ).on( 'click', '.rttchr-unattach-media-item', function( e ) {
				e.preventDefault();
				e.stopPropagation();
				var unattachId = $( this ).attr( "id" );
				rttchr_media_attach( wp.media.view.settings.post.id, -1, unattachId );
			});
			/* Call media window for re-attacher in page gallery */		
			$( '#rttchr-gallery-media-buttons' ).on( 'click', '#rttchr-attach-media-item', function( e ) {
				e.preventDefault();
				e.stopPropagation();	
				gallery_uploader.open();
			});
			/* Action to click "don`t attach" for re-attacher in page gallery */
			$( '#rttchr_preview_media' ).on( 'click', '.rttchr-noattach-media-item', function( e ) {
				e.preventDefault();
				e.stopPropagation();	
				$( this ).parent().remove();
			});
		}	
	});	
})( jQuery );
/* Create preview on a gallery page */
function rttchr_img_unattach( id ) {
	(function( $ ) {
		$( '#' + id ).hide();
		if ( $( "div" ).is( "#img_unattach" ) ) {
			$( '#img_unattach' ).append( '<input type="hidden" name="img_unattach[]" value="' + id + '" />' );
		}
		else {
			$( '#delete_images' ).after( '<div id="img_unattach"></div>' );
			$( '#img_unattach' ).append( '<input type="hidden" name="img_unattach[]" value="' + id + '" />' );
		}
	})( jQuery );
}
/* Create notice on a portfolio page */
function rttchr_portfolio_notice_wiev( data_id ) {
	(function( $ ) {
		/*	function to send Ajax request to portfolio notice */
		rttchr_notice_media_attach = function( post_id, thumb_id, typenow ) {
			$.ajax({
				url: "/wp-admin/admin-ajax.php",
				type: "POST",
				data: "action=portfolio_notice&portfolio_post_id=" + post_id + "&portfolio_thumbnail_id=" + thumb_id + "&nonce=" + rttchr.nonce + "&post_type="+ typenow + "",
				success: function( html ) {				
					if ( undefined != html.data ){
						$( "#rttchr_portfolio_frame" ).find( "#rttchr_portfolio_notice" ).html( html.data );
					}
				}
			});	
		}
		rttchr_notice_media_attach( wp.media.view.settings.post.id, data_id, typenow ); 
	})( jQuery );
}
