<?php

/** A neat and Clean theme made by Team Taranaa */

get_header();

/** Get the total amount of jobs */
$args = array(
	'post_type' => 'job',
	'post_status' => array('publish'),
	'paged' => 1
);
$query = new WP_Query($args);

include('template/front-end/hero-banner.php');
include('template/front-end/search-bar.php');
include('template/front-end/scholer.php');
include('template/front-end/eimams.php');
include('template/front-end/endorsement.php');
include('template/front-end/top-employers.php');
include('template/front-end/video.php');

get_footer();
