<?php

/* Template Name: Employers  Profile Frontend */

get_header();


global $wpdb;
$sub_active = $wpdb->prefix . 'jbs_subactive';
$jbs_subpack = $wpdb->prefix . 'jbs_subpack';


// if the user is logged in it will redirect to employer user backend add new job page
if (is_user_logged_in()) {
	wp_safe_redirect(site_url('add-new-job'));
}

?>

<link href="<?php echo get_template_directory_uri(); ?>/css/datepicker.css" rel="stylesheet" />

<?php
$posted['ad_close_date'] = date("d-M-Y", strtotime("+12 days"));
$posted['in_start_date'] = date("d-M-Y", strtotime("+14 days"));

// ######  Error Hangling start here  #################

if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['submit_a_job'])) {

	$errors = new WP_Error();
	$fields = array(
		'title',
		'company_name',
		'website',
		'address1',
		'address2',
		'city',
		'state_pro_reg',
		'post_code',
		'phone_number',
		'usr_email',
		'UserName',
		'PassWord',
		'representative_name',
		'rep_position',
		'marketing_area',

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

		'eligible_work_in',
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
		'accomodation',
		'other_information',
		'how_to_apply',
		'manual_apply_details',
		'monitoring_equalty',
		'equalty_statement',
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

	if ($posted['representative_name'] != null)
		$representative_name =  $posted['representative_name'];
	else
		$errors->add('empty_representative_name', __('<strong>Notice</strong>: Please enter Representative Name.', 'kv_project'));


	if ($posted['rep_position'] != null)
		$rep_position =  $posted['rep_position'];
	else
		$errors->add('empty_rep_position', __('<strong>Notice</strong>: Please enter Position of Representative.', 'kv_project'));

	if ($posted['address1'] != null)
		$address1 =  $posted['address1'];
	else
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
		$errors->add('empty_title', __('<strong>Notice</strong>: Please enter Job Title.', 'kv_project'));


	if ($posted['company_name'] != null)
		$company_name =  $posted['company_name'];
	else
		$errors->add('empty_company_name', __('<strong>Notice</strong>: Please enter your Company Name.', 'kv_project'));


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
	// if ($_POST['usr_language'] != -1 )
	// $usr_language =  $_POST['usr_language'];
	// else
	// $errors->add('empty_usr_language', __('<strong>Notice</strong>: Please Select Your Language.', 'kv_project'));
	// var_dump($_POST['usr_language']);
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

	/* if ($posted['confidential'] != null )
		$confidential =  $posted['confidential'];
	else
		$errors->add('empty_confidential', __('<strong>Notice</strong>: Please Select Confidential', 'kv_project'));
			*/
	if ($posted['accomodation'] != null)
		$accomodation =  $posted['accomodation'];
	else
		$errors->add('empty_accomodation', __('<strong>Notice</strong>: Please Select Accommodation', 'kv_project'));

	if ($posted['monitoring_equalty'] != null) {
		if (empty($_FILES['monitoring_equality']['name']) && $posted['monitoring_equalty'] == 'yes')
			$errors->add('empty_monitoring_equality', __('<strong>Notice</strong>: Please Select Attachment for Monitoring Equality', 'kv_project'));
	} else
		$errors->add('empty_monitoring_equalty', __('<strong>Notice</strong>: Please Select Monitoring Equality', 'kv_project'));

	if ($posted['equalty_statement'] != null) {
		if (empty($_FILES['equality_statement']['name']) && $posted['equalty_statement'] == 'yes')
			$errors->add('empty_equality_statement', __('<strong>Notice</strong>: Please Select Attachment for Equality Statement', 'kv_project'));
	} else
		$errors->add('empty_equalty_statement', __('<strong>Notice</strong>: Please Select Equality Statement', 'kv_project'));

	if ($posted['pension_provision'] != null) {
		if ($posted['pension_provision'] == 'yes') {
			if ($posted['pension_provision_dropdown'] != null) {
			} else {
				$errors->add('empty_pension_provision_dropdown', __('<strong>Notice</strong>: Please Select an option from the pensions drop down', 'kv_project'));
			}
		}
	} else
		$errors->add('empty_pension_provision', __('<strong>Notice</strong>: Please Select Pension Provision', 'kv_project'));


	if ($posted['dbs'] == 'yes' || $posted['dbs'] == 'no' || $posted['dbs'] == 'Not Applicable') {
		/*if (empty($_FILES['dbs_file_upload']['name']) || $posted['dbs_info_box'] == null)
			$errors->add('empty_dbs', __('<strong>Notice</strong>: Please Provide the DBS description.', 'kv_project'));	*/
	} else
		$errors->add('empty_dbs', __('<strong>Notice</strong>: Please Provide the DBS.', 'kv_project'));


	if ($posted['marketing_area'] == -1)
		$errors->add('marketing_area', __('<strong>Notice</strong>: Please Select How did you hear about us.', 'kv_project'));

	if (!$errors->get_error_code()) {
		$user_id = wp_create_user($UserName, $PassWord, $usr_email);
		$u = new WP_User($user_id);
		// Remove role
		$u->remove_role('subscriber');
		// Add role
		$u->add_role('employer');

		add_user_meta($user_id, 'representative_name', $representative_name);
		//add_user_meta( $user_id, 'rep_position', $rep_position);
		add_user_meta($user_id, 'company_name',  $posted['company_name']);
		add_user_meta($user_id, 'marketing_area',  $posted['marketing_area']);
		add_user_meta($user_id, 'address1', $posted['address1']);
		add_user_meta($user_id, 'address2', $posted['address2']);
		add_user_meta($user_id, 'state_pro_reg', $posted['state_pro_reg']);
		add_user_meta($user_id, 'city', $posted['city']);
		add_user_meta($user_id, 'usr_zone', $posted['usr_zone']);

		add_user_meta($user_id, 'post_code', $posted['post_code']);
		add_user_meta($user_id, 'phone_number', $phone_number);
		//update_user_meta($current_user->ID, 'company_description', $company_description);
		add_user_meta($current_user->ID, 'rep_position', $rep_position);
		kv_new_user_notification($user_id, $PassWord);

		wp_clear_auth_cookie();
		wp_set_current_user($user_id);
		wp_set_auth_cookie($user_id);
		$pack_details = get_all_packdetails_using_id($pack_name);
		if ($pack_details['price'] != 0)
			$post_status = 'draft';
		else
			$post_status = 'pending';
		$new_post = array(
			'post_title'	=>	$title,
			'post_content'	=>	$job_duties,
			//'post_category' =>   $qualification,
			'post_status'	=>	$post_status,
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
				'languages' => $_POST['usr_language'],
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
			update_post_meta($jid, '_yoast_wpseo_metadesc', substr($job_duties, 0, 140));
			update_post_meta($jid, 'custom_qualification', $posted['custom_qualification']);
			update_post_meta($jid, 'company_name', $posted['company_name']);
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
			update_post_meta($jid, 'pension_provision', $posted['pension_provision']);
			if ($posted['pension_provision'] != null || $posted['pension_provision'] != '')
				update_post_meta($jid, 'pension_provision_dropdown', $posted['pension_provision_dropdown']);
			update_post_meta($jid, 'confidential', $confidential);
			update_post_meta($jid, 'accomodation', $accomodation);
			update_post_meta($jid, 'other_information', $other_information);
			update_post_meta($jid, 'website',  $posted['website']);
			update_post_meta($jid, 'company_description', $posted['company_description']);
			update_post_meta($jid, 'no_of_vacancy', $posted['no_of_vacancy']);
			update_post_meta($jid, 'address1', $posted['address1']);
			update_post_meta($jid, 'address2', $posted['address2']);
			update_post_meta($jid, 'state_pro_reg', $posted['state_pro_reg']);
			update_post_meta($jid, 'city', $posted['city']);
			update_post_meta($jid, 'post_code', $posted['post_code']);
			update_post_meta($jid, 'monitoring_equality', $posted['monitoring_equalty']);
			update_post_meta($jid, 'equality_statement', $posted['equalty_statement']);
			update_post_meta($jid, 'eligible_work_in', $posted['eligible_work_in']);
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
					if ($file == 'equality_statement') {
						if ($array['type'] == 'application/pdf' || $array['type'] == 'application/msword' || $array['type'] == 'application/vnd.ms-powerpoint' || $array['type'] == 'application/rtf') {

							add_filter('upload_dir', 'kv_files_dir');

							$equality_statement = kv_job_attachment($file, $jid, false);
							update_post_meta($jid, 'equality_statement', $equality_statement);
							remove_filter('upload_dir', 'kv_files_dir');
						}
					}
					if ($file == 'monitoring_equality') {
						if ($array['type'] == 'application/pdf' || $array['type'] == 'application/msword' || $array['type'] == 'application/vnd.ms-powerpoint' || $array['type'] == 'application/rtf') {

							add_filter('upload_dir', 'kv_files_dir');

							$monitoring_equality = kv_job_attachment($file, $jid, false);
							update_post_meta($jid, 'monitoring_equality', $monitoring_equality);
							remove_filter('upload_dir', 'kv_files_dir');
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
<!-- // Include Signup template  -->
<?php include(get_template_directory() . "/template/form/employer-sign-up.php") ?>


<!--
	Modal and the other things
	:: Like ::
	-- Display content accroding to the form behabear
	-- Display modal of the donation form
	-- Some custom css that is needed
-->


<!-- Modal content-->
<div id="myModal" class="max_modal">
	<div class="new-modal">
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


<script type="text/javascript">
	$(document).ready(function() {

		// alert('W');
		$('#myModal').css("visibility", "visible");

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
</script>
<style>
	.SunniDenomination,
	.ShiaDenomination {
		display: none;
	}
</style>


<script>
	jQuery(document).ready(function() {

		$.get("https://api.ipdata.co", function(response) {
			//alert(response['country_name']);
			$('select[name="usr_zone"]').find('option[text=' + response['country_name'] + ']').prop("selected", true);

		}, "jsonp");

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