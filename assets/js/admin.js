/**
 * Events list
 */
jQuery( document ).ready( initWidget );
jQuery( document ).on( 'widget-updated widget-added ready', initWidget );

/**
 * Initialization widget js
 *
 * @returns {undefined}
 */
function initWidget() {

	// Upload image
	jQuery( '.upload_image_button' ).click( function() {
		var _this = jQuery( this );
		var inputImage = _this.parents( '.tm-about-author-form-widget' ).find( '.custom-image-url' );
		var inputAvatar = _this.parents( '.tm-about-author-form-widget' ).find( '.avatar img' );

		window.send_to_editor = function( html ) {

			var imgurl = jQuery( 'img', html ).attr( 'src' );

			inputImage.val( imgurl ).trigger( 'change' );;
			inputAvatar.attr( 'src', imgurl );

			window.tb_remove();
		};

		window.tb_show( '', 'media-upload.php?type=image&TB_iframe=true' );
		return false;
	});

	// Delete image
	jQuery( '.delete_image_url' ).click( function() {
		_this = jQuery( this );
		var inputImage = _this.parents( '.tm-about-author-form-widget' ).find( '.custom-image-url' );
		var inputAvatar = _this.parents( '.tm-about-author-form-widget' ).find( '.avatar img' );
		var defaultAvatar = inputAvatar.attr( 'default_image' );
		inputAvatar.attr( 'src', defaultAvatar );
		inputImage.val( '' ).trigger( 'change' );
	});
}
