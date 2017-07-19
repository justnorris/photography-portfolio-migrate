<?php


namespace Phormig\Migrate\Requirements;


class Categories_To_migrate extends Requirement {
	/**
	 * @var
	 */
	private $taxonomy;


	/**
	 * Categories_To_migrate constructor.
	 */
	public function __construct( $category_slug ) {

		parent::__construct();
		$this->taxonomy = $category_slug;
	}


	public function instructions() {

		$dynamic_url = admin_url( 'edit.php?taxonomy=' . $this->taxonomy );
		$epp_url     = admin_url( 'edit.php?post_type=phort_post&taxonomy=phort_post_category' );

		return "
		<div class='phormig-condition-unmet'>
			<p>
			<b>If you're not using portfolio categories</b> - you can ignore this warning!
			Looks like there are no portfolio categories to migrate in taxonomy <b>{$this->taxonomy}</b> <br>
			</p>
			
			<ol>
				<li>Make sure that the old version of the portfolio plugin is still active while migrating
				<li>Have you already migrated the portfolio? Check Easy Photography Portfolio posts: <a target='_blank' href='$epp_url'>{$epp_url}</a>
				<li>Do you have <b>{$this->taxonomy}</b> post type posts? You can check here: <a target='_blank' href='$dynamic_url'>{$dynamic_url}</a>
			</ol>
		</div>
		";


	}


	public function title() {

		$count = $this->get_term_count();
		if ( $this->requirement_is_met ) {
			return "Existing portfolio category count: <b>{$count}</b> ";
		}

		if ( ! taxonomy_exists( $this->taxonomy ) ) {
			return "Taxonomy <b>{$this->taxonomy}</b> not found!";
		}

		if ( $this->get_term_count() === 0 ) {
			return "Portfolio category count is <b>0</b>";
		}


	}


	public function check() {


		return (
			taxonomy_exists( $this->taxonomy )
			&&
			$this->get_term_count() > 0
		);
	}


	public function get_term_count() {

		$terms = get_terms(
			$this->taxonomy,
			[
				'hide_empty' => false,
			]
		);

		return count( $terms );

	}
}