<?php 
global $current_user, $wp_roles, $wpdb, $enable_shia_subscription;
wp_get_current_user();
$job_seeker_table = $wpdb->prefix.'jobseeker';
$users_table = $wpdb->prefix.'users';
$sub_table = $wpdb->prefix.'newsletter';
$enable_shia_subscription = get_option('enable_shia_subscription'); 


$jobseeker_details = array();
$jobseeker_details = kv_get_jobseeker_details($current_user->ID);
$jobseeker_details['user_email'] = $current_user->user_email; 
$jobseeker_details['user_displayname'] = $current_user->display_name; 
$new_user = '';
if(isset($jobseeker_details['cv_info'])) $cv_file_path = $jobseeker_details['cv_info']; 
else $cv_file_path = '';
if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['update_profile'])) {

	$errors = new WP_Error();	
	$fields = array(
				'jobseeker_name',
				'address1',
				'address2',
				'city',
				'state_pro_reg',
				'post_code',
				'phone_number',
				'user_email',
				'password',				
				'usr_job_category',				
				'usr_gen_job_category',				
				'gender',				
				'usr_qualification',	
				'custom_qualification',
				'usr_types',				
				'usr_yr_of_exp',				
				'usr_madhab',				
				'usr_aqeeda',
				'usr_madhab_shia',				
				'usr_aqeeda_shia',				
				//'usr_language',				
				'usr_zone',				
				'pref_sal_brn',				
				'pref_sal_end',				
				'pref_sal_prd',				
				'pref_sal_optn',
				'job_alert',
				'dbs',
				'dbs_info_box'	
	
			);
	foreach ($fields as $field) {
		if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
	}
	
	if ($posted['jobseeker_name'] != null )
		$jobseeker_name =  $_POST['jobseeker_name'];
	else 
		$errors->add('empty_jobseeker_name', __('<strong>Notice</strong>: Please enter your Name.', 'kv_project'));
	
	if ($posted['address1'] != null )
		$address1 =  $_POST['address1'];
	else 
		$errors->add('empty_address1', __('<strong>Notice</strong>: Please enter your Address.', 'kv_project'));
		
	if ($posted['phone_number'] != null )
		$phone_number =  $_POST['phone_number'];
	else 
		$errors->add('empty_phone_number', __('<strong>Notice</strong>: Please enter your Contact Number.', 'kv_project'));

	if ($posted['user_email'] != null )
		$user_email =  $_POST['user_email'];
	else 
		$errors->add('empty_user_email', __('<strong>Notice</strong>: Please enter your Email.', 'kv_project'));

	if ($posted['usr_job_category'] != -1 || $posted['usr_gen_job_category'] != -1 )
		$usr_job_category =  $_POST['usr_job_category'];
	else 
		$errors->add('empty_usr_job_category', __('<strong>Notice</strong>: Please Select a Job Category.', 'kv_project'));

	if ($posted['usr_qualification'] != -1 ){
		if($posted['usr_qualification'] == 542 && $posted['custom_qualification'] == '')
			$errors->add('empty_usr_qualification', __('<strong>Notice</strong>: Please Enter your Qualification.', 'kv_project'));
		else
			$usr_qualification =  $posted['usr_qualification'];
	}else 
		$errors->add('empty_usr_qualification', __('<strong>Notice</strong>: Please Select Your Qualification.', 'kv_project'));
		
	if ($posted['usr_types'] == -1 )
		$errors->add('empty_usr_types', __('<strong>Notice</strong>: Please Select a Job Types.', 'kv_project'));
		
	if ($posted['usr_yr_of_exp'] == -1 )
		$errors->add('empty_usr_yr_of_exp', __('<strong>Notice</strong>: Please Select your Experience.', 'kv_project'));
		
	if ($posted['usr_gen_job_category'] == -1) {

		if ($posted['usr_madhab'] == -1 && $posted['usr_madhab_shia'] == -1)
			$errors->add('empty_usr_madhab', __('<strong>Notice</strong>: Please Select your Madhab.', 'kv_project'));	
		elseif($posted['usr_madhab'] != -1 )
			$posted['usr_madhab_shia'] =-1;
		elseif($posted['usr_madhab_shia'] !=-1)
			$posted['usr_madhab'] =-1;
	
		if ($posted['usr_aqeeda'] == -1 && $posted['usr_aqeeda_shia'] == -1)
			$errors->add('empty_usr_aqeeda', __('<strong>Notice</strong>: Please Select your Aqeeda.', 'kv_project'));
		elseif($posted['usr_aqeeda'] != -1)
			$posted['usr_aqeeda_shia'] = -1;
		elseif($posted['usr_aqeeda_shia'] != -1)
			$posted['usr_aqeeda'] = -1; 
	} else {
		$posted['usr_madhab'] = $posted['usr_aqeeda'] = $posted['usr_aqeeda_shia'] = $posted['usr_madhab_shia'] =-1;
	}

	if ($posted['usr_zone'] == -1 )
		$errors->add('empty_usr_zone', __('<strong>Notice</strong>: Please Select a User Zone.', 'kv_project'));
		
	if ($posted['gender'] != null )
		$gender =  $_POST['gender'];
	else 
		$errors->add('empty_gender', __('<strong>Notice</strong>: Please enter your Gender.', 'kv_project'));
	
	//if((eimams_has_current_user_cv($current_user->ID) != '' || eimams_has_current_user_cv($current_user->ID) != null) && $_FILES['upload_cv']['size'] < 0)
		//$errors->add('empty_cv', __('<strong>Notice</strong>: Please You must provide your CV.', 'kv_project'));
		
	if ( !$errors->get_error_code() ) { 		
		
		if ( $_FILES) {
			foreach($_FILES as $file => $array) {
				if ($file == 'profile_photo' &&  $_FILES['profile_photo']['size'] > 0) {
					
					add_filter('upload_dir', 'kv_jobseeker_dp_dir');
					$existing_img = get_user_meta($current_user->ID,'user_image', true); 
					if( $existing_img['file']!= null)
						unlink ($existing_img['file']);
					$jobseeker_dp = kv_insert_attachment( $_FILES['profile_photo']); 
					
					remove_filter('upload_dir', 'kv_jobseeker_dp_dir');
					update_user_meta($current_user->ID, 'user_image', $jobseeker_dp );
				}
				if ($file == 'upload_cv' && $_FILES['upload_cv']['size'] > 0 ) {				
						add_filter('upload_dir', 'kv_jobseeker_cv_dir');
						if(eimams_has_current_user_cv($current_user->ID) != '' || eimams_has_current_user_cv($current_user->ID) != null)
							wp_delete_attachment( eimams_has_current_user_cv($current_user->ID));
							
						$cv_file_path = kv_job_attachment($file,0,false); 	

						remove_filter('upload_dir', 'kv_jobseeker_cv_dir');
						
						$wpdb->update( $job_seeker_table, 	array('cv_info' =>  $cv_file_path), array( 'wp_usr_id' => $current_user->ID ));
						//echo eimams_has_current_user_cv($current_user->ID);
						//exit(0);
				}
				if ($file == 'dbs_file_upload' && $_FILES['dbs_file_upload']['size'] > 0 ) {
					$cv_name = pathinfo($_FILES["dbs_file_upload"]["name"]);
					//print_r($cv_name); 
					if($cv_name['extension'] == 'doc' || $cv_name['extension'] == 'docx'|| $cv_name['extension'] == 'rtf' || $cv_name['extension'] == 'pdf' || $cv_name['extension'] == 'txt' ) {
						add_filter('upload_dir', 'kv_jobseeker_cv_dir');						
						$dbs_file_upload = kv_job_attachment($file,0,false); 	
						remove_filter('upload_dir', 'kv_jobseeker_cv_dir');
					}
				}
			}
		}		//upload_cv 
		
		update_user_meta($current_user->ID, 'languages', $_POST['usr_language']);
		
		//echo $posted['usr_yr_of_exp'];
		wp_update_user( array( 'ID' => $current_user->ID, 'user_email' => $user_email ) );
		wp_update_user( array( 'ID' => $current_user->ID, 'display_name' => $jobseeker_name ) );
		if ($posted['password'] != null )
			wp_set_password($posted['password'], $current_user->ID );
		$query = $wpdb->prepare('SELECT * FROM ' . $job_seeker_table . ' WHERE wp_usr_id = %d  ', $current_user->ID  );
		$wpdb->query( $query );
		if ($wpdb->num_rows ) {	
			$wpdb->update( $job_seeker_table, 
				array( 					
					'address1' 			=>  $address1,
					'address2' 			=>  $posted['address2'],
					'post_code'			=>  $posted['post_code'],
					'phone' 			=>  $posted['phone_number'],
					'category' 			=> ($posted['usr_job_category'] != -1 ? $posted['usr_job_category']: ($posted['usr_gen_job_category'] != -1 ? $posted['usr_gen_job_category'] : '')),
					'gender' 			=>  $posted['gender'],
					'qualification' 	=>  $posted['usr_qualification'],
					'type' 				=>  $posted['usr_types'],
					'yr_or_exp' 		=>  $posted['usr_yr_of_exp'],
					'madhab' 			=>  $posted['usr_madhab'],
					'madhab_shia' 		=>  $posted['usr_madhab_shia'],
					'aqeeda' 			=>  $posted['usr_aqeeda'],
					'aqeeda_shia' 		=>  $posted['usr_aqeeda_shia'],
					//'language' 			=>  $posted['usr_language'],
					'location' 			=>  $posted['usr_zone'],
					'pref_sal_bgn' 		=>  $posted['pref_sal_brn'],
					'pref_sal_end' 		=>  $posted['pref_sal_end'],
					'pref_sal_prd' 		=>  $posted['pref_sal_prd'],
					'pref_sal_optn' 	=>  $posted['pref_sal_optn'],					
					'dbs' 				=>  $posted['dbs'],
					'dbs_description'	=>  $posted['dbs_info_box']
				), array( 'wp_usr_id' 	=> 	$current_user->ID )
			);
		} else {
			$new_user = $wpdb->insert( $job_seeker_table, 
				array( 
					'wp_usr_id' 		=> 	$current_user->ID, 
					'address1' 			=>  $address1,
					'address2' 			=>  $posted['address2'],
					'post_code'			=>  $posted['post_code'],
					'phone' 			=>  $posted['phone_number'],
					'category' 			=>  ($posted['usr_job_category'] != -1 ? $posted['usr_job_category']: ($posted['usr_gen_job_category'] != -1 ? $posted['usr_gen_job_category'] : '')),
					'gender' 			=>  $posted['gender'],
					'qualification' 	=>  $posted['usr_qualification'],
					'type' 				=>  $posted['usr_types'],
					'yr_or_exp' 		=>  $posted['usr_yr_of_exp'],
					'madhab' 			=>  $posted['usr_madhab'],
					'madhab_shia' 		=>  $posted['usr_madhab_shia'],
					'aqeeda' 			=>  $posted['usr_aqeeda'],
					'aqeeda_shia' 		=>  $posted['usr_aqeeda_shia'],
					//'language' 			=>  $posted['usr_language'],
					'location' 			=>  $posted['usr_zone'],
					'pref_sal_bgn' 		=>  $posted['pref_sal_brn'],
					'pref_sal_end' 		=>  $posted['pref_sal_end'],
					'pref_sal_prd' 		=>  $posted['pref_sal_prd'],
					'pref_sal_optn' 	=>  $posted['pref_sal_optn'],					
					'dbs' 				=>  $posted['dbs'],
					'dbs_description'	=>  $posted['dbs_info_box']
				)
			);
		} 
		if(isset($dbs_file_upload) && $dbs_file_upload > 0 )
			$wpdb->update( $job_seeker_table, array( 'dbs_file' =>  $dbs_file_upload) , array( 'wp_usr_id' => 	$current_user->ID )); 
		if(kv_get_user_newsletter_sub_info_frm_ID() == null) { 			
			$status = $wpdb->insert($sub_table, array( 
										'date' 			=> 	date('Y-m-d'), 
										'unit_id'		=>	uniqid(),
										'wp_user_id'	=> 	$current_user->ID,
										'email' 		=> 	$current_user->user_email,
										'common'		=>	($_POST['common_alert'] != null ?  1: 0) ,
										'jobalert'		=>	($_POST['job_alert']!= null ?  1: 0),
										'job_cat_id'	=>	($_POST['usr_job_category']!= null ?  1: 0),
										'status' 		=> 	0
						) );
		}	else { 		
			$status = $wpdb->update($sub_table, array( 
										'date' 		=> date('Y-m-d'),										
										'email' 	=> $current_user->user_email,
										'common'	=>	($_POST['common_alert']!= null ?  1: 0),
										'jobalert'	=>	($_POST['job_alert']!= null ?  1: 0),
										'job_cat_id'	=>	$_POST['usr_job_category'],
										'status' 	=> 0
						), array('wp_user_id'	=> 	$current_user->ID) );
		}
		
		
		if($_FILES["certificates_form"]) {
			$certificates_form_ar	 = array();
			$files = $_FILES["certificates_form"];
					
			/*if(isset($_POST['edit_job'])){						
				$ids= maybe_unserialize(get_post_meta($_POST['edit_job'], 'certificates_form', true));													
				if(!empty($ids)){
					foreach($ids as $id){
						kv_delete_attached_files($id );
					}
				}
			}*/
			add_filter('upload_dir', 'kv_jobseeker_cv_dir');
					
			foreach ($files['name'] as $key => $value) { 								
				if ($files['name'][$key]) { 
					$file = array( 
						'name' => $files['name'][$key],
						'type' => $files['type'][$key], 
						'tmp_name' => $files['tmp_name'][$key], 
						'error' => $files['error'][$key],
						'size' => $files['size'][$key]
					); 
					$_FILES = array ("certificates_form" => $file); 
					foreach ($_FILES as $file => $array) {				
						$certificates_form_ar[] = kv_job_attachment($file,$jid,false); 
					}
				} 
			}	
			$existing_certificates = get_user_meta($current_user->ID, 'certificates_form', true);
			if(!empty($existing_certificates)){
				$final_certificates_ar = array();
				foreach($existing_certificates as $key => $Single)
					if($Single != '')
						$certificates_form_ar[] = $Single;
				///foreach($certificates_form_ar as $key => $Single)
				//	$final_certificates_ar[] = $Single;
				//$final_certificates_ar = $existing_certificates;
			}// else
				//$final_certificates_ar = $certificates_form_ar;
			
			//print_r($certificates_form_ar);
			//$certificates_form_ser = maybe_serialize($final_certificates_ar); 
			update_user_meta($current_user->ID, 'certificates_form', $certificates_form_ar);	
			update_user_meta($current_user->ID, 'state_pro_reg', $posted['state_pro_reg']);	
			update_user_meta($current_user->ID, 'city', $posted['city']);	
			update_user_meta($current_user->ID, 'custom_qualification', $posted['custom_qualification']);	
			remove_filter('upload_dir', 'kv_jobseeker_cv_dir');	
		}		
	}
	
	$jobseeker_details = array();
	$jobseeker_details = kv_get_jobseeker_details($current_user->ID);
	$user_info = get_userdata($current_user->ID);     
	$jobseeker_details['user_email'] = $user_info->user_email; 
	$jobseeker_details['user_displayname'] = $user_info->display_name; 
	if ( !$errors->get_error_code() ) { 
		$status='success';
	}
} 
 if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['deactivate_profile'])) {
	$wpdb->update( $users_table, array( 	'user_status' 	=>  1), array( 'ID' 	=> 	$current_user->ID )	);
	$deactivated = 'yes';
	kv_userbackend_user_deactivation($current_user->ID);
}
if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['enable_profile'])) {
	$wpdb->update( $users_table, array( 	'user_status' 	=>  0), array( 'ID' 	=> 	$current_user->ID )	);
	$deactivated = 'no';
	kv_userbackend_user_activation($current_user->ID);
}

$current_user_sub = kv_get_user_newsletter_sub_info_frm_ID($current_user->ID);?>
<div class="row">
    <div class="col-md-12">
        <h2>Profile Update</h2>   
        <h5>Welcome to eImams , Love to see you back. </h5>
    </div>
</div> <!-- /. ROW  -->
<hr>
<div class="row">
    <div class="col-md-12">
        <div class="row" style="margin:15px">
            <div class="col-md-6"><span style="font-size:24px;"> Personal Details  </span> </div>
                <div class="col-md-6"><a href="<?php echo get_template_directory_uri() ?>/images/jobseeker-form.pdf"><button class="btn btn-info pull-right"> Download a PDF version of this form </button></a></div>
            </div>
                
                    <!-- Form Elements -->
            <div class="panel panel-default">
                <div class="panel-heading"></div>
                <div class="panel-body">
                    <div class="row">
                       <div class="col-md-6">                                    
						 <!-- ##############################   Form start here ################################################################################# -->
					 <?php 	if (isset($errors) && sizeof($errors)>0 && $errors->get_error_code()) :
								echo '<ul class="error">';
								foreach ($errors->errors as $error) {
									echo '<li>'.$error[0].'</li>';
								}
								echo '</ul>';
							endif; 	
							if(isset($status) && $status == 'success'){ 
								echo '<div class="success" style="margin-bottom:20px"> Your Profile has been Updated!.</div>'; 		
								header("Refresh:0");
							} 	
							if(isset($deactivated) && $deactivated == 'yes'){ 
								echo '<div class="success" style="margin-bottom:20px"> Your Profile has been Deactivated!.</div>'; 	
								header("Refresh:0");		
							} elseif(isset($deactivated) && $deactivated == 'no'){
								echo '<div class="success" style="margin-bottom:20px"> Your Profile has been Activated!.</div>'; 	
								header("Refresh:0");
							}?>
							<?php $form_opacity = 1;
								$disabled ='';
								if(kv_get_user_status()  == 2) {
								echo '<div class="alert alert-warning box-shadow">
								<a href="#" class="close" data-dismiss="alert">×</a>
								<strong>Warning!</strong> You are blocked by Administrator. Use Help and Support to know contact Us. Check your email for reason. </div>';
								$form_opacity = 0.4;
								$disabled = 'disabled';
						} ?>
						<form class="form-horizontal" enctype='multipart/form-data' style="opacity: <?php echo $form_opacity;?>" name="user_profile" method="post" action="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI']; ?>" >
						   <div class="form-group">
								<label class="control-label col-xs-3" for="Name">Name: </label>
								<div class="col-xs-9">
								   <input type="text" class="form-control" id="Name" name="jobseeker_name" placeholder="Name" <?php echo 'value="'.$jobseeker_details['user_displayname'].'"'; ?>  ><span class="mandatory">  * </span> 
								</div>
							</div>
							
							 <div class="form-group">
								<label class="control-label col-xs-3" for="Name">Address 1:</label>
								<div class="col-xs-9">
								   <textarea type="text" class="form-control" id="Name" name="address1" placeholder="Address" ><?php if(isset($jobseeker_details['address1'])) {echo  $jobseeker_details['address1'];}?></textarea> <span class="mandatory"> * </span> 
								</div>
							</div>        
							
							 <div class="form-group">
								<label class="control-label col-xs-3" for="Name">Address 2: </label>
								<div class="col-xs-9">
								   <textarea type="text" class="form-control" id="Name" name="address2" placeholder="Address"><?php if(isset($jobseeker_details['address2'])) echo $jobseeker_details['address2']; ?></textarea>
								</div>
							</div>
							
							<div class="form-group">
					            <label class="control-label col-xs-3" for="city">City: </label>
					            <div class="col-xs-9">
					                <input type="text" class="form-control" id="city" name="city" value="<?php  echo get_user_meta($current_user->ID,'city', true); ?>" placeholder="Address"> 
					            </div>
					        </div>
							
							<div class="form-group">
					            <label class="control-label col-xs-3" for="state_pro_reg">State/Province/Region: </label>
					            <div class="col-xs-9">
					                <input type="text" class="form-control" id="state_pro_reg" name="state_pro_reg" value="<?php  echo get_user_meta($current_user->ID,'state_pro_reg', true); ?>" placeholder="Address"> 
					            </div>
					        </div>
							 <div class="form-group">
								<label class="control-label col-xs-3" for="Name">Zip/Post Code:</label>
								<div class="col-xs-9">
									<input type="text" class="form-control" id="Name" name="post_code"  placeholder="Post Code" <?php if(isset($jobseeker_details['post_code'])) echo  'value="'.$jobseeker_details['post_code'].'"'; ?>>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3" for="Name">Country:</label>
								<div class="col-xs-9">
								   <?php $usr_zone = wp_dropdown_categories( array( 'show_option_none'=> 'Select Country' ,'echo' => 0, 'taxonomy' => 'zone','selected' => (isset($jobseeker_details['location'])) ? $jobseeker_details['location'] : 0, 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0, 'orderby'            => 'name', 'order'      => 'ASC' ) );
								$usr_zone = str_replace( "name='cat' id=", "name='usr_zone' id=", $usr_zone );
								echo $usr_zone; ?>
									<span class="mandatory">  * </span> 
								</div>
							</div>

							
							<div class="form-group">
								<label class="control-label col-xs-3" for="phoneNumber">Phone:</label>
								<div class="col-xs-9">
									<input type="tel" class="form-control" id="phoneNumber"  name="phone_number" placeholder="Phone Number" <?php if(isset($jobseeker_details['phone'])) echo  'value="'.$jobseeker_details['phone'].'"'; ?>> <span class="mandatory"> * </span> 
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail" class="control-label col-xs-3">Email:</label>
								<div class="col-xs-9">
									<input type="email" class="form-control" id="inputEmail"  name="user_email" placeholder="Email" <?php echo 'value="'.$jobseeker_details['user_email'].'"'; ?> >  <span class="mandatory"> * </span> 
								</div>
							</div>
						
							<div class="form-group">
								<label for="inputPassword" class="control-label col-xs-3">Password: </label>
								<div class="col-xs-9">
									<input type="password" class="form-control" id="inputPassword" name="password"  placeholder="Password"> 
								</div>
							</div>
							<br>

						   <h3>Registration Form </h3><br>
    
                           <style type="text/css">
							   #job-classifications optgroup[label] { color: #3c763d; 	font-size:25px; }
								 #job-classifications optgroup[label] * { color:#333; font-size:15px; }
						   </style>
						   
						   <div id="job-classifications" class="form-group">
								<label class="control-label col-xs-3" for="Name">Job Classifications:</label>
								<div class="col-xs-9">
								   <?php $usr_job_category = wp_dropdown_categories( array('show_option_none'=> 'Select category' ,  'echo' => 0, 'taxonomy' => 'job_category','selected' => (isset($jobseeker_details['category'])) ? $jobseeker_details['category'] : 0 , 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0,  'orderby'            => 'name', 'order'      => 'ASC' ) );
								$usr_job_category = str_replace( "name='cat' id=", "name='usr_job_category' id=", $usr_job_category );
								//echo $usr_job_category; 
								echo kv_merged_taxonomy_dropdown('job_category', 'gen_job_category', (isset($jobseeker_details['category'])) ? $jobseeker_details['category'] : 0);
								echo '<input type="hidden" name="usr_job_category" value="'.(isset($jobseeker_details['category']) ? $jobseeker_details['category'] : -1).'" id="usr_job_category" > 
									<input type="hidden" name="usr_gen_job_category" value="-1" id="usr_gen_job_category" > '; 
								
								?>  
									<span class="mandatory"> * </span> 
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-xs-3">Gender: <span class="mandatory"> * </span> </label>
								<div class="col-xs-2">

									<label class="radio-inline"> <input type="radio" name="gender" value="male" <?php if(isset($jobseeker_details['gender']) && $jobseeker_details['gender'] == 'male' ) echo  'checked'; ?> > Male </label>
								</div>
								<div class="col-xs-2">
									<label class="radio-inline"> <input type="radio" name="gender" value="female" <?php if(isset($jobseeker_details['gender']) && $jobseeker_details['gender'] == 'female' ) echo  'checked'; ?> > Female </label>
								</div>
							</div>                
							
							<div class="form-group">
								<label class="control-label col-xs-3" for="Name">Qualification:</label>
								<div class="col-xs-9">
								   <?php 
								   $user_qualification = ((isset($jobseeker_details['qualification'])) ? $jobseeker_details['qualification'] : 0); 
								   if( isset($posted['custom_qualification']))
									   $custom_qualification = $posted['custom_qualification'];
								   else
										$custom_qualification = get_user_meta($current_user->ID, 'custom_qualification', true); 	
										
								   $usr_qualification = wp_dropdown_categories( array('show_option_none'=> 'Select Qualification' , 'echo' => 0, 'id' => 'job_qualification', 'taxonomy' => 'qualification','selected' => $user_qualification, 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0 ) );
								$usr_qualification = str_replace( "name='cat' id=", "name='usr_qualification' id=", $usr_qualification );
								echo $usr_qualification; ?>  
								<input type="text" class="form-control" id="custom_qualification"  name="custom_qualification" value="<?php echo $custom_qualification; ?>" >
									<span class="mandatory">  * </span> 
								</div>
							</div>        
							
							<div class="form-group">
								<label class="control-label col-xs-3" for="Name">Type:</label>
								<div class="col-xs-9">
									<?php $usr_types = wp_dropdown_categories( array( 'show_option_none'=> 'Select Type' ,'echo' => 0, 'taxonomy' => 'types','selected' => (isset($jobseeker_details['type'])) ? $jobseeker_details['type'] : 0, 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0 ) );
								$usr_types = str_replace( "name='cat' id=", "name='usr_types' id=", $usr_types );
								echo $usr_types; ?>  
									<span class="mandatory">  * </span> 
								</div>
							</div>        
							
							<div class="form-group">
								<label class="control-label col-xs-3" for="Name">Years of Exprerience:</label>
								<div class="col-xs-9">
									<?php $usr_yr_of_exp = wp_dropdown_categories( array('show_option_none'=> 'Select Experience' , 'echo' => 0, 'taxonomy' => 'yr_of_exp','selected' => (isset($jobseeker_details['yr_or_exp'])) ? $jobseeker_details['yr_or_exp'] : 0, 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0 ) );
								$usr_yr_of_exp = str_replace( "name='cat' id=", "name='usr_yr_of_exp' id=", $usr_yr_of_exp );
								echo $usr_yr_of_exp; ?>  
									<span class="mandatory">  * </span> 
								</div>
							</div>
							<hr class="showhideJobCategory" >	
							<?php  if($jobseeker_details['madhab_shia'] > 0 || $jobseeker_details['aqeeda_shia'] >0 ){
								$denomination_selected = 'shia';
							}else{
								$denomination_selected ="sunni";
							} ?>
							<?php if( $enable_shia_subscription == 'yes') { ?>
				<style>
				.shia_selection { 
					visibility: visibile;
				} </style>         
				<?php } else {?> 
				<style>
				.shia_selection { 
					visibility: hidden;
				} </style> 
				<?php } ?>
							<h3 class="showhideJobCategory " > Denomination : <label class="" > <input type="radio" value="sunni" class="denominationSelection shia_selection" name="denominationSelection" <?php echo ($denomination_selected == 'sunni' ? 'checked="checked"' : ''); ?> > Sunni </label>  <label class="shia_selection" > <input name="denominationSelection" type="radio" value="shia" class="denominationSelection" <?php echo ($denomination_selected == 'shia' ? 'checked="checked"' : ''); ?>> Shia </label>  </h3>
							<div class="SunniDenomination" > 
						   <div class="form-group showhideJobCategory">
								<label class="control-label col-xs-3" for="Name">Madhab/School of Law:</label>
								<div class="col-xs-9">
									 <?php $usr_madhab = wp_dropdown_categories( array('show_option_none'=> 'Select Madhab' ,  'echo' => 0, 'taxonomy' => 'madhab','selected' => (isset($jobseeker_details['madhab'])) ? $jobseeker_details['madhab'] : 0, 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0 ) );
								$usr_madhab = str_replace( "name='cat' id=", "name='usr_madhab' id=", $usr_madhab );
								echo $usr_madhab; ?>                 
									<span class="mandatory">  * </span>                 
								</div>
							</div>
							
							<div class="form-group showhideJobCategory">
								<label class="control-label col-xs-3" for="Name">Aqeeda/Belief:</label>
								<div class="col-xs-9">
									 <?php $usr_aqeeda = wp_dropdown_categories( array('show_option_none'=> 'Select Aqeeda/Belief' , 'echo' => 0, 'taxonomy' => 'aqeeda','selected' => (isset($jobseeker_details['aqeeda'])) ? $jobseeker_details['aqeeda'] : 0, 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0 ) );
								$usr_aqeeda = str_replace( "name='cat' id=", "name='usr_aqeeda' id=", $usr_aqeeda );
								echo $usr_aqeeda; ?>              
									<span class="mandatory">  * </span>                 
								</div>
							</div>
							</div>

							<div class="ShiaDenomination" > 
								<div class="form-group showhideJobCategory">
									<label class="control-label col-sm-3" for="Name">Madhab/School of Law: </label>
									<div class="col-sm-9">
									   <?php
											$selected_usr_madhab_shia = ((isset($posted['usr_madhab_shia'])) ? $posted['usr_madhab_shia'] : $jobseeker_details['madhab_shia']  );
										
										$usr_madhab_shia = wp_dropdown_categories( array('show_option_none'=> 'Select Madhab', 'echo' => 0, 'taxonomy' => 'Shiamadhab','selected' => $selected_usr_madhab_shia, 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0,'orderby' => 'NAME', 'order' => 'ASC', ) );
										$usr_madhab_shia = str_replace( "name='cat' id='cat'", "name='usr_madhab_shia' ", $usr_madhab_shia );
										echo $usr_madhab_shia; ?><span class="mandatory">*</span>
									</div>
								</div>
								
								<div class="form-group showhideJobCategory">
									<label class="control-label col-sm-3" for="Name">Aqeeda/Belief </label>
									<div class="col-sm-9">
									   <?php $selected_usr_aqeeda_shia = ((isset($posted['usr_aqeeda_shia'])) ? $posted['usr_aqeeda_shia'] : $jobseeker_details['aqeeda_shia']  );?>
											
										<select name="usr_aqeeda_shia" id="usr_aqeeda_shia" class="form-control">
										   <?php Shia_Aqeeda_select($selected_usr_aqeeda_shia); ?>
										</select><span class="mandatory">*</span>	
									</div>
								</div>
							</div>
							
						   <div class="form-group">
								<label class="control-label col-xs-3" for="Name">Language:</label>
								<div class="col-xs-9">
									<?php $summa_lang = get_user_meta( $current_user->ID, 'languages', false );
										if(!empty($summa_lang)){										
											$final_array = array_values( $summa_lang[0] );
											//print_r($final_array);
										} 
										else{
										if(isset($_POST['usr_language'])) { $final_array=$_POST['usr_language']; }
										else
											$final_array=4;
											}
											 $usr_language = wp_dropdown_categories( array( 'show_option_none'=> 'Select Language' , 'echo' => 0, 'taxonomy' => 'languages','selected' => array(4, 122, 124), 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0,  'orderby'   => 'name', 'order'  => 'ASC') );
										$usr_language = str_replace( "name='cat' id=", "name='usr_language[]' multiple='multiple' id=", $usr_language );
										
										  if (is_array($final_array)) {
										  foreach ($final_array as $post_term) {
											  $usr_language = str_replace(' value="' . $post_term . '"', ' value="' . $post_term . '" selected="selected"', $usr_language);
										  }
									  } else {
										  $usr_language = str_replace(' value="' . $final_array . '"', ' value="' . $final_array . '" selected="selected"', $usr_language);
									  }	    	  
									echo $usr_language;
									 ?>
								</div>
							</div>		
							
						  <div class="form-group">
								<label class="control-label col-sm-3" for="Name">Preferred Salary:</label>
								<div class="col-xs-9">
								 <input type="text" class="form-control" name="pref_sal_brn" style="width:50px;" <?php if(isset($jobseeker_details['pref_sal_bgn'])) echo  'value='.$jobseeker_details['pref_sal_end']; ?> > to <input type="text" name="pref_sal_end" class="form-control" style="width:50px;" <?php if(isset($jobseeker_details['pref_sal_end'])) echo  'value='.$jobseeker_details['pref_sal_end']; ?> >  
								
								   per <?php $pref_sal_prd = wp_dropdown_categories( array( 'show_option_none'=> 'Select An Option' ,'echo' => 0, 'taxonomy' => 'sal_prd','selected' => (isset($jobseeker_details['pref_sal_prd'])) ? $jobseeker_details['pref_sal_prd'] : 0, 'hierarchical' => true,'class'  => 'form-control width_30',  'hide_empty' => 0 ) );
								$pref_sal_prd = str_replace( "name='cat' id=", "name='pref_sal_prd' id=", $pref_sal_prd );
								echo $pref_sal_prd; ?> OR
								
								<?php $pref_sal_optn = wp_dropdown_categories( array('show_option_none'=> 'Select An Option' , 'echo' => 0, 'taxonomy' => 'sal_optn','selected' => ($jobseeker_details['pref_sal_optn']> 0 ? $jobseeker_details['pref_sal_optn'] : 0), 'hierarchical' => true,'class'  => 'form-control width_30',  'hide_empty' => 0 ) );
								$pref_sal_optn = str_replace( "name='cat' id=", "name='pref_sal_optn' id=", $pref_sal_optn );
								echo $pref_sal_optn; 
								
								//print_r($jobseeker_details);?>
							   
								</div>
							</div>
							
						   <div class="form-group">
								<label class="control-label col-sm-3" for="Name">Upload a CV : </label>
								<div class="col-sm-9">
								   <input type="file" class="form-control" name="upload_cv"  accept=".doc,.docx, .rtf, .txt, .pdf" id="upload-cv">  <!-- <span class="mandatory">*</span>   -->
								   <?php 	$file_url=  wp_get_attachment_url( eimams_has_current_user_cv($current_user->ID) );
										if( $file_url != null  || $file_url != '')
											echo '<a class="view_resume" data-fancybox-type="iframe" href="'. site_url( 'view-resume' ).'?job_seeker_id='.$current_user->ID.'"> Your CV </a>'; 
										else 
											echo 'No CV you have'; ?>
								</div>
							</div>
							
							<div class="form-group" id="application_form">
								<label class="control-label col-sm-3" for="monitoring-equality">Qualification Certificates: </label>
								<div class="col-sm-8">
									 <input type="file" class="form-control" id="rtf-pdf" name="certificates_form[]" multiple="multiple" accept='.doc,.docx, .rtf, .pdf'>  
                                               
                                      <p class="help-block"> You can add your Additional Qualified Certificates here to let your Employer can see it. <br />  (RTF and PDF format) can be uploaded here	 </p>  
							                                                   
									<?php  $ids= get_user_meta($current_user->ID, 'certificates_form', true);
													//var_dump($ids);
										if(!empty($ids)){
											echo '<ul class="application_files"> ';
											foreach($ids as $id){
												echo '<li><a class="view_resume" data-fancybox-type="iframe" href="'. site_url( 'view-resume' ).'/?attach_id='. $id.'" > '.get_the_title ($id).' </a> <a href="#" class="remove_current_file" attach_id="'.$id.'" ><button class="btn-file-upload"> X </button></a></li>'; 
											}
											echo '</ul>' ;												
										}?>
								 </div> 
							</div>
							 <div class="form-group">
												<label class="control-label col-xs-3">do you have or require any legal disclosures?<span class="mandatory">*</span> </label>
												<div class="col-sm-8">
													<div class="col-sm-1">
														<label class="radio-inline">
															<input type="radio" name="dbs" id="dbs_yes"  value="yes" <?php if($jobseeker_details['dbs']=='yes') {echo  'checked'; }elseif(isset($_POST['dbs'])  && $_POST['dbs'] == 'yes') {echo 'checked'; }?>> Yes 
														</label>
													</div>
													<div class="col-sm-1">
														<label class="radio-inline">
															<input type="radio" name="dbs" id="dbs_no" value="no" <?php if($jobseeker_details['dbs']=='no') { echo  'checked'; }elseif(isset($_POST['dbs'])  && $_POST['dbs'] == 'no'){ echo 'checked'; } ?>> No
														</label>
													</div>
													 <br />
														   <p class="help-block"> It is strongly recommended that you (as employer) understand what DBS (Disclosures and Barring service) is and when recruiting an individual for tuition or working with children and or vulnerable individuals, that they (employee) have DBS clearance. </p>
													 
																													   
														 <div id="dbs_upload" style="margin-top:10px;">         
															 <textarea class="form-control" id="dbs_description" name="dbs_description" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"><?php if($jobseeker_details['dbs_description'] !=null) { echo $jobseeker_details['dbs_description'];} elseif(isset($_POST['dbs_description'])) {  echo $_POST['dbs_description']; }  ?></textarea>
															 <input type="file" class="form-control" id="dbs_file_upload" name="dbs_file_upload" accept='.doc,.docx, .rtf, .pdf'>
															   <?php 
											   
																	$dbs_file_url=  wp_get_attachment_url( $jobseeker_details['dbs_file']);
																if( $dbs_file_url != null  || $dbs_file_url != '')
																	echo '<a class="view_resume" data-fancybox-type="iframe" href="'. site_url( 'view-resume' ).'/?attach_id='.$jobseeker_details['dbs_file'].'"> View File </a>'; 
																else 
																	echo 'No View File '; 
																?>
														 </div>
																			 
												</div>
												
											</div> 
											
							
						  <div class="form-group">
								<label class="control-label col-xs-3" for="Name">Upload your photo : </label>
								<div class="col-xs-9">
								   <input type="file" class="form-control" name="profile_photo" accept="image/*" id="profile_photo">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-3" for="minimum-wage">Email Alert :  </label>
								<div class="col-sm-8">
									<label><input type="checkbox" id="job-alert-wage" <?php if( $current_user_sub->jobalert == 1) echo 'checked'; ?> name="job_alert" value="1"> Email me, when new jobs posted and which matches my profile</label>
									<label><input type="checkbox" id="job-alert-wage" <?php if($current_user_sub->common == 1) echo 'checked'; ?> name="common_alert" value="1"> Common Newsletters and Special Offers</label>
								</div>
							</div>
                            
                            
							
						  <div class="form-group">
								<div class="checkbox col-xs-9 col-xs-offset-3">
								  <input type="submit" class="btn btn-primary" name="update_profile" value=" Update Profile" <?php echo $disabled; ?> >   
								  <?php if(kv_get_user_status()  == 0) { ?> 
									<input type="submit" class="btn btn-info" name="deactivate_profile" value=" Deactivate Profile" >     
								  <?php } elseif(kv_get_user_status()  == 1) {?>
								  <input type="submit" class="btn btn-info" name="enable_profile" value=" Activate Profile" >   
								  <?php } ?>
								</div>
							<br><br>       
							</div>
						</form>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<style> 
.width_30 { width: 30%; } 
ul.errors { list-style: none;  border: 1px solid #f00; padding: 5px; text-align: center; background-color: #F6FFB0;}
.btn-file-upload {margin-bottom:3px; background:#d9534f; padding:2px; color:#fff; border-radius:5px; border:none;}
@media (min-width: 992px){
	.col-md-6 {	width: 75%;	}
}
</style> 
<script> 
ajax_url = location.protocol + "//" +document.domain+'/ajax';
$(document).ready(function () {

	var custom_qualification= jQuery('#custom_qualification').val();
	if(custom_qualification == '')
		jQuery('#custom_qualification').hide();
	
	$(document).on('change', '#job_qualification', function(){
		if($(this).val() == 542)
			$('#custom_qualification').show();
		else
			$('#custom_qualification').hide();
	});
	
	
	if(jQuery("#dbs_yes").is(":checked")) {} else {
			 jQuery('#dbs_upload').hide();
	   }
		
	   $(document).on('change','#dbs_yes', function() {
  			if(jQuery(this).is(":checked")) {
				 jQuery('#dbs_upload').show();
			}
	   });
	   
	    $(document).on('change','#dbs_no', function() {
  			if(jQuery(this).is(":checked")) {
				 jQuery('#dbs_upload').hide();
			}
	   });
	$(".view_resume").fancybox({
			maxWidth	: 900,
			maxHeight	: 900,
			fitToView	: false,
			width		: '70%',
			height		: '90%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none',
			type:"iframe"	
	});
	
		   // Remove attachments 
	   $(".remove_current_file").on("click", function(e){
			e.preventDefault();
			var dele_attachment_id = $(this).attr('attach_id');			
			jQuery.ajax({
				type:"POST",
				url: ajax_url,
				data: {
					  action: "delete_selected_attachment",
					  attach_id: dele_attachment_id,
					  post_id : "0",
				},
				success:function(data){
					alert(data);
					if(data = 'Success'){
						 $(this).closest('li').remove();
						 location.reload();
					}
				},
				error: function(errorThrown){
					   console.log(errorThrown);
				} 
			});
		});

	   $(".denominationSelection").on("change", function(e){
			var SelectedDenomination = $('.denominationSelection:checked').val();
				//alert(SelectedDenomination);
			if(SelectedDenomination == 'sunni'){
				$(".SunniDenomination").show();
				$(".ShiaDenomination").hide();
			}else{
				$(".SunniDenomination").hide();
				$(".ShiaDenomination").show();
			}
		});
		
		jQuery("#merged_taxonomy").on("change", function(){
		var txt_val = $(this).val();
		var typE = jQuery('option:selected',this).attr('type');
		//alert(typE);
		if(typE == 'job_category'){
			jQuery("#usr_job_category").val(txt_val);
			jQuery("#usr_gen_job_category").val(-1);
			jQuery(".showhideJobCategory").show(); 
			jQuery(".denominationSelection").trigger('change');
	    }else {
			jQuery("#usr_gen_job_category").val(txt_val);
			jQuery("#usr_job_category").val(-1);
			jQuery(".showhideJobCategory").hide(); 
			jQuery(".denominationSelection").trigger('change');
	    }
	});
	jQuery("#merged_taxonomy").trigger('change');
});
</script>
