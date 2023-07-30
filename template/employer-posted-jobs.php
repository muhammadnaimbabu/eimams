<?php

/**
 * Template Name: Posted Jobs By Employer
 */
$theme_root = dirname(__FILE__);
require_once($theme_root . "/../library/user-backend-main.php");
$kv_current_role = kv_get_current_user_role();
global $current_user, $wp_roles, $wpdb;
wp_get_current_user();
if (is_user_logged_in() && $kv_current_role == 'employer') {
	kv_header();
	kv_top_nav();
	kv_leftside_nav();
	kv_section_start();
	$args_current_user = array(
		'post_type' => 'job',
		'author' => $current_user->ID,
		'post_status' => 'any',
		'posts_per_page' => -1

	);
	// Before footer insert the main template called employer-poested-job
	include(get_template_directory() . '/template/view/job/emp-posted-job.php');
	kv_footer();
} else {
	wp_redirect(kv_login_url());
	exit;
}
