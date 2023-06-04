<?php
/**
 * The file to register Job Applicant custom post type.
 *
 * @package JobBoard
 */

namespace JobBoard;

if ( ! class_exists( 'JobBoard\Job_Applicant_CPT_Registration' ) ) {
	/**
	 * The class to register custom post type Job.
	 */
	class Job_Applicant_CPT_Registration {
		/**
		 * Instance of the class.
		 *
		 * @var Job_Applicant_CPT_Registration
		 */
		protected static $instance = null;

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_job_applicant_cpt' ) );
		}

		/**
		 * Return the single instance of the class.
		 *
		 * @return Job_Applicant_CPT_Registration Return the singleton instance of the class.
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Register the custom post type Job Applicant.
		 *
		 * @return void
		 */
		public function register_job_applicant_cpt() {
			$labels = array(
				'name'               => _x( 'Job Applicants', 'Post type general name', 'job-board' ),
				'singular_name'      => _x( 'Job Applicant', 'Post type singular name', 'job-board' ),
				'menu_name'          => _x( 'Job Applicants', 'Admin Menu text', 'job-board' ),
				'name_admin_bar'     => _x( 'Job Applicant', 'Add New on Toolbar', 'job-board' ),
				'add_new'            => __( 'Add New', 'job-board' ),
				'add_new_item'       => __( 'Add New Job Applicant', 'job-board' ),
				'new_item'           => __( 'New Job Applicant', 'job-board' ),
				'edit_item'          => __( 'Edit Job Applicant', 'job-board' ),
				'view_item'          => __( 'View Job Applicant', 'job-board' ),
				'all_items'          => __( 'All Job Applicants', 'job-board' ),
				'search_items'       => __( 'Search Jobs', 'job-board' ),
				'parent_item_colon'  => __( 'Parent Job Applicants:', 'job-board' ),
				'not_found'          => __( 'No job applicants found.', 'job-board' ),
				'not_found_in_trash' => __( 'No job applicants found in Trash.', 'job-board' ),
				'archives'           => _x( 'Job Applicant archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'job-board' ),
			);

			$args = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'job_applicant' ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'menu_icon'          => 'dashicons-networking',
				'show_in_rest'       => true,
				'supports'           => array( 'title', 'editor', 'custom-fields' ),
			);

			register_post_type( 'job_applicant', $args );
		}
	}

	Job_Applicant_CPT_Registration::get_instance();
}
