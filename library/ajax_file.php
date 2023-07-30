<?php


################################################################################
// Ajax function to refresh Captcha
################################################################################

add_action( 'wp_ajax_refresh_captcha', 'refresh_captcha_fn' );
add_action( 'wp_ajax_nopriv_refresh_captcha', 'refresh_captcha_fn' );
function refresh_captcha_fn() {

	global $captcha_arr ;

	$captcha_arr = array(
						0 => array('2-1', '1'),
						1 => array('5-2', '3'),
						2 => array('7-4', '3'),
						3 => array('2+3', '5'),
						4 => array('9-3', '6'),
						5 => array('2+7', '9'),
						6 => array('3+4', '7'),
						7 => array('6+1', '7'),
						8 => array('8-3', '5'),
						9 => array('5-1', '4')
		);
	$rand_captcha = rand(0,5);
		echo $captcha_arr[$rand_captcha][0].','.$rand_captcha;
	wp_die();
}



/*****************************************
* AJAX function for login access
******************************************/
add_action( 'wp_ajax_kv_get_login_access', 'kv_get_login_access' );
add_action( 'wp_ajax_nopriv_kv_get_login_access', 'kv_get_login_access' );
function  kv_get_login_access() {
	$creds                  = array();
	$creds['user_login']    = stripslashes( trim( $_POST['user_login'] ) );
	$creds['user_password'] = stripslashes( trim( $_POST['user_pass'] ) );
	$creds['remember_me'] = true;
	$requesting_url = stripslashes( trim( $_POST['requesting_url'] ) );
	$requesting_url = substr($requesting_url, 0, -1);

	$secure_cookie  = false;
	$employer_signup_url = site_url().'/employer-sign-up';
	$jobseeker_signup_url = site_url().'/jobseeker-sign-up';
	$user = wp_signon( $creds, $secure_cookie );
	if ( ! is_wp_error( $user ) ) {

		$user = new WP_User( $user );
		$role= wp_sprintf_l( '%l', $user->roles );
    //     var_dump($role);
	   // exit;
		if(site_url() == $requesting_url && $role == 'administrator')
			$redirect_urll =admin_url();
		elseif(site_url() == $requesting_url)
			$redirect_urll =site_url().'/dashboard';
		elseif($requesting_url == $employer_signup_url){
			if($role == 'administrator')
				$redirect_urll = admin_url();
			else
				$redirect_url1 = site_url().'/dashboard';
		}elseif($requesting_url == $jobseeker_signup_url){
			if($role == 'administrator')
				$redirect_urll = admin_url();
			else
				$redirect_url1 = site_url().'/dashboard';
		}else
			$redirect_urll = $requesting_url;

		wp_die($redirect_urll);

	} else {
		wp_die('error');
	}
	exit;
}

################################################################################
//  sign up newsletter function in footer
################################################################################
add_action( 'wp_ajax_submit_subscription', 'submit_subscriptionfn' );
add_action( 'wp_ajax_nopriv_submit_subscription', 'submit_subscriptionfn' );
function submit_subscriptionfn() {
		if( filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) ){

			if(kv_user_has_newsletter_sub_info(stripslashes($_POST['user_email'])) == null) {
				global $wpdb;

				$sub_table = $wpdb->prefix.'newsletter';
				$status = $wpdb->insert($sub_table, array(
									'date' 		=> date('Y-m-d'),
									'common' 	=> 1,
									'unit_id'	=> uniqid(),
									'email' 	=> stripslashes($_POST['user_email']),
									'status' 	=> 0
								) );
				if($status>0){
						kv_newsletter_subscription_first_email(stripslashes($_POST['user_email']));
						echo 'Your e-mail has been added to our mailing list!';
				}else
					echo 'There was a problem with your e-mail (' . $_POST['user_email'] . ')';
			} else
				echo 'Your e-mail (' . $_POST['user_email'] . ') Already exist in our system';
		}	else	{
			echo 'Your email is not valid, please enter a valid email address';
		}
	wp_die();
}

################################################################################
// Ajax function to delete a attachment
################################################################################
add_action( 'wp_ajax_delete_selected_attachment', 'delete_selected_attachment_fn' );
add_action( 'wp_ajax_nopriv_delete_selected_attachment', 'delete_selected_attachment_fn' );

function delete_selected_attachment_fn() {
	global $current_user;
	wp_get_current_user();
	$del_id = intval( $_POST['attach_id'] );
	$post_id = intval( $_POST['post_id'] );
	if (wp_delete_attachment( $del_id ) )  {
		if($post_id== 0){
			$ids= get_user_meta($current_user->ID, 'certificates_form', true);
		} else {
			$ids= get_post_meta($post_id, 'app_form', true);
		}
			if(!empty($ids)){
				$pos = array_search($del_id, $ids);
				unset($ids[$pos]);
				//$app_form_ser = $ids;
				//echo 'sfdvfvas'.$post_id;
				if($post_id== 0){

					update_user_meta($current_user->ID, 'certificates_form', $ids);
					//echo $post_id. $current_user->ID;
				}
				else
					update_post_meta($post_id, 'app_form', $ids);
			}
		echo 'Success';
	} else
		echo 'Failure';
	wp_die();
}


################################################################################
// Ajax function to delete a job
################################################################################

add_action( 'wp_ajax_delete_job', 'delete_jobfn' );
add_action( 'wp_ajax_nopriv_delete_job', 'delete_jobfn' );
function delete_jobfn() {
	$del_id = intval( $_POST['del_id'] );
	if( wp_trash_post($del_id))
		echo 'Success';
	else
		echo 'Failure';
	wp_die();
}

################################################################################
// Delete Subscription
################################################################################
add_action( 'wp_ajax_delete_subscription', 'delete_subscriptionfn' );
add_action( 'wp_ajax_nopriv_delete_subscription', 'delete_subscriptionfn' );
function delete_subscriptionfn() {
	global $wpdb;
	$tbl_name = $wpdb->prefix."jbs_subactive";
	$del_id = intval( $_POST['del_id'] );
	$sub_status = $wpdb->get_var("SELECT status FROM ".$tbl_name." WHERE id=".$del_id." LIMIT 1");
	if( $sub_status == 'Expired') {
		$sub_status = $wpdb->get_var("DELETE FROM ".$tbl_name." WHERE id=".$del_id." LIMIT 1");
		echo " Success";
	}
	else
		echo 'Failure';
	wp_die();
}

################################################################################
// Ajax function to delete a ticket on help and support
################################################################################
add_action( 'wp_ajax_delete_ticket', 'delete_ticketfn' );
add_action( 'wp_ajax_nopriv_delete_ticket', 'delete_ticketfn' );
function delete_ticketfn(){
	$del_id = intval( $_POST['del_id'] );
	if( wp_delete_post($del_id))
		echo 'Success';
	else
		echo 'Failure';
	wp_die();
}

################################################################################
// Ajax function to delete an applied job
################################################################################
add_action( 'wp_ajax_applied_delete', 'applied_deletefn' );
add_action( 'wp_ajax_nopriv_applied_delete', 'applied_deletefn' );
function applied_deletefn() {
	global $wpdb;
	$table = $wpdb->prefix.'applied_jobs';
	$del_id = intval( $_POST['del_id'] );
	if( $wpdb->delete( $table, array('id' => $del_id)))
		echo 'Success';
	else
		echo 'Failure';
	wp_die();
}

################################################################################
// Ajax function to get pack info on the back end
################################################################################
add_action( 'wp_ajax_get_pack_info', 'get_pack_info_fn' );

function get_pack_info_fn(){
	global $wpdb;
	$pack_name =  $_POST['pack_name'] ;
	$get_res=$wpdb->get_row("SELECT * FROM eimam_jbs_subpack WHERE id='".$pack_name."'", ARRAY_A);
	//print_r($get_res);
	if($get_res['per_post']!=0)
		echo $get_res['per_post'].",".$get_res['id'];
	else
		echo $get_res['period'].$get_res['duration'].$get_res['id'];
		wp_die();
}

################################################################################
// Ajax function to get pack info on the back end
################################################################################
add_action( 'wp_ajax_get_rolebased_packs', 'get_rolebased_packs_fn' );

function get_rolebased_packs_fn(){
	global $wpdb;
	$role =  $_POST['role'] ;
	$packs_list = array();
	//$get_res=$wpdb->get_row("SELECT * FROM eimam_jbs_subpack WHERE id='".$pack_name."'", ARRAY_A);
	$sub_pack_name = $wpdb->get_results( "SELECT * FROM eimam_jbs_subpack WHERE status='Active' AND role='".$role."'");
	foreach ( $sub_pack_name as $pack ) {
		$packs_list[]  = array( 'id' => $pack->id, 'name' =>  $pack->pack_name);
	}
	echo json_encode($packs_list);
	wp_die();
}
