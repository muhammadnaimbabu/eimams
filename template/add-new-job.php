<?php

/**
 * Template Name: Add New Job
 */
$theme_root = dirname(__FILE__);
require_once($theme_root . "/../library/user-backend-main.php");
$kv_current_role = kv_get_current_user_role();
if (is_user_logged_in() && $kv_current_role == 'employer') {
	global $post, $current_user, $wp_roles, $wpdb, $enable_employer_subscription;
	$enable_employer_subscription = get_option('enable_employer_subscription');
	wp_get_current_user();
	$job_details = array();
	$job_details['user_email'] = $current_user->user_email;
	$job_details['user_displayname'] = $current_user->display_name;

	$post_slug = $post->post_name;
	kv_header();
	if ($post_slug == 'edit-job' && !isset($_GET['edit_id'])) {
		wp_die(' This is not a valid Page <a href=' . site_url('add-new-job') . ' > Click Here</a> to Add New Job ', 404);
	}
	if (isset($_GET['edit_id'])) {
		$post_author_id = get_post_field('post_author', $_GET['edit_id']);
		//echo $post->post_author;
		if ($post_author_id != $current_user->ID) {
			status_header(404);
			nocache_headers();
			include(get_query_template('404'));
			die();
		}
	}
	kv_top_nav();
	kv_leftside_nav();
	kv_section_start();


	//kv_subscribers_status_change();
	if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['submit_a_job'])) {
		$errors = new WP_Error();
		$fields = array(
			'title',
			'company_name',
			'address1',
			'address2',
			'city',
			'state_pro_reg',
			'website',
			'company_description',
			'employer_ref',
			'ad_start_date',
			'ad_close_date',
			'in_start_date',
			'usr_job_category',
			'usr_gen_job_category',
			'gender',
			'usr_qualification',
			'custom_qualification',
			'usr_types',
			'usr_yr_of_exp',
			'usr_madhab',
			'usr_madhab_shia',
			'usr_aqeeda',
			'usr_aqeeda_shia',
			'usr_zone',
			'sal_amount',
			'sal_period',
			'no_of_vacancy',
			'sa_option',
			'hours_per_week',
			'work_time',
			'job_duties',
			'experience_details',
			'pension_provision',
			'pension_provision_dropdown',
			'confidential',
			'accomodation',
			'accomodation-details',
			'other_information',
			'how_to_apply',
			'manual_apply_details',
			'monitoring_equalty',
			'equalty_statement',
			'pack_name',
			'eligible_work_in',
			'dbs',
			'dbs_description'
		);

		foreach ($fields as $field) {
			if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field]));
			else $posted[$field] = '';
		}

		$ad_close_date = date('Y-m-d', strtotime($posted['ad_close_date']));
		$other_information =  $posted['other_information'];
		$accomodation =  $posted['accomodation'];
		$confidential =  $posted['confidential'];

		// Validation start here

		if ($posted['address1'] == '' ||  $posted['address1'] == null)
			$errors->add('empty_address1', __('<strong>Notice</strong>: Please enter Company address1.', 'kv_project'));

		if ($enable_employer_subscription == 'yes') {
			if ($posted['pack_name'] >= 1)
				$pack_name =  $_POST['pack_name'];
			else if ($posted['pack_name'] == 0)
				$errors->add('empty_pack_name', __('<strong>Notice</strong>: Please Select a Pack before Submit.', 'kv_project'));
		}
		if ($posted['title'] != null)
			$title =  $posted['title'];
		else
			$errors->add('empty_title', __('<strong>Notice</strong>: Please enter job title.', 'kv_project'));

		$website =  $posted['website'];
		if (has_private_employer($current_user->ID) == false) {
			if ($posted['company_name'] != null)
				$company_name =  $posted['company_name'];
			else
				$errors->add('empty_company_name', __('<strong>Notice</strong>: Please enter your Company Name.', 'kv_project'));
		}

		if ($posted['ad_start_date'] != null) {
			if (kv_validateDate($posted['ad_start_date']))
				$ad_start_date = date('Y-m-d', strtotime($posted['ad_start_date']));
			else
				$errors->add('empty_ad_start_date', __('<strong>Notice</strong>: Please enter Valid Date.', 'kv_project'));
		} else
			$errors->add('empty_ad_start_date', __('<strong>Notice</strong>: Please enter Advertisement Start Date.', 'kv_project'));

		if ($posted['in_start_date'] != null) {
			if (kv_validateDate($posted['in_start_date']))
				$in_start_date = date('Y-m-d', strtotime($posted['in_start_date']));
			else
				$errors->add('empty_in_start_date', __('<strong>Notice</strong>: Please enter Valid Date.', 'kv_project'));
		} else
			$errors->add('empty_in_start_date', __('<strong>Notice</strong>: Please enter Interview Start Date.', 'kv_project'));

		if ($posted['usr_job_category'] == -1 &&  $posted['usr_gen_job_category'] == -1)
			$errors->add('empty_usr_job_category', __('<strong>Notice</strong>: Please Select a Job Category.', 'kv_project'));

		if ($posted['gender'] != null)
			$gender =  $posted['gender'];
		else
			$errors->add('empty_gender', __('<strong>Notice</strong>: Please Select a Gender.', 'kv_project'));

		if ($posted['usr_qualification'] != -1) {
			if ($posted['usr_qualification'] == 542 && $posted['custom_qualification'] == '')
				$errors->add('empty_usr_qualification', __('<strong>Notice</strong>: Please Enter your Job Qualification.', 'kv_project'));
			else
				$usr_qualification =  $posted['usr_qualification'];
		} else
			$errors->add('empty_usr_qualification', __('<strong>Notice</strong>: Please Select a Qualification.', 'kv_project'));

		if ($posted['usr_types'] != -1)
			$usr_types =  $posted['usr_types'];
		else
			$errors->add('empty_usr_types', __('<strong>Notice</strong>: Please Select a Type.', 'kv_project'));

		if ($posted['usr_yr_of_exp'] != -1)
			$usr_yr_of_exp =  $posted['usr_yr_of_exp'];
		else
			$errors->add('empty_usr_yr_of_exp', __('<strong>Notice</strong>: Please Select Your Year of Experience.', 'kv_project'));

		if ($posted['usr_gen_job_category'] == -1) {
			if ($posted['usr_madhab'] == -1 && $posted['usr_madhab_shia'] == -1)
				$errors->add('empty_usr_madhab', __('<strong>Notice</strong>: Please Select your Madhab.', 'kv_project'));
			elseif ($posted['usr_madhab'] != -1)
				$posted['usr_madhab_shia'] = -1;
			elseif ($posted['usr_madhab_shia'] != -1)
				$posted['usr_madhab'] = -1;

			if ($posted['usr_aqeeda'] == -1 && $posted['usr_aqeeda_shia'] == -1)
				$errors->add('empty_usr_aqeeda', __('<strong>Notice</strong>: Please Select your Aqeeda.', 'kv_project'));
			elseif ($posted['usr_aqeeda'] != -1)
				$posted['usr_aqeeda_shia'] = -1;
			elseif ($posted['usr_aqeeda_shia'] != -1)
				$posted['usr_aqeeda'] = -1;
		} else {
			$posted['usr_madhab'] = $posted['usr_aqeeda'] = $posted['usr_aqeeda_shia'] = $posted['usr_madhab_shia'] = -1;
		}

		if ($posted['eligible_work_in'] == -1)
			$errors->add('empty_eligible_work_in', __('<strong>Notice</strong>: Please Select Your Eligible Work Country.', 'kv_project'));

		if ($_POST['usr_language'] != -1 && $_POST['usr_language'] != null && $_POST['usr_language'] != '')
			$usr_language =  $_POST['usr_language'];
		else
			$errors->add('empty_usr_language', __('<strong>Notice</strong>: Please Select Your Language.', 'kv_project'));

		if ($posted['usr_zone'] != -1)
			$usr_zone =  $posted['usr_zone'];
		else
			$errors->add('empty_usr_zone', __('<strong>Notice</strong>: Please Select Your Zone.', 'kv_project'));


		//if ($posted['sal_amount'] != null &&  $posted['sal_period'] != -1 && $posted['sa_option'] != -1){
		if (($posted['sal_amount'] != null &&  $posted['sal_period'] != -1) || $posted['sa_option'] != -1) {
			$sal_amount =  $posted['sal_amount'];
		} else
			$errors->add('empty_sal_amount', __('<strong>Notice</strong>: Please Select Salary amount, Term and Its Options. It is mandatory one.', 'kv_project'));


		if ($posted['hours_per_week'] != null)
			$hours_per_week =  $posted['hours_per_week'];
		else
			$errors->add('empty_hours_per_week', __('<strong>Notice</strong>: Please Select Hours Per Week', 'kv_project'));


		if ($posted['work_time'] != null)
			$work_time =  $posted['work_time'];
		else
			$errors->add('empty_work_time', __('<strong>Notice</strong>: Please Select Work Time', 'kv_project'));

		if ($posted['job_duties'] != null)
			$job_duties =  $posted['job_duties'];
		else
			$errors->add('empty_job_duties', __('<strong>Notice</strong>: Please Enter Job Duties', 'kv_project'));

		if ($posted['no_of_vacancy'] != null)
			$no_of_vacancy =  $posted['no_of_vacancy'];
		else
			$errors->add('empty_no_of_vacancy', __('<strong>Notice</strong>: Please Select Number of Vacancy', 'kv_project'));

		if ($posted['how_to_apply'] != null) {
			$how_to_apply =  $posted['how_to_apply'];
			if ($how_to_apply == "manual_mtd") {
				if ($posted['manual_apply_details'] != null) {
				} else {
					$errors->add('empty_how_to_apply', __('<strong>Notice</strong>: You Selected Manual Method So please specify the Manual Applying details', 'kv_project'));
				}
				/*if( $_FILES['app_form']['size'] > 0 ){ }
		 	else {
				 	$application_url=  wp_get_attachment_url( get_post_meta( $_POST['edit_job'], 'app_form', true ));
					if( $application_url != null  || $application_url != ''){}
					else
				 		$errors->add('empty_application form', __('<strong>Notice</strong>: You should provide application form for manual method', 'kv_project'));
		 	}*/
			}
		} else
			$errors->add('empty_how_to_apply', __('<strong>Notice</strong>: Please Select How to apply', 'kv_project'));

		if ($posted['dbs'] == 'yes' || $posted['dbs'] == 'no' || $posted['dbs'] == 'Not Applicable') {
			/*	if (empty($_FILES['dbs_file_upload']['name']) || $posted['dbs_description'] == null)
			$errors->add('empty_dbs', __('<strong>Notice</strong>: Please Provide the DBS description.', 'kv_project'));*/
		} else
			$errors->add('empty_dbs', __('<strong>Notice</strong>: Please Provide the legal check requirement at the bottom of the form.', 'kv_project'));

		if (!$errors->get_error_code()) {

			if (isset($pack_name) && $pack_name >= 1) {
				$jp_status = 'draft';
			} else {
				$jp_status = 'pending';
				$postdate = date('Y-m-d H:i:s', strtotime($ad_start_date));
				$postdate_gmt = gmdate('Y-m-d H:i:s', strtotime($postdate));
			}
			if (isset($_POST['edit_job'])) {

				$update_post = array(
					'ID' 			=> $_POST['edit_job'],
					'post_title'	=>	$title,
					'post_content'	=>	$job_duties,
					//'post_category' =>   $qualification,
					'post_status'	=>	$jp_status,
					'post_type'		=>	'job',
					'post_author'	=>	$current_user->ID,
					'tax_input' 	=> array(
						'job_category' 	=> $posted['usr_job_category'],
						'gen_job_category' => $posted['usr_gen_job_category'],
						'qualification' => $posted['usr_qualification'],
						'types' 		=> $posted['usr_types'],
						'yr_of_exp' 	=> $posted['usr_yr_of_exp'],
						'madhab' 		=> $posted['usr_madhab'],
						'Shiamadhab' => $posted['usr_madhab_shia'],
						'aqeeda' => $posted['usr_aqeeda'],
						'Shiaaqeeda' => $posted['usr_aqeeda_shia'],
						'languages' 	=> $_POST['usr_language'],
						'zone' 			=> $posted['usr_zone'],
						'sal_prd' 		=> $posted['sal_period'],
						'sal_optn' 		=> $posted['sa_option']
					)
				);
				$jid = wp_update_post($update_post);
				kv_admin_mail_edited_job_pending($jid);
				if (has_private_employer($current_user->ID) == false)
					update_post_meta($jid, 'company_name', $company_name);
			} else {
				$new_post = array(
					'post_title'	=>	$title,
					'post_content'	=>	$job_duties,
					//'post_category' =>   $qualification,
					'post_status'	=>	$jp_status,
					'post_type'		=>	'job',
					'post_author'	=>	$current_user->ID,
					'tax_input'	 	=> array(
						'job_category' 	=> $posted['usr_job_category'],
						'gen_job_category' => $posted['usr_gen_job_category'],
						'qualification' => $posted['usr_qualification'],
						'types' 		=> $posted['usr_types'],
						'yr_of_exp' 	=> $posted['usr_yr_of_exp'],
						'madhab' 		=> $posted['usr_madhab'],
						'Shiamadhab' => $posted['usr_madhab_shia'],
						'aqeeda' => $posted['usr_aqeeda'],
						'Shiaaqeeda' => $posted['usr_aqeeda_shia'],
						'languages' 	=> $_POST['usr_language'],
						'zone' 			=> $posted['usr_zone'],
						'sal_prd' 		=> $posted['sal_period'],
						'sal_optn' 		=> $posted['sa_option']
					)
				);

				// 'post_date_gmt'  => $postdate_gmt,
				//             'post_date'  => $postdate,

				$jid = wp_insert_post($new_post);
				save_next_ref($posted['employer_ref']);
				kv_admin_mail_new_job_pending($jid);
				kv_owner_new_job_pending($jid);
				if ($jp_status == 'pending')
					kv_subscribe_email_to_reduce_perpost($current_user->ID);
				$post_id = wp_insert_post($post, $wp_error);
			}

			if ($jid) {
				$sub_success = 'Success';
				do_action('save_post', $jid);

				/* save/update post */
				update_post_meta($jid, 'custom_qualification', $posted['custom_qualification']);
				update_post_meta($jid, '_yoast_wpseo_focuskw_text_input', $title);
				update_post_meta($jid, '_yoast_wpseo_metadesc', substr($job_duties, 0, 130));
				update_post_meta($jid, 'full_name', $current_user->display_name);
				update_post_meta($jid, 'employer_ref', $posted['employer_ref']);
				update_post_meta($jid, 'ad_start_date', $ad_start_date);
				update_post_meta($jid, 'ad_close_date', $ad_close_date);
				update_post_meta($jid, 'in_start_date', $in_start_date);
				update_post_meta($jid, 'gender', $gender);
				update_post_meta($jid, 'sal_amount', $posted['sal_amount']);
				update_post_meta($jid, 'hours_per_week', $posted['hours_per_week']);
				update_post_meta($jid, 'work_time', $posted['work_time']);
				update_post_meta($jid, 'how_to_apply', $how_to_apply);
				update_post_meta($jid, 'experience_details', $posted['experience_details']);
				if ($posted['manual_apply_details'] != null || $posted['manual_apply_details'] != '')
					update_post_meta($jid, 'manual_apply_details', $posted['manual_apply_details']);
				if (has_private_employer($current_user->ID) == false) {
					update_post_meta($jid, 'pension_provision', $posted['pension_provision']);
					if ($posted['pension_provision'] != null || $posted['pension_provision'] != '')
						update_post_meta($jid, 'pension_provision_dropdown', $posted['pension_provision_dropdown']);
					update_post_meta($jid, 'website',  $posted['website']);
					update_post_meta($jid, 'company_description', $posted['company_description']);
				}
				update_post_meta($jid, 'confidential', $confidential);
				update_post_meta($jid, 'accomodation', $accomodation);
				update_post_meta($jid, 'accomodation-details', $posted['accomodation-details']);
				update_post_meta($jid, 'other_information', $other_information);
				update_post_meta($jid, 'monitoring_equality', $posted['monitoring_equalty']);
				update_post_meta($jid, 'equality_statement', $posted['equalty_statement']);
				update_post_meta($jid, 'no_of_vacancy', $posted['no_of_vacancy']);
				update_post_meta($jid, 'eligible_work_in', $posted['eligible_work_in']);
				update_post_meta($jid, 'address1', $posted['address1']);
				update_post_meta($jid, 'address2', $posted['address2']);
				update_post_meta($jid, 'state_pro_reg', $posted['state_pro_reg']);
				update_post_meta($jid, 'city', $posted['city']);
				update_post_meta($jid, 'dbs', $posted['dbs']);
				if ($posted['dbs'] == 'yes' &&  $posted['dbs_description'] != null)
					update_post_meta($jid, 'dbs_description', $posted['dbs_description']);

				$company_logo  = get_user_meta($current_user->ID, 'company_logo_attachment_id', true);
				if ($company_logo > 0)
					set_post_thumbnail($jid, $company_logo);

				if ($_FILES) {

					foreach ($_FILES as $file => $array) {
						/*if( $file == 'company_logo' ) {
							add_filter('upload_dir', 'kv_company_logo_dir');
							$newupload = kv_job_attachment($file,$jid,true);
							remove_filter('upload_dir', 'kv_company_logo_dir');
					}*/

						if (has_private_employer($current_user->ID) == false) {
							if ($file == 'equality_statement') {
								if ($array['type'] == 'application/pdf' || $array['type'] == 'application/msword' || $array['type'] == 'application/vnd.ms-powerpoint' || $array['type'] == 'application/rtf') {
									if (isset($_POST['edit_job'])) {
										$equality_statement_id =	get_post_meta($_POST['edit_job'], 'equality_statement', true);
										if ($equality_statement_id > 0)
											kv_delete_attached_files($equality_statement_id);
									}
									add_filter('upload_dir', 'kv_files_dir');

									$equality_statement = kv_job_attachment($file, $jid, false);
									update_post_meta($jid, 'equality_statement', $equality_statement);
									remove_filter('upload_dir', 'kv_files_dir');
								}
							}
							if ($file == 'monitoring_equality') {
								if ($array['type'] == 'application/pdf' || $array['type'] == 'application/msword' || $array['type'] == 'application/vnd.ms-powerpoint' || $array['type'] == 'application/rtf') {
									if (isset($_POST['edit_job'])) {
										$monitoring_equality_id =	get_post_meta($_POST['edit_job'], 'monitoring_equality', true);
										if ($monitoring_equality_id > 0)
											kv_delete_attached_files($monitoring_equality_id);
									}
									add_filter('upload_dir', 'kv_files_dir');

									$monitoring_equality = kv_job_attachment($file, $jid, false);
									update_post_meta($jid, 'monitoring_equality', $monitoring_equality);
									remove_filter('upload_dir', 'kv_files_dir');
								}
							}

							if ($file == 'dbs_file_upload' && $_FILES['dbs_file_upload']['size'] > 0) {
								$cv_name = pathinfo($_FILES["dbs_file_upload"]["name"]);
								//print_r($cv_name);
								if ($cv_name['extension'] == 'doc' || $cv_name['extension'] == 'docx' || $cv_name['extension'] == 'rtf' || $cv_name['extension'] == 'pdf' || $cv_name['extension'] == 'txt') {
									add_filter('upload_dir', 'kv_jobseeker_cv_dir');
									$dbs_file_upload = kv_job_attachment($file, 0, false);
									remove_filter('upload_dir', 'kv_jobseeker_cv_dir');
									update_post_meta($jid, 'dbs_file', $dbs_file_upload);
								}
							}
						}
					}

					if ($_FILES["app_form"]) {
						$app_form_ar	 = array();
						$files = $_FILES["app_form"];
						if (isset($_POST['edit_job'])) {
							$ids = maybe_unserialize(get_post_meta($_POST['edit_job'], 'app_form', true));
							if (!empty($ids)) {
								foreach ($ids as $id) {
									wp_delete_attachment($id);
								}
							}
						}
						add_filter('upload_dir', 'kv_files_dir');

						foreach ($files['name'] as $key => $value) {
							if ($files['name'][$key]) {
								$file = array(
									'name' => $files['name'][$key],
									'type' => $files['type'][$key],
									'tmp_name' => $files['tmp_name'][$key],
									'error' => $files['error'][$key],
									'size' => $files['size'][$key]
								);
								$_FILES = array("app_form" => $file);
								foreach ($_FILES as $file => $array) {
									$app_form_ar[] = kv_job_attachment($file, $jid, false);
								}
							}
						}
						$app_form_ser = maybe_serialize($app_form_ar);
						update_post_meta($jid, 'app_form', $app_form_ser);
						remove_filter('upload_dir', 'kv_files_dir');
					}
				}
			}
			if (($enable_employer_subscription == 'yes') && isset($pack_name) && $pack_name >= 1) {
				if (isset($_POST['edit_job']))
					$job_name = 'job_id';
				else
					$job_name = 'jobs_id';
				wp_safe_redirect(site_url('payment-page') . '?pack_id=' . $pack_name . '&' . $job_name . '=' . $jid);
			} else {
				if ($_POST['submit_a_job'] == 'Submit and Add New Job') {
					wp_safe_redirect(site_url('add-new-job') . '?status=yes');
				} else {
					if (isset($_POST['edit_job']))
						wp_safe_redirect(site_url('posted-jobs') . '?status=updated');
					else
						wp_safe_redirect(site_url('posted-jobs') . '?status=added');
				}
			}
		}
	} ?>
	<style>
		.kv_notification {
			background-color: #B0F5B0;
			margin-left: 10%;
			margin-right: 10%;
			padding: 2px;
			margin-top: 15px;
			border: 1px solid #40D060;
			border-radius: 5px;
		}
	</style>

	<script src="<?php echo get_template_directory_uri(); ?>/assets/jquery-te-1.4.0.min.js"></script>
	<link href="<?php echo get_template_directory_uri(); ?>/assets/jquery-te-1.4.0.css" rel="stylesheet" type="text/css">

	<div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">


		<div id="kt_app_toolbar_container" class="container-xxl d-flex flex-stack ">


			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">

				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
					<?php if (isset($_GET['edit_id'])) { ?>
						<h2> <?php _e('Edit Job', 'eimams'); ?> </h2>
					<?php } else { ?>
						<h2><?php _e('Add a New Job', 'eimams'); ?> </h2> <?php } ?>
				</h1>
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">

					<span>Welcome to eimams, Love to see you back.</span>
				</ul>
			</div>
		</div>

	</div>


	<div id="kt_app_content" class="app-content  flex-column-fluid ">
		<div class="card">
			<div class="card-body">
				<?php $form_opacity = 1;
				$disabled = '';
				if (kv_get_user_status() != 0) {
					$disabled = 'disabled';
					$form_opacity = 0.4;
					echo '<div class="alert alert-warning box-shadow">
									<a href="#" class="close" data-dismiss="alert">×</a>
									<strong>Warning!</strong> Your Profile is not Active to Submit/edit a Job Vacancy.  </div>';
				}

				// $result=$wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'jbs_subactive WHERE status="Active" AND wp_user_id='.$current_user->ID);
				// $today=date('Y-m-d');
				// if($result->per_post!=0  ||  $result->end_date>$today) { } else {
				// 	echo '<div class="alert alert-warning box-shadow">
				// 	<a href="#" class="close" data-dismiss="alert">×</a>
				// 	<strong>Warning!</strong> Your Subscription has Expired. So Please Buy one at the bottom of the Form.  </div>';
				// }

				if (isset($_GET['status']) && $_GET['status'] == 'yes') {
					echo '<div class="kv_notification">
									<center>New job has been Added  </center>
									</div>';
				}
				if (isset($errors) && $errors->get_error_code()) :
					echo '<ul class="alert mw-600px mx-auto alert alert-danger box-shadow">';
					foreach ($errors->errors as $error) {
						echo '<li>' . $error[0] . '</li>';
					}
					echo '</ul>';
				endif;
				?>
				<form style="opacity: <?php echo $form_opacity; ?>" class="mx-auto mw-600px w-100 pt-15 pb-10 fv-plugins-bootstrap5 fv-plugins-framework" name="submit_a_new_job" method="post" action="">
					<div class="w-100">
						<?php
						if (isset($_GET['edit_id']) || isset($_GET['clone_id'])) {
							if (isset($_GET['edit_id'])) {
								$selected_id = $_GET['edit_id'];
								echo " <input type='hidden' class='edit_job_id' name='edit_job' value='" . $selected_id . "'>";
							} else {
								$selected_id = $_GET['clone_id'];
								echo " <input type='hidden' name='clone_job' value='" . $selected_id . "'>";
							}
						} else {
							$selected_id = '';
							echo '<h3>Creating a New Vacancy </h3><br>';
						}
						?>

						<div class="d-flex justify-content-between align-items-center">
							<h2>Job Details</h2>
							<a href="<?php echo get_template_directory_uri(); ?>/images/eimams-pdf.pdf"><button class="btn btn-primary pull-right" id="print">Download a PDF version of this form </button></a>
						</div>

						<div class="fv-row fv-plugins-icon-container fv-plugins-bootstrap5-row-valid">
							<div class="row">
								<div class="mb-10 fv-row fv-plugins-icon-container">

									<label class="form-label mb-3">Job Title</label>



									<input type="text" class="form-control form-control-lg form-control-solid" name="title" placeholder="Job title" value="<?php if ($selected_id != '') {
																																								echo get_the_title($selected_id);
																																							} else if (isset($_POST['title'])) {
																																								echo $_POST['title'];
																																							}  ?>" required>
								</div>

								<div class="mb-10 fv-row fv-plugins-icon-container">

									<label class="form-label mb-3">Company/Organisation Name</label>



									<input type="text" class="form-control form-control-lg form-control-solid" name="company_name" placeholder="Name" value="<?php if ($selected_id != '') {
																																									echo get_post_meta($selected_id, 'company_name', true);
																																								} else if (isset($_POST['company_name'])) {
																																									echo $_POST['company_name'];
																																								} ?>" required>

								</div>

								<div class="mb-10 fv-row fv-plugins-icon-container">

									<label class="form-label mb-3">Website</label>



									<input type="text" class="form-control form-control-lg form-control-solid " name="website" placeholder="Website" value="<?php if ($selected_id != '') {
																																								echo get_post_meta($selected_id, 'website', true);
																																							} else if (isset($_POST['website'])) {
																																								echo $_POST['website'];
																																							} ?>">
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div>

								<div class="mb-10 fv-row fv-plugins-icon-container">

									<label class="form-label mb-3">Description</label>



									<textarea type="text" class="form-control form-control-lg form-control-solid " name="company_description" placeholder="Company Description"><?php if ($selected_id != '') {
																																													echo get_post_meta($selected_id, 'company_description', true);
																																												} else if (isset($_POST['company_description'])) {
																																													echo $_POST['company_description'];
																																												} ?></textarea>
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div>
							</div>

						</div>

					</div>

					<div class="w-100">

						<div class="mb-10 fv-row fv-plugins-icon-container">

							<label class="form-label mb-3">Address 1</label>



							<input type="text" class="form-control form-control-lg form-control-solid" name="address1" value="<?php if ($selected_id != '') {
																																	echo get_post_meta($selected_id, 'address1', true);
																																} elseif (isset($_POST['address1'])) {
																																	echo $_POST['address1'];
																																} else {
																																	echo  get_user_meta($current_user->ID, 'address1', true);
																																} ?>" placeholder="Address">

							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="mb-10 fv-row fv-plugins-icon-container">

							<label class="form-label mb-3">Address 2</label>



							<input type="text" class="form-control form-control-lg form-control-solid" name="address2" value="<?php if ($selected_id != '') {
																																	echo get_post_meta($selected_id, 'address2', true);
																																} elseif (isset($_POST['address2'])) {
																																	echo $_POST['address2'];
																																} else {
																																	echo  get_user_meta($current_user->ID, 'address2', true);
																																} ?>" placeholder="Address 2 ">

							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="mb-10 fv-row fv-plugins-icon-container">

							<label class="form-label mb-3">City</label>



							<input type="text" class="form-control form-control-lg form-control-solid" name="city" value="<?php if ($selected_id != '') {
																																echo get_post_meta($selected_id, 'city', true);
																															} elseif (isset($_POST['city'])) {
																																echo $_POST['city'];
																															} else {
																																echo  get_user_meta($current_user->ID, 'city', true);
																															} ?>" placeholder="City">

							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="mb-10 fv-row fv-plugins-icon-container">

							<label class="form-label mb-3">State/Province/Region</label>



							<input type="text" class="form-control form-control-lg form-control-solid" name="state_pro_reg" value="<?php if ($selected_id != '') {
																																		echo get_post_meta($selected_id, 'state_pro_reg', true);
																																	} else if (isset($_POST['state_pro_reg'])) {
																																		echo $_POST['state_pro_reg'];
																																	} else {
																																		echo  get_user_meta($current_user->ID, 'state_pro_reg', true);
																																	} ?>" placeholder="State/Province/Region">

							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

					</div>



					<div class="w-100">


						<div class="fv-row mb-10 fv-plugins-icon-container">

							<label class="form-label required">Job Reference</label>



							<input class="form-control form-control-lg form-control-solid" placeholder="Job Reference" value="<?php if ($selected_id != '') {
																																	echo get_post_meta($selected_id, 'employer_ref', true);
																																} else {
																																	echo get_next_reference();
																																} ?>" disabled="disabled">

							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>


						<div class="fv-row mb-10 fv-plugins-icon-container">

							<label class="form-label required">Advertisement Start Date</label>



							<input type="date" class="form-control form-control-lg form-control-solid" name="ad_start_date" placeholder="Advertisement Start Date" value="<?php if ($selected_id != '') {
																																												$ad_start_date = get_post_meta($selected_id, 'ad_start_date', true);
																																												$start_date = mysql2date("d-M-Y", $ad_start_date);
																																												echo $start_date;
																																											} else {
																																												$start_date = date("d-M-Y", strtotime("+2 days"));
																																												echo $start_date;
																																											} ?>">

							<div class="fv-plugins-message-container invalid-feedback"></div>
							<div class="form-text">
								Enter the date from which the vacancy is to be advertised i.e. date the vacancy will be made available to jobseekers.
							</div>
						</div>


						<div class="fv-row mb-10 fv-plugins-icon-container">

							<label class="form-label required">Advertisement Close Date</label>



							<input type="date" class="form-control form-control-lg form-control-solid" name="ad_close_date" placeholder="Advertisement Close Date" value="<?php if ($selected_id != '') {
																																												$ad_close_date = get_post_meta($selected_id, 'ad_close_date', true);
																																												$close_date = mysql2date("d-M-Y", $ad_close_date);
																																												echo $close_date;
																																											} else {
																																												$start_date = date("d-M-Y", strtotime("+12 days"));
																																												echo $start_date;
																																											}  ?>">

							<div class="fv-plugins-message-container invalid-feedback"></div>
							<div class="form-text">
								Enter the date on which the vacancy will be withdrawn from display.
							</div>

						</div>


						<div class="fv-row mb-10 fv-plugins-icon-container">

							<label class="form-label required">Interview Start Date</label>



							<input type="date" class="form-control form-control-lg form-control-solid" name="in_start_date" placeholder="Interview Start Date" value="<?php if ($selected_id != '') {
																																											$in_start_date = get_post_meta($selected_id, 'in_start_date', true);
																																											$in_start_date_edit = mysql2date("d-M-Y", $in_start_date);
																																											echo $in_start_date_edit;
																																										} else {
																																											$start_date = date("d-M-Y", strtotime("+14 days"));
																																											echo $start_date;
																																										}  ?>">

							<div class="fv-plugins-message-container invalid-feedback"></div>
							<div class="form-text">
								Enter the date on which the interview will be started
							</div>

						</div>



						<div class="fv-row mb-10 fv-plugins-icon-container">

							<label class="form-label required">Job Classifications</label>



							<?php
							if (isset($_POST['usr_job_category']) &&  $_POST['usr_job_category'] != -1) {
								$selected_cat = $usr_job_category = $_POST['usr_job_category'];
								$usr_gen_job_category = -1;
							} elseif (isset($_POST['usr_gen_job_category']) &&  $_POST['usr_gen_job_category'] != -1) {
								$selected_cat = $usr_gen_job_category = $_POST['usr_gen_job_category'];
								$usr_job_category = -1;
							} elseif ($selected_id != '') {
								$term_list = wp_get_post_terms($selected_id, 'job_category', array("fields" => "ids"));
								if ($term_list[0]) {
									$selected_cat =  $usr_job_category =  $term_list[0];
									$usr_gen_job_category =  -1;
								} else {
									$term_list = wp_get_post_terms($selected_id, 'gen_job_category', array("fields" => "ids"));
									$usr_job_category =  -1;
									$selected_cat =  $usr_gen_job_category =  $term_list[0];
								}
							} else {
								$selected_cat = $usr_job_category = $usr_gen_job_category = -1;
							}

							echo kv_merged_taxonomy_dropdown('job_category', 'gen_job_category', $selected_cat);
							echo '<input type="hidden" name="usr_job_category" value="' . $usr_job_category . '" id="usr_job_category" >
											<input type="hidden" name="usr_gen_job_category" value="' . $usr_gen_job_category . '" id="usr_gen_job_category" > ';  	?>

							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>



						<div class="fv-row mb-10">

							<label class="form-label">Gender</label>

							<div class="group-input mt-2 d-flex gap-5">

								<label class="form-check-label"> <input class="form-check-input" type="radio" name="gender" value="male" <?php if ($selected_id != '') {
																																				$gen = get_post_meta($selected_id, 'gender', true);
																																				if ($gen == 'male') echo  'checked';
																																			} elseif (isset($_POST['gender']) && $_POST['gender'] == 'male') {
																																				echo 'checked';
																																			} ?>> Male </label>
								<label class="form-check-label"> <input class="form-check-input" type="radio" name="gender" value="female" <?php if ($selected_id != '') {
																																				$gen = get_post_meta($selected_id, 'gender', true);
																																				if ($gen == 'female') echo  'checked';
																																			} elseif (isset($_POST['gender']) && $_POST['gender'] == 'female') {
																																				echo 'checked';
																																			} ?>> Female </label>
								<label class="form-check-label"> <input class="form-check-input" type="radio" name="gender" value="any" <?php if ($selected_id != '') {
																																			$gen = get_post_meta($selected_id, 'gender', true);
																																			if ($gen == 'any') echo  'checked';
																																		} elseif (isset($_POST['gender']) && $_POST['gender'] == 'any') {
																																			echo 'checked';
																																		} ?>> Any </label>
							</div>
							<div class="fv-plugins-message-container invalid-feedback"></div>

						</div>



						<div class="fv-row  mb-10 fv-plugins-icon-container">

							<label class="fs-6 fw-semibold form-label required">No Of Vacancies</label>



							<input class="form-control form-control-lg form-control-solid" name="no_of_vacancy" placeholder="No Of Vacancies" value="<?php if ($selected_id != '') {
																																							echo get_post_meta($selected_id, 'no_of_vacancy', true);
																																						} elseif (isset($_POST['no_of_vacancy'])) {
																																							echo $_POST['no_of_vacancy'];
																																						} else {
																																							echo '';
																																						} ?>">

							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="fv-row mb-10 fv-plugins-icon-container">

							<label class="form-label required">Qualification</label>
							<?php if ($selected_id != '') {
								$term_list = wp_get_post_terms($selected_id, 'qualification', array("fields" => "ids"));
								$qualification = $term_list[0];
								$custom_qualification = get_post_meta($selected_id, 'custom_qualification', true);
							} else {
								if (isset($_POST['usr_qualification'])) {
									$qualification = $_POST['usr_qualification'];
								} else
									$qualification = 0;
								$custom_qualification = '';
							}
							$usr_qualification = wp_dropdown_categories(array('show_option_none' => 'Select category', 'echo' => 0, 'taxonomy' => 'qualification', 'id' => 'job_qualification', 'selected' => $qualification, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
							$usr_qualification = str_replace("name='cat' id=", "name='usr_qualification' id=", $usr_qualification);
							echo $usr_qualification; ?>
							<input type="text" class="form-control form-control-lg form-control-solid mt-5" id="custom_qualification" placeholder="Custom Qualification" name="custom_qualification" value="<?php echo $custom_qualification; ?>">

							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="fv-row mb-10 fv-plugins-icon-container">

							<label class="form-label required">Job Type</label>
							<?php if ($selected_id != '') {
								$term_list = wp_get_post_terms($selected_id, 'types', array("fields" => "ids"));
								$type = $term_list[0];
							} else {
								if (isset($_POST['usr_types'])) {
									$type = $_POST['usr_types'];
								} else
									$type = 0;
							}
							$usr_types = wp_dropdown_categories(array('show_option_none' => 'Select Type', 'echo' => 0, 'taxonomy' => 'types', 'selected' => $type, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
							$usr_types = str_replace("name='cat' id=", "name='usr_types' id=", $usr_types);
							echo $usr_types; ?>

							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="fv-row mb-10 fv-plugins-icon-container">

							<label class="form-label required">Years of Exprerience:</label>
							<?php if ($selected_id != '') {
								$term_list = wp_get_post_terms($selected_id, 'yr_of_exp', array("fields" => "ids"));
								$experience = $term_list[0];
							} else {
								if (isset($_POST['usr_yr_of_exp'])) {
									$experience = $_POST['usr_yr_of_exp'];
								} else
									$experience = 0;
							}
							$usr_yr_of_exp = wp_dropdown_categories(array('show_option_none' => 'Select Years of Exprerience', 'echo' => 0, 'taxonomy' => 'yr_of_exp', 'selected' => $experience, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
							$usr_yr_of_exp = str_replace("name='cat' id=", "name='usr_yr_of_exp' id=", $usr_yr_of_exp);
							echo $usr_yr_of_exp; ?>

							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="fv-row  mb-10 fv-plugins-icon-container">

							<label class="fs-6 fw-semibold form-label required">Experience Details</label>



							<textarea class="form-control form-control-lg form-control-solid" name="experience_details" placeholder="Experience Details"><?php if ($selected_id != '') {
																																								echo get_post_meta($selected_id, 'experience_details', true);
																																							} else if (isset($_POST['experience_details'])) {
																																								echo $_POST['experience_details'];
																																							} ?></textarea>

							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>


						<div class="showhideJobCategory" style="border-top:1px solid #ddd; width:100%; height:4px; margin:15px 0; float:left"> </div>
						<?php
						if ($selected_id != '') {
							$madhabterm_list = wp_get_post_terms($selected_id, 'madhab', array("fields" => "ids"));
							$madhab = $madhabterm_list[0];
							$aqeedaterm_list = wp_get_post_terms($selected_id, 'aqeeda', array("fields" => "ids"));
							$aqeeda = $aqeedaterm_list[0];
							if ($madhabShiaterm_list = wp_get_post_terms($selected_id, 'Shiamadhab', array("fields" => "ids")))
								$madhabShia = $madhabShiaterm_list[0];
							else
								$madhabShia = 0;
							if ($Shiaaqeedaterm_list = wp_get_post_terms($selected_id, 'Shiaaqeeda', array("fields" => "ids")))
								$Shiaaqeeda = $Shiaaqeedaterm_list[0];
							else
								$Shiaaqeeda = 0;
						} else {
							if (isset($_POST['usr_madhab'])) {
								$madhab = $_POST['usr_madhab'];
							} else
								$madhab = -1;
							if (isset($_POST['usr_aqeeda'])) {
								$aqeeda = $_POST['usr_aqeeda'];
							} else
								$aqeeda = -1;

							if (isset($_POST['usr_madhab_shia'])) {
								$madhab = $_POST['usr_madhab_shia'];
							} else
								$madhabShia = -1;

							if (isset($_POST['usr_aqeeda_shia'])) {
								$aqeeda = $_POST['usr_aqeeda_shia'];
							} else
								$Shiaaqeeda = -1;
						}
						if ($Shiaaqeeda > 0 || $madhabShia > 0) {
							$denomination_selected = 'shia';
						} else {
							$denomination_selected = "sunni";
						} ?>
						<?php if ($enable_shia_subscription == 'yes') { ?>
							<style>
								.shia_selection {
									visibility: visibile;
								}
							</style>
						<?php } else { ?>
							<style>
								.shia_selection {
									visibility: hidden;
								}
							</style>
						<?php } ?>


						<h3 class="showhideJobCategory "> Denomination : <label class=" "> <input type="radio" value="sunni" class="denominationSelection shia_selection" name="denominationSelection" <?php echo ($denomination_selected == 'sunni' ? 'checked="checked"' : ''); ?>> Sunni </label> <label class="shia_selection"> <input name="denominationSelection" type="radio" value="shia" class="denominationSelection" <?php echo ($denomination_selected == 'shia' ? 'checked="checked"' : ''); ?>> Shia </label> </h3>
						<div class="sunniDenomination">
							<div class="fv-row  mb-10 fv-plugins-icon-container showhideJobCategory">

								<label class="fs-6 fw-semibold form-label required">Madhab/School of Law</label>

								<?php
								$usr_madhab = wp_dropdown_categories(array('show_option_none' => 'Select Madhab', 'echo' => 0, 'taxonomy' => 'madhab', 'selected' => $madhab, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
								$usr_madhab = str_replace("name='cat' id=", "name='usr_madhab' id=", $usr_madhab);
								echo $usr_madhab; ?>

								<div class="fv-plugins-message-container invalid-feedback"></div>
							</div>

							<div class="fv-row  mb-10 fv-plugins-icon-container showhideJobCategory">

								<label class="fs-6 fw-semibold form-label required">Aqeeda/Belief</label>

								<?php
								$usr_aqeeda = wp_dropdown_categories(array('show_option_none' => 'Select Aqeeda/Belief', 'echo' => 0, 'taxonomy' => 'aqeeda', 'selected' => $aqeeda, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
								$usr_aqeeda = str_replace("name='cat' id=", "name='usr_aqeeda' id=", $usr_aqeeda);
								echo $usr_aqeeda; ?>

								<div class="fv-plugins-message-container invalid-feedback"></div>
							</div>
						</div>

						<div class="sunniDenomination">
							<div class="fv-row  mb-10 fv-plugins-icon-container showhideJobCategory">

								<label class="fs-6 fw-semibold form-label required">Madhab/School of Law</label>

								<?php
								$usr_madhab_shia = wp_dropdown_categories(array('show_option_none' => 'Select Madhab', 'echo' => 0, 'taxonomy' => 'Shiamadhab', 'selected' => $madhabShia, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
								$usr_madhab_shia = str_replace("name='cat' id=", "name='usr_madhab_shia' id=", $usr_madhab_shia);
								echo $usr_madhab_shia; ?>

								<div class="fv-plugins-message-container invalid-feedback"></div>
							</div>

							<div class="fv-row  mb-10 fv-plugins-icon-container showhideJobCategory">

								<label class="fs-6 fw-semibold form-label required">Aqeeda/Belief</label>

								<select name="usr_aqeeda_shia" id="usr_aqeeda_shia" class="form-control">
									<?php echo Shia_Aqeeda_select($Shiaaqeeda); ?>
								</select>

								<div class="fv-plugins-message-container invalid-feedback"></div>
							</div>
						</div>
					</div>




					<div class="w-100">
						<div class="fv-row mb-10 fv-plugins-icon-container">

							<label class="form-label required">Language</label>
							<?php if ($selected_id != '') {
								//$term_list = wp_get_post_terms($selected_id, 'languages', array("fields" => "ids"));
								$summa_lan = get_the_terms($selected_id, 'languages');
								if (!$summa_lan || is_wp_error($summa_lan))
									$summa_lang = array();
								else
									$summa_lang = array_values($summa_lan);

								foreach (array_keys($summa_lang) as $key) {
									_make_cat_compat($summa_lang[$key]);
								}

								foreach ($summa_lang as $summa1)
									$final_array[] = $summa1->term_id;
								//print_r($final_array);
								//print_r($_POST['usr_language']);
								//var_dump($terms_list);
								$languages = $final_array[0];
							} else {
								if (isset($_POST['usr_language'])) {
									$final_array = $_POST['usr_language'];
								} else
									$final_array = 4;
							}
							$usr_language = wp_dropdown_categories(array('show_option_none' => 'Select Language', 'echo' => 0, 'taxonomy' => 'languages', 'selected' => array(4, 122, 124), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0,  'orderby'            => 'name', 'order'      => 'ASC'));
							$usr_language = str_replace("name='cat' id=", "name='usr_language[]' multiple='multiple' id=", $usr_language);

							if (is_array($final_array)) {
								foreach ($final_array as $post_term) {
									$usr_language = str_replace(' value="' . $post_term . '"', ' value="' . $post_term . '" selected="selected"', $usr_language);
								}
							} else {
								$usr_language = str_replace(' value="' . $final_array . '"', ' value="' . $final_array . '" selected="selected"', $usr_language);
							}

							echo $usr_language; ?>
							<span class="form-text">To select multiple language press CTRL + click</span>
							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="fv-row mb-10 fv-plugins-icon-container">

							<label class="form-label required">Country</label>
							<?php if ($selected_id != '') {
								$term_list = wp_get_post_terms($selected_id, 'zone', array("fields" => "ids"));
								$location = $term_list[0];
							} else {
								if (isset($_POST['usr_zone'])) {
									$location = $_POST['usr_zone'];
								} else
									$location = 0;
							}
							$usr_zone = wp_dropdown_categories(array('show_option_none' => 'Select Country', 'echo' => 0, 'taxonomy' => 'zone', 'selected' => $location, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby'            => 'name', 'order'      => 'ASC'));
							$usr_zone = str_replace("name='cat' id=", "name='usr_zone' id=", $usr_zone);
							echo $usr_zone; ?>
							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>


						<div class="fv-row mb-10 fv-plugins-icon-container">

							<label class="required fs-6 fw-semibold form-label mb-2">Salary</label>



							<div class="d-flex gap-5 align-items-center fv-row fv-plugins-icon-container">

								<div class="col-3">
									<input type="text" class="form-control form-control-solid" name="sal_amount" value="<?php if ($selected_id != '') {
																															echo get_post_meta($selected_id, 'sal_amount', true);
																														} elseif (isset($_POST['sal_amount'])) {
																															echo $_POST['sal_amount'];
																														} ?>">
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div>

								<span class="fs-6 fw-semibold form-label">Per</span>

								<div class="col-3">
									<?php if ($selected_id != '') {
										if ($term_list = wp_get_post_terms($selected_id, 'sal_prd', array("fields" => "ids")))
											$per = $term_list[0];
										else
											$per = 0;
									} else {
										if (isset($_POST['sal_period'])) {
											$per = $_POST['sal_period'];
										} else	$per = 0;
									}
									$sal_period = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'sal_prd', 'selected' => $per, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
									$sal_period = str_replace("name='cat' id=", "name='sal_period' id=", $sal_period);
									echo $sal_period; ?>
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div>
								<span class="fs-6 fw-semibold form-label">Or</span>
								<div class="col-3">
									<?php if ($selected_id != '') {
										$term_list = wp_get_post_terms($selected_id, 'sal_optn', array("fields" => "ids"));
										$sal_option = $term_list[0];
									} else {
										if (isset($_POST['sa_option'])) {
											$sal_option = $_POST['sa_option'];
										} else	$sal_option = 0;
									}
									$sa_option = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'sal_optn', 'selected' => $sal_option, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
									$sa_option = str_replace("name='cat' id=", "name='sa_option' id=", $sa_option);
									echo $sa_option; ?>
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div>

							</div>
						</div>
						<div class="fv-row  mb-10 fv-plugins-icon-container">

							<label class="fs-6 fw-semibold form-label required">Hours Per Week</label>



							<input class="form-control form-control-lg form-control-solid" name="hours_per_week" value="<?php if ($selected_id != '') {
																															echo get_post_meta($selected_id, 'hours_per_week', true);
																														} else if (isset($_POST['hours_per_week'])) {
																															echo $_POST['hours_per_week'];
																														} ?>">

							<span class="form-text">
								Enter the total minimum number of hours to be worked each week. Any variation can be included under "other information" (see below). Hours offered should comply with current legislation.
							</span>
							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>


						<div class="fv-row  mb-10 fv-plugins-icon-container">

							<label class="fs-6 fw-semibold form-label required">Work Time</label>



							<input class="form-control form-control-lg form-control-solid" name="work_time" value="<?php if ($selected_id != '') {
																														echo get_post_meta($selected_id, 'work_time', true);
																													} else if (isset($_POST['work_time'])) {
																														echo $_POST['work_time'];
																													} ?>">

							<span class="form-text">
								Enter a breakdown of working hours eg. 9.00am to 5.00pm Mon - Fri, 2.00pm to 9.00pm Wed, Thur &amp; Fri etc. You can enter negotiable as well.
							</span>
							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="fv-row  mb-10 fv-plugins-icon-container">

							<label class="fs-6 fw-semibold form-label required">Job Duties</label>



							<textarea class="form-control form-control-lg form-control-solid" name="job_duties" placeholder="Job Duties"> <?php if ($selected_id != '') {
																																				$content_post = get_post($selected_id);
																																				// echo nl2br($content_post->post_content);
																																				echo strip_tags($content_post->post_content);
																																			} else if (isset($_POST['job_duties'])) {
																																				echo $_POST['job_duties'];
																																			} ?></textarea>

							<span class="form-text">
								Enter the duties of the job. These should be clear and concise. Unfamiliar abbreviations should not be used. A hyperlink or reference to a more detailed job specification can be included in the "other information" field (see below). Capital letters should be used at the beginning of every sentence with one space after each comma, two spaces after each full stop and capitals at the beginning of person or place names. <br>
								You may find it useful before saving your vacancy to select the text in the Job Description window, copy (Ctrl C) and paste it (Ctrl V) into a blank Word Document. Spelling and grammatical errors will be identified and can be corrected and copied back to appropriate box (es). </span>
							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="fv-row  mb-10 fv-plugins-icon-container">

							<label class="fs-6 fw-semibold form-label required"> How to Apply</label>



							<?php if ($selected_id != '') {
								$apply_online = get_post_meta($selected_id, 'how_to_apply', true);
							}  ?>
							<label class="radio-inline"> <input type="radio" id="apply-online" name="how_to_apply" value="apply_online" <?php if (isset($apply_online) && $apply_online == 'apply_online') {
																																			echo  'checked';
																																		} else if (isset($_POST['how_to_apply']) && $_POST['how_to_apply'] == 'apply_online') {
																																			echo 'checked';
																																		} ?>> Apply through eimams </label>



							<label class="radio-inline"> <input type="radio" id="manual-method" name="how_to_apply" value="manual_mtd" <?php if (isset($apply_online) && $apply_online == 'manual_mtd') {
																																			echo  'checked';
																																		} else if (isset($_POST['how_to_apply']) &&  $_POST['how_to_apply'] == 'manual_mtd') {
																																			echo 'checked';
																																		} ?>> Apply Manually </label>


							<textarea class="form-control" id="manual-application-method" name="manual_apply_details" placeholder="Manual Application Method"><?php if ($selected_id != '') {
																																									echo get_post_meta($selected_id, 'manual_apply_details', true);
																																								} else if (isset($_POST['manual_apply_details'])) {
																																									echo $_POST['manual_apply_details'];
																																								} ?> </textarea>

							<span class="form-text apply-through-eimams">By clicking this option you will only receive profile information of potential employees held by eimams. </span>
							<span class="form-text apply-online">by clicking this option you give consent to eimams to forward your profile information to potential employers. Enter clear instructions on how to apply for the job: e.g. where application forms can be obtained and, where completed, forms are to be sent. </span>
							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="fv-row  mb-10 fv-plugins-icon-container">

							<label class="fs-6 fw-semibold form-label required">Application Form/Pack</label>



							<input type="file" class="form-control form-control-lg form-control-solid" id="rtf-pdf" name="app_form[]" multiple="multiple" accept='.doc,.docx, .rtf, .pdf'>

							<span class="form-text">
								<?php if ($selected_id != null) {
									echo ' You can change the files by uploading new  file here. ';
								} else {
									echo 'Covering Note, Person Specification, Application Form <br />  (RTF and PDF format) can be uploaded here';
								} ?> </span>

							<?php if ($selected_id != null) {
								$ids = maybe_unserialize(get_post_meta($selected_id, 'app_form', true));
								//var_dump($ids);
								if (!empty($ids)) {
									echo '<ul class="application_files"> ';
									foreach ($ids as $id) {
										echo '<li><a class="view_resume" data-fancybox-type="iframe" href="' . site_url('view-resume') . '/?attach_id=' . $id . '" > ' . get_the_title($id) . ' </a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="#" class="remove_current_file" attach_id="' . $id . '" > X </a></li>';
									}
									echo '</ul>';
								}
							} ?>
							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<?php if (has_private_employer($current_user->ID) == false) { ?>


							<div class="fv-row  mb-10 fv-plugins-icon-container">
								<label class="fs-6 fw-semibold form-label required" for="Name">Pension Provision:<span class="mandatory"> * </span></label>

								<label class="radio-inline"><input type="radio" name="pension_provision" id="pension_provision_no" value="no" <?php if ($selected_id != '') {
																																					$confidential = get_post_meta($selected_id, 'pension_provision', true);
																																					if ($confidential == 'no') echo  'checked';
																																				} elseif (isset($_POST['pension_provision']) && $_POST['pension_provision'] == 'no') {
																																					echo 'checked';
																																				}  ?>> No </label>
								<label class="radio-inline"><input type="radio" name="pension_provision" id="pension_provision_yes" value="yes" <?php if ($selected_id != '') {
																																					$confidential = get_post_meta($selected_id, 'pension_provision', true);
																																					if ($confidential == 'yes') echo  'checked';
																																				} elseif (isset($_POST['pension_provision']) && $_POST['pension_provision'] == 'yes') {
																																					echo 'checked';
																																				} ?>> Yes </label>
								<label class="radio-inline"><input type="radio" name="pension_provision" id="pension_provision_no" value="Not Applicable" <?php if ($selected_id != '') {
																																								$confidential = get_post_meta($selected_id, 'pension_provision', true);
																																								if ($confidential == 'Not Applicable') echo  'checked';
																																							} elseif (isset($_POST['pension_provision']) && $_POST['pension_provision'] == 'Not Applicable') {
																																								echo 'checked';
																																							} ?>> Not Applicable </label>

								<?php global  $pension_provisions_ar;
								echo ' <select class="form-control  form-control-lg form-control-solid my-5" name="pension_provision_dropdown" id="pension_provision_dropdown">';
								foreach ($pension_provisions_ar as $key => $value) {
									echo '<option value="' . $key . '">' . $value . '</option>';
								}
								echo '</select>'; ?>
								<input type="text" name="pension_provision_dropdown" class="form-control mb-5  form-control-lg form-control-solid" id="PensionProvisionText" value="">
								<span class="form-text">Select from Yes/No . If Yes then a further drop down will become available to you to select the appropriate pension type. </span>


							</div>

							<div class="fv-row  mb-10 fv-plugins-icon-container">
								<label class="fs-6 fw-semibold form-label required" for="monitoring-equality">Monitoring / Equality:<span class="mandatory"> * </span></label>


								<label class="radio-inline"><input type="radio" name="monitoring_equalty" id="monitoring_equality_no" value="no" <?php if ($selected_id != '') {
																																						$monitoring_equality = get_post_meta($selected_id, 'monitoring_equality', true);
																																						if ($monitoring_equality == 'no') {
																																							echo  'checked';
																																						}
																																					} elseif (isset($_POST['monitoring_equalty']) && $_POST['monitoring_equalty'] == 'no') {
																																						echo 'checked';
																																					} ?>> No </label>
								<label class="radio-inline"><input type="radio" name="monitoring_equalty" id="monitoring_equality_yes" value="yes" <?php if ($selected_id != '') {
																																						$monitoring_equality = get_post_meta($selected_id, 'monitoring_equality', true);
																																						if ($monitoring_equality == 'yes') {
																																							echo  'checked';
																																						}
																																					} elseif (isset($_POST['monitoring_equalty']) && $_POST['monitoring_equalty'] == 'yes') {
																																						echo 'checked';
																																					} ?>> Yes </label>
								<label class="radio-inline"><input type="radio" name="monitoring_equalty" id="monitoring_equality_no" value="Not Applicable" <?php if ($selected_id != '') {
																																									$monitoring_equality = get_post_meta($selected_id, 'monitoring_equality', true);
																																									if ($monitoring_equality == 'Not Applicable') {
																																										echo  'checked';
																																									}
																																								} elseif (isset($_POST['monitoring_equalty']) && $_POST['monitoring_equalty'] == 'Not Applicable') {
																																									echo 'checked';
																																								} ?>> Not Applicable </label>

								<div id="monitoring_equality_upload" style="margin-top:10px;">
									<input type="file" class="form-control" id="monitoring-equality" name="monitoring_equality" accept='.doc,.docx, .rtf, .pdf'>
									<?php if ($selected_id != null) {
										$monitoring_equality_url =  wp_get_attachment_url(get_post_meta($selected_id, 'monitoring_equality', true));
										if ($monitoring_equality_url != null  || $monitoring_equality_url != '')
											echo '<a class="view_resume" data-fancybox-type="iframe" href="' . site_url('view-resume') . '/?attach_id=' . get_post_meta($selected_id, 'monitoring_equality', true) . '"> <button  class="btn btn-primary" > View File </button>  </a>';
										else
											echo 'No View File ';
									} ?>


								</div>
								<span class="form-text"> Indicate whether a monitoring form is required. A monitoring form can be uploaded here. </span>
							</div>



							<div class="fv-row  mb-10 fv-plugins-icon-container">
								<label class="fs-6 fw-semibold form-label required" for="equality-statement">Equality Statement:<span class="mandatory"> * </span></label>

								<label class="radio-inline"><input type="radio" name="equalty_statement" id="equality_statement_no" value="no" <?php if ($selected_id != '') {
																																					$equality_statement = get_post_meta($selected_id, 'equality_statement', true);
																																					if ($equality_statement == 'no') {
																																						echo  'checked';
																																					}
																																				} elseif (isset($_POST['equalty_statement']) && $_POST['equalty_statement'] == 'no') {
																																					echo 'checked';
																																				} ?>> No </label>
								<label class="radio-inline"><input type="radio" name="equalty_statement" id="equality_statement_yes" value="yes" <?php if ($selected_id != '') {
																																						$equality_statement = get_post_meta($selected_id, 'equality_statement', true);
																																						if ($equality_statement == 'yes') {
																																							echo  'checked';
																																						}
																																					} elseif (isset($_POST['equalty_statement']) && $_POST['equalty_statement'] == 'yes') {
																																						echo 'checked';
																																					} ?>> Yes </label>
								<label class="radio-inline"><input type="radio" name="equalty_statement" id="equality_statement_no" value="Not Applicable" <?php if ($selected_id != '') {
																																								$equality_statement = get_post_meta($selected_id, 'equality_statement', true);
																																								if ($equality_statement == 'Not Applicable') {
																																									echo  'checked';
																																								}
																																							} elseif (isset($_POST['equalty_statement']) && $_POST['equalty_statement'] == 'Not Applicable') {
																																								echo 'checked';
																																							} ?>> Not Applicable </label>

								<div id="equality_statement_upload" style="margin-top:10px;">
									<input type="file" class="form-control" id="equality-statement" name="equality_statement" accept='.doc,.docx, .rtf, .pdf'>
									<?php if ($selected_id != null) {

										$equality_statement_url =  wp_get_attachment_url(get_post_meta($selected_id, 'equality_statement', true));
										if ($equality_statement_url != null  || $equality_statement_url != '')
											echo '<a class="view_resume" data-fancybox-type="iframe" href="' . site_url('view-resume') . '/?attach_id=' . get_post_meta($selected_id, 'equality_statement', true) . '"> View File </a>';
										else
											echo 'No View File ';
									} ?>
								</div>
								<span class="form-text"> If an employer equality statement is available, it should be entered here and will appear under "other information" in the published vacancy.
								</span>

							</div>
						<?php } ?>

						<div class="fv-row  mb-10 fv-plugins-icon-container">

							<label class="fs-6 fw-semibold form-label required">Eligible to work in </label>


							<?php if ($selected_id != '') {
								$eligible_work = get_post_meta($selected_id, 'eligible_work_in', true);
							} else {
								if (isset($_POST['eligible_work_in']))
									$eligible_work = $_POST['eligible_work_in'];
								else
									$eligible_work = 0;
							}
							$eligible_work_in = wp_dropdown_categories(array('show_option_none' => 'Select Country', 'echo' => 0, 'taxonomy' => 'zone', 'selected' => $eligible_work, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby'            => 'name', 'order'      => 'ASC'));
							$eligible_work_in = str_replace("name='cat' id=", "name='eligible_work_in' id=", $eligible_work_in);
							echo $eligible_work_in; ?>
							<span class="form-text">
								Enter a breakdown of working hours eg. 9.00am to 5.00pm Mon - Fri, 2.00pm to 9.00pm Wed, Thur &amp; Fri etc. You can enter negotiable as well.
							</span>
							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="fv-row  mb-10 fv-plugins-icon-container">
							<label class="fs-6 fw-semibold form-label required" for="equality-statement">Equality Statement:<span class="mandatory"> * </span></label>
							<label class="radio-inline">
								<input type="radio" name="dbs" id="dbs_yes" value="yes" <?php if ($selected_id != '') {
																							$dbs_yes = get_post_meta($selected_id, 'dbs', true);
																							if ($dbs_yes == 'yes') echo  'checked';
																						} elseif (isset($_POST['dbs']) && $_POST['dbs'] == 'yes') echo 'checked'; ?>> Yes
							</label>


							<label class="radio-inline">
								<input type="radio" name="dbs" id="dbs_no" value="no" <?php if ($selected_id != '') {
																							$dbs_no = get_post_meta($selected_id, 'dbs', true);
																							if ($dbs_no == 'no') echo  'checked';
																						} elseif (isset($_POST['dbs']) && $_POST['dbs'] == 'no') echo 'checked'; ?>> No
							</label>

							<label class="radio-inline">
								<input type="radio" name="dbs" id="dbs_not_applicable" value="Not Applicable" <?php if ($selected_id != '') {
																													$dbs_no = get_post_meta($selected_id, 'dbs', true);
																													if ($dbs_no == 'Not Applicable') echo  'checked';
																												} elseif (isset($_POST['dbs']) && $_POST['dbs'] == 'Not Applicable') echo 'checked'; ?>> Not Applicable
							</label>

							<div id="dbs_upload" style="margin-top:10px;">
								<textarea class="form-control  form-control-lg form-control-solid" id="dbs_description" name="dbs_description" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"><?php if ($selected_id != '') {
																																																																		echo get_post_meta($selected_id, 'dbs_description', true);
																																																																	} else if (isset($_POST['dbs_description'])) {
																																																																		echo $_POST['dbs_description'];
																																																																	}  ?></textarea>
								<input type="file" class="form-control form-control-lg form-control-solid my-5" id="dbs_file_upload" name="dbs_file_upload" accept='.doc,.docx, .rtf, .pdf'>
								<?php if ($selected_id != null) {

									$dbs_file_url =  wp_get_attachment_url(get_post_meta($selected_id, 'dbs_file', true));
									if ($dbs_file_url != null  || $dbs_file_url != '')
										echo '<a class="view_resume" data-fancybox-type="iframe" href="' . site_url('view-resume') . '/?attach_id=' . get_post_meta($selected_id, 'dbs_file', true) . '"> View File </a>';
									else
										echo 'No View File ';
								} ?>
							</div>
							<span class="form-text"> It is strongly recommended that you (as employer) understand what DBS (Disclosures and Barring service) is and when recruiting an individual for tuition or working with children and or vulnerable individuals, that they (employee) have DBS clearance.
							</span>

						</div>

						<div class="fv-row  mb-10 fv-plugins-icon-container">
							<label class="fs-6 fw-semibold form-label required" for="equality-statement">Accomodation <span class="mandatory"> * </span></label>
							<label class="radio-inline"> <input type="radio" name="accomodation" value="yes" <?php if ($selected_id != '') {
																													$confidential = get_post_meta($selected_id, 'accomodation', true);
																													if ($confidential == 'yes') echo  'checked';
																												} elseif (isset($_POST['accomodation']) && $_POST['accomodation'] == 'yes') echo 'checked';  ?>>Yes</label>
							<label class="radio-inline"> <input type="radio" name="accomodation" value="no" <?php if ($selected_id != '') {
																												$confidential = get_post_meta($selected_id, 'accomodation', true);
																												if ($confidential == 'no') echo  'checked';
																											} elseif (isset($_POST['accomodation']) && $_POST['accomodation'] == 'no') echo 'checked';  ?>>No</label>


							<textarea class="form-control" name="accomodation-details" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"></textarea>


							<span class="form-text">If accommodation is provided or assistance given with the sourcing of accommodation, this should be entered here.
							</span>

						</div>


						<div class="fv-row  mb-10 fv-plugins-icon-container showhideJobCategory">

							<label class="fs-6 fw-semibold form-label required">Other Information</label>

							<input type="text" class="form-control form-control-lg form-control-solid" name="other_information" placeholder="Other Information" <?php if ($selected_id != '') {
																																									echo get_post_meta($selected_id, 'other_information', true);
																																								} else if (isset($_POST['other_information'])) {
																																									echo $_POST['other_information'];
																																								}  ?>>

							<div class="fv-plugins-message-container invalid-feedback"></div>
						</div>

						<div class="mb-10 fv-plugins-icon-container showhideJobCategory">

							<h2>Ongoing Contract</h2>
							<span class="form-text">
								Ongoing contact will be maintained with you throughout the life of the vacancy. When the vacancy closes you will be asked to provide feedback information on the applications who have applied through a Jobs &amp; Benefits Office / Jobcentre or directly via Jobcentreonline. You should therefore maintain records of the applications received and the outcomes.
							</span>
						</div>
					</div>

					<div class="d-flex flex-stack pt-15">
						<div class="checkbox col-xs-12 col-sm-9 col-lg-8 col-sm-offset-3 col-lg-offset-4">
							<?php
							if (isset($_GET['edit_id']) || isset($_GET['clone_id'])) {
								if (isset($_GET['edit_id']))
									$button_name = "Update job";
								else
									$button_name = "Submit a clone job";
							} else
								$button_name = "Submit job";	?>
							<input type="submit" class="btn btn-primary" style="margin-bottom:10px;" name="submit_a_job" <?php echo $disabled; ?> value=" <?php echo $button_name; ?>">
							<input type="submit" class="btn btn-primary" style="margin-bottom:10px;" name="submit_a_job" <?php echo $disabled; ?> value="Submit and Add New Job">
						</div>
					</div>

				</form>
			</div>

		</div>

	</div>




	<style type="text/css">
		#manual-application-method {
			margin-top: 10px;
		}

		.application_files li {
			list-style: none;
		}
	</style>

	<script>
		//  download pdf script
		function printContent(el) {
			var restorepage = $('body').html();
			var printcontent = $('#' + el).clone();
			$('body').empty().html(printcontent);
			window.print();
			$('body').html(restorepage);
		}

		ajax_url = location.protocol + "//" + document.domain + '/ajax';
		jQuery(document).ready(function() {

			var custom_qualification = jQuery('#custom_qualification').val();
			if (custom_qualification == '')
				jQuery('#custom_qualification').hide();

			$(document).on('change', '#job_qualification', function() {
				if ($(this).val() == 542)
					$('#custom_qualification').show();
				else
					$('#custom_qualification').hide();
			});

			if (jQuery("#dbs_yes").is(":checked")) {} else {
				jQuery('#dbs_upload').hide();
			}

			$(document).on('change', '#dbs_yes', function() {
				if (jQuery(this).is(":checked")) {
					jQuery('#dbs_upload').show();
				}
			});

			$(document).on('change', '#dbs_no', function() {
				if (jQuery(this).is(":checked")) {
					jQuery('#dbs_upload').hide();
				}
			});

			jQuery('.jqte-test').jqte();

			<?php
			if (is_page('edit-job')) { ?>

				jQuery(".view_resume").fancybox({
					maxWidth: 900,
					maxHeight: 900,
					fitToView: false,
					width: '70%',
					height: '90%',
					autoSize: false,
					closeClick: false,
					openEffect: 'none',
					closeEffect: 'none',
					type: "iframe"
				});
			<?php } ?>

			jQuery("#select_pack").on('change', function() {
				var id = jQuery(this).val();
				//alert(id);
				jQuery.ajax({
					type: "POST",
					url: ajax_url,
					data: {
						action: "get_pack_info",
						pack_name: id,
					},
					success: function(data) {
						//alert(data);
						jQuery("#per_post").val(data);
					},
					error: function(errorThrown) {
						console.log(errorThrown);
					}
				});
			});

			// manual method
			if (jQuery("#manual-method").is(":checked")) {} else {
				jQuery('#manual-application-method').hide();
				jQuery('#application_form').hide();
				jQuery('.apply-through-eimams').hide();
				jQuery('.apply-online').hide();
			}


			$(document).on('change', '#apply-online', function() {
				if (jQuery(this).is(":checked")) {
					jQuery('#manual-application-method').hide();
					jQuery('#application_form').hide();
					jQuery('.apply-online').hide();
					jQuery('.apply-through-eimams').show();
				}
			});

			$(document).on('change', '#manual-method', function() {
				if (jQuery(this).is(":checked")) {
					jQuery('#manual-application-method').show();
					jQuery('#application_form').show();
					jQuery('.apply-online').show();
					jQuery('.apply-through-eimams').hide();
				}
			});

			// Pension Provision
			if (jQuery("#pension_provision_yes").is(":checked")) {} else {
				jQuery('#pension_provision_dropdown, #PensionProvisionText').hide();
			}

			$(document).on('change', '#pension_provision_yes', function() {
				var CountryVal = $('select[name=usr_zone]').val();
				var eligibleVal = $('select[name=eligible_work_in]').val();
				if (jQuery(this).is(":checked") && (CountryVal == 390 || eligibleVal == 390)) {
					jQuery('#pension_provision_dropdown').show();
					$("#PensionProvisionText").prop('disabled', true);
					$("#pension_provision_dropdown").prop('disabled', false);
				} else {
					jQuery('#PensionProvisionText').show();
					$("#PensionProvisionText").prop('disabled', false);
					$("#pension_provision_dropdown").prop('disabled', true);
				}
			});

			$(document).on('change', '#pension_provision_no, #pension_provision_not_applicable', function() {
				if (jQuery(this).is(":checked")) {
					jQuery('#pension_provision_dropdown, #PensionProvisionText').hide();
				}
			});

			// monitoring equality
			if (jQuery("#monitoring_equality_yes").is(":checked")) {} else {
				jQuery('#monitoring_equality_upload').hide();
			}

			$(document).on('change', '#monitoring_equality_yes', function() {
				if (jQuery(this).is(":checked")) {
					jQuery('#monitoring_equality_upload').show();
				}
			});

			$(document).on('change', '#monitoring_equality_no, #monitoring_equality_not_applicable', function() {
				if (jQuery(this).is(":checked")) {
					jQuery('#monitoring_equality_upload').hide();
				}
			});

			// equality statement
			if (jQuery("#equality_statement_yes").is(":checked")) {} else {
				jQuery('#equality_statement_upload').hide();
			}

			$(document).on('change', '#equality_statement_yes', function() {
				if (jQuery(this).is(":checked")) {
					jQuery('#equality_statement_upload').show();
				}
			});

			$(document).on('change', '#equality_statement_no, #equality_statement_not_applicable', function() {
				if (jQuery(this).is(":checked")) {
					jQuery('#equality_statement_upload').hide();
				}
			});

			// Remove attachments
			$(".remove_current_file").on("click", function(e) {
				e.preventDefault();
				var dele_attachment_id = $(this).attr('attach_id');
				var post_id_ = $(".edit_job_id").val();
				jQuery.ajax({
					type: "POST",
					url: ajax_url,
					data: {
						action: "delete_selected_attachment",
						attach_id: dele_attachment_id,
						post_id: post_id_,
					},
					success: function(data) {
						alert(data);
						//if(data = 'Success'){
						$(this).closest('li').remove();
						//}
					},
					error: function(errorThrown) {
						console.log(errorThrown);
					}
				});
			});

			$(".denominationSelection").on("change", function() {
				var SelectedDenomination = $('.denominationSelection:checked').val();
				//alert(SelectedDenomination);
				if (SelectedDenomination == 'sunni') {
					$(".sunniDenomination").show();
					$(".shiaDenomination").hide();
				} else {
					$(".sunniDenomination").hide();
					$(".shiaDenomination").show();
				}
			});

			jQuery("#merged_taxonomy").on("change", function() {
				var txt_val = $(this).val();
				var typE = jQuery('option:selected', this).attr('type');
				//alert(typE);
				if (typE == 'job_category') {
					jQuery("#usr_job_category").val(txt_val);
					jQuery("#usr_gen_job_category").val(-1);
					jQuery(".showhideJobCategory").show();
					jQuery(".denominationSelection").trigger('change');
				} else {
					jQuery("#usr_gen_job_category").val(txt_val);
					jQuery("#usr_job_category").val(-1);
					jQuery(".showhideJobCategory").hide();
					jQuery(".denominationSelection").trigger('change');
				}
			});
			jQuery("#merged_taxonomy").trigger('change');
		});
	</script><?php
				kv_footer();
			} else {
				wp_redirect(kv_login_url());
				exit;
			}
				?>