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
	public function __construct( $category_slug = '' ) {

		$this->taxonomy = $category_slug;
		parent::__construct();

	}


	public function instructions() {

		$dynamic_url = admin_url( 'edit.php?taxonomy=' . $this->taxonomy );
		$epp_url     = admin_url( 'edit.php?post_type=phort_post&taxonomy=phort_post_category' );

		$error = "<div class='phormig-condition-unmet'>";

		if ( $this->requirement_status == 'warn' ) {
			$error .= "<p><b>If you're not using portfolio categories</b> - you can ignore this warning!</p>";
		}

		$error .= "<ol>
				<li>Have you already migrated the portfolio? <br> Check Easy Photography Portfolio posts: <a target='_blank' href='$epp_url'>{$epp_url}</a>
				<li>Do you have any <b>{$this->taxonomy}</b> categories? <br> You can check here: <a target='_blank' href='$dynamic_url'>{$dynamic_url}</a>
			</ol>
		</div>
		";

		return $error;


	}


	public function title() {

		$status = $this->requirement_status;

		if ( $status == 'pass' ) {
			$count = $this->get_term_count();

			return "Existing portfolio category count: <b>{$count}</b> ";
		}

		if ( $status === 'fail' ) {
			return "Taxonomy <b>{$this->taxonomy}</b> not found!";
		}

		if ( $status === 'warn' ) {
			return "You're missing portfolio categories in <b>{$this->taxonomy}</b>";
		}


	}


	public function check() {

		if ( empty( $this->taxonomy ) ) {
			return $this->ignore();

		}

		if ( ! taxonomy_exists( $this->taxonomy ) ) {
			return $this->fail();

		}

		if ( $this->get_term_count() == 0 ) {
			return $this->warn();
		}

		return $this->pass();


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