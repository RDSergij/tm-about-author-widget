/**
 * Events list
 */
//jQuery( document ).ready( initWidget );
jQuery( document ).on( 'widget-updated widget-added ready', initWidget );

/**
 * Initialization widget js
 *
 * @returns {undefined}
 */
function initWidget() {

	// Upload image
	jQuery( '.tm-about-author-form-widget input[type=button].upload_image_button' ).on( 'click', function( e ) {
		e.preventDefault();
		var _this = jQuery( this );
		var inputImage = _this.parents( '.tm-about-author-form-widget' ).find( '.custom-image-url' );
		var inputAvatar = _this.parents( '.tm-about-author-form-widget' ).find( '.avatar img' );
		var custom_uploader = wp.media( {
			title: 'Upload a Image',
			button: {
				text: 'Select',
			},
			multiple: false
		} );
		custom_uploader.on('select', function() {
			var imgurl = custom_uploader.state().get( 'selection' ).first().attributes.url;
			inputImage.val( imgurl ).trigger( 'change' );
			inputAvatar.attr( 'src', imgurl );
		});
		custom_uploader.open();
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
