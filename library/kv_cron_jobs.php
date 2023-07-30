<?php

/*****************************************
*Cron Job Scheduler 
****************************************/
function eimams_event_scheduler() {
  $check = get_option('eimams_activation_check');

  if ( $check != "set" ) {
    // One time stuffs
	wp_schedule_event(time(), 'daily', 'kv_daily_post_expire_check');
	wp_schedule_event(time(), 'daily', 'kv_subscribers_status_change');
	wp_schedule_event(time(), 'hourly', 'kv_newsletter_mailing_queue');
	wp_schedule_event(1456884600, 'daily', 'kv_check_subscription_expire_in_3posts');
	wp_schedule_event(1456888320, 'daily', 'kv_check_subscription_expire_in_3day');
	wp_schedule_event(1456928320, 'daily', 'kv_check_subscription_expire_in_1day');
	wp_schedule_event(1456968320, 'daily', 'kv_subscribers_status_change');
    // Add marker so it doesn't run in future
    add_option('eimams_activation_check', "set");
  }
  
}
add_action('kv_daily_post_expire_check', 'kv_daily_post_expire_check');
add_action('kv_subscribers_status_change', 'kv_subscribers_status_change');
add_action('kv_newsletter_mailing_queue', 'kv_schedule_emails_from_queue');
add_action('kv_check_subscription_expire_in_3posts', 'kv_check_subscription_expire_in_3posts');
add_action('kv_check_subscription_expire_in_3day', 'kv_check_subscription_expire_in_3day');
add_action('kv_check_subscription_expire_in_1day', 'kv_check_subscription_expire_in_1day');
add_action('kv_subscribers_status_change', 'kv_subscribers_status_change');
  
add_action('wp_head', 'eimams_event_scheduler');


/*****************************************
*cron Job  testing function
****************************************/
// Scheduled Action Hook
function send_mail(  ) {
	//test_email_with_cron_job_fn();
	//return wp_mail( 'kvvaradha@gmail.com', 'Notification TEST', 'TEST email by running send_email function<br> Automated email ', null );
}
add_action( 'send_mail', 'send_mail' );
// Schedule Cron Job Event
function custom_cron_job() {
	if ( ! wp_next_scheduled( 'send_mail' ) ) {
		wp_schedule_event( current_time( 'timestamp' ), 'daily', 'send_mail' );
	}
}
add_action( 'wp', 'custom_cron_job' );


/***************************************************
//create Cron queue for job alert to users
****************************************************/
function Kv_jobAlert_toSubscribed_jobseekers($post_id) {
	global $wpdb; 
	$db_table_name= $wpdb->prefix.'mailing_queue';
	$table_jobseeker= $wpdb->prefix.'jobseeker';
	$table_newsletter= $wpdb->prefix.'newsletter';
	$term_list = wp_get_post_terms($post_id, 'job_category', array("fields" => "ids"));
	//echo $term_list[0];
	//$users_list = $wpdb->get_results("SELECT newslet.* FROM ".$table_newsletter." AS newslet  INNER JOIN ".$table_jobseeker." AS seeker ON seeker.wp_usr_id = newslet.wp_user_id WHERE seeker.category=".$term_list[0]." AND AND newslet.jobalert= 1 ORDER BY wp_usr_id LIMIT 100");

	/*SELECT newslet.*
FROM eimam_newsletter AS newslet 
INNER JOIN  eimam_jobseeker AS seeker ON seeker.wp_usr_id = newslet.wp_user_id
WHERE seeker.category =45 AND newslet.jobalert= 1
ORDER BY wp_usr_id*/
	//print_r($users_list);
 $message = 'Hi , <br> 
					There is a new job which matches your profile. <a href="'.site_url('jobs-available') . '"> Click Here</a> to check the job details. <br>' . '<br>';


	$insrted_id = $wpdb->insert($db_table_name, array('type' =>'Job Alert', 'subject' => 'A New Job Vacancy matches your Category', 'message' => $message, 'start_id' => 0, 'job_cat_id' => $term_list[0],  'status' => 'Queued'));


} 


/*****************************************
* Email Scheduler from the queue - 1 hour once queue
****************************************/
function kv_schedule_emails_from_queue(){
	global $wpdb; 
	$db_table_name= $wpdb->prefix.'mailing_queue';
	$newslette_table= $wpdb->prefix.'newsletter';
	$table_jobseeker= $wpdb->prefix.'jobseeker';
	$status = '';
	$Current_row = $wpdb->get_row("SELECT * FROM ".$db_table_name." WHERE status='Processing'");
	 var_dump($Current_row);
	if($Current_row != null){
		
	}else { 
		$Current_row = $wpdb->get_row("SELECT * FROM ".$db_table_name." WHERE status='Queued' ORDER BY  id DESC");
		// $change_it_to_process = $wpdb->update($db_table_name, array('status'=> 'Processing'), array('id' => $Current_row->id));		
	}
	if($Current_row != null){

		$just_check = 'no' ;
		if($Current_row->type == 'Job Seekers'){
			$users_list = get_users("role=job_seeker&number=100&offset=".$Current_row->start_id);
			$just_check = 'yes' ;
			$users =  get_users("role=job_seeker&number=1&order=DESC");
			$last_user_registered = $users[0]; // the first user from the list

			$last_user_id =  $last_user_registered->ID; 
		}elseif($Current_row->type == 'Employers'){
			$users_list = get_users("role=employer&number=100&offset=".$Current_row->start_id);
			$just_check = 'yes' ;
			$users =  get_users("role=employer&number=1&order=DESC");
			$last_user_registered = $users[0]; // the first user from the list

			$last_user_id =  $last_user_registered->ID; 
		}elseif($Current_row->type == 'Common')
			$joint = " common = 1"; 
		elseif($Current_row->type == 'Common Without Users')
			$joint = " common = 1 AND wp_user_id = 0 "; 
		elseif($Current_row->type == 'Unsubscribed')
			$joint = " common = 0"; 
		elseif($Current_row->type == 'Job Alert'){
			$joint = "job_cat_id=".$Current_row->job_cat_id." AND jobalert= 1"; 
		}
		
		$headers = 'From: '. __('Admin', 'kvc') .' <info@testsite.co.uk>' . '<br>';
		

	$message  = email_header();	
	$message .= $Current_row->message;    
	$message .= kv_email_footer(); 
		
		if($just_check == 'no'){
			if($Current_row->type == 'Job Alert'){

				$resultss = $wpdb->get_results("SELECT newslet.* FROM ".$newslette_table." AS newslet  INNER JOIN ".$table_jobseeker." AS seeker ON seeker.wp_usr_id = newslet.wp_user_id WHERE seeker.category=".$Current_row->job_cat_id." AND newslet.id >=".$Current_row->start_id." AND newslet.jobalert= 1 ORDER BY wp_usr_id LIMIT 100");

			}else {
				$resultss = $wpdb->get_results("SELECT * FROM ".$newslette_table." WHERE ".$joint." AND id >=".$Current_row->start_id." ORDER BY id LIMIT 100" );				
				
			}

			$results_count = $wpdb->get_var("SELECT max(id) FROM ".$newslette_table." WHERE ".$joint);
			//var_dump($resultss);
			//echo $results_count;
			if(!empty($resultss)){
				foreach($resultss as $resu) {				
					wp_mail($resu->email, $Current_row->subject, $message, $headers);	
					$last_id = $resu->id; 
					
					if($last_id ==$results_count)
						$status = "Sent"; 
				}
			}else {
				$change_start_id = $wpdb->update($db_table_name, array('start_id'=> $last_id, 'status' => 'Sent'), array('id' => $Current_row->id));
				kv_schedule_emails_from_queue();
			}
		}else {
			foreach($users_list as $user) {				
				wp_mail($user->user_email, $Current_row->subject, $message, $headers);	
				$last_id = $user->ID;
				//$max_count = $wpdb->get_var("SELECT max(ID) FROM " .$newslette_table." LIMIT 1");
				if($last_id >= $last_user_id)
					$status = "Sent";
			}	
		}
		
		if($status == 'Sent')
			$change_start_id = $wpdb->update($db_table_name, array('start_id'=> $last_id, 'status' => 'Sent'), array('id' => $Current_row->id));	
		else
			$change_start_id = $wpdb->update($db_table_name, array('start_id'=> $last_id), array('id' => $Current_row->id));	
	}
}
