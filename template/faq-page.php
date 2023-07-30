<?php

/**
 * Template Name: Applied Job -Jobseeker
 */
$theme_root = dirname(__FILE__);
require_once($theme_root . "/../library/user-backend-main.php");

$i = 1;

$args = array(
	'post_type' => 'faqs',
	'post_status' => array('publish'),
	'posts_per_page' => -1
);

$query = new WP_Query($args);
if (is_user_logged_in()) {
	kv_header();
	kv_top_nav();
	kv_leftside_nav();
	kv_section_start();

?>

	<div id="faq-page">
		<div id="page-inner">
			<div id="kt_app_content" class="app-content mt-10  flex-column-fluid ">
				<!--begin::Content container-->
				<div id="kt_app_content_container" class="app-container  container-xxl ">
					<!--begin::FAQ card-->
					<div class="card">
						<div class="card-body p-lg-15">
							<!-- Advanced Tables -->
							<div class="mb-13">
								<!--begin::Intro-->
								<div class="mb-15">
									<!--begin::Title-->
									<h4 class="fs-2x text-gray-800 w-bolder mb-6">
										Frequesntly Asked Questions
									</h4>
									<!--end::Title-->

									<!--begin::Text-->
									<p class="fw-semibold fs-4 text-gray-600 mb-2">
										First, a disclaimer – the entire process of writing a blog post
										often takes more than a couple of hours,
										even if you can type eighty words as per minute and your writing
										skills are sharp.
									</p>
									<!--end::Text-->
								</div>
								<!--end::Intro-->



								<div class="panel-group" id="accordion">

									<?php while ($query->have_posts()) {
										$query->the_post();


										echo '<div class="row mb-12">
										<!--begin::Col-->
										<div class="col-md-6 pe-md-10 mb-10 mb-md-0">
											<!-- egin::Section-->
											<div class="m-0">
												<!--begin::Heading-->
												<div class="d-flex align-items-center collapsible py-3 toggle mb-0 collapsed"
													data-bs-toggle="collapse" data-bs-target="#kt_job_4_1"
													aria-expanded="false">
													<!--begin::Icon-->
													<div
														class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
														<i
															class="ki-duotone ki-minus-square toggle-on text-primary fs-1"><span
																class="path1"></span><span
																class="path2"></span></i>
														<i class="ki-duotone ki-plus-square toggle-off fs-1"><span
																class="path1"></span><span
																class="path2"></span><span
																class="path3"></span></i>
													</div>
													<!--end::Icon-->

													<!--begin::Title-->
													<h4 class="text-gray-700 fw-bold cursor-pointer mb-0">
														​
														' . get_the_title() . '
													</h4>
													<!--end::Title-->
												</div>
												<!--end::Heading-->

												<!--begin::Body-->
												<div id="kt_job_4_1" class="fs-6 ms-1 collapse">
													<!--begin::Text-->
													<div class="mb-4 text-gray-600 fw-semibold fs-6 ps-10">
													' . get_the_content() . '</div>
													<!--end::Text-->
												</div>
												<!--end::Content-->


												<!--begin::Separator-->
												<div class="separator separator-dashed"></div>
												<!--end::Separator-->
											</div>

											<!--end::Accordion-->
										</div>
										<!--end::Col-->


									</div>';
									} ?>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php

	kv_footer();
} else {


	get_header(); ?>

		<section id="content">

			<!-- Page Heading -->
			<section class="section page-heading animate-onscroll">

				<div class="row">
					<div class="col-lg-9 col-md-9 col-sm-9">

						<h1><?php echo esc_html(get_the_title()); ?></h1>

						<?php //if(get_option('sense_show_breadcrumb') == 'show') {
						?>
						<?php candidat_the_breadcrumbs(); ?>
						<?php //}
						?>

					</div>
				</div>

			</section>
			<!-- Page Heading -->

			<!-- Section -->
			<section class="section full-width-bg gray-bg">

				<div class="row">

					<div class="<?php echo esc_attr($sidebar_class); ?>">

						<br />

						<div id="faq-page-frontend">
							<div class="panel-group" id="accordion">

								<?php while ($query->have_posts()) {
									$query->the_post();

									echo '<div class="panel panel-default">
								<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse' . $i . '">
									<i class="box-orange-icon-panel"></i>
									​<span class="fa fa-caret-right"></span>
									<h4 style="font-weight: normal;" class="panel-title">
									   ' . get_the_title() . '
									</h4>
								</div>
								<div id="collapse' . $i++ . '" class="panel-collapse collapse">
									<div class="panel-body">
														' . get_the_content() . '
									</div>
								</div>
							</div>';
								} ?>

							</div>
						</div> <!-- Faq page frontend -->






					</div>
				</div>

			</section>
			<!-- /Section -->

		</section>
	</div>
	</div>
	<div class="container">
	<?php
	get_footer();
}

	?>

	<script>
		$(function() {
			$(".panel-heading").on("click", function() {


				if ($(this).find("span").is(".fa-caret-right")) {

					$("#accordion span").each(function(index, elem) {
						$(this).removeClass("fa-caret-right").addClass("fa-caret-right");
					});

					$(this).find("span").removeClass("fa-caret-right").addClass("fa-caret-down");
				} else {
					$(this).find("span").removeClass("fa-caret-down").addClass("fa-caret-right");
					// $('span').not(".fa-active").addClass( "fa-caret-right" );
				}

			});
		});
	</script>