<?php
/**
 * Frontend view
 *
 * @package TM_About_Author_Widget
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<div class="tm-about-author-widget">
	<div class="avatar">
		<img src="<?php echo $main_avatar ?>">
	</div>
	<div class="info">
		<h4>
			<?php echo $user_info->display_name; ?>
		</h4>
		<?php if ( $user_info->description ) : ?>
		<div class="description">
			<?php echo $user_info->description; ?>
		</div>
		<?php endif; ?>
	</div>
	<?php if ( $url ) : ?>
	<div class="read-more">
		<a href="<?php echo $url; ?>">
			<?php _e( 'Read more', PHOTOLAB_BASE_TM_ALIAS ) ?>
		</a>
	</div>
	<?php endif; ?>
</div>
