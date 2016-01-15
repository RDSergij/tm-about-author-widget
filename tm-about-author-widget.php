<?php
/**
 * Plugin Name:  TM About Author Widget
 * Plugin URI: https://github.com/RDSergij
 * Description: About author widget
 * Version: 1.0.0
 * Author: Osadchyi Serhii
 * Author URI: https://github.com/RDSergij
 * Text Domain: photolab-base-tm
 *
 * @package TM_About_Author_Widget
 *
 * @since 1.0.0
 */

if ( ! class_exists( 'TM_About_Author_Widget' ) ) {
	/**
	 * Set constant text domain.
	 *
	 * @since 1.0.0
	 */
	if ( ! defined( 'PHOTOLAB_BASE_TM_ALIAS' ) ) {
		define( 'PHOTOLAB_BASE_TM_ALIAS', 'photolab-base-tm' );
	}

	/**
	 * Set constant path of text domain.
	 *
	 * @since 1.0.0
	 */
	if ( ! defined( 'PHOTOLAB_BASE_TM_PATH' ) ) {
		define( 'PHOTOLAB_BASE_TM_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Adds TM_About_Author_Widget widget.
	 */
	class TM_About_Author_Widget extends WP_Widget {

		/**
		 * Default settings
		 *
		 * @var type array
		 */
		private $instance_default = array();
		/**
		 * Register widget with WordPress.
		 */
		function __construct() {
			parent::__construct(
				'tm_about_author_widget', // Base ID
				__( 'TM About Author Widget', PHOTOLAB_BASE_TM_ALIAS ),
				array( 'description' => __( 'About author widget', PHOTOLAB_BASE_TM_ALIAS ) )
			);
			// Set default settings
			$this->instance_default = array(
				'title'		=> __( 'About me', PHOTOLAB_BASE_TM_ALIAS ),
				'user_id'	=> 1,
				'image'		=> '',
				'text_link'	=> __( 'Read more', PHOTOLAB_BASE_TM_ALIAS ),
				'url'		=> home_url(),
			);

			// disable WordPress sanitization to allow more than just $allowedtags from /wp-includes/kses.php
			remove_filter( 'pre_user_description', 'wp_filter_kses' );
			// add sanitization for WordPress posts
			add_filter( 'pre_user_description', 'wp_filter_post_kses' );
		}

		/**
		 * Load languages
		 *
		 * @since 1.0.0
		 */
		public function include_languages() {
			load_plugin_textdomain( PHOTOLAB_BASE_TM_ALIAS, false, PHOTOLAB_BASE_TM_PATH );
		}

		/**
		 * Frontend view
		 *
		 * @param type $args array.
		 * @param type $instance array.
		 */
		public function widget( $args, $instance ) {
			foreach ( $this->instance_default as $key => $value ) {
				$$key = ! empty( $instance[ $key ] ) ? $instance[ $key ] : $value;
			}

			// Custom js
			wp_register_script( 'tm-about-author-script-frontend', plugins_url( 'assets/js/frontend.min.js', __FILE__ ), '', '', true );
			wp_enqueue_script( 'tm-about-author-script-frontend' );

			// Custom styles
			wp_register_style( 'tm-about-author-frontend', plugins_url( 'assets/css/frontend.min.css', __FILE__ ) );
			wp_enqueue_style( 'tm-about-author-frontend' );

			$user_info = get_userdata( $user_id );

			if ( ! empty( $user_info->user_email ) ) {
				$gravatar_url = get_avatar_url( $user_info->user_email, array( 'size' => 512 ) );

				if ( ! empty( $image ) ) {
					$main_avatar = $image;
				} elseif ( ! empty( $gravatar_url ) ) {
					$main_avatar = $gravatar_url;
				}

				require __DIR__ . '/views/frontend.php';
			}
		}

		/**
		 * Create admin form for widget
		 *
		 * @param type $instance array.
		 */
		public function form( $instance ) {
			foreach ( $this->instance_default as $key => $value ) {
				$$key = ! empty( $instance[ $key ] ) ? $instance[ $key ] : $value;
			}

			wp_enqueue_media();

			// Ui cherri api
			wp_register_script( 'tm-about-author-script-api', plugins_url( 'assets/js/cherry-api.js', __FILE__ ), array( 'jquery' ) );
			wp_localize_script( 'tm-about-author-script-api', 'cherry_ajax', wp_create_nonce( 'cherry_ajax_nonce' ) );
			wp_localize_script( 'tm-about-author-script-api', 'wp_load_style', null );
			wp_localize_script( 'tm-about-author-script-api', 'wp_load_script', null );
			wp_enqueue_script( 'tm-about-author-script-api' );

			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'thickbox' );

			// Custom styles
			wp_register_style( 'tm-about-author-admin', plugins_url( 'assets/css/admin.min.css', __FILE__ ) );
			wp_enqueue_style( 'tm-about-author-admin' );

			// Custom script
			wp_register_script( 'tm-about-author-admin', plugins_url( 'assets/js/', __FILE__ ) . 'admin.min.js', array( 'jquery' ) );
			wp_localize_script( 'tm-about-author-admin', 'TMAboutAuthorWidgetParam', array( 'image' => $this->get_field_id( 'image' ), 'avatar' => $this->get_field_id( 'avatar' ) ) );
			wp_enqueue_script( 'tm-about-author-admin' );

			wp_enqueue_style( 'thickbox' );

			// include ui-elements
			require_once __DIR__ . '/admin/lib/ui-elements/ui-text/ui-text.php';
			require_once __DIR__ . '/admin/lib/ui-elements/ui-select/ui-select.php';

			$title_field = new UI_Text(
							array(
									'id'            => $this->get_field_id( 'title' ),
									'type'          => 'text',
									'name'          => $this->get_field_name( 'title' ),
									'placeholder'   => __( 'New title', PHOTOLAB_BASE_TM_ALIAS ),
									'value'         => $title,
									'label'         => __( 'Title widget', PHOTOLAB_BASE_TM_ALIAS ),
							)
					);
			$title_html = $title_field->render();

			$users_list = get_users();
			foreach ( $users_list as $user ) {
				$users[ $user->ID ] = $user->display_name;
			}

			$users_field = new UI_Select(
							array(
								'id'				=> $this->get_field_id( 'user_id' ),
								'name'				=> $this->get_field_name( 'user_id' ),
								'value'				=> $user_id,
								'options'			=> $users,
							)
						);
			$users_html = $users_field->render();

			$url_field = new UI_Text(
							array(
									'id'			=> $this->get_field_id( 'url' ),
									'type'			=> 'url',
									'name'			=> $this->get_field_name( 'url' ),
									'placeholder'	=> __( 'detail url', PHOTOLAB_BASE_TM_ALIAS ),
									'value'			=> $url,
									'label'			=> __( 'Detail url', PHOTOLAB_BASE_TM_ALIAS ),
							)
					);
			$url_html = $url_field->render();

			$text_link_field = new UI_Text(
							array(
									'id'			=> $this->get_field_id( 'text_link' ),
									'type'			=> 'text',
									'name'			=> $this->get_field_name( 'text_link' ),
									'placeholder'	=> __( 'link text', PHOTOLAB_BASE_TM_ALIAS ),
									'value'			=> $text_link,
									'label'			=> __( 'Link text', PHOTOLAB_BASE_TM_ALIAS ),
							)
					);
			$text_link_html = $text_link_field->render();

			$upload_file_field = new UI_Text(
							array(
									'id'			=> $this->get_field_id( 'upload_image_button' ),
									'class'			=> 'upload_image_button button-image',
									'type'			=> 'button',
									'name'			=> $this->get_field_name( 'upload_image_button' ),
									'value'			=> __( 'Upload image', PHOTOLAB_BASE_TM_ALIAS ),
							)
					);
			$upload_html = $upload_file_field->render();

			$image_url_field = new UI_Text(
							array(
									'id'			=> $this->get_field_id( 'image' ),
									'class'			=> ' custom-image-url',
									'type'			=> 'hidden',
									'name'			=> $this->get_field_name( 'image' ),
									'value'			=> $image,
							)
					);
			$image_html = $image_url_field->render();

			$delete_image_url_field = new UI_Text(
							array(
									'id'			=> $this->get_field_id( 'delete_image' ),
									'class'			=> 'delete_image_url button-image',
									'type'			=> 'button',
									'name'			=> $this->get_field_name( 'delete_image' ),
									'value'			=> __( 'Delete image', PHOTOLAB_BASE_TM_ALIAS ),
							)
					);
			$delete_image_html = $delete_image_url_field->render();

			$user_info = get_userdata( $user_id );

			$default_avatar = plugins_url( 'images/', __FILE__ ) . 'default-avatar.png';
			if ( ! empty( $image ) ) {
				$main_avatar = $image;
			} else {
				$main_avatar = $default_avatar;
			}

			// show view
			require 'views/widget-form.php';
		}

		/**
		 * Update settings
		 *
		 * @param type $new_instance array.
		 * @param type $old_instance array.
		 * @return type array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			foreach ( $this->instance_default as $key => $value ) {
				$instance[ $key ] = ! empty( $new_instance[ $key ] ) ? $new_instance[ $key ] : $value;
			}

			return $instance;
		}
	}

	/**
	 * Register widget
	 */
	function register_tm_about_author_widget() {
		register_widget( 'tm_about_author_widget' );
	}
	add_action( 'widgets_init', 'register_tm_about_author_widget' );

}
