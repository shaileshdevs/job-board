<?php
/**
 * The file to initialize.
 *
 * @package JobBoard
 */

namespace JobBoard;

if ( ! class_exists( '\JobBoard\Init' ) ) {
	/**
	 * The class to initialize.
	 */
	class Init {
		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->include_files();
		}

		/**
		 * Include the necessary files.
		 *
		 * @return void
		 */
		public function include_files() {
			// Register the custom post type Job.
			require_once dirname( JOB_BOARD_PLUGIN_FILE ) . '/includes/job/class-job-cpt-registration.php';
			// Register the custom post type Job Applicant.
			require_once dirname( JOB_BOARD_PLUGIN_FILE ) . '/includes/job-applicant/class-job-applicant-cpt-registration.php';

			// Include files on admin interface.
			if ( is_admin() ) {
				require_once dirname( JOB_BOARD_PLUGIN_FILE ) . '/includes/job-applicant/admin/class-job-applicant-custom-fields.php';
				require_once dirname( JOB_BOARD_PLUGIN_FILE ) . '/includes/job-applicant/admin/class-job-applicant-list-filter.php';
			}
		}
	}
}
