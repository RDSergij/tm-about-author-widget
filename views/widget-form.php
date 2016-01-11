<?php
/**
 * Admin view
 *
 * @package TM_Posts_Widget
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<!-- Widget Form -->
<div class="tm-about-author-form-widget">
	<p>
		<?php echo $title_html ?>
	</p>

	<p>
		<label for="user_id"><?php _e( 'Author', PHOTOLAB_BASE_TM_ALIAS ) ?></label>
		<?php echo $users_html ?>
	</p>

	<p>
		<?php echo $url_html ?>
	</p>

	<p>
		<?php echo $text_link_html ?>
	</p>

	<p>
		<label><?php _e( 'Custom image', PHOTOLAB_BASE_TM_ALIAS ) ?></label><br/>
		<?php echo $upload_html ?>
		<?php echo $delete_image_html ?>
		<?php echo $image_html ?>
	</p>

	<p class="avatar" id="<?php echo $this->get_field_id( 'avatar' ) ?>">
		<img default_image="<?php echo $default_avatar ?>" src="<?php echo $main_avatar ?>">
	</p>

	<p>&nbsp;</p>
</div>
<!-- End widget Form -->
