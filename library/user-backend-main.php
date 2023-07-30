<?php

//      http://codepen.io/FilipDanic/pen/dPNRQm        - here the bubble looks nice. if possible we can use this one
require_once("kv-functions.php");
global  $kv_current_role, $user_status, $user_opacity, $enable_subscription, $enable_employer_subscription, $enable_shia_subscription;
$kv_current_role = kv_get_current_user_role();

$enable_subscription = get_option('enable_jobseeker_subscription');
$enable_employer_subscription = get_option('enable_employer_subscription');
$enable_shia_subscription = get_option('enable_shia_subscription');
function kv_header()
{
	global  $kv_current_role; ?>

	<!DOCTYPE html>

	<html lang="en">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

	<head>
		<title><?php wp_title('|', true, 'right'); ?> <?php bloginfo('name'); ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!-- <link rel="shortcut icon" href="../assets/media/logos/favicon.ico" /> -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<link href="<?php echo get_template_directory_uri(); ?>/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo get_template_directory_uri(); ?>/assets/plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo get_template_directory_uri(); ?>/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo get_template_directory_uri(); ?>/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

	</head>

	<body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">

	<?php }

function kv_login_header()
{ ?>
		<!DOCTYPE html>
		<html lang="en">

		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">

			<!-- // title  -->
			<title><?php wp_title('|', true, 'right'); ?></title>

			<!-- // Grid system of bootstrap  -->
			<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap-grid.css">

			<!-- // icon  -->
			<script src="<?php echo get_template_directory_uri(); ?>/js/feather.js"></script>

			<!-- Favicon -->
			<link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/img/eimams-favicon.ico">

			<!-- Swiper js  -->
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

			<style>
				.swiper {
					width: 100%;
					height: 100%;
				}

				.swiper-slide {
					width: fit-content;
				}
			</style>

			<!-- // main css  -->
			<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/main.css">


			<!-- Some important tag  -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=UA-78379053-1"></script>
			<script>
				window.dataLayer = window.dataLayer || [];

				function gtag() {
					dataLayer.push(arguments);
				}
				gtag('js', new Date());

				gtag('config', 'UA-78379053-1');
			</script>
			<meta name="google-site-verification" content="dQ312LITZxKyojgo31aKZ6wiv3B41j4yJ9T97FlIi3U" />
			<?php wp_head(); ?>
		</head>

		<body>
			<header class="bootstrap-wrapper">
				<div class="container">
				<?php }

			function kv_top_nav()
			{
				global  $kv_current_role, $current_user, $wpdb;
				wp_get_current_user(); ?>
					<div class="d-flex flex-column flex-root app-root">
						<div class="app-page flex-column flex-column-fluid">
							<div id="kt_app_header" class="app-header ">
								<div class="app-container container-fluid d-flex align-items-stretch justify-content-between " id="kt_app_header_container">
									<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
										<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
											<i class="ki-duotone ki-abstract-14 fs-2 fs-md-1"><span class="path1"></span><span class="path2"></span></i>
										</div>
									</div>
									<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
										<a href="../index.html" class="d-lg-none">
											<img alt="Logo" src="../assets/media/logos/default-small.svg" class="h-30px" />
										</a>
									</div>
									<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
										<div class=" app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
											<div class=" menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">
												<div class="d-flex align-items-center" id="kt_docs_header_title">
													<div class="docs-page-title d-flex flex-column flex-lg-row align-items-lg-center py-5 mb-lg-0" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_docs_content_container', 'lg': '#kt_docs_header_title'}">

														<!--begin::Title-->
														<h1 class="d-flex align-items-center text-dark my-1 fs-4">
															Eimams

															<span class="badge fw-semibold fs-9 px-2 ms-2 bg-body text-dark text-hover-primary shadow-sm">
																v2.0.0 </span>
														</h1>
														<!--end::Title-->

														<!--begin::Separator-->
														<span class="d-none d-lg-block bullet h-20px w-1px bg-secondary mx-4"></span>
														<!--end::Separator-->

														<!--begin::Breadcrumb-->
														<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-6 my-1">
															<li class="breadcrumb-item text-dark">
																<?php $current_user = wp_get_current_user();
																echo $current_user->display_name; ?>
																<span class="badge badge-success fw-semibold fs-9 px-2 ms-2 cursor-default ms-2" data-bs-toggle="tooltip" data-bs-original-title="Developed in-house for Metronic" data-kt-initialized="1">
																	<?php if ($kv_current_role == 'job_seeker') {
																		echo "Job Seeker";
																	} elseif ($kv_current_role == 'employer')
																		echo "Employer";
																	?>
																</span>
															</li>
															<!--end::Item-->

														</ul>
														<!--end::Breadcrumb-->
													</div>

													<!--begin::Page title-->

													<!--end::Page title-->
												</div>
											</div>
										</div>
										<div class="app-navbar flex-shrink-0">
											<div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
												<div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
													<?php
													$get_user_image = get_user_meta($current_user->ID, 'user_image', true);
													$get_user_image_id = get_user_meta($current_user->ID, 'company_logo_attachment_id', true);

													$user_status = '';
													if (kv_get_user_status() == 0) {
														$user_status = 'success';
														$user_opacity = 1;
													} else {
														$user_status = 'warning';
														$user_opacity = 0.1;
													}

													if ((isset($get_user_image['url']) && $get_user_image['url'] != null) || ($kv_current_role == 'employer' &&  $get_user_image_id > 0)) {
														if ($get_user_image != null)
															echo '<img src="' . $get_user_image['url'] . '?' . uniqid() . '" class="user-image img-responsive" style="opacity:' . $user_opacity . ';" />';
														else {
															$url = wp_get_attachment_image_src($get_user_image_id);
															echo '<img src="' . $url[0] . '?' . uniqid() . '" class="user-image img-responsive" style="opacity:' . $user_opacity . ';" />';
														}
													} else {
														if ($kv_current_role == 'job_seeker') {
															$job_see_tbl = $wpdb->prefix . "jobseeker";
															$gender = $wpdb->get_var("SELECT gender FROM " . $job_see_tbl . " WHERE wp_usr_id=" . $current_user->ID . " LIMIT 1");
															if ($gender == 'male')
																$dp_file_name = "male-profile-pic.png";
															elseif ($gender == 'female')
																$dp_file_name = "female-profile-pic.png";
														} elseif ($kv_current_role == 'employer') {
															$dp_file_name = "default-dp-eimams.png";
														}
														echo '<img src="' . get_template_directory_uri() . '/images/' . $dp_file_name . '" class="user-image img-responsive" />';
													}
													//echo '<img src="'.get_template_directory_uri().'/assets/img/find_user.png" class="user-image img-responsive"  style="opacity:'.$user_opacity.';"/>';

													?>
												</div>
												<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
													<div class="menu-item px-3">
														<div class="menu-content d-flex align-items-center px-3">
															<div class="symbol symbol-50px me-5">
																<?php
																$get_user_image = get_user_meta($current_user->ID, 'user_image', true);
																$get_user_image_id = get_user_meta($current_user->ID, 'company_logo_attachment_id', true);

																$user_status = '';
																if (kv_get_user_status() == 0) {
																	$user_status = 'success';
																	$user_opacity = 1;
																} else {
																	$user_status = 'warning';
																	$user_opacity = 0.1;
																}

																if ((isset($get_user_image['url']) && $get_user_image['url'] != null) || ($kv_current_role == 'employer' &&  $get_user_image_id > 0)) {
																	if ($get_user_image != null)
																		echo '<img src="' . $get_user_image['url'] . '?' . uniqid() . '" class="user-image img-responsive" style="opacity:' . $user_opacity . ';" />';
																	else {
																		$url = wp_get_attachment_image_src($get_user_image_id);
																		echo '<img src="' . $url[0] . '?' . uniqid() . '" class="user-image img-responsive" style="opacity:' . $user_opacity . ';" />';
																	}
																} else {
																	if ($kv_current_role == 'job_seeker') {
																		$job_see_tbl = $wpdb->prefix . "jobseeker";
																		$gender = $wpdb->get_var("SELECT gender FROM " . $job_see_tbl . " WHERE wp_usr_id=" . $current_user->ID . " LIMIT 1");
																		if ($gender == 'male')
																			$dp_file_name = "male-profile-pic.png";
																		elseif ($gender == 'female')
																			$dp_file_name = "female-profile-pic.png";
																	} elseif ($kv_current_role == 'employer') {
																		$dp_file_name = "default-dp-eimams.png";
																	}
																	echo '<img src="' . get_template_directory_uri() . '/images/' . $dp_file_name . '" class="user-image img-responsive" />';
																}
																//echo '<img src="'.get_template_directory_uri().'/assets/img/find_user.png" class="user-image img-responsive"  style="opacity:'.$user_opacity.';"/>';

																?>
															</div>
															<div class="d-flex flex-column">
																<div class="fw-bold d-flex align-items-center fs-5">
																	<?php $current_user = wp_get_current_user();
																	echo $current_user->display_name;
																	?>
																	<span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
																		<?php if ($kv_current_role == 'job_seeker') {
																			echo "Job Seeker";
																		} elseif ($kv_current_role == 'employer')
																			echo "Employer";
																		?></span>

																</div>
																<a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
																	<?php $current_user = wp_get_current_user();
																	echo $current_user->user_email;
																	?>
																</a>
															</div>
														</div>
													</div>
													<div class="separator my-2"></div>
													<div class="menu-item px-5 my-1">
														<a href="<?php echo site_url('profile-update') ?>" class="menu-link px-5">
															Account Settings
														</a>
													</div>
													<div class="menu-item px-5">
														<a href="<?php echo wp_logout_url(site_url()); ?>" class="menu-link px-5">
															Sign Out
														</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php }

					function kv_leftside_nav()
					{
						global  $kv_current_role, $current_user, $wpdb, $enable_subscription, $enable_employer_subscription; ?>
							<div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
								<div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
									<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
										<a href="<?php site_url() ?>">
											<img alt="Logo" src="<?php echo get_template_directory_uri() ?> /images/eimams-main-logo.png" class="h-40px app-sidebar-logo-default" />
											<img alt="Logo" src="<?php echo get_template_directory_uri() ?> /images/eimams-icon.png" class="h-50px app-sidebar-logo-minimize" />
										</a>
										<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate " data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
											<i class="ki-duotone ki-double-left fs-2 rotate-180"><span class="path1"></span><span class="path2"></span></i>
										</div>
									</div>
									<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
										<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
											<div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
												<div class="menu-item  menu-accordion">
													<span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-element-11 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span><a href="<?php echo site_url('dashboard') ?>" class="menu-title">Dashboard</a></span>
												</div>
												<?php if ($kv_current_role == 'job_seeker') {
												?>

													<div class="menu-item pt-5">
														<div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Job</span></div>
													</div>
													<div class="menu-item menu-accordion">
														<span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-check-square fs-2"><span class="path1"></span><span class="path2"></span></i></span><a href="<?php echo site_url('/applied-job') ?>" class="menu-title">Applied
																Job</a></span>
													</div>
													<div class="menu-item menu-accordion">
														<span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-tablet-text-down
                                                fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span><a href="<?php echo site_url('jobs') ?>" class="menu-title">Job
																Listing</a></span>
													</div>
													<div class="menu-item menu-accordion">
														<span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-office-bag
                                                fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span><a href="<?php echo site_url('/jobs-available') ?>" class="menu-title">Available
																Jobs</a></span>
													</div>

												<?php } elseif ($kv_current_role == 'employer') { ?>

													<div class="menu-item pt-5">
														<div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Employer</span></div>
													</div>
													<div class="menu-item menu-accordion">
														<span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-check-square fs-2"><span class="path1"></span><span class="path2"></span></i></span><a href="<?php echo site_url('/applied-resumes') ?>" class="menu-title">Applied
																Resumes</a></span>
													</div>
													<div class="menu-item menu-accordion">
														<span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-tablet-text-down
                                                fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span><a href="<?php echo site_url('posted-jobs') ?>" class="menu-title">Posted Jobs</a></span>
													</div>
													<div class="menu-item menu-accordion">
														<span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-office-bag
                                                fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span><a href="<?php echo site_url('/add-new-job') ?>" class="menu-title">Add New Job</a></span>
													</div>
												<?php } ?>
												<div class="menu-item pt-5">
													<div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Subscription</span></div>
												</div>
												<div class="menu-item menu-accordion">
													<span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-dollar
                                                fs-2"><span class="path1"></span><span class="path2"></span></i></span><a href="<?php echo site_url('/subscription') ?>" class="menu-title">Subscriptions</a></span>
												</div>
												<div class="menu-item pt-5">
													<div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Other</span></div>
												</div>
												<div class="menu-item menu-accordion">
													<span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-information-4 fs-2"><span class="path1"></span><span class="path2"></span></i></span><a href="<?php echo site_url('/help-and-support') ?>" class="menu-title">
															Help & Support</a></span>
												</div>
												<div class="menu-item menu-accordion">
													<span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-question fs-2"><span class="path1"></span><span class="path2"></span></i></span><a href="<?php echo site_url('/faq') ?>" class="menu-title">FAQ</a></span>
												</div>
											</div>
										</div>
									</div>
									<div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
										<a href="https://taranaa.co.uk" class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" title="We focus each and Every detail">
											<span class="btn-label">
												Design & Dev - Taranaa
											</span>
											<i class="ki-duotone ki-document btn-icon fs-2 m-0"><span class="path1"></span><span class="path2"></span></i> </a>
									</div>
								</div>
							<?php }

						function kv_section_start()
						{ ?>
								<div id="kt_app_content" class="app-content container flex-column-fluid ">

									<div id="kt_app_content_container" class="app-container   container-xxl ">
										<div class="d-flex flex-column flex-column-fluid">
										<?php global   $current_user;
										$verify_status = get_user_meta($current_user->ID, 'verification', true);
										if ($verify_status !== 'yes')

											echo '<div class="alert mt-5 alert-danger box-shadow">
    <a href="#" class="close" data-dismiss="alert">×</a>
    <strong>Warning!</strong> Your  Email address is not verified. Please check your mail. may be your junk folder too. If you didnt receive , then <a href="' . kv_login_url() . '?resend_validation=' . wp_generate_password(8, false) . '"> click here </a> . </div>';

										//	echo '<span class="errors"> Your  Email address is not verified. Please check your mail. may be your junk folder too. If you didnt receive , than <a href="'.kv_login_url().'?resend_validation='.wp_generate_password(8, false).'"> click here </a> .</span>';
									}
									function kv_footer()
									{
										global  $kv_current_role; ?>
										</div>
									</div>
								</div>
							</div>

							<!-- </div> -->
							<script>
								var hostUrl = "../assets/index.html";
							</script>
							<script src="<?php echo get_template_directory_uri() ?>/assets/plugins/global/plugins.bundle.js"></script>
							<script src="<?php echo get_template_directory_uri() ?>/assets/js/scripts.bundle.js"></script>
							<script src="<?php echo get_template_directory_uri() ?>/assets/plugins/custom/datatables/datatables.bundle.js"></script>
							<script src="<?php echo get_template_directory_uri() ?>/assets/js/widgets.bundle.js"></script>
							<script src="<?php echo get_template_directory_uri() ?>/assets/js/custom/widgets.js"></script>
							<script src="<?php echo get_template_directory_uri() ?>/assets/js/custom/utilities/modals/create-account.js"></script>
		</body>

		</html>

	<?php }

									function kv_login_footer()
									{ ?>
		</div>
		</div>
		<!-- JQUERY SCRIPTS -->
		<script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery-1.10.2.js"></script>
		<script>
			$(document).ready(function() {
				$('#forgot_password').hide();
				$('#not_register').hide();
				<?php $current_url = substr("http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI'], 0, -1);
										$site_login_url = site_url('login');
										if ($site_login_url == $current_url) echo "$('#TopSignIn').hide();"; ?>
				$('#forgot-password').on('click', function(e) {
					e.preventDefault();
					$('#forgot_password').show();
					$('#login-form').hide();
					$('#TopSignIn').hide();
				});

				$('.not_register_button').on('click', function(e) {
					e.preventDefault();
					$('#not_register').show();
					$('#forgot_password').hide();
					$('#TopSignIn').show();
					$('#login-form').hide();
				});

				$('#have_id').on('click', function(e) {
					e.preventDefault();
					$('#forgot_password').hide();
					$('#TopSignIn').hide();
					$('#login-form').show();
				});


				$('.close').on('click', function(e) {
					e.preventDefault();
					$('.error-message').hide();
					$('.error').hide();

				});

			});
		</script>
		<!-- BOOTSTRAP SCRIPTS -->
		<script src="<?php echo get_template_directory_uri(); ?>/assets/js/bootstrap.min.js"></script>

	</body>

	</html>
<?php } ?>