<?php


namespace Phormig\Migrate\Requirements;


class Categories_To_migrate extends Requirement {
	/**
	 * @var
	 */
	private $category_slug;


	/**
	 * Categories_To_migrate constructor.
	 */
	public function __construct( $category_slug ) {

		parent::__construct();
		$this->category_slug = $category_slug;
	}


	public function instructions() {

		$dynamic_url = admin_url( 'edit.php?taxonomy=' . $this->category_slug );
		$epp_url     = admin_url( 'edit.php?post_type=phort_post&taxonomy=phort_post_category' );

		return "
		<div class='phormig-condition-unmet'>
			<p>
			<b>If you're not using portfolio categories</b> - you can ignore this warning!
			Looks like there are no portfolio categories to migrate in taxonomy <b>{$this->category_slug}</b> <br>
			</p>
			
			<ol>
				<li>Make sure that the old version of the portfolio plugin is still active while migrating
				<li>Have you already migrated the portfolio? Check Easy Photography Portfolio posts: <a target='_blank' href='$epp_url'>{$epp_url}</a>
				<li>Do you have <b>{$this->category_slug}</b> post type posts? You can check here: <a target='_blank' href='$dynamic_url'>{$dynamic_url}</a>
			</ol>
		</div>
		";


	}


	public function title() {

		$count = $this->get_terms();

		return "Existing portfolio category count: <b>{$count}</b> ";
	}


	public function check() {

		return $this->get_terms() > 0;
	}


	public function get_terms() {

		$terms = get_terms(
			$this->category_slug,
			[
				'hide_empty' => false,
			]
		);

		return count( $terms );

	}
}