<?php
/**
 * The file to register Job custom post type and taxonomies to it.
 *
 * @package JobBoard
 */

namespace JobBoard;

if ( ! class_exists( 'JobBoard\Job_CPT_Registration' ) ) {
	/**
	 * The class to register custom post type Job.
	 */
	class Job_CPT_Registration {
		/**
		 * Instance of the class.
		 *
		 * @var Job_CPT_Registration
		 */
		protected static $instance = null;

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_job_cpt' ) );
		}

		/**
		 * Return the single instance of the class.
		 *
		 * @return Job_CPT_Registration Return the singleton instance of the class.
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Register the custom post type Job.
		 *
		 * @return void
		 */
		public function register_job_cpt() {
			$labels = array(
				'name'               => _x( 'Jobs', 'Post type general name', 'job-board' ),
				'singular_name'      => _x( 'Job', 'Post type singular name', 'job-board' ),
				'menu_name'          => _x( 'Jobs', 'Admin Menu text', 'job-board' ),
				'name_admin_bar'     => _x( 'Job', 'Add New on Toolbar', 'job-board' ),
				'add_new'            => __( 'Add New', 'job-board' ),
				'add_new_item'       => __( 'Add New Job', 'job-board' ),
				'new_item'           => __( 'New Job', 'job-board' ),
				'edit_item'          => __( 'Edit Job', 'job-board' ),
				'view_item'          => __( 'View Job', 'job-board' ),
				'all_items'          => __( 'All Jobs', 'job-board' ),
				'search_items'       => __( 'Search Jobs', 'job-board' ),
				'parent_item_colon'  => __( 'Parent Jobs:', 'job-board' ),
				'not_found'          => __( 'No jobs found.', 'job-board' ),
				'not_found_in_trash' => __( 'No jobs found in Trash.', 'job-board' ),
				'archives'           => _x( 'Job archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'job-board' ),
			);

			$args = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'job' ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'menu_icon'          => 'dashicons-clipboard',
				'show_in_rest'       => true,
				'supports'           => array( 'title', 'editor', 'revisions', 'author', 'custom-fields' ),
				'taxonomies'         => array( 'job_location', 'job_department' ),
			);

			register_post_type( 'job', $args );

			$this->register_job_taxonomies();
		}

		/**
		 * Register the Job Taxonomies Location and Department.
		 *
		 * @return void
		 */
		public function register_job_taxonomies() {
			$this->register_location_taxonomy();
			$this->register_department_taxonomy();
		}

		/**
		 * Register the Location taxonomy.
		 *
		 * @return void
		 */
		public function register_location_taxonomy() {
			$labels = array(
				'name'                       => _x( 'Locations', 'taxonomy general name', 'job-board' ),
				'singular_name'              => _x( 'Location', 'taxonomy singular name', 'job-board' ),
				'search_items'               => __( 'Search Locations', 'job-board' ),
				'popular_items'              => __( 'Popular Locations', 'job-board' ),
				'all_items'                  => __( 'All Locations', 'job-board' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => __( 'Edit Location', 'job-board' ),
				'update_item'                => __( 'Update Location', 'job-board' ),
				'add_new_item'               => __( 'Add New Location', 'job-board' ),
				'new_item_name'              => __( 'New Location Name', 'job-board' ),
				'separate_items_with_commas' => __( 'Separate locations with commas', 'job-board' ),
				'add_or_remove_items'        => __( 'Add or remove locations', 'job-board' ),
				'choose_from_most_used'      => __( 'Choose from the most used locations', 'job-board' ),
				'not_found'                  => __( 'No location found.', 'job-board' ),
				'menu_name'                  => __( 'Locations', 'job-board' ),
			);

			$args = array(
				'hierarchical'          => true,
				'labels'                => $labels,
				'show_in_rest'          => true,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
			);

			register_taxonomy(
				'job_location',
				'job',
				$args
			);
		}

		/**
		 * Register the Department taxonomy.
		 *
		 * @return void
		 */
		public function register_department_taxonomy() {
			$labels = array(
				'name'                       => _x( 'Departments', 'taxonomy general name', 'job-board' ),
				'singular_name'              => _x( 'Department', 'taxonomy singular name', 'job-board' ),
				'search_items'               => __( 'Search Departments', 'job-board' ),
				'popular_items'              => __( 'Popular Departments', 'job-board' ),
				'all_items'                  => __( 'All Departments', 'job-board' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => __( 'Edit Department', 'job-board' ),
				'update_item'                => __( 'Update Department', 'job-board' ),
				'add_new_item'               => __( 'Add New Department', 'job-board' ),
				'new_item_name'              => __( 'New Department Name', 'job-board' ),
				'separate_items_with_commas' => __( 'Separate departments with commas', 'job-board' ),
				'add_or_remove_items'        => __( 'Add or remove departments', 'job-board' ),
				'choose_from_most_used'      => __( 'Choose from the most used departments', 'job-board' ),
				'not_found'                  => __( 'No department found.', 'job-board' ),
				'menu_name'                  => __( 'Departments', 'job-board' ),
			);

			$args = array(
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_in_rest'          => true,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
			);

			register_taxonomy(
				'job_department',
				'job',
				$args
			);
		}
	}

	Job_CPT_Registration::get_instance();
}
