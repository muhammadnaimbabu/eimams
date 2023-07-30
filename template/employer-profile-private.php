<?php

/* Template Name: Employers  Profile Private */

get_header();

// if the user is logged in it will redirect to employer user backend add new job page
if (is_user_logged_in()) {
	wp_safe_redirect(site_url('add-new-job'));
}



echo '<link href="' . get_template_directory_uri() . '/css/datepicker.css" rel="stylesheet" />';

$posted['ad_close_date'] = date("d-M-Y", strtotime("+12 days"));
$posted['in_start_date'] = date("d-M-Y", strtotime("+14 days"));

// ######  Error Hangling start here  #################

if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['submit_a_job'])) {

	$errors = new WP_Error();
	$fields = array(
		'full_name',
		'address1',
		'address2',
		'city',
		'state_pro_reg',
		'post_code',
		'phone_number',
		'usr_email',
		'UserName',
		'PassWord',

		'title',
		'no_of_vacancy',
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
		'experience_details',

		'eligible_work_in',
		'usr_madhab',
		'usr_madhab_shia',
		'usr_aqeeda',
		'usr_aqeeda_shia',
		'usr_language',
		'usr_zone',
		'sal_amount',
		'sal_period',
		'sa_option',
		'hours_per_week',
		'work_time',
		'job_duties',
		'how_to_apply',

		'confidential',
		'accomodation',
		'other_information',
		'manual_apply_details',
		'pack_name',
		'dbs',
		'dbs_info_box'
	);

	//  filterring the input	
	foreach ($fields as $field) {
		if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field]));
		else $posted[$field] = '';
	}


	$ad_close_date = date('Y-m-d',  strtotime($posted['ad_close_date']));
	$other_information =  $posted['other_information'];
	$accomodation =  $posted['accomodation'];
	$confidential =  $posted['confidential'];

	if ($posted['phone_number'] != null)
		$phone_number =  $posted['phone_number'];
	else
		$errors->add('empty_phone_number', __('<strong>Notice</strong>: Please enter phone number.', 'kv_project'));


	if ($posted['usr_email'] != null) {
		if (is_email($posted['usr_email'])) {
			if (email_exists($posted['usr_email']) == false)
				$usr_email =  $posted['usr_email'];
			else
				$errors->add('exist_usr_email', __('<strong>Notice</strong>: Email Already Registered in it.', 'kv_project'));
		} else
			$errors->add('invalid_usr_email', __('<strong>Notice</strong>: Please enter proper email.', 'kv_project'));
	} else
		$errors->add('empty_usr_email', __('<strong>Notice</strong>: Please enter Your Email.', 'kv_project'));


	if ($posted['UserName'] != null) {
		$user_id = username_exists($posted['UserName']);
		if (!$user_id)
			$UserName =  $posted['UserName'];
		else
			$errors->add('exist_UserName', __('<strong>Notice</strong>: UserName Already Exist.', 'kv_project'));
	} else
		$errors->add('empty_UserName', __('<strong>Notice</strong>: Please enter UserName.', 'kv_project'));

	if ($posted['PassWord'] != null)
		$PassWord =  $posted['PassWord'];
	else
		$errors->add('empty_PassWord', __('<strong>Notice</strong>: Please enter Password.', 'kv_project'));


	if ($posted['full_name'] != null)
		$full_name =  $posted['full_name'];
	else
		$errors->add('empty_full_name', __('<strong>Notice</strong>: Please enter Representative Name.', 'kv_project'));


	if ($posted['address1'] != null)
		$address1 =  $posted['address1'];
	else
		$errors->add('empty_address1', __('<strong>Notice</strong>: Please enter Company address1.', 'kv_project'));

	if ($posted['dbs'] == 'yes' || $posted['dbs'] == 'no') {
		/*if (empty($_FILES['dbs_file_upload']['name']) || $posted['dbs_info_box'] == null)
			$errors->add('empty_dbs', __('<strong>Notice</strong>: Please Provide the DBS description.', 'kv_project'));	*/
	} else
		$errors->add('empty_dbs', __('<strong>Notice</strong>: Please Provide the DBS.', 'kv_project'));

	if ($enable_employer_subscription == 'yes') {
		if ($posted['pack_name'] >= 1)
			$pack_name =  $_POST['pack_name'];
		else if ($posted['pack_name'] == 0)
			$errors->add('empty_pack_name', __('<strong>Notice</strong>: Please Select a Pack before Submit.', 'kv_project'));
	}
	if ($posted['title'] != null)
		$title =  $posted['title'];
	else
		$errors->add('empty_title', __('<strong>Notice</strong>: Please enter Job Title.', 'kv_project'));


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

		if ($posted['usr_aqeeda'] == -1 && $posted['usr_aqeeda_shia'] == -1)
			$errors->add('empty_usr_aqeeda', __('<strong>Notice</strong>: Please Select your Aqeeda.', 'kv_project'));
	} else {
		$posted['usr_madhab'] = $posted['usr_aqeeda'] = $posted['usr_aqeeda_shia'] = $posted['usr_madhab_shia'] = '';
	}

	if ($posted['eligible_work_in'] == -1)
		$errors->add('empty_eligible_work_in', __('<strong>Notice</strong>: Please Select Your Eligible Work Country.', 'kv_project'));

	if ($_POST['usr_language'] != -1)
		$usr_language =  $_POST['usr_language'];
	else
		$errors->add('empty_usr_language', __('<strong>Notice</strong>: Please Select Your Language.', 'kv_project'));

	if ($posted['usr_zone'] != -1)
		$usr_zone =  $posted['usr_zone'];
	else
		$errors->add('empty_usr_zone', __('<strong>Notice</strong>: Please Select Your Zone.', 'kv_project'));

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
		$errors->add('empty_job_duties', __('<strong>Notice</strong>: Please Select Job Duties', 'kv_project'));

	if ($posted['no_of_vacancy'] != null)
		$no_of_vacancy =  $posted['no_of_vacancy'];
	else
		$errors->add('empty_no_of_vacancy', __('<strong>Notice</strong>: Please enter Number of Vacancy.', 'kv_project'));


	if ($posted['how_to_apply'] != null) {
		$how_to_apply =  $posted['how_to_apply'];
		if ($how_to_apply == "manual_mtd") {
			if ($posted['manual_apply_details'] != null) {
			} else
				$errors->add('empty_how_to_apply', __('<strong>Notice</strong>: You Selected Manual Method So please specify the Manual Applying details', 'kv_project'));
		}
	} else
		$errors->add('empty_how_to_apply', __('<strong>Notice</strong>: Please Select How to apply', 'kv_project'));

	if ($posted['confidential'] != null)
		$confidential =  $posted['confidential'];
	else
		$errors->add('empty_confidential', __('<strong>Notice</strong>: Please Select Confidential', 'kv_project'));

	if ($posted['accomodation'] != null)
		$accomodation =  $posted['accomodation'];
	else
		$errors->add('empty_accomodation', __('<strong>Notice</strong>: Please Select Accomodation', 'kv_project'));

	if (!$errors->get_error_code()) {
		$user_id = wp_create_user($UserName, $PassWord, $usr_email);
		$u = new WP_User($user_id);
		// Remove role
		$u->remove_role('subscriber');
		// Add role
		$u->add_role('employer');
		wp_update_user(array('ID' => $user_id, 'display_name' => $full_name));
		add_user_meta($user_id, 'private_employer', 'yes');
		//add_user_meta( $user_id, 'rep_position', $rep_position);
		//add_user_meta($user_id, 'company_name',  $posted['company_name']);
		add_user_meta($user_id, 'address1', $posted['address1']);
		add_user_meta($user_id, 'address2', $posted['address2']);

		add_user_meta($user_id, 'state_pro_reg', $posted['state_pro_reg']);
		add_user_meta($user_id, 'city', $posted['city']);
		add_user_meta($user_id, 'usr_zone', $posted['usr_zone']);
		add_user_meta($user_id, 'post_code', $posted['post_code']);
		add_user_meta($user_id, 'phone_number', $phone_number);
		//update_user_meta($current_user->ID, 'company_description', $company_description);
		//add_user_meta($current_user->ID, 'rep_position', $rep_position);
		kv_new_user_notification($user_id, $PassWord);

		wp_clear_auth_cookie();
		wp_set_current_user($user_id);
		wp_set_auth_cookie($user_id);

		$new_post = array(
			'post_title'	=>	$title,
			'post_content'	=>	$job_duties,
			//'post_category' =>   $qualification, 	
			'post_status'	=>	'draft',
			'post_type'	=>	'job',

			'tax_input' => array(
				'job_category' => $posted['usr_job_category'],
				'gen_job_category' => $posted['usr_gen_job_category'],
				'qualification' => $posted['usr_qualification'],
				'types' => $posted['usr_types'],
				'yr_of_exp' => $posted['usr_yr_of_exp'],
				'madhab' => $posted['usr_madhab'],
				'Shiamadhab' => $posted['usr_madhab_shia'],
				'aqeeda' => $posted['usr_aqeeda'],
				'Shiaaqeeda' => $posted['usr_aqeeda_shia'],
				'languages' => $usr_language,
				'zone' => $posted['usr_zone'],
				'sal_prd' => $posted['sal_period'],
				'sal_optn' => $posted['sa_option']
			),
			'post_author'	=>	$user_id,
		);

		$jid = wp_insert_post($new_post);
		save_next_ref($posted['employer_ref']);
		kv_admin_mail_new_job_pending($jid);
		kv_owner_new_job_pending($jid);
		//kv_subscribe_email_to_reduce_perpost($current_user->ID);			
		if ($jid) {
			$sub_success = 'Success';
			do_action('save_post', $jid);

			// save/update post		
			update_post_meta($jid, '_yoast_wpseo_focuskw_text_input', $title);
			update_post_meta($jid, '_yoast_wpseo_metadesc', substr($job_duties, 0, 130));
			update_post_meta($jid, 'custom_qualification', $posted['custom_qualification']);
			update_post_meta($jid, 'full_name', $posted['full_name']);
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

			update_post_meta($jid, 'confidential', $confidential);
			update_post_meta($jid, 'accomodation', $accomodation);
			update_post_meta($jid, 'other_information', $other_information);

			update_post_meta($jid, 'no_of_vacancy', $posted['no_of_vacancy']);
			update_post_meta($jid, 'eligible_work_in', $posted['eligible_work_in']);
			update_post_meta($jid, 'address1', $posted['address1']);
			update_post_meta($jid, 'address2', $posted['address2']);
			update_post_meta($jid, 'state_pro_reg', $posted['state_pro_reg']);
			update_post_meta($jid, 'city', $posted['city']);
			update_post_meta($jid, 'post_code', $posted['post_code']);
			update_post_meta($jid, 'dbs', $posted['dbs']);
			if ($posted['dbs'] == 'yes')
				update_post_meta($jid, 'dbs_description', $posted['dbs_info_box']);

			if ($_FILES) {
				foreach ($_FILES as $file => $array) {
					if ($file == 'company_logo') {

						if ($array['type'] == 'image/jpeg' || $array['type'] == 'image/jpg' || $array['type'] == 'image/gif' || $array['type'] == 'image/png' || $array['type'] == 'image/bmp') {
							add_filter('upload_dir', 'kv_company_logo_dir');
							$newupload = kv_job_attachment($file, $jid, true);
							remove_filter('upload_dir', 'kv_company_logo_dir');
							add_user_meta($user_id, 'company_logo_attachment_id', $newupload);
						}
					}

					if ($file == 'app_form') {
						if ($array['type'] == 'application/pdf' || $array['type'] == 'application/msword' || $array['type'] == 'application/vnd.ms-powerpoint' || $array['type'] == 'application/rtf') {

							add_filter('upload_dir', 'kv_files_dir');

							$app_form = kv_job_attachment($file, $jid, false);
							update_post_meta($jid, 'app_form', $app_form);
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
		}
		if ($enable_employer_subscription == 'yes') {
			$pack_details = get_all_packdetails_using_id($pack_name);
			if ($pack_details['price'] != 0)
				wp_safe_redirect(site_url('payment-page') . '?pack_id=' . $pack_name . '&jobs_id=' . $jid);
			else {
				$start_date = date('Y-m-d');
				$pack_details = get_all_packdetails_using_id($pack_name);
				$pack_posts = $pack_details['per_post'] - 1;
				$array_val = array(
					'pack_id'     		=> $pack_name,
					'wp_user_id' 		=> $user_id,
					'date_subscribed' 	=> $start_date,
					'start_date'		=> $start_date,
					'end_date' 			=> $start_date,
					'per_post' 			=> $pack_posts,
					'amount'			=> 0,
					'status' 			=> 'Active'
				);

				$wpdb->insert($sub_active, $array_val);

				if (isset($pack_details['per_post'])) {
					$left_count = $pack_details['left_count'] + 1;
					if ($left_count < 0)
						$wpdb->update($jbs_subpack, array('left_count'	=> 	$left_count), array('id' =>  $pack_name));
					else
						$wpdb->update($jbs_subpack, array('left_count'	=> 	$left_count, 'left_offer' => 'no'), array('id' =>  $pack_name));
				}

				wp_safe_redirect(site_url('posted-jobs'));
			}
		}
	}
}

?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/jquery-te-1.4.0.min.js"></script>

<link href="<?php echo get_template_directory_uri(); ?>/assets/jquery-te-1.4.0.css" rel="stylesheet" type="text/css">

<div style="padding:10px;margin:10px; background:#fff;">
	<div class="row">
		<div class="col-md-12">
			<!--   <h2>Profile and Resume</h2>    -->
			<h5>Welcome to eimams, Love to see you back. </h5>
		</div>
	</div> <!-- /. ROW  -->
	<hr />

	<!-- ##############################   Form start here ################################################################################# -->

	<?php
	if (isset($errors) && sizeof($errors) > 0 && $errors->get_error_code()) :
		echo '<ul class="error text-center">';
		foreach ($errors->errors as $error) {
			echo '<li>' . $error[0] . '</li>';
		}
		echo '</ul>';
	endif;
	?>


	<div class="row">
		<div class="col-md-12">
			<div class="row" style="margin:15px">
				<div class="col-md-6"><span style="font-size:24px;">Private/Individual Employer Registration</span> </div>
				<div class="col-md-6">
					<a href="<?php echo get_template_directory_uri(); ?>/images/private-pdf-sunni.pdf"><button class="btn btn-info pull-right" id="print">Download a PDF version of this form </button></a>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"></div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-offset-1">If you are a company employer then click here for another <button type="button button-default"><a href="<?php echo site_url('employer-sign-up'); ?>">Employers Form: Company/Organisation</a></button> </div><br>
						<div class="col-md-11">

							<form class="form-horizontal" enctype='multipart/form-data' name="submit_a_new_job" method="post" action="<?php echo "http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']; ?>">
								<h3>Personal Details</h3><br />

								<div class="form-group">
									<label class="control-label col-sm-4" for="Name">Name: <span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="Name" name="full_name" placeholder="Name" value="<?php echo $posted['full_name']; ?>">
									</div>
								</div>

								<h3>Job Location: </h3>

								<div class="form-group">
									<label class="control-label col-sm-4" for="address1">Address 1:<span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="address1" name="address1" value="<?php echo $posted['address1']; ?>" placeholder="Address">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="address2">Address 2: </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="address2" name="address2" value="<?php echo $posted['address2']; ?>" placeholder="Address">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="city">City: </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="city" name="city" value="<?php echo $posted['city']; ?>" placeholder="Address">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="state_pro_reg">State/Province/Region: </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="state_pro_reg" name="state_pro_reg" value="<?php echo $posted['state_pro_reg']; ?>" placeholder="Address">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="post-code">Zip/Post Code:</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="post-code" name="post_code" value="<?php echo $posted['post_code']; ?>" placeholder="Post Code">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="Name">Country: <span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<?php if ($posted['usr_zone'] == '' || $posted['usr_zone'] == null)
											$selected_usr_zone = 0;
										else
											$selected_usr_zone = $posted['usr_zone'];
										$usr_zone = wp_dropdown_categories(array('show_option_none' => 'Select Country', 'echo' => 0, 'taxonomy' => 'zone', 'selected' => $selected_usr_zone, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby'            => 'name', 'order'      => 'ASC'));
										$usr_zone = str_replace("name='cat' id=", "name='usr_zone' id=", $usr_zone);
										echo $usr_zone; ?>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="phoneNumber">Phone:<span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<input type="tel" class="form-control" id="phoneNumber" value="<?php echo $posted['phone_number']; ?>" name="phone_number" placeholder="Phone Number">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail" class="control-label col-sm-4">Email:<span class="mandatory">*</span></label>
									<div class="col-sm-8">
										<input type="email" class="form-control" id="inputEmail" name="usr_email" value="<?php echo $posted['usr_email']; ?>" placeholder="Email">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail" class="control-label col-sm-4">Username:<span class="mandatory">*</span></label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="inputUsername" name="UserName" value="<?php echo $posted['UserName']; ?>" placeholder="Username">
									</div>
								</div>

								<div class="form-group">
									<label for="inputPassword" class="control-label col-sm-4">Password:<span class="mandatory">*</span></label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="inputPassword" name="PassWord" value="<?php echo $posted['PassWord']; ?>" placeholder="Password">
									</div>
								</div>


								<div style="border-top:1px solid #ddd; width:100%; height:4px; margin:15px 0; float:left"> </div>


								<h3>Creating a New Vacancy </h3><br>

								<div class="form-group">
									<label class="control-label col-sm-4" for="employer-ref"> Job Title :<span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="employer-ref" name="title" value="<?php echo $posted['title']; ?>" placeholder="Job Title">
										<p class="help-block"> Enter the title for the job vacancy. i.e. Arabic Teacher, Language Teacher etc. </p>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="employer-ref"> Number Of Vacancy :<span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="employer-ref" name="no_of_vacancy" value="<?php echo $posted['no_of_vacancy']; ?>" placeholder="No.of Vacancy">
										<p class="help-block"> Enter the title for the job vacancy. </p>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="employer-ref"> Job Reference :</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="employer-ref" name="employer_ref" value="<?php echo get_next_reference();  ?>" disabled="disabled">
										<input type="hidden" class="form-control" id="employer-ref" name="employer_ref" value="<?php echo get_next_reference();  ?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="ad-start-date"> Advertisement Start Date : <span class="mandatory">*</span> </label>
									<div class="col-sm-8">

										<?php $posted['ad_start_date'] = date('d-M-Y', strtotime("+1 days")); ?>

										<input type="text" class="datepicker form-control" id="dt1" name="ad_start_date" value="<?php echo $posted['ad_start_date']; ?>" placeholder="Advertisement Start Date">


										<p class="help-block"> Enter the date from which the vacancy is to be advertised i.e. date the vacancy will be made available to jobseekers. </p>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="ad-close-date"> Advertisement Close Date :</label>
									<div class="col-sm-8">
										<input type="text" class="datepicker form-control" id="dt2" name="ad_close_date" value="<?php echo $posted['ad_close_date']; ?>" placeholder="Advertisement Close Date">
										<p class="help-block">Enter the date on which the vacancy will be withdrawn from display. </p>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="ad-close-date">Interview Start Date :</label>
									<div class="col-sm-8">
										<input type="text" class="datepicker form-control" id="dt3" name="in_start_date" value="<?php echo $posted['in_start_date']; ?>">
										<p class="help-block"> Enter the date on which the interview will be started </p>
									</div>
								</div>


								<style type="text/css">
									#job-classifications optgroup[label] {
										color: #3c763d;
										font-size: 25px;
									}

									#job-classifications optgroup[label] * {
										color: #333;
										font-size: 15px;
									}
								</style>


								<div id="job-classifications" class="form-group">
									<label class="control-label col-sm-4" for="Name">Job Classifications:<span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<?php
										if ($posted['usr_job_category'] == '' || $posted['usr_job_category'] == null)
											$selected_cat = 0;
										else
											$selected_cat = $posted['usr_job_category'];

										$usr_job_category = wp_dropdown_categories(array('show_option_none' => 'Select category', 'echo' => 0, 'taxonomy' => 'job_category', 'selected' => $selected_cat, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
										$usr_job_category = str_replace("name='cat' id=", "name='usr_job_category' id=", $usr_job_category);
										//echo $usr_job_category; 

										if (isset($posted['usr_job_category']) &&  $posted['usr_job_category'] != -1) {
											$selected_cat = $usr_job_category = $posted['usr_job_category'];
											$usr_gen_job_category = -1;
										} elseif (isset($posted['usr_gen_job_category']) &&  $posted['usr_gen_job_category'] != -1) {
											$selected_cat = $usr_gen_job_category = $posted['usr_gen_job_category'];
											$usr_job_category = -1;
										} else {
											$selected_cat = $usr_job_category = $usr_gen_job_category = -1;
										}

										echo kv_merged_taxonomy_dropdown('job_category', 'gen_job_category', $selected_cat);
										echo '<input type="hidden" name="usr_job_category" value="' . $usr_job_category . '" id="usr_job_category" > 
					<input type="hidden" name="usr_gen_job_category" value="' . $usr_gen_job_category . '" id="usr_gen_job_category" > ';
										?>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4">Gender:<span class="mandatory">*</span> </label>
									<div class="col-sm-2">
										<label class="radio-inline">
											<input type="radio" name="gender" value="male" <?php if ($posted['gender'] == 'male') echo 'checked';  ?>> Male
										</label>
									</div>
									<div class="col-sm-2">
										<label class="radio-inline">
											<input type="radio" name="gender" value="female" <?php if ($posted['gender'] == 'female') echo 'checked';  ?>> Female
										</label>
									</div>

									<div class="col-sm-2">
										<label class="radio-inline">
											<input type="radio" name="gender" value="any" <?php if ($posted['gender'] == 'any') echo 'checked';  ?>> Any
										</label>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="Name">Qualification:<span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<?php if ($posted['usr_qualification'] == '' || $posted['usr_qualification'] == null)
											$selected_qul = 0;
										else
											$selected_qul = $posted['usr_qualification'];
										$usr_qualification = wp_dropdown_categories(array('show_option_none' => 'Select category', 'echo' => 0, 'taxonomy' => 'qualification', 'id' => 'job_qualification', 'selected' => $selected_qul, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
										$usr_qualification = str_replace("name='cat' id=", "name='usr_qualification' id=", $usr_qualification);
										echo $usr_qualification; ?>
										<input type="text" class="form-control" id="custom_qualification" name="custom_qualification" value="<?php echo $posted['custom_qualification']; ?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="Name">Job Type:<span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<?php if ($posted['usr_types'] == '' || $posted['usr_types'] == null)
											$selected_type = 0;
										else
											$selected_type = $posted['usr_types'];
										$usr_types = wp_dropdown_categories(array('show_option_none' => 'Select Type', 'echo' => 0, 'taxonomy' => 'types', 'selected' => $selected_type, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
										$usr_types = str_replace("name='cat' id=", "name='usr_types' id=", $usr_types);
										echo $usr_types; ?>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="Name">Years of Exprerience:<span class="mandatory">*</span></label>
									<div class="col-sm-8">
										<?php if ($posted['usr_yr_of_exp'] == '' || $posted['usr_yr_of_exp'] == null)
											$selected_yr_exp = 0;
										else
											$selected_yr_exp = $posted['usr_yr_of_exp'];
										$usr_yr_of_exp = wp_dropdown_categories(array('show_option_none' => 'Select Years of Exprerience', 'echo' => 0, 'taxonomy' => 'yr_of_exp', 'selected' => $selected_yr_exp, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
										$usr_yr_of_exp = str_replace("name='cat' id=", "name='usr_yr_of_exp' id=", $usr_yr_of_exp);
										echo $usr_yr_of_exp; ?>
									</div>
								</div>


								<div class="form-group">
									<label class="control-label col-sm-4" for="experience-details"> Experience Details: </label>
									<div class="col-sm-8">
										<textarea class="form-control" id="job-duties" name="experience_details" placeholder="Experience Details"><?php echo $posted['experience_details']; ?></textarea>
									</div>
								</div>

								<div class="showhideJobCategory" style="border-top:1px solid #ddd; width:100%; height:4px; margin:15px 0; float:left"> </div>
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
								<h3 class="showhideJobCategory "> Denomination : <label class=""> <input type="radio" value="sunni" class="denominationSelection shia_selection" name="denominationSelection" checked="checked"> Sunni </label> <label class="shia_selection"> <input name="denominationSelection" type="radio" value="shia" class="denominationSelection"> Shia </label> </h3>
								<div class="SunniDenomination">
									<div class="form-group showhideJobCategory">
										<label class="control-label col-sm-4" for="Name">Madhab/School of Law:<span class="mandatory">*</span> </label>
										<div class="col-sm-8">
											<?php if ($posted['usr_madhab'] == '' || $posted['usr_madhab'] == null)
												$selected_usr_madhab = 0;
											else
												$selected_usr_madhab = $posted['usr_madhab'];
											$usr_madhab = wp_dropdown_categories(array('show_option_none' => 'Select Madhab', 'echo' => 0, 'taxonomy' => 'madhab', 'selected' => $selected_usr_madhab, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
											$usr_madhab = str_replace("name='cat' id=", "name='usr_madhab' id=", $usr_madhab);
											echo $usr_madhab; ?>
										</div>
									</div>

									<div class="form-group showhideJobCategory">
										<label class="control-label col-sm-4" for="Name">Aqeeda/Belief<span class="mandatory">*</span> </label>
										<div class="col-sm-8">
											<?php if ($posted['usr_aqeeda'] == '' || $posted['usr_aqeeda'] == null)
												$selected_usr_aqeeda = 0;
											else
												$selected_usr_aqeeda = $posted['usr_aqeeda'];
											$usr_aqeeda = wp_dropdown_categories(array('show_option_none' => 'Select Aqeeda/Belief', 'echo' => 0, 'taxonomy' => 'aqeeda', 'selected' => $selected_usr_aqeeda, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
											$usr_aqeeda = str_replace("name='cat' id=", "name='usr_aqeeda' id=", $usr_aqeeda);
											echo $usr_aqeeda;  ?>
										</div>
									</div>
								</div>

								<div class="ShiaDenomination">
									<div class="form-group showhideJobCategory">
										<label class="control-label col-sm-4" for="Name">Madhab/School of Law:<span class="mandatory">*</span> </label>
										<div class="col-sm-8">
											<?php if ($posted['usr_madhab_shia'] == '' || $posted['usr_madhab_shia'] == null)
												$selected_usr_madhab_shia = 0;
											else
												$selected_usr_madhab_shia = $posted['usr_madhab_shia'];
											$usr_madhab_shia = wp_dropdown_categories(array('show_option_none' => 'Select Madhab', 'echo' => 0, 'taxonomy' => 'Shiamadhab', 'selected' => $selected_usr_madhab_shia, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby' => 'NAME', 'order' => 'ASC',));
											$usr_madhab_shia = str_replace("name='cat' id='cat'", "name='usr_madhab_shia' ", $usr_madhab_shia);
											echo $usr_madhab_shia; ?>
										</div>
									</div>

									<div class="form-group showhideJobCategory">
										<label class="control-label col-sm-4" for="Name">Aqeeda/Belief<span class="mandatory">*</span> </label>
										<div class="col-sm-8">
											<?php if ($posted['usr_aqeeda_shia'] == '' || $posted['usr_aqeeda_shia'] == null)
												$selected_usr_aqeeda_shia = 0;
											else
												$selected_usr_aqeeda_shia = $posted['usr_aqeeda_shia']; ?>

											<select name="usr_aqeeda_shia" id="usr_aqeeda_shia">
												<?php Shia_Aqeeda_select($selected_usr_aqeeda_shia); ?>
											</select>

										</div>
									</div>
								</div>


								<div class="form-group">
									<label class="control-label col-sm-4" for="language">Language:<span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<?php if ($posted['usr_language'] == '' || $posted['usr_language'] == null)
											$selected_usr_language = 4;
										else
											$selected_usr_language = $posted['usr_language'];
										$usr_language = wp_dropdown_categories(array('show_option_none' => 'Select Language', 'echo' => 0, 'taxonomy' => 'languages', 'selected' => $selected_usr_language, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0,  'orderby'  => 'name', 'order'      => 'ASC'));
										$usr_language = str_replace("name='cat' id=", "name='usr_language[]' multiple='multiple' id=", $usr_language);
										echo $usr_language; ?>
										<span class="help-block"> To select multiple language press CTRL + click </span>

									</div>
								</div>


								<div class="form-group">

									<label class="control-label col-sm-4" for="Name">Salary: <span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<div style="float:left;width:25%;">
											<input type="text" style="width:90px;" class="form-control2" name="sal_amount" placeholder="amount" value="<?php echo $posted['sal_amount']; ?>">&nbsp; per
										</div>

										<div style="float:left;width:45%;">
											<?php if ($posted['sal_period'] == '' || $posted['sal_period'] == null)
												$selected_sal_period = 0;
											else
												$selected_sal_period = $posted['sal_period'];
											$sal_period = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'sal_prd', 'selected' => $selected_sal_period, 'hierarchical' => true, 'class'  => 'form-control2',  'hide_empty' => 0));
											$sal_period = str_replace("name='cat' id=", "name='sal_period' id=", $sal_period);
											echo $sal_period; ?> OR </div>
										<div style="float:left;width:30%;">

											<?php if ($posted['sa_option'] == '' || $posted['sa_option'] == null)
												$selected_sa_option = 0;
											else
												$selected_sa_option = $posted['sa_option'];
											$sa_option = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'sal_optn', 'selected' => $selected_sa_option, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
											$sa_option = str_replace("name='cat' id=", "name='sa_option' id=", $sa_option);
											echo $sa_option;  ?>
										</div>
									</div>
								</div>




								<div class="form-group">
									<label class="control-label col-sm-4" for="hours-per-week"> Hours Per Week: <span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="hours-per-week" name="hours_per_week" value="<?php echo $posted['hours_per_week']; ?>" />
										<p class="help-block"> Enter the total minimum number of hours to be worked each week. Any variation can be included under "other information" (see below). Hours offered should comply with current legislation. </p>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="work-time"> Work Time:<span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="work-time" name="work_time" value="<?php echo $posted['work_time']; ?>" />
										<p class="help-block"> Enter a breakdown of working hours eg. 9.00am to 5.00pm Mon - Fri, 2.00pm to 9.00pm Wed, Thur & Fri.. </p>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="job-duties"> Job Duties: <span class="mandatory"> * </span> </label>
									<div class="col-sm-8">
										<textarea class="form-control jqte-test" id="rich_text" name="job_duties" placeholder="Job Duties"> <?php if (isset($_POST['job_duties'])) {
																																				echo $_POST['job_duties'];
																																			} ?></textarea>
										<p class="help-block"> Enter the duties of the job. These should be clear and concise. Unfamiliar abbreviations should not be used. A hyperlink or reference to a more detailed job specification can be included in the "other information" field (see below). Capital letters should be used at the beginning of every sentence with one space after each comma, two spaces after each full stop and capitals at the beginning of person or place names. <br>

											You may find it useful before saving your vacancy to select the text in the Job Description window, copy (Ctrl C) and paste it (Ctrl V) into a blank Word Document. Spelling and grammatical errors will be identified and can be corrected and copied back to appropriate box (es).
										</p>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="how-to-apply"> How to Apply: <span class="mandatory"> * </span> </label>
									<div class="col-sm-8"><?php if ($selected_id != '') {
																$apply_online = get_post_meta($selected_id, 'how_to_apply', true);
															}  ?>
										<label class="radio-inline"> <input type="radio" id="apply-online" name="how_to_apply" value="apply_online" <?php if ($_POST['how_to_apply'] == 'apply_online') {
																																						echo 'checked';
																																					} ?>> Apply Manually </label>

										<!-- <label class="radio-inline">	<input type="radio" id="apply-through-email" name="how_to_apply" value="through_email" <?php //if(  $_POST['how_to_apply'] == 'through_email') {  echo 'checked'; }
																																									?>> Apply Through Email </label>-->

										<label class="radio-inline"> <input type="radio" id="manual-method" name="how_to_apply" value="manual_mtd" <?php if ($_POST['how_to_apply'] == 'manual_mtd') {
																																						echo 'checked';
																																					} ?>> Manual Method </label>

										<textarea class="form-control" id="manual-application-method" name="manual_apply_details" placeholder="Manual Application Method"><?php if (isset($_POST['manual_application_method'])) {
																																												echo $_POST['manual_application_method'];
																																											} ?> </textarea>

										<p class="help-block">Enter clear instructions on how to apply for the job: e.g. where application forms can be obtained and, where completed, forms are to be sent. </p>
									</div>
								</div>

								<div style="border-top:1px solid #ddd; width:100%; height:4px; margin:15px 0; float:left"> </div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="monitoring-equality">Application Form/Pack:</label>
									<div class="col-sm-8">
										<input type="file" class="form-control" id="rtf-pdf" name="app_form" value="<?php echo $_FILE['app_form']; ?>">
										<p class="help-block"> Covering Note, Person Specification, Application Form <br />

											(RTF and PDF format) can be uploaded here


										</p>
									</div>
								</div>



								<div class="form-group">
									<label class="control-label col-sm-4" for="confidential">Confidentiality:<span class="mandatory"> * </span> </label>
									<div class="col-sm-8">
										<label class="radio-inline"> <input type="radio" name="confidential" value="yes" <?php if ($selected_id != '') {
																																$confidential = get_post_meta($selected_id, 'confidential', true);
																																if ($confidential == 'yes') echo  'checked';
																															} else if ($_POST['confidential'] == 'yes') echo 'checked';  ?>>Yes</label>
										<label class="radio-inline"> <input type="radio" name="confidential" value="no" <?php if ($selected_id != '') {
																															$confidential = get_post_meta($selected_id, 'confidential', true);
																															if ($confidential == 'no') echo  'checked';
																														} else if ($_POST['confidential'] == 'no') echo 'checked';  ?>>No</label>
										<p class="help-block">Check the tick box if you have a valid reason for your name, address and contact details remaining confidential from applicants. If this is selected the employer's name and contact details will not appear on Job Listing. Employers must have a valid reason for withholding these details.
										</p>

									</div>
								</div>


								<div class="form-group">
									<label class="control-label col-sm-4" for="Name">Eligible to work in : <span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<?php if ($posted['eligible_work_in'] == '' || $posted['eligible_work_in'] == null)
											$selected_usr_zone = 0;
										else
											$selected_usr_zone = $posted['eligible_work_in'];
										$eligible_work_in = wp_dropdown_categories(array('show_option_none' => 'Select Country', 'echo' => 0, 'taxonomy' => 'zone', 'selected' => $selected_usr_zone, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby'            => 'name', 'order'      => 'ASC'));
										$eligible_work_in = str_replace("name='cat' id=", "name='eligible_work_in' id=", $eligible_work_in);
										echo $eligible_work_in; ?>
									</div>
								</div>




								<div class="form-group">
									<label class="control-label col-sm-4">Do you have any legal check requirements?<span class="mandatory">*</span> </label>
									<div class="col-sm-8">
										<div class="col-sm-1">
											<label class="radio-inline">
												<input type="radio" name="dbs" id="dbs_yes" value="yes" <?php if ($posted['dbs'] == 'yes') echo 'checked';  ?>> Yes
											</label>
										</div>
										<div class="col-sm-1">
											<label class="radio-inline">
												<input type="radio" name="dbs" id="dbs_no" value="no" <?php if ($posted['dbs'] == 'no') echo 'checked';  ?>> No
											</label>
										</div>
										<br />
										<p class="help-block"> It is strongly recommended that you (as employer) understand what legal check requirements is and when recruiting an individual for tuition or working with children and or vulnerable individuals, that they (employee) have legal check clearance. </p>

										<br />

										<div id="dbs_upload" style="margin-top:10px;">
											<textarea class="form-control" id="dbs_info_box" name="dbs_info_box" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;">Enter your descriptoin here</textarea>


											<input type="file" class="form-control" id="dbs_file_upload" name="dbs_file_upload" accept='.doc,.docx, .rtf, .pdf'>
										</div>

									</div>

								</div>













								<div class="form-group">
									<label class="control-label col-sm-4" for="accomodation">Accomodation:<span class="mandatory"> * </span> </label>
									<div class="col-sm-8">
										<label class="radio-inline"> <input type="radio" name="accomodation" value="yes" <?php if ($selected_id != '') {
																																$confidential = get_post_meta($selected_id, 'accomodation', true);
																																if ($confidential == 'yes') echo  'checked';
																															} else if ($_POST['accomodation'] == 'yes') echo 'checked';  ?>>Yes</label>
										<label class="radio-inline"> <input type="radio" name="accomodation" value="no" <?php if ($selected_id != '') {
																															$confidential = get_post_meta($selected_id, 'accomodation', true);
																															if ($confidential == 'no') echo  'checked';
																														} else if ($_POST['accomodation'] == 'no') echo 'checked';  ?>>No</label>

										<br /><br />

										<textarea class="form-control" name="accomodation-details" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;">If accommodation is provided or assistance given with the sourcing of accommodation, this should be entered here.</textarea>


									</div>
								</div>


								<div class="form-group">
									<label class="control-label col-sm-4" for="upload-logo">Upload your logo : </label>
									<div class="col-sm-8">
										<input type="file" class="form-control" id="upload-logo" name="company_logo" value="<?php echo $_FILE['company_logo']; ?>">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-4" for="other-information"> Other Information: </label>
									<div class="col-sm-8">
										<textarea class="form-control wysiwyg-editor" id="other-information" name="other_information" placeholder="Other Information"> <?php echo $posted['other_information']; ?></textarea>
									</div>
								</div>

								<div class="col-sm-offset-4">
									<h3>Ongoing Contract</h3>
									<p class="help-block">Ongoing contact will be maintained with you throughout the life of the vacancy. When the vacancy closes you will be asked to provide feedback information on the applications who have applied through a Jobs & Benefits Office / Jobcentre or directly via Jobcentreonline. You should therefore maintain records of the applications received and the outcomes. </p>

								</div>
								<hr />

								<?php if ($enable_employer_subscription == 'yes') {
									$result = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'jbs_subactive WHERE status="Active" AND wp_user_id=' . $current_user->ID);
									$today = date('Y-m-d');
									if ($result->per_post != 0)
										echo "You have " . $result->per_post . " posts <input type='hidden' name='pack_name' value='-1'><input type='hidden' name='pack_no' value='yes'>";
									else if ($result->per_post == 0  && $result->end_date > $today)
										echo "your subscription valid upto " . $result->end_date . "<input type='hidden' name='pack_name' value='-1'><input type='hidden' name='pack_no' value='yes'>";
									else {
										$myrow = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "jbs_subpack WHERE role='employer' AND status ='Active'");
										//print_r($myrow);
										echo '<div class="form-group">
									     	<div class="col-sm-4"> 
                                          		<label class="subscription_pack_label">Subscription <span class="select-pack-text">( Select a Pack)</span> </label>
                                   			</div> 
										
										<div class="col-sm-8">
											<select class="form-control" id="select_pack"  name="pack_name">';
										echo '<option id="pack_name" value="0">Select pack</option>';
										foreach ($myrow as $pack) {
											echo '<option value="' . $pack->id . '" ' . (($posted['pack_name'] == $pack->id) ? 'selected' : '') . '>' . $pack->pack_name . ' ( &pound' . $pack->price . ' : ';
											if ($pack->per_post != 0)
												echo $pack->per_post . " Jobs";
											else
												echo $pack->duration . " " . $pack->period;
											if ($pack->left_count < 0)
												$left_count = ': ' . abs($pack->left_count) . ' Subscriptions Available';
											else
												$left_count = '';

											echo  $left_count . ' )</option>';
										}
										echo '</select></div></div>';
										//echo '<label id="per_post" >price</label>';
										//echo '<label id="">price</label>';
										//echo "You will Subscribe your Post";
									}
								}
								?>


								<div class="checkbox col-xs-12 col-sm-9 col-lg-8 col-sm-offset-3 col-lg-offset-4">
									<?php
									if (isset($_GET['edit_id']) || isset($_GET['clone_id'])) {
										if (isset($_GET['edit_id']))
											$button_name = "Update job";
										else
											$button_name = "Submit a clone job";
									} else
										$button_name = "Submit a job";

									?>
									<input type="submit" class="btn btn-primary" name="submit_a_job" value=" <?php echo $button_name; ?>">

								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- End Form Elements -->
		</div>
	</div>
	<!-- /. ROW  -->
</div>



<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<!--         <h4 class="modal-title">Modal Header</h4> -->
			</div>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<!--         <h4 class="modal-title">Modal Header</h4> -->
				</div>
				<div class="modal-body text-center">
					<div class="<?php echo esc_attr($sidebar_class); ?> donate-btn-employerForm emplDonateForm">
						<?php if (isset($_GET['donation']) && $_GET['donation'] == 'yes') { ?>
							<p class="success">Thank you for your funding and motivate us to provide better service for you.</p>
						<?php } ?>
						<p>Dear user, thank you for using eimams.com to advertise your vacancies. This is currently free of charge, however, if you feel that your organisation/institution/business can support us with some financial help we would be very grateful. This will also allow us to maintain and develop this very needed platform.
						</p>
						<form target="_blank" action="<?php echo site_url(); ?>/payment-page/?donation_yes=yes" method="post">
							<input type="hidden" name="name_of_org" value="Mercy Foundation">
							<select name="amount" id="donation_amt_drp" style="width:120px; margin-right:10px">
								<option value="20.00">&pound;20</option>
								<option value="30.00">&pound;30</option>
								<option value="40.00">&pound;40</option>
								<option value="50.00">&pound;50</option>
								<option value="60.00">&pound;60</option>
								<option value="70.00">&pound;70</option>
								<option value="80.00">&pound;80</option>
								<option value="90.00">&pound;90</option>
								<option value="100.00">&pound;100</option>
								<option value="0">Custom</option>
							</select>
							<input type="text" name="custom_amt" id="custom_amt" value="" size="5" style="width: 65px;">
							<input formtarget="_blank" name="submit" type="submit" style="padding:6px 10px 5px 10px;" value="Support" title="The developers need your donation! Make payments with PayPal - it's fast, free and secure!">
						</form>
					</div> <!-- end of mercy foundation -->
				</div>
			</div>
		</div>

	</div>
</div>

<style>
	.modal-body.text-center {
		padding-left: 20px;
	}

	.modal-dialog {
		max-width: 450px;
		display: flex;
		justify-content: center;
		align-items: center;
		z-index: 9999;
		position: relative;
		height: 100vh;
		margin: auto;
		background: transparent;
	}

	.modal-header {
		border-bottom: 0;
		min-height: 0;
		padding: 0px;
	}

	.modal-header .close {
		margin-top: -4px;
		margin-right: 3px;
		position: relative;
		z-index: 99999;
		font-size: 30px;
	}

	button.close:hover {
		background: transparent;
	}

	.modal {
		z-index: 9999999999999999999;
	}

	.modal-backdrop {
		z-index: 99999;
	}
</style>


<style>
	.SunniDenomination,
	.ShiaDenomination {
		display: none;
	}
</style>
<script>
	$(document).ready(function() {
		$('#myModal').modal('show');

		$('.popup-btn').click(function() {
			$('#myModal').modal('show');
		});
		$(".emplDonateFormBtn").on("click", function(e) {
			e.preventDefault();
			var donation_amt_drp = $("#donation_amt_drp").val();
			var custom_amt = $("#custom_amt").val();
			var rdr_url = '<?php echo site_url("payment-page"); ?>?donation_yes=yes&amount=' + donation_amt_drp + '&custom_amt=' + custom_amt;
			location.replace(rdr_url);
		});
	});

	$(function() {
		$("#custom_amt").hide();
		$("#donation_amt_drp").change(function() {
			//var selectedText = $(this).find("option:selected").text();
			var selectedValue = $(this).val();
			if (selectedValue == 0) {
				$("#custom_amt").show();
			} else {
				$("#custom_amt").hide();
			}
		});
		$("#custom_amt").keyup(function() {
			this.value = this.value.replace(/[^0-9\.]/g, '');
		});
	});

	jQuery(document).ready(function() {

		//Qualitification 
		$('#custom_qualification').hide();
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
		// manual method
		if (jQuery("#manual-method").is(":checked")) {} else {
			jQuery('#manual-application-method').hide();
		}


		$(document).on('change', '#manual-method', function() {
			if (jQuery(this).is(":checked")) {
				jQuery('#manual-application-method').show();
			}
		});

		$(document).on('change', '#apply-online', function() {
			if (jQuery(this).is(":checked")) {
				jQuery('#manual-application-method').hide();
			}
		});

		/*   $(document).on('change','#apply-through-email', function() {
  			if(jQuery(this).is(":checked")) {
				 jQuery('#manual-application-method').hide();
			}
	   });*/


		//  dbs options
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

		$(".denominationSelection").on("change", function(e) {
			var SelectedDenomination = $('.denominationSelection:checked').val();
			//alert(SelectedDenomination);
			if (SelectedDenomination == 'sunni') {
				$(".SunniDenomination").show();
				$(".ShiaDenomination").hide();
			} else {
				$(".SunniDenomination").hide();
				$(".ShiaDenomination").show();
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
</script>


<?php get_footer(); ?>