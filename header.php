<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- // title  -->
	<title><?php wp_title('|', true, 'right'); ?></title>

	<!-- // Grid system of bootstrap  -->
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap-grid.css">

	<!-- // Form css  -->
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/form.css">

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


	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>


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
			<section class="navbar sticky">
				<div class="img-and-links">
					<a href="<?php echo site_url() ?>" class="logo"><img src="<?php echo get_template_directory_uri(); ?>/img/Logo.svg" width="100px" alt=""></a>


					<ul class="menu-items">
						<li><a href="<?php echo site_url() ?>" class="menu-item first-item">Home</a></li>

						<li class="dropdown">
							<a href="javascript:void()" class="menu-item first-item expand-btn">Career Advice
								<i class="feather-16" data-feather="chevron-down"></i>
							</a>
							<ul class="dropdown-menu sample">
								<li><a href="<?php echo site_url('category/cover-letter-tips') ?>" class="menu-item">Cover Letter Tips</a></li>
								<li><a href="<?php echo site_url('category/cv-tips') ?>" class="menu-item">CV Tips</a></li>
								<li><a href="<?php echo site_url('category/gaining-work-experience') ?>" class="menu-item">Gaining Work Experience</a></li>
								<li><a href="<?php echo site_url('category/graduates') ?>" class="menu-item">Graduates</a></li>
								<li><a href="<?php echo site_url('category/interview-tips') ?>" class="menu-item">Interview Tips</a></li>
								<li><a href="<?php echo site_url('category/preparation-tips') ?>" class="menu-item">Preparation Tips</a></li>
								<li><a href="<?php echo site_url('category/types-of-interview') ?>" class="menu-item">Types of Interview</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:void()" class="menu-item first-item expand-btn">Consultancy
								<i class="feather-16" data-feather="chevron-down"></i>
							</a>
							<ul class="dropdown-menu sample">
								<li class="dropdown dropdown-right">
									<a href="javascript:void()" class="menu-item expand-btn">
										Chaplaincy
										<i class="feather-16" data-feather="chevron-right"></i>
									</a>
									<ul class="menu-right sample">
										<li><a href="<?php echo site_url("hospital") ?>" class="menu-item">Hospital</a></li>
										<li><a href="<?php echo site_url("military") ?>" class="menu-item">Military</a></li>
										<li><a href="<?php echo site_url("police") ?>" class="menu-item">Police</a></li>
										<li><a href="<?php echo site_url("prison") ?>" class="menu-item">Prison</a></li>
										<li><a href="<?php echo site_url("school-and-university") ?>" class="menu-item">School And University</a></li>
									</ul>
								</li>
								<li><a href="<?php echo site_url("imams-and-teacher-training-courses") ?>" class="menu-item">Eimams Teacher and Training courses</a></li>
								<li><a href="<?php echo site_url('we-give-advice') ?>" class="menu-item">We Give Advice</a></li>
								<li><a href="<?php echo site_url("speaker-artist") ?>" class="menu-item">Artist and Speakers</a></li>
							</ul>
						</li>

						<div class="mobile-buttons">
							<?php if (!is_user_logged_in()) { ?>
								<li><a href="<?php echo site_url("login") ?>" class="secondary-btn login-btn">Login</a></li>
							<?php } else {
								global $current_user, $wp_roles, $wpdb;
								wp_get_current_user();
								echo "<li><a href=";
								if ($kv_current_role == 'administrator')
									echo admin_url();
								else
									echo site_url('dashboard');
								echo " class='secondary-btn login-btn' ></a>";
							} ?>
							<li><a href="<?php echo site_url("donate-today") ?>" class="primary-btn donate-btn">Donate Now</a></li>
						</div>

					</ul>
				</div>
				<div class="menu-btn">
					<i data-feather="align-right"></i>
				</div>
				<div class="cta-buttons">
					<?php if (!is_user_logged_in()) { ?>
						<a href="<?php echo site_url("login") ?>" class="secondary-btn login-btn">Login</a>
					<?php } else {
						global $current_user, $wp_roles, $wpdb;
						wp_get_current_user();
						echo "<a class='secondary-btn login-btn'  href=";
						if ($kv_current_role == 'administrator')
							echo admin_url();
						else
							echo site_url('dashboard');
						echo ">Dashboard</a>";
					} ?>
					<a href="<?php echo site_url("donate-today") ?>" class="primary-btn donate-btn">Donate Now</a>
				</div>
			</section>
		</div>
	</header>

	<!-- // Mobile version header  -->
	<div class="overlay"></div>

	<main class="bootstrap-wrapper">