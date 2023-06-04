<?php
/**
 * The file to handle Job Applicant custom filter on list page.
 *
 * @package JobBoard
 */

namespace JobBoard;

if ( ! class_exists( 'JobBoard\Job_Applicant_List_Filter' ) ) {
	/**
	 * The class to handle the custom filter for Job Applicant post type.
	 */
	class Job_Applicant_List_Filter {
		/**
		 * Instance of the class.
		 *
		 * @var Job_Applicant_List_Filter
		 */
		protected static $instance = null;

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'restrict_manage_posts', array( $this, 'show_filter_by_job' ) );
			add_filter( 'parse_query', array( $this, 'filter_job_applicants_by_job' ) );
		}

		/**
		 * Return the single instance of the class.
		 *
		 * @return Job_Applicant_List_Filter Return the singleton instance of the class.
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Show the filter by jobs dropdown.
		 *
		 * @param string $post_type The post type.
		 *
		 * @return void
		 */
		public function show_filter_by_job( $post_type ) {
			if ( 'job_applicant' === $post_type ) {
				$current_value = isset( $_GET['jb_job_applicant_filter_by_job'] ) ? sanitize_text_field( wp_unslash( $_GET['jb_job_applicant_filter_by_job'] ) ) : 0;
				$jobs          = get_posts(
					array(
						'numberposts' => -1,
						'post_type'   => 'job',
						'post_status' => 'published',
					)
				);

				?>
				<select name="jb_job_applicant_filter_by_job">
					<option value="0"><?php echo esc_html__( 'Select Job', 'job-board' ); ?></option>
					<?php
					foreach ( $jobs as $job ) {
						?>
						<option value="<?php echo esc_attr( $job->ID ); ?>" <?php selected( $job->ID, $current_value ); ?> ><?php echo esc_html( $job->post_title . '(#' . $job->ID . ')' ); ?></option>
						<?php
					}
					?>
				</select>
				<?php
			}
		}

		/**
		 * Filter the job applicants by selected job.
		 *
		 * @param WP_Query $query The WP_Query object.
		 *
		 * @return void
		 */
		public function filter_job_applicants_by_job( $query ) {
			global $pagenow, $wpdb;

			$post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : '';
			$job_id    = isset( $_GET['jb_job_applicant_filter_by_job'] ) ? intval( $_GET['jb_job_applicant_filter_by_job'] ) : 0;

			if ( 'job_applicant' === $post_type && is_admin() && 'edit.php' === $pagenow && ! empty( $job_id ) && $query->is_main_query() ) {
				$job_applicant_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT post_id from ' . $wpdb->postmeta . ' WHERE meta_key = "jb_job_applicant_job_applied_id" AND meta_value = %d', $job_id ) );

				if ( empty( $job_applicant_ids ) ) {
					$job_applicant_ids = array( -1 );
				} elseif ( ! empty( $query->query_vars['post__in'] ) ) {
					$job_applicant_ids = array_intersect( $job_applicant_ids, $query->query_vars['post__in'] );
				}

				$query->query_vars['post__in'] = $job_applicant_ids;
			}
		}
	}

	Job_Applicant_List_Filter::get_instance();
}
