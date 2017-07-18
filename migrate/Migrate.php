<?php


namespace Phormig\Migrate;


class Migrate {


	/**
	 * Migrate constructor.
	 */
	public function __construct() {

		// Don't migrate on ajax
		if ( ! is_admin() || defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}
	}


	public function migrate() {

		// Make sure our post types exist
		if ( ! post_type_exists( 'portfolio' ) || ! post_type_exists( 'phort_post' ) ) {
			return false;
		}


		$this->migrate_portfolio_post_type();
		$this->migrate_portfolio_categories();
		$this->migrate_menu();


		// Disable the old Portfolio Post Type
		deactivate_plugins( 'village-portfolio-post-type/village-portfolio-post-type.php' );

		// Double refresh rewrite rules
		flush_rewrite_rules( true );
		flush_rewrite_rules( true );

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
				'post_type'   => 'portfolio',
				'numberposts' => - 1,
			]
		);

		foreach ( $posts as $post ) {
			if ( $post->post_type === 'portfolio' ) {

				// Migrate Post Meta
				$this->migrate_post_meta( $post );

				// Migrate Post Type
				$post->post_type = 'phort_post';
				wp_update_post( $post );


			}

		}


	}


	public function migrate_portfolio_categories() {


		global $wpdb;

		$wpdb->update( 'wp_term_taxonomy', [ 'taxonomy' => 'phort_post_category' ], [ 'taxonomy' => 'portfolio_category' ] );


	}


	public function migrate_menu() {

		global $wpdb;


		// Rename from `portfolio_category` to `phort_post_category`
		$wpdb->update(
			'wp_postmeta',
			[ 'meta_value' => 'phort_post_category' ],
			[ 'meta_value' => 'portfolio_category' ]
		);

		// Rename from `portfolio` to `phort_post`
		$wpdb->update(
			'wp_postmeta',
			[ 'meta_value' => 'phort_post' ],
			[ 'meta_value' => 'portfolio' ]
		);


	}


	public function migrate_post_meta( $post ) {

		$image_ids = get_post_meta( $post->ID, 'village_gallery', true );

		/**
		 * Convert to Photography Portfolio Format
		 */
		$images = [];
		foreach ( $image_ids as $id ) {
			$images[ $id ] = wp_get_attachment_image_url( $id, 'full' );
		}

		update_post_meta( $post->ID, 'phort_gallery', $images );
	}

}