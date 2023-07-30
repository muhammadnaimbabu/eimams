<?php

/* Template Name: Jobseekers Profile Frontend */

get_header();
if (is_user_logged_in()) {
	wp_safe_redirect(site_url('profile-update'));
}
global $wpdb;
$job_seeker_table = $wpdb->prefix . 'jobseeker';
$sub_table = $wpdb->prefix . 'newsletter';

$fields = array(
	'jobseeker_name',
	'address1',
	'address2',
	'city',
	'state_pro_reg',
	'post_code',
	'phone_number',
	'UserName',
	'usr_email',
	'PassWord',
	'usr_job_category',
	'usr_gen_job_category',
	'custom_qualification',
	'gender',
	'usr_qualification',
	'job_types',
	'usr_yr_of_exp',
	'usr_madhab',
	'usr_madhab_shia',
	'usr_aqeeda',
	'usr_aqeeda_shia',
	//'usr_language',
	'usr_zone',
	'marketing_area',

	//'pref_sal_brn',
	//'pref_sal_end',
	//'pref_sal_prd',
	//'pref_sal_optn',

	'sal_amount',
	'sal_period',
	'sa_option',

	'job_alert',
	'common_alert',
	'pack_name',
	'term_check',
	'dbs',
	'dbs_info_box'
);

foreach ($fields as $field) {
	if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field]));
	else $posted[$field] = '';
}

if (isset($jobseeker_details['cv_info'])) $cv_file_path = $jobseeker_details['cv_info'];
else $cv_file_path = '';

if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['submit_a_job'])) {
	$errors = new WP_Error();

	if ($posted['jobseeker_name'] == null)
		$errors->add('empty_jobseeker_name', __('<strong>Notice</strong>: Please enter your Name.', 'kv_project'));

	if ($posted['address1'] == null)
		$errors->add('empty_address1', __('<strong>Notice</strong>: Please enter your Address.', 'kv_project'));

	if ($posted['phone_number'] == null)
		$errors->add('empty_phone_number', __('<strong>Notice</strong>: Please enter your Contact Number.', 'kv_project'));


	if ($posted['UserName'] != null) {
		$user_id = username_exists($posted['UserName']);
		if (!$user_id)
			$UserName =  $posted['UserName'];
		else
			$errors->add('exist_UserName', __('<strong>Notice</strong>: UserName Already Exist.', 'kv_project'));
	} else
		$errors->add('empty_UserName', __('<strong>Notice</strong>: Please enter UserName.', 'kv_project'));


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


	if ($posted['PassWord'] != null)
		$PassWord =  $posted['PassWord'];
	else
		$errors->add('empty_PassWord', __('<strong>Notice</strong>: Please enter Password.', 'kv_project'));


	if ($posted['usr_job_category'] == -1 &&  $posted['usr_gen_job_category'] == -1)
		$errors->add('empty_usr_job_category', __('<strong>Notice</strong>: Please Select a Job Category.', 'kv_project'));

	if ($posted['gender'] == null)
		$errors->add('empty_gender', __('<strong>Notice</strong>: Please Select your Gender.', 'kv_project'));


	if ($posted['usr_qualification'] != -1) {
		if ($posted['usr_qualification'] == 542 && $posted['custom_qualification'] == '')
			$errors->add('empty_usr_qualification', __('<strong>Notice</strong>: Please Enter your Qualification.', 'kv_project'));
		else
			$usr_qualification =  $posted['usr_qualification'];
	} else
		$errors->add('empty_usr_qualification', __('<strong>Notice</strong>: Please Select Your Qualification.', 'kv_project'));


	if ($posted['job_types'] == -1)
		$errors->add('empty_job_types', __('<strong>Notice</strong>: Please Select your Job Types', 'kv_project'));

	if ($posted['usr_yr_of_exp'] == -1)
		$errors->add('empty_usr_yr_of_exp', __('<strong>Notice</strong>: Please Select your Experience.', 'kv_project'));


	if ($posted['usr_gen_job_category'] == -1) {

		if ($posted['usr_madhab'] == -1 && $posted['usr_madhab_shia'] == -1)
			$errors->add('empty_usr_madhab', __('<strong>Notice</strong>: Please Select your Madhab.', 'kv_project'));

		if ($posted['usr_aqeeda'] == -1 && $posted['usr_aqeeda_shia'] == -1)
			$errors->add('empty_usr_aqeeda', __('<strong>Notice</strong>: Please Select your Aqeeda.', 'kv_project'));
	} else {
		$posted['usr_madhab'] = $posted['usr_aqeeda'] = $posted['usr_aqeeda_shia'] = $posted['usr_madhab_shia'] = '';
	}

	if ($posted['usr_language'] == -1)
		$errors->add('empty_usr_language', __('<strong>Notice</strong>: Please Select your Language.', 'kv_project'));


	if ($posted['usr_zone'] == -1)
		$errors->add('empty_usr_zone', __('<strong>Notice</strong>: Please Select your Location.', 'kv_project'));


	if (($posted['sal_amount'] != null &&  $posted['sal_period'] != -1) || $posted['sa_option'] != -1) {
		$sal_amount =  $posted['sal_amount'];
	}/* else
		$errors->add('empty_sal_amount', __('<strong>Notice</strong>: Please Select Salary amount, Term and Its Options. It is mandatory one.', 'kv_project'));
		*/

	if ($posted['usr_zone'] == -1)
		$errors->add('empty_usr_zone', __('<strong>Notice</strong>: Please Select your Location.', 'kv_project'));

	if ($posted['dbs'] == 'yes' || $posted['dbs'] == 'no') {
		/*if (empty($_FILES['dbs_file_upload']['name']) || $posted['dbs_info_box'] == null)
			$errors->add('empty_dbs', __('<strong>Notice</strong>: Please Provide the DBS description.', 'kv_project'));	*/
	} else
		$errors->add('empty_dbs', __('<strong>Notice</strong>: Please Provide the DBS.', 'kv_project'));

	if ($posted['term_check'] != 'checked')
		$errors->add('empty_term_check', __('<strong>Notice</strong>: Please Select Terms and Condition.', 'kv_project'));
	if ($enable_subscription == 'yes') {
		if ($posted['pack_name'] >= 1)
			$pack_name =  $_POST['pack_name'];
		else if ($posted['pack_name'] == 0)
			$errors->add('empty_pack_name', __('<strong>Notice</strong>: Please Select a Pack before Submit.', 'kv_project'));
	}
	//if (empty($_FILES['upload_cv']['name']) )
	//	$errors->add('empty_upload_cv', __('<strong>Notice</strong>: Please Upload Your CV', 'kv_project'));

	if ($posted['marketing_area'] == -1)
		$errors->add('marketing_area', __('<strong>Notice</strong>: Please Select How did you hear about us.', 'kv_project'));

	$dbs_file_upload = '';

	//var_dump($posted);
	if (!$errors->get_error_code()) {

		$user_id = wp_create_user($UserName, $PassWord, $usr_email);
		$u = new WP_User($user_id);
		// Remove role
		$u->remove_role('subscriber');
		// Add role
		$u->add_role('job_seeker');

		wp_clear_auth_cookie();
		wp_set_current_user($user_id);
		wp_set_auth_cookie($user_id);
		wp_update_user(array('ID' => $user_id, 'display_name' => $website));

		if ($_FILES) {
			foreach ($_FILES as $file => $array) {
				if ($file == 'profile_photo' &&  $_FILES['profile_photo']['size'] > 0) {
					$name = pathinfo($_FILES["profile_photo"]["name"]);

					add_filter('upload_dir', 'kv_jobseeker_dp_dir');

					$jobseeker_dp = kv_insert_attachment($_FILES['profile_photo']);
					update_user_meta($user_id, 'user_image', $jobseeker_dp);
					remove_filter('upload_dir', 'kv_jobseeker_dp_dir');
				}
				if ($file == 'upload_cv' && $_FILES['upload_cv']['size'] > 0) {
					$cv_name = pathinfo($_FILES["upload_cv"]["name"]);
					//print_r($cv_name);
					if ($cv_name['extension'] == 'doc' || $cv_name['extension'] == 'docx' || $cv_name['extension'] == 'rtf' || $cv_name['extension'] == 'pdf' || $cv_name['extension'] == 'txt') {
						add_filter('upload_dir', 'kv_jobseeker_cv_dir');
						$cv_file_path = kv_job_attachment($file, 0, false);
						remove_filter('upload_dir', 'kv_jobseeker_cv_dir');
					}
				}
				if ($file == 'dbs_file_upload' && $_FILES['dbs_file_upload']['size'] > 0) {
					$cv_name = pathinfo($_FILES["dbs_file_upload"]["name"]);
					//print_r($cv_name);
					if ($cv_name['extension'] == 'doc' || $cv_name['extension'] == 'docx' || $cv_name['extension'] == 'rtf' || $cv_name['extension'] == 'pdf' || $cv_name['extension'] == 'txt') {
						add_filter('upload_dir', 'kv_jobseeker_cv_dir');
						$dbs_file_upload = kv_job_attachment($file, 0, false);
						remove_filter('upload_dir', 'kv_jobseeker_cv_dir');
					}
				}
			}
		} //upload_cv
		//update_user_meta($current_user->ID, 'user_email', $user_email);

		$wpdb->insert(
			$job_seeker_table,
			array(
				'wp_usr_id' 		=> 	$user_id,
				'address1' 			=>  $posted['address1'],
				'address2' 			=>  $posted['address2'],
				'post_code'			=>  $posted['post_code'],
				'phone' 			=>  $posted['phone_number'],
				'category' 			=> ($posted['usr_job_category'] != -1 ? $posted['usr_job_category'] : ($posted['usr_gen_job_category'] != -1 ? $posted['usr_gen_job_category'] : '')),
				'gender' 			=>  $posted['gender'],
				'qualification' 	=>  $posted['usr_qualification'],
				'type' 				=>  $posted['job_types'],
				'yr_or_exp' 		=>  $posted['usr_yr_of_exp'],
				'madhab' 			=>  $posted['usr_madhab'],
				'madhab_shia' 		=>  $posted['usr_madhab_shia'],
				'aqeeda' 			=>  $posted['usr_aqeeda'],
				'aqeeda_shia' 		=>  $posted['usr_aqeeda_shia'],
				//'language' 			=>  $posted['usr_language'],
				'location' 			=>  $posted['usr_zone'],
				'pref_sal_bgn' 		=>  $posted['sal_amount'],
				'pref_sal_end' 		=>  $posted['sal_amount'],
				'pref_sal_prd' 		=>  $posted['sal_period'],
				'pref_sal_optn' 	=>  $posted['sa_option'],
				'dbs' 				=>  $posted['dbs'],
				'cv_info' 			=>  $cv_file_path,
				'dbs_description'	=>  $posted['dbs_info_box'],
				'dbs_file' 			=>  $dbs_file_upload
			)
		);

		add_user_meta($user_id, 'marketing_area',  $posted['marketing_area']);
		add_user_meta($user_id, 'languages',  $_POST['usr_language']);
		add_user_meta($user_id, 'state_pro_reg', $posted['state_pro_reg']);
		add_user_meta($user_id, 'city', $posted['city']);
		add_user_meta($user_id, 'custom_qualification', $posted['custom_qualification']);


		$subscriber_exist = eimams_has_email_subscriber($usr_email);
		if ($subscriber_exist) {
			$wpdb->update($sub_table, array(
				'wp_user_id'	=> 	$user_id,
				'email' 		=> 	$usr_email,
				'common'		=>	$posted['common_alert'],
				'jobalert'		=>	$posted['job_alert'],
				'job_cat_id'	=>	$_POST['usr_job_category']
			), array('id' =>  $subscriber_exist));
		} else {
			$status = $wpdb->insert($sub_table, array(
				'date' 			=> 	date('Y-m-d'),
				'unit_id'		=>	uniqid(),
				'wp_user_id'	=> 	$user_id,
				'email' 		=> 	$usr_email,
				'common'		=>	$posted['common_alert'],
				'jobalert'		=>	$posted['job_alert'],
				'job_cat_id'	=>	$_POST['usr_job_category'],
				'status' 		=> 	0
			));
		}
		kv_new_user_notification($user_id, $PassWord);
		if ($enable_subscription == 'yes') {
			$pack_details = get_all_packdetails_using_id($posted['pack_name']);
			if ($pack_details['price'] != 0)
				wp_safe_redirect(site_url('payment-page') . '?pack_id=' . $posted['pack_name']);
			else
				wp_safe_redirect(site_url('dashboard'));
		} else
			wp_safe_redirect(site_url('dashboard'));
	}
} ?>

<section class="bootstrap-wrapper employe__registration">
	<!-- // Require the jobseeker profile template  -->
	<?php include(get_template_directory() . "/template/form/jobseeker-sign-up.php") ?>
</section>

<!--
	Modal and the other things
	:: Like ::
	-- Display content accroding to the form behabear
	-- Some custom css that is needed
-->
<script type="text/javascript">
	jQuery(document).ready(function() {


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


		//Qualitification
		$('#custom_qualification').hide();
		$(document).on('change', '#job_qualification', function() {
			if ($(this).val() == 542)
				$('#custom_qualification').show();
			else
				$('#custom_qualification').hide();
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

		jQuery(".merged_taxonomy").on("change", function() {
			var txt_val = $(this).val();
			var typE = jQuery('option:selected', this).attr('type');
			//alert(typE);
			if (typE == 'job_category') {
				jQuery("#usr_job_category").val(txt_val);
				jQuery("#usr_gen_job_category").val(-1);
				jQuery(".showhideJobCategory").show();
				jQuery(".denominationSelection").trigger('change');
			} else if (typE != '') {
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