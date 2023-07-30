<?php 
/**
 * Template Name: User Profile
 */ 
global $current_user, $wp_roles, $wpdb;
wp_get_current_user();

$template_root = dirname(__FILE__);
require_once($template_root."/../library/user-backend-main.php");

foreach ( $wp_roles->role_names as $role => $name ) :
	if ( current_user_can( $role ) )
		$current_usr_rle =  $role;
endforeach;	
	if( is_user_logged_in() ) {		 
		kv_header();
		kv_top_nav();
		kv_leftside_nav();		
		kv_section_start(); 
		if($current_usr_rle == 'employer') { 
			require_once($template_root. '/profile/employer-profile.php');
		} elseif($current_usr_rle == 'job_seeker') { 
			require_once($template_root. '/profile/jobseeker-profile.php');
		} 
		
	kv_footer();
	} else {
	wp_redirect( kv_login_url() ); exit; 
}
	
?>