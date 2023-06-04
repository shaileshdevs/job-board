<?php
/**
 * The file to handle Job Applicant custom fields.
 *
 * @package JobBoard
 */

namespace JobBoard;

if ( ! class_exists( 'JobBoard\Job_Applicant_Custom_Fields' ) ) {
	/**
	 * The class to handle the custom fields.
	 */
	class Job_Applicant_Custom_Fields {
		/**
		 * Instance of the class.
		 *
		 * @var Job_Applicant_Custom_Fields
		 */
		protected static $instance = null;

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts_styles' ) );
			add_action( 'add_meta_boxes_job_applicant', array( $this, 'add_meta_box' ) );
			add_action( 'save_post_job_applicant', array( $this, 'save_meta' ) );
		}

		/**
		 * Return the single instance of the class.
		 *
		 * @return Job_Applicant_Custom_Fields Return the singleton instance of the class.
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Register scripts and styles.
		 */
		public function register_scripts_styles() {
			wp_register_script( 'jb-job-applicant-edit-script', plugin_dir_url( JOB_BOARD_PLUGIN_FILE ) . 'assets/js/admin/job-applicant-edit.js', array( 'jquery' ), '1.0.0', true );
		}

		/**
		 * Enqueue scripts and styles.
		 */
		public function enqueue_scripts() {
			wp_enqueue_media();
			wp_enqueue_script( 'jb-job-applicant-edit-script' );
		}

		/**
		 * To add the meta box.
		 *
		 * @return void
		 */
		public function add_meta_box() {
			add_meta_box(
				'jb_job_applicant_meta_box',
				__( 'The Applicant\'s data', 'job-board' ),
				array( $this, 'render_meta_box_content' ),
				'job_applicant',
				'advanced',
				'high'
			);
		}

		/**
		 * To render Meta Box content.
		 *
		 * @param WP_Post $post The current post object.
		 */
		public function render_meta_box_content( $post ) {
			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'jb_job_applicant_meta_box', 'jb_job_applicant_meta_box_nonce' );

			// Use get_post_meta to retrieve an existing value from the database.
			$job_applied_id = get_post_meta( $post->ID, 'jb_job_applicant_job_applied_id', true );
			$email          = get_post_meta( $post->ID, 'jb_job_applicant_email', true );
			$phone_number   = get_post_meta( $post->ID, 'jb_job_applicant_phone_number', true );

			$jobs = get_posts(
				array(
					'numberposts' => -1,
					'post_type'   => 'job',
					'post_status' => 'published',
				)
			);

			// Display the form, using the current value.
			?>
			<div>
				<div style="margin: 0.5em auto">
					<label for="jb_job_applicant_job_applied_id" style="width: 12%; display: inline-block;">
					<?php echo esc_html__( 'Job Applied for', 'job-board' ); ?>
					</label>
					<select id="jb_job_applicant_job_applied_id" name="jb_job_applicant_job_applied_id" style="width: 35%;">
						<option value="0"><?php echo esc_html__( 'Select Job', 'job-board' ); ?></option>
						<?php
						foreach ( $jobs as $job ) {
							?>
							<option value="<?php echo esc_attr( $job->ID ); ?>" <?php selected( $job->ID, $job_applied_id ); ?> ><?php echo esc_html( $job->post_title . '(#' . $job->ID . ')' ); ?></option>
							<?php
						}
						?>
					</select>
				</div>
				<div style="margin: 0.5em auto">
					<label for="jb_job_applicant_email" style="width: 12%; display: inline-block;">
						<?php echo esc_html__( 'Email', 'job-board' ); ?>
					</label>
					<input type="email" id="jb_job_applicant_email" name="jb_job_applicant_email" value="<?php echo esc_attr( $email ); ?>" style="width: 35%;" />
				</div>
				<div style="margin: 0.5em auto">
					<label for="jb_job_applicant_phone_number" style="width: 12%; display: inline-block;">
					<?php echo esc_html__( 'Phone Number', 'job-board' ); ?>
					</label>
					<input type="text" id="jb_job_applicant_phone_number" name="jb_job_applicant_phone_number" value="<?php echo esc_attr( $phone_number ); ?>" style="width: 35%;" />
				</div>
				<div style="margin: 0.5em auto">
					<?php
					$this->render_file_upload_field( $post, 'jb_job_applicant_resume_attachment_id', __( 'Resume', 'job-board' ) );
					?>
				</div>
				<div style="margin: 0.5em auto">
					<?php
					$this->render_file_upload_field( $post, 'jb_job_applicant_cover_letter_attachment_id', __( 'Cover Letter', 'job-board' ) );
					?>
				</div>
			</div>
			<?php

			$this->enqueue_scripts();
		}

		/**
		 * To render file upload field.
		 *
		 * @param WP_Post $post The current post object.
		 * @param string  $meta_key The meta key name.
		 * @param string  $field_label The field label.
		 *
		 * @return void.
		 */
		public function render_file_upload_field( $post, $meta_key, $field_label ) {
			$file_id   = get_post_meta( $post->ID, $meta_key, true );
			$file_url  = wp_get_attachment_url( $file_id );

			?>
			<div class="file-upload-field-wrapper">
				<label for="<?php echo esc_attr( $meta_key ); ?>" style="width: 12%; display: inline-block;">
					<?php echo esc_html( $field_label ); ?>
				</label>
				<input type="hidden" name="<?php echo esc_attr( $meta_key ); ?>" id="<?php echo esc_attr( $meta_key ); ?>" class="file_id_input" value="<?php echo esc_attr( $file_id ); ?>" style="width: 35%;" />
				<input type="url" class="file_url_input" value="<?php echo esc_attr( $file_url ); ?>" style="width: 35%;" disabled/>
				<a href="#" class="button jb-file-upload-button"><?php echo esc_html__( 'Upload', 'job-board' ); ?></a>
				<a href="#" class="button jb-file-remove-button"><?php echo esc_html__( 'Remove', 'job-board' ); ?></a>
			</div>
			<?php
		}

		/**
		 * Save the meta when the Job Applicant is saved.
		 *
		 * @param int $post_id The ID of the Job Applicant being saved.
		 */
		public function save_meta( $post_id ) {
			// Check if our nonce is set.
			if ( ! isset( $_POST['jb_job_applicant_meta_box_nonce'] ) ) {
				return $post_id;
			}

			$nonce = $_POST['jb_job_applicant_meta_box_nonce'];

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $nonce, 'jb_job_applicant_meta_box' ) ) {
				return $post_id;
			}

			/*
			* If this is an autosave, our form has not been submitted,
			* so we don't want to do anything.
			*/
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			// Check the user's permissions.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}

			// Sanitize the user input.
			$job_applied_id = isset( $_POST['jb_job_applicant_job_applied_id'] ) ? intval( ( $_POST['jb_job_applicant_job_applied_id'] ) ) : 0;
			$email          = isset( $_POST['jb_job_applicant_email'] ) ? sanitize_email( wp_unslash( $_POST['jb_job_applicant_email'] ) ) : '';
			$phone_number   = isset( $_POST['jb_job_applicant_phone_number'] ) ? sanitize_text_field( wp_unslash( $_POST['jb_job_applicant_phone_number'] ) ) : '';
			$resume_file_id = isset( $_POST['jb_job_applicant_resume_attachment_id'] ) ? intval( $_POST['jb_job_applicant_resume_attachment_id'] ) : 0;
			$cover_letter_file_id = isset( $_POST['jb_job_applicant_cover_letter_attachment_id'] ) ? intval( $_POST['jb_job_applicant_cover_letter_attachment_id'] ) : 0;


			// error_log( 'resume > '. $resume_file_id );
			// error_log( 'cl > '. $cover_letter_file_id );

			// Update the meta field.
			$this->update_post_meta_custom( $post_id, 'jb_job_applicant_job_applied_id', $job_applied_id );
			$this->update_post_meta_custom( $post_id, 'jb_job_applicant_email', $email );
			$this->update_post_meta_custom( $post_id, 'jb_job_applicant_phone_number', $phone_number );
			$this->update_post_meta_custom( $post_id, 'jb_job_applicant_resume_attachment_id', $resume_file_id );
			$this->update_post_meta_custom( $post_id, 'jb_job_applicant_cover_letter_attachment_id', $cover_letter_file_id );
		}

		/**
		 * Update or delete post meta.
		 * The meta key is deleted if the meta value is empty.
		 *
		 * @param int    $post_id The post id.
		 * @param string $meta_key The meta key to update or to delete.
		 * @param string $meta_value The value to be updated.
		 *
		 * @return mixed Return 1 if updated, 2 if deleted, false on failure.
		 */
		public function update_post_meta_custom( $post_id, $meta_key, $meta_value ) {
			if ( empty( $meta_value ) ) {
				// error_log('in f meta_key > '. $meta_key);
				// error_log('in f meta_value > '. $meta_value);
				$result = ( false === delete_post_meta( $post_id, $meta_key ) ) ? false : 2;
			} else {
				$result = ( false === update_post_meta( $post_id, $meta_key, $meta_value ) ) ? false : 1;
			}

			return $result;
		}
	}

	Job_Applicant_Custom_Fields::get_instance();
}
