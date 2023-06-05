# Job Board

## What does this plugin do?
This plugin registers the post types Job and Job Applicant. The Job post type has the taxonomies Location and Departments.
The Job Applicant post type has the following custom fields:
 - Job Applied for
 - Email
 - Phone Number
 - Resume
 - Cover Letter

On the Job Applicants list page that is https://your_domain.com/wp-admin/edit.php?post_type=job_applicant, you can filter the Job Applicants by the Job they applied to. To filter the Job Applicants by the Job, you need to select the Job from the dropdown which is present beside the All Dates dropdown.

The plugin currently provides the backend dashboard or admin interface.

You may need to refresh the Permalinks. To do so, you need to go to Settings > Permalinks > click on the Save Changes button. When you Publish the Job or Job Applicant by clicking on the Publish button, you may get 404 Not Found error when you try to view the published Job or Job Applicant. In that case, you may need to refresh the Permalinks.
