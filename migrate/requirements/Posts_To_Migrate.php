<?php


namespace Phormig\Migrate\Requirements;


class Posts_To_Migrate extends Requirement {
	/**
	 * @var
	 */
	private $post_type;


	/**
	 * Posts_To_Migrate constructor.
	 */
	public function __construct( $post_type ) {

		parent::__construct();
		$this->post_type = $post_type;
	}


	public function instructions() {

		$dynamic_url = admin_url( 'edit.php?post_type=' . $this->post_type );
		$epp_url     = admin_url( 'edit.php?post_type=phort_post' );

		return "
		<div class='phormig-condition-unmet'>
			<p>
			Looks like there aren't any portfolio entries to migrate for post type <b>{$this->post_type}</b>
			</p>
			
			<ol>
				<li>Have you already migrated the portfolio? Check Easy Photography Portfolio posts: <a target='_blank' href='$epp_url'>{$epp_url}</a>
				<li>Do you have <b>{$this->post_type}</b> post type posts? You can check here: <a target='_blank' href='$dynamic_url'>{$dynamic_url}</a>
			</ol>
		</div>
		";


	}


	public function title() {

		$count = $this->get_entries();

		return "Existing portfolio entry count: <b>{$count}</b> ";
	}


	public function check() {


		return (
			post_type_exists( $this->post_type )
			&&
			$this->get_entries() > 0
		);
	}


	public function get_entries() {

		$posts = get_posts(
			[
				'post_type'      => $this->post_type,
				'posts_per_page' => - 1,
				'numberposts'    => - 1,
			]
		);

		return count( $posts );

	}
}