<?php


namespace Phormig\Migrate;


class Migrate {
	public  $migration_successful;
	private $settings;


	/**
	 * Migrate constructor.
	 */
	public function __construct( $settings ) {

		$this->settings             = $settings;
		$this->migration_successful = false;

	}


	public function delete_first_portfolio_entry() {


		$title = 'First Portfolio Entry';
		$posts = get_posts(
			[
				'name'        => $title,
				'post_type'   => 'phort_post',
				'post_status' => '',
				'numberposts' => - 1,
			]
		);

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				wp_delete_post( $post->ID );
			}
		}

	}


	public function migrate() {

		$this->migrate_portfolio_post_type();
		$this->migrate_portfolio_categories();
		$this->migrate_menu();
		$this->delete_first_portfolio_entry();


		if ( ! empty( $this->settings['plugin'] ) ) {
			// Disable the old Portfolio Post Type
			deactivate_plugins( $this->settings['plugin'] );
		}


		// TRIPLE refresh rewrite rules
		flush_rewrite_rules( true );
		flush_rewrite_rules( true );
		add_action( 'shutdown', 'flush_rewrite_rules' );

		// Migration successful!
		$this->migration_successful = true;

		// Deactivate self
		deactivate_plugins( PHORMIG_PLUGIN_BASENAME );

		return true;
	}


	/**
	 * Migrate Post Type `portfolio` to `phort_post`
	 *
	 * Cannot do this with a DB Query because it might mess up meta configuration.
	 * Instead, get all posts and update their post type individually.
	 */
	function migrate_portfolio_post_type() {

		$posts = get_posts(
			[
				'post_type'   => $this->settings['post_type'],
				'numberposts' => - 1,
			]
		);

		foreach ( $posts as $post ) {
			if ( $post->post_type === $this->settings['post_type'] ) {

				// Migrate Post Meta
				$this->migrate_post_gallery( $post );
				$this->migrate_portfolio_subtitle( $post );


				// Trigger migration hooks:
				do_action( 'phormig_post_meta', $post, $this->settings );

				// Migrate Post Type
				$post->post_type = 'phort_post';
				wp_update_post( $post );


			}

		}


	}


	public function migrate_portfolio_subtitle( $post ) {

		$subtitle = get_post_meta( $post->ID, $this->settings['portfolio_subtitle_key'], true );

		if ( empty( $subtitle ) ) {
			return;
		}
		update_post_meta( $post->ID, 'phort_subtitle', $subtitle );

	}


	public function migrate_portfolio_categories() {


		global $wpdb;

		$wpdb->update( 'wp_term_taxonomy', [ 'taxonomy' => 'phort_post_category' ], [ 'taxonomy' => $this->settings['taxonomy'] ] );


	}


	public function migrate_menu() {

		global $wpdb;


		// Rename from `portfolio_category` to `phort_post_category`
		$wpdb->update(
			'wp_postmeta',
			[ 'meta_value' => 'phort_post_category' ],
			[ 'meta_value' => $this->settings['taxonomy'] ]
		);

		// Rename from `portfolio` to `phort_post`
		$wpdb->update(
			'wp_postmeta',
			[ 'meta_value' => 'phort_post' ],
			[ 'meta_value' => $this->settings['post_type'] ]
		);


	}


	public function migrate_post_gallery( $post ) {

		$image_ids = get_post_meta( $post->ID, $this->settings['gallery_key'], true );

		if ( ! $image_ids ) {

			?>

			<div class="notice notice-warning">
				Failed to migrate Gallery ID <?php echo $post->ID ?>: No images found in gallery
			</div>

			<?php

			return false;
		}
		/**
		 * Convert to Photography Portfolio Format
		 */
		$images = [];
		foreach ( $image_ids as $id ) {
			$images[ $id ] = wp_get_attachment_image_url( $id, 'full' );
		}

		update_post_meta( $post->ID, 'phort_gallery', $images );
	}


	public function valid_post_request() {

		return (
			// Post request is set
			! empty( $_POST )

			// Has the correct admin referer nonce
			&& check_admin_referer( PHORMIG_NONCE_KEY )

			// Not doing ajax
			&& false === ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		);
	}

}