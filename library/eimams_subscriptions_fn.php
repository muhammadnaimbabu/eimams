<?php 

################################################################################
//Getting a users subscription end date
################################################################################
function  kv_get_jobseeker_subend_date($userid) {
	global $wpdb; 
	$jobseeker_subatv = $wpdb->prefix.'jbs_subactive';
	$subend_date = $wpdb->get_var("SELECT MAX(end_date) FROM $jobseeker_subatv WHERE wp_user_id = ".$userid);
	return $subend_date;
}

################################################################################
// Check whether a users subscription expired or not
################################################################################
function kv_check_jobseeker_sub_expire($userid){ 
	$usr_role = kv_get_current_user_role();
	if($usr_role = 'administrator')
		return true; 
	$e_date =kv_get_jobseeker_subend_date($userid);
	$t_date = date('Y-m-d');
	if($e_date>=$t_date) 
		return true; 
	else 
		return false;
}

/*****************************************
*User Subscription Status Check and Change
****************************************/
function kv_subscribe_email_to_reduce_perpost($user_id){
	global $wpdb;   
	$database_name = $wpdb->prefix.'jbs_subactive' ;
	$get_post=$wpdb->get_row("SELECT id,per_post,wp_user_id FROM ".$database_name." WHERE status = 'Active' AND wp_user_id =".$user_id, ARRAY_A);
	if($get_post['per_post']!=0)
		check_post_value($get_post);
}

function check_post_value($get_post){
	global $wpdb;
	//print_r($get_post);
	$database_name = $wpdb->prefix.'jbs_subactive' ;
	$less_post=$get_post['per_post']-1;
	if($less_post == 0){
		$update_post=$wpdb->update($database_name, 
			array( 	'per_post' 	=>  $less_post, 'status' => 'Expired'), 
			array( 'id' 		=> 	$get_post['id'] )
		);
		
		$next_pack_id = $wpdb->get_row("SELECT id, per_post, pack_id FROM ".$database_name." WHERE status = 'Yet To Activate' AND wp_user_id=".$get_post['wp_user_id']." ORDER BY id LIMIT 1", ARRAY_A); 
		//print_r($next_pack_id);
			if($next_pack_id['id'] != null){
				if($next_pack_id['per_post'] != 0)
					$update_post=$wpdb->update($database_name, 	array( 'status' => 'Active', 'start_date' => date('Y-m-d')),	array( 'id'	=> 	$next_pack_id['id'] )	);
				else { 
					$end_date = kv_get_end_date_for_pack($next_pack_id['pack_id'], date('Y-m-d'));
					$update_post=$wpdb->update($database_name, 	array( 'status' => 'Active', 'start_date' => date('Y-m-d'), 'end_date' => $end_date),	array( 'id'	=> 	$next_pack_id['id'] )	);
				}					
				do_action('user_subscription_ended_renew', $get_post['id'], $next_pack_id['id']);
			}else {
				do_action('user_subscription_ended', $get_post['id']);
			}
			
	}else {
		$update_post=$wpdb->update($database_name, 
			array( 	'per_post' 	=>  $less_post), 
			array( 'id' 		=> 	$get_post['id'] )
		); 
	}
}

/*****************************************
*Check Subscribers status and change it
****************************************/
function kv_subscribers_status_change() {
	global $wpdb; 

	$subactive_tbl = $wpdb->prefix.'jbs_subactive'; 
	$end_date = $wpdb->get_results("SELECT * FROM ".$subactive_tbl." WHERE status = 'Active' AND end_date < CURDATE() AND per_post = 0"); 
	if ( $end_date ) {
		foreach ( $end_date as $single )	{
			$update_post=$wpdb->update($subactive_tbl, 	array( 'status' => 'Expired'),	array( 'id'	=> 	$single->id )	);
			$next_pack_id = $wpdb->get_row("SELECT id, per_post, pack_id FROM ".$subactive_tbl." WHERE status = 'Yet To Activate' AND wp_user_id=".$single->wp_user_id." ORDER BY id LIMIT 1", ARRAY_A); 
			if($next_pack_id['id'] != null){
				if($next_pack_id['per_post'] != 0)
					$update_post=$wpdb->update($subactive_tbl, 	array( 'status' => 'Active', 'start_date' => date('Y-m-d')),	array( 'id'	=> 	$next_pack_id['id'] )	);
				else { 
					$end_date = kv_get_end_date_for_pack($next_pack_id['pack_id'], date('Y-m-d'));
					$update_post=$wpdb->update($subactive_tbl, 	array( 'status' => 'Active', 'start_date' => date('Y-m-d'), 'end_date' => $end_date),	array( 'id'	=> 	$next_pack_id['id'] )	);
				}					
				do_action('user_subscription_ended_renew', $single->id, $next_pack_id['id']);
				
				//$update_post=$wpdb->update($subactive_tbl, 	array( 'status' => 'Active'),	array( 'id'	=> 	$next_pack_id['id'] )	);
				//do_action('user_subscription_ended_renew', $single->id, $next_pack_id['id']);
			}else {
				do_action('user_subscription_ended', $single->id);
				//kv_user_subscription_ended_email( $single->id ); 
			}
		}
	}
	//echo "sergser"; 
}


/*****************************************
*Check Subscription and Alert users
****************************************/
function kv_check_subscription_expire_in_1day() {
	global $wpdb; 

	$subactive_tbl = $wpdb->prefix.'jbs_subactive'; 
	$end_date = $wpdb->get_results("SELECT * FROM ".$subactive_tbl." WHERE status = 'Active' AND end_date < DATE_ADD(CURDATE(), INTERVAL -1 DAY) AND per_post = 0"); 
	if ( $end_date ) {
		foreach ( $end_date as $single )	{
			//$update_post=$wpdb->update($subactive_tbl, 	array( 'status' => 'Expired'),	array( 'id'	=> 	$single->id )	);
			
			kv_user_subscription_expire_inday( $single->id , 1); 
		}
	}
}

################################################################################
//Cron job,subscription expire in 3 days
################################################################################
function kv_check_subscription_expire_in_3day() {
	global $wpdb; 

	$subactive_tbl = $wpdb->prefix.'jbs_subactive'; 
	$end_date = $wpdb->get_results("SELECT * FROM ".$subactive_tbl." WHERE status = 'Active' AND end_date < DATE_ADD(CURDATE(), INTERVAL -3 DAY) AND per_post = 0"); 
	if ( $end_date ) {
		foreach ( $end_date as $single )	{
			//$update_post=$wpdb->update($subactive_tbl, 	array( 'status' => 'Expired'),	array( 'id'	=> 	$single->id )	);
			
			kv_user_subscription_expire_inday( $single->id , 3); 
		}
	}
}

################################################################################
// Cron job- Subscription expire in 3 posts
################################################################################
function kv_check_subscription_expire_in_3posts() {
	global $wpdb; 

	$subactive_tbl = $wpdb->prefix.'jbs_subactive'; 
	$end_date = $wpdb->get_results("SELECT * FROM ".$subactive_tbl." WHERE status = 'Active' AND  per_post = 3"); 
	if ( $end_date ) {
		foreach ( $end_date as $single )	{
			//$update_post=$wpdb->update($subactive_tbl, 	array( 'status' => 'Expired'),	array( 'id'	=> 	$single->id )	);
			
			kv_user_subscription_expire_inpost( $single->id , 3); 
		}
	}
}


/*****************************************
* Change Job Posts Status when it expires
****************************************/
function kv_daily_post_expire_check() {
	global $wpdb;
	
	$the_query = get_posts( 'post_type=job&post_status=publish&numberposts=-1' );	
	foreach($the_query as $single_post) {
		$id=$single_post->ID;
		$ad_close_date=get_post_meta($id, 'ad_close_date', true );
		if($ad_close_date!=''){
			$today=date("Y-m-d");
			$ad_less_close_date=$ad_close_date-3;
			if($ad_close_date<$today){
				$update_post = array(
				'ID' 			=> $id,
				'post_status'	=>	'expired',
				'post_type'		=>	'job' );
				wp_update_post($update_post);
			}elseif($ad_less_close_date==$today){				 
				kv_owner_job_expiring_soon($id,3);
			}	
		} 
	}	
}

################################################################################
// Get a packname using id
################################################################################
function get_packname_using_id($pack_id){
	global $wpdb; 
	$database_packname = $wpdb->prefix.'jbs_subpack' ;
	$pack_name=$wpdb->get_var('select pack_name from '.$database_packname.' where id='.$pack_id);
	return $pack_name; 
} 

################################################################################
// Getfull pack details with its id
################################################################################
function get_all_packdetails_using_id($pack_id){
	global $wpdb; 
	$database_packname = $wpdb->prefix.'jbs_subpack' ;
	$pack_data=$wpdb->get_row('select * from '.$database_packname.' where id='.$pack_id, ARRAY_A); 
	return $pack_data; 
}

################################################################################
// Get Active pack informations by using id
################################################################################
function get_packactiveinfo_using_id($id){
	global $wpdb;
	$database_packactive = $wpdb->prefix.'jbs_subactive' ;
	$pack_active=$wpdb->get_row('select * from '.$database_packactive.' where id='.$id);
	//echo $pack_active->pack_id; 
	return $pack_active;
}

/***************************************************
// Get Subscription Start date  from sub active table ...
****************************************************/
function kv_get_sub_start_date($user_id){
	global $wpdb; 
	$subactive_tbl = $wpdb->prefix.'jbs_subactive'; 
	$end_date = $wpdb->get_var("SELECT MAX(end_date) FROM ".$subactive_tbl." WHERE wp_user_id=".$user_id); 
	if(date('Y-m-d') <= $end_date) 
		return date('Y-m-d', strtotime($end_date . ' +1 day')); 
	else 
		return date('Y-m-d'); 
}

/***************************************************
//Get end date of a pack ...
****************************************************/
function kv_get_end_date_for_pack($pack_id, $date,$ajax_yes=false) {
	global $wpdb;
	$sub_pack_tbl = $wpdb->prefix."jbs_subpack"; 
	$mylink = $wpdb->get_row("SELECT * FROM ".$sub_pack_tbl." WHERE id = ".$pack_id, ARRAY_A);
	
	if($mylink['per_post']!=0 ){
		if($ajax_yes== false)
			return $mylink['per_post']; 
		else 
			return date('Y-m-d');
	} 
	else{ 
		if($mylink['period']=='Days')
			$add_days=1;
		else if($mylink['period']=='Months')
			$add_days=30;
		else if($mylink['period']=='Weeks')
			$add_days=7;
		else if($mylink['period']=='Years')
			$add_days=365;
		$count_days=$mylink['duration'] * $add_days;
		return $end_date = date('Y-m-d', strtotime($date. ' +'.$count_days.'days'));
	}
}

################################################################################
// Getting end date of active subscription
################################################################################
function get_subactive_data_for_subscription_end(){
	global $wpdb;
	$active_table = $wpdb->prefix.'jbs_subactive';	
	$myrow=$wpdb->get_results("select * from ".$active_table." where per_post=0 AND status='Active' AND end_date<CURDATE()");
	return $myrow; 
}

################################################################################
// Check whether the user has subscribed post count pack
################################################################################
function check_user_has_per_posts($user_id){
	global $wpdb; 
	$subactive_tbl = $wpdb->prefix.'jbs_subactive'; 
	return $wpdb->get_var("SELECT MAX(per_post) FROM ".$subactive_tbl." WHERE wp_user_id=".$user_id); 	
}


################################################################################
// Check whether the selected pack is active or not. if its active, we dont allow it to delete it.
################################################################################
function check_pack_is_active($pack_id){
	global $wpdb; 
	$subactive_tbl = $wpdb->prefix.'jbs_subactive'; 
	return $wpdb->get_var("SELECT status FROM ".$subactive_tbl." WHERE id=".$pack_id); 	
}

################################################################################
// Check whether the selected pack is active or not. if its active, we dont allow it to delete it.
################################################################################
function check_pack_has_purchased($pack_id){
	global $wpdb; 
	$subactive_tbl = $wpdb->prefix.'jbs_subactive'; 
	return $wpdb->get_var("SELECT id FROM ".$subactive_tbl." WHERE pack_id=".$pack_id." AND status = 'Active'"); 	
}

?>
