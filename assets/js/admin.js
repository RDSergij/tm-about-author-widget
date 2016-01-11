jQuery( document ).ready( function( $ ) {

	// Declare image elements
	var inputImage = jQuery( '#' + window.TMAboutAuthorWidgetParam.image );
	var inputAvatar = jQuery( '#' + window.TMAboutAuthorWidgetParam.avatar + ' img' );

	// Init Cherry Api
	window.CHERRY_API.ui_elements.switcher.init( jQuery( 'body' ) );

	// Upload image
	$( document ).on( "click", ".upload_image_button", function() {

		jQuery.data( document.body, 'prevElement', $( this ).prev() );

		window.send_to_editor = function( html ) {
			var imgurl = jQuery( 'img', html ).attr( 'src' );

			if ( inputImage != undefined && inputImage != '' )
			{
				inputImage.val( imgurl );
				inputAvatar.attr( 'src', imgurl );
			}

			tb_remove();
		};

		tb_show( '', 'media-upload.php?type=image&TB_iframe=true' );
		return false;
	});

	// Delete image
	$( document ).on( "click", ".delete_image_url", function() {
		var default_avatar = inputAvatar.attr( 'default_image' );
		inputAvatar.attr( 'src', default_avatar );
		inputImage.val( '' );
	});
});