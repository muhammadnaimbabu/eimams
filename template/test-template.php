<?php 
/**
Template Name: Test purpose 
*/


/******************************************
*		New User Registration		*
******************************************/
//kv_new_user_notification(59, 'srgsergsers'); 


/******************************************
*	ReSend validation code and link		*
******************************************/
//kv_Resend_validation_mail(59);


/******************************************
*		New User Verified			      *
******************************************/
//user_verified_email(70);


/******************************************
*		User Subscription ended		 *
******************************************/
//kv_user_subscription_ended_email(77);

//kv_user_subscription_ended_renew(42, 63);

//kv_user_subscription_expire_inday( 77 , 3);
//kv_user_subscription_expire_inpost(63, 5);
//kv_owner_new_job_pending(585);
//test_email_with_cron_job_fn();

  /* global $wpdb;
   $table = $wpdb->prefix.'postmeta';
   $wpdb->delete ($table, array('meta_key' => '_edit_last'));
   $wpdb->delete ($table, array('meta_key' => '_edit_lock'));
   $wpdb->delete ($table, array('meta_key' => '_wp_old_slug')); */
   
 //employer_notification_new_user_applied(20);
 
 //Kv_jobAlert_toSubscribed_jobseekers(573);

 // wp_mail('kvvaradhauk@gmail.com', 'test mail direct from arvixe wp fn', 'test mail content');
 
 echo admin_url().'edit.php?post_status=pending&post_type=tickets';
 
  //if(wp_mail( 'kvvaradha@gmail.com', 'Notification TEST', 'TEST email by running send_email function<br> Automated email ', null ))
//	echo "success " ;
//	else echo "failure" ; 

//kv_subscribe_email_to_reduce_perpost(70);

//kv_subscribers_status_change();


 kv_schedule_emails_from_queue();
?>

