<?php

global $current_user, $wp_roles, $errors, $wpdb, $user_status, $user_opacity;
wp_get_current_user();
$employer_details = array();
$employer_details['user_email'] = $current_user->user_email;
$employer_details['user_displayname'] = $current_user->display_name;
$wp_upload_path = wp_upload_dir();
$users_table = $wpdb->prefix . 'users';

if (isset($_POST['update_profile'])) {
	$errors = new WP_Error();

	$fields = array(
		'company_name',
		'address1',
		'address2',
		'state_pro_reg',
		'city',
		'usr_zone',
		'post_code',
		'phone_number',
		'user_email',
		'password',
		'website',
		'rep_name',
		'remove_logo',
		'rep_position'
	);

	foreach ($fields as $field) {
		if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field]));
		else $posted[$field] = '';
	}

	$post_code =  $posted['post_code'];
	$address2 =  $posted['address2'];
	if (has_private_employer($current_user->ID) == false) {
		if ($posted['company_name'] != null)
			$company_name =  $posted['company_name'];
		else
			$errors->add('empty_company_name', __('<strong>Notice</strong>: Please enter your Company Name.', 'kv_project'));

		if ($posted['rep_position'] != null)
			$rep_position =  $posted['rep_position'];
		else
			$errors->add('empty_rep_position', __('<strong>Notice</strong>: Please enter your Representative Position.', 'kv_project'));
	}
	if ($posted['address1'] != null)
		$address1 =  $posted['address1'];
	else
		$errors->add('empty_address1', __('<strong>Notice</strong>: Please enter your Address.', 'kv_project'));

	if ($posted['phone_number'] != null)
		$phone_number =  $posted['phone_number'];
	else
		$errors->add('empty_phone_number', __('<strong>Notice</strong>: Please enter your Contact Number.', 'kv_project'));

	if ($posted['user_email'] != null)
		$user_email =  $posted['user_email'];
	else
		$errors->add('empty_user_email', __('<strong>Notice</strong>: Please enter your Email.', 'kv_project'));


	if ($posted['rep_name'] != null)
		$rep_name =  $posted['rep_name'];
	else
		$errors->add('empty_rep_name', __('<strong>Notice</strong>: Please enter your Representative Name.', 'kv_project'));

	if (!$errors->get_error_code()) {

		if (isset($posted['remove_logo']) && $posted['remove_logo'] == 'yes') {
			$company_logo  = get_user_meta($current_user->ID, 'company_logo_attachment_id', true);
			if ($company_logo > 0) {
				kv_delete_attached_files($company_logo);
				delete_user_meta($current_user->ID, 'company_logo_attachment_id');
			}
		}

		if ($_FILES['profile_photo']['size'] > 0) {
			$company_logo  = get_user_meta($current_user->ID, 'company_logo_attachment_id', true);
			if ($company_logo > 0) {
				kv_delete_attached_files($company_logo);
				add_filter('upload_dir', 'kv_company_logo_dir');
				$newupload = kv_insert_attachment($_FILES['profile_photo']);
				remove_filter('upload_dir', 'kv_company_logo_dir');
				$post = strpos($newupload['file'], $wp_upload_path['basedir']);
				$post = $post + 1;
				$modified_upld_path = substr($newupload['file'], $post);
				update_attached_file($company_logo, $modified_upld_path);
				if ($attach_data = wp_generate_attachment_metadata($company_logo, $newupload['file'])) {
					wp_update_attachment_metadata($company_logo, $attach_data);
				}
			} else {
				foreach ($_FILES as $file => $array) {
					if ($file == 'profile_photo') {
						add_filter('upload_dir', 'kv_company_logo_dir');
						$newupload = kv_job_attachment($file, 0, false);
						remove_filter('upload_dir', 'kv_company_logo_dir');
						update_user_meta($current_user->ID, 'company_logo_attachment_id', $newupload);
					}
				}
			}
		}
		/* save/update meta */
		if (has_private_employer($current_user->ID) == false) {
			update_user_meta($current_user->ID, 'company_name', $company_name);
			update_user_meta($current_user->ID, 'rep_position', $rep_position);
		} else
			$website = '';
		update_user_meta($current_user->ID, 'address1', $address1);
		update_user_meta($current_user->ID, 'address2', $address2);
		update_user_meta($current_user->ID, 'state_pro_reg', $posted['state_pro_reg']);
		update_user_meta($current_user->ID, 'city', $posted['city']);
		update_user_meta($current_user->ID, 'usr_zone', $posted['usr_zone']);
		update_user_meta($current_user->ID, 'post_code', $post_code);
		update_user_meta($current_user->ID, 'phone_number', $phone_number);
		//update_user_meta($current_user->ID, 'company_description', $company_description);


		wp_update_user(array('ID' => $current_user->ID, 'user_email' => $user_email, 'display_name' => $rep_name, 'user_url' => $website));
		if ($posted['password'] != null)
			wp_set_password($posted['password'], $current_user->ID);
	}
	if (!$errors->get_error_code()) {
		$status = 'success';
	} else {
		echo $errors->get_error_message();
	}
}
if (isset($_POST['deactivate_profile'])) {

	$wpdb->update($users_table, array('user_status' 	=>  1), array('ID' 	=> 	$current_user->ID));
	$deactivated = 'yes';
	kv_userbackend_user_deactivation($current_user->ID);
}
if (isset($_POST['enable_profile'])) {
	$wpdb->update($users_table, array('user_status' 	=>  0), array('ID' 	=> 	$current_user->ID));
	$deactivated = 'no';
	kv_userbackend_user_activation($current_user->ID);
}
$employer_details = array();
$user_info = get_userdata($current_user->ID);
$employer_details['user_email'] = $user_info->user_email;
$employer_details['user_displayname'] = $user_info->display_name;
?>

<!-- ##############################   Form start here ################################################################################# -->


<?php
if (isset($errors) && $errors->get_error_code()) :
	echo '<ul class="error">';
	foreach ($errors->errors as $error) {
		echo '<li>' . $error[0] . '</li>';
	}
	echo '</ul>';
endif;

if (isset($status) && $status == 'success') {
	echo '<div class="success" > Your Profile has been Updated!.</div>';
	header("Refresh:0");
}

if (isset($deactivated) && $deactivated == 'yes') {
	echo '<div class="success" > Your Profile has been Deactivated!.</div>';
	/*echo "<script type=\"text/javascript\">\n"
		. "<!--\n"
		." sessionStorage.setItem('is_reloaded', 'yes');\n"
		."-->\n"
		. "</script>\n";*/
	header("Refresh:0");
} elseif (isset($deactivated) && $deactivated == 'no') {
	echo '<div class="success" > Your Profile has been Activated!.</div>';
	/*echo "<script type=\"text/javascript\">\n"
		. "<!--\n"
		." sessionStorage.setItem('is_reloaded', 'yes');\n"
		."-->\n"
		. "</script>\n";*/
	header("Refresh:0");
}

?>

<div class="card mb-5 mb-xl-10 mt-5">
	<!--begin::Card header-->
	<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
		<!--begin::Card title-->
		<div class="card-title m-0">
			<h3 class="fw-bold m-0">Profile Details</h3>
		</div>
		<!--end::Card title-->
	</div>
	<!--begin::Card header-->

	<!--begin::Content-->
	<div id="kt_account_settings_profile_details" class="collapse show">
		<?php $form_opacity = 1;
		$disabled = '';
		if (kv_get_user_status()  == 2) {
			echo '<div class="alert alert-warning box-shadow">
								<a href="#" class="close" data-dismiss="alert">×</a>
								<strong>Warning!</strong> You are blocked by Administrator. Use Help and Support to know contact Us. Check your email for reason. </div>';
			$form_opacity = 0.4;
			$disabled = 'disabled';
		} ?>
		<!--begin::Form-->
		<form method="post" enctype="multipart/form-data" class="form fv-plugins-bootstrap5 fv-plugins-framework">
			<!--begin::Card body-->
			<div class="card-body border-top p-9">
				<!--begin::Input group-->
				<div class="row mb-6">
					<!--begin::Label-->
					<label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
					<!--end::Label-->

					<!--begin::Col-->
					<div class="col-lg-8">
						<input type="file" class="form-control" name="profile_photo" accept="image/*" id="profile_photo">
						<?php
						$get_user_image_id = get_user_meta($current_user->ID, 'company_logo_attachment_id', true);
						if ($get_user_image_id > 0) { ?>
							<label> <input type="checkbox" name="remove_logo" class="mt-5" value="yes"> Remove Logo</label> <?php } ?>
						<!--begin::Hint-->
						<div class="form-text">Allowed file types: png, jpg, jpeg.</div>
						<!--end::Hint-->
					</div>
					<!--end::Col-->
				</div>
				<!--end::Input group-->

				<?php if (has_private_employer($current_user->ID) == false) {	 ?>
					<!--begin::Input group-->
					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-4 col-form-label required fw-semibold fs-6">Company/Organisation Name:</label>
						<!--end::Label-->

						<!--begin::Col-->
						<div class="col-lg-8 fv-row fv-plugins-icon-container">
							<input type="text" class="form-control form-control-lg form-control-solid" name="company_name" placeholder="Name" value="<?php echo get_user_meta($current_user->ID, 'company_name', true); ?>">
						</div>
						<!--end::Col-->
					</div>
					<!--end::Input group-->
				<?php } ?>

				<div class="row mb-6">
					<label class="col-lg-4 col-form-label required fw-semibold fs-6">Address 1</label>
					<div class="col-lg-8 fv-row fv-plugins-icon-container">
						<input type="text" class="form-control form-control-lg form-control-solid" name="address1" placeholder="Address" value="<?php echo get_user_meta($current_user->ID, 'address1', true); ?>">
					</div>
				</div>

				<div class="row mb-6">
					<label class="col-lg-4 col-form-label required fw-semibold fs-6">Address 2</label>
					<div class="col-lg-8 fv-row fv-plugins-icon-container">
						<input type="text" class="form-control form-control-lg form-control-solid" name="address2" placeholder="Address" value="<?php echo get_user_meta($current_user->ID, 'address2', true); ?>">
					</div>
				</div>

				<div class="row mb-6">
					<label class="col-lg-4 col-form-label required fw-semibold fs-6">State/Province/Region:</label>
					<div class="col-lg-8 fv-row fv-plugins-icon-container">
						<input type="text" class="form-control form-control-lg form-control-solid" name="state_pro_reg" placeholder="State/Province/Region:" value="<?php echo get_user_meta($current_user->ID, 'state_pro_reg', true); ?>">
					</div>
				</div>

				<div class="row mb-6">
					<label class="col-lg-4 col-form-label required fw-semibold fs-6">City: </label>
					<div class="col-lg-8 fv-row fv-plugins-icon-container">
						<input type="text" class="form-control form-control-lg form-control-solid" name="city" placeholder="City" value="<?php echo get_user_meta($current_user->ID, 'city', true); ?>">
					</div>
				</div>

				<div class="row mb-6">
					<label class="col-lg-4 col-form-label required fw-semibold fs-6">Post Code: </label>
					<div class="col-lg-8 fv-row fv-plugins-icon-container">
						<input type="text" class="form-control form-control-lg form-control-solid" name="post_code" placeholder="Post Code" value="<?php echo get_user_meta($current_user->ID, 'post_code', true); ?>">
					</div>
				</div>

				<div class="row mb-6">
					<label class="col-lg-4 col-form-label required fw-semibold fs-6">Country: </label>
					<div class="col-lg-8 fv-row fv-plugins-icon-container">
						<?php $selected_usr_zone = get_user_meta($current_user->ID, 'usr_zone', true);
						$usr_zone = wp_dropdown_categories(array('show_option_none' => 'Select Country', 'echo' => 0, 'taxonomy' => 'zone', 'selected' => $selected_usr_zone, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby'            => 'name', 'order'      => 'ASC'));
						$usr_zone = str_replace("name='cat' id=", "name='usr_zone' id=", $usr_zone);
						echo $usr_zone; ?> </div>
				</div>

				<div class="row mb-6">
					<label class="col-lg-4 col-form-label required fw-semibold fs-6">Phone </label>
					<div class="col-lg-8 fv-row fv-plugins-icon-container">
						<input type="tel" class="form-control form-control-lg form-control-solid" name="phone_number" placeholder="Phone Number" value="<?php echo get_user_meta($current_user->ID, 'phone_number', true); ?>">
					</div>
				</div>

				<div class="row mb-6">
					<label class="col-lg-4 col-form-label required fw-semibold fs-6">Email </label>
					<div class="col-lg-8 fv-row fv-plugins-icon-container">
						<input type="email" class="form-control form-control-lg form-control-solid" name="user_email" placeholder="Email" value="<?php echo $current_user->user_email; ?>"> <span class="mandatory">
					</div>
				</div>

				<div class="row mb-6">
					<label class="col-lg-4 col-form-label required fw-semibold fs-6">Password </label>
					<div class="col-lg-8 fv-row fv-plugins-icon-container">
						<input type="password" class="form-control form-control-lg form-control-solid" name="password" placeholder="Password"> <span class="mandatory">
					</div>
				</div>

				<?php if (has_private_employer($current_user->ID) == false) {	 ?>
					<div class="row mb-6">
						<label class="col-lg-4 col-form-label required fw-semibold fs-6">Website </label>
						<div class="col-lg-8 fv-row fv-plugins-icon-container">
							<input type="url" class="form-control form-control-lg form-control-solid" name="website" value="<?php echo $current_user->user_url; ?>" placeholder="Website">
						</div>
					</div>
				<?php } ?>
				<div class="border-top my-5"></div>
				<h3 class="my-5">Personal Details of Representative of the Company/Organisation </h3>
				<!--begin::Input group-->

				<div class="row mb-6">
					<label class="col-lg-4 col-form-label required fw-semibold fs-6">Name </label>
					<div class="col-lg-8 fv-row fv-plugins-icon-container">
						<input type="text" class="form-control form-control-lg form-control-solid" name="rep_name" value="<?php echo $current_user->display_name; ?>" placeholder="Name">
					</div>
				</div>
				<?php if (has_private_employer($current_user->ID) == false) { ?>
					<div class="row mb-6">
						<label class="col-lg-4 col-form-label required fw-semibold fs-6">Position </label>
						<div class="col-lg-8 fv-row fv-plugins-icon-container">
							<input type="text" class="form-control form-control-lg form-control-solid" name="rep_position" value="<?php echo get_user_meta($current_user->ID, 'rep_position', true); ?>" placeholder="Position">
						</div>
					</div>
				<?php } ?>
			</div>
			<!--end::Card body-->

			<!--begin::Actions-->
			<div class="card-footer d-flex justify-content-end gap-2 py-6 px-9">

				<input type="submit" class="btn btn-primary" name="update_profile" value=" Update Profile" <?php echo $disabled; ?>>

				<?php if (kv_get_user_status()  == 0) { ?> <input type="submit" class="btn btn-info" name="deactivate_profile" value=" Deactivate Profile"> <?php } elseif (kv_get_user_status()  == 1) { ?>
					<input type="submit" class="btn btn-info" name="enable_profile" value=" Activate Profile">
				<?php } ?>

				<!--end::Actions-->
				<input type="hidden">
		</form>
		<!--end::Form-->
	</div>
	<!--end::Content-->
</div>
<!-- /. ROW  -->
<?php
/*echo "<script type=\"text/javascript\">\n"
		. "<!--\n"
		." if (sessionStorage.getItem('is_reloaded')){ "
		. "storage.removeItem('is_reloaded');\n"
		."window.location.reload();\n"
		//." sessionStorage.setItem('is_reloaded', 'yes');\n"
		. "}  \n"
		."-->\n"
		. "</script>\n";*/

?>