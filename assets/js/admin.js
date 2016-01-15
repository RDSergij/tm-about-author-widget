/**
 * Events list
 */
jQuery( document ).on( 'widget-updated widget-added ready', initWidget );

/**
 * Initialization widget js
 *
 * @returns {undefined}
 */
function initWidget() {

	// Upload image
	jQuery( '.tm-about-author-form-widget input[type=button].upload_image_button' ).on( 'click', function( e ) {
		var _this = jQuery( this );
		var inputImage = _this.parents( '.tm-about-author-form-widget' ).find( '.custom-image-url' );
		var inputAvatar = _this.parents( '.tm-about-author-form-widget' ).find( '.avatar img' );
		var customUploader = wp.media( {
			title: 'Upload a Image',
			button: {
				text: 'Select'
			},
			multiple: false
		} );

		e.preventDefault();

		customUploader.on( 'select', function() {
			var imgurl = customUploader.state().get( 'selection' ).first().attributes.url;
			inputImage.val( imgurl ).trigger( 'change' );
			inputAvatar.attr( 'src', imgurl );
		});
		customUploader.open();
	});

	// Delete image
	jQuery( '.delete_image_url' ).click( function() {
		var _this = jQuery( this );
		var inputImage = _this.parents( '.tm-about-author-form-widget' ).find( '.custom-image-url' );
		var inputAvatar = _this.parents( '.tm-about-author-form-widget' ).find( '.avatar img' );
		var defaultAvatar = inputAvatar.attr( 'default_image' );
		inputAvatar.attr( 'src', defaultAvatar );
		inputImage.val( '' ).trigger( 'change' );
	});
}
