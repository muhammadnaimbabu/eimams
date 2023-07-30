<?php

/**
 * Template Name: Dashboard
 */
$theme_root = dirname(__FILE__);
require_once($theme_root . "/../library/user-backend-main.php");
if (is_user_logged_in()) {
  global $current_user;
  wp_get_current_user();
  kv_header();
  kv_top_nav();
  kv_leftside_nav();
  kv_section_start();
  $kv_current_role = kv_get_current_user_role();

  if ($kv_current_role == 'employer') {
    // Job seeker dashboard is the view->dashboard->dashboard.php
    include(get_template_directory() . '/template/view/dashboard/dashboard.php');

    /** Job seeker dashbaord */
  } else if ($kv_current_role == 'job_seeker') {
    global $current_user, $wp_roles, $wpdb;
    wp_get_current_user();
    $job_seeker_table = $wpdb->prefix . 'jobseeker';
    include(get_template_directory() . '/template/view/dashboard/jobseeker_dashboard.php');
  }
  kv_footer();
} else {
  wp_redirect(kv_login_url());
  exit;
}
