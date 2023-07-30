<?php

get_header();
$sidebar_class = 'col-lg-12 col-md-12 col-sm-12';

global $current_user, $kv_current_role;
wp_get_current_user();
$kv_current_role = kv_get_current_user_role();

?>



<section class="container" id="content">

	<!-- Page Heading -->
	<div class="page-heading animate-onscroll">

		<div class="row">
			<div class="col-lg-9 col-md-9 col-sm-9">


				<?php candidat_the_breadcrumbs(); ?>
				<?php //}
				?>

			</div>

		</div>

	</div>
	<!-- Page Heading -->

	<!-- Section -->
	<section class="section full-width-bg gray-bg">

		<div class="row">

			<div class="<?php echo esc_attr($sidebar_class); ?>">

				<?php if (have_posts()) while (have_posts()) : the_post();
					$page_type = get_post_meta($post->ID, 'page_type', true);
					if ($page_type == 'private') {
						if (kv_check_jobseeker_sub_expire($current_user->ID) || $kv_current_role == 'administrator') {
							the_content();
						} else {
							echo " Sorry, you need subscription to access and read this article ! If you are a registered User, buy a new subscription to read this article, else register and buy a pack";
						}
					} else
						the_content();
				endwhile; ?>
			</div>


		</div>

	</section>
	<!-- /Section -->

</section>
<?php
get_footer();

?>