<?php

/**
 * The Template for displaying all single posts.
 *
 */
get_header();
global $current_user, $kv_current_role;
wp_get_current_user();
$kv_current_role = kv_get_current_user_role();
global $post_id;
$post_id = $post->ID;
$full_class = 'fullwidth-post';
$type = 'post-blog';
$sidebar_position = 'full'; ?>

<section class="container" id="content">

	<!-- Page Heading -->
	<section class="section page-heading animate-onscroll">

		<!--	<h1><?php // echo esc_html(get_the_title());
					?></h1>	-->

		<?php candidat_the_breadcrumbs(); ?>
	</section>
	<!-- Page Heading -->

	<!-- Section -->
	<section class="max-post-content">

		<?php if (have_posts()) while (have_posts()) : the_post();
			global $post;
			if (kv_check_jobseeker_sub_expire($current_user->ID) || $kv_current_role == 'administrator') {
		?>
				<div class="<?php echo 'col-lg-12 col-md-12 col-sm-12'; ?>">

					<!-- Single Blog Post -->
					<div class="blog-post-single <?php echo esc_attr($full_class); ?>">

						<?php if ($sidebar_position == 'full') {  ?>

						<?php }  ?>


						<?php if (has_post_thumbnail() && $format == 'standard') {
						?>
							<?php the_post_thumbnail($type); ?>
						<?php } ?>

						<?php if (has_post_thumbnail() && $format == 'standard1') {
							$large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
						?>
							<div class="media-hover">
								<div class="media-icons">
									<a href="<?php echo esc_url($large_image_url[0]); ?>" data-group="media-jackbox" class="jackbox media-icon"><i class="icons icon-zoom-in"></i></a>
									<a href="<?php echo esc_url(get_permalink()); ?>" class="media-icon"><i class="icons icon-link"></i></a>
								</div>
							</div>
						<?php } ?>


						<div class="post-meta animate-onscroll">
							<span>by <?php the_author_posts_link(); ?></span>
							<span>in <?php echo get_the_category_list(', ', 'multiple', $post_id); ?></span>
						</div>


						<div class="post-content">
							<?php the_content(); ?>
						</div>



						<div class="animate-onscroll">
							<div class="numeric-pagination">
								<?php  // echo candidate_link_pages();
								?>
							</div>
						</div>



						<!-- Post Meta Track -->
						<div class="post-meta-track animate-onscroll">

							<table class="project-details">

								<tr>
									<td class="share-media">
										<ul class="social-share">
											<li><?php esc_html_e('Share this', THEMENAME); ?>:</li>
											<li class="facebook"><a href="#" class="tooltip-ontop" title="Facebook"><i class="icons icon-facebook"></i></a></li>
											<li class="twitter"><a href="#" class="tooltip-ontop" title="Twitter"><i class="icons icon-twitter"></i></a></li>
											<li class="google"><a href="#" class="tooltip-ontop" title="Google Plus"><i class="icons icon-gplus"></i></a></li>
											<li class="pinterest"><a href="#" class="tooltip-ontop" title="Pinterest"><i class="icons icon-pinterest-3"></i></a></li>
											<li class="email"><a href="#" class="tooltip-ontop" title="Email"><i class="icons icon-mail"></i></a></li>
										</ul>
									</td>
									<td class="tags"><?php // esc_html_e( 'Tags', THEMENAME );
														?> <!-- : --> <?php echo get_the_tag_list('', ', '); ?></td>
								</tr>

							</table>

						</div>
						<!-- /Post Meta Track -->




						<!-- Pagination -->
						<div class="row animate-onscroll">

							<div class="col-lg-6 col-md-6 col-sm-6 button-pagination align-left">
								<?php previous_post_link('%link', 'Prev post');  ?>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-6 button-pagination align-right">
								<?php next_post_link('%link', 'Next post'); ?>
							</div>

						</div>
						<!-- /Pagination -->




						<?php if (get_the_author_meta('description')) : // If a user has filled out their description, show a bio on their entries
						?>
							<!-- Post Author -->
							<div class="post-author animate-onscroll">

								<h4><?php // printf( esc_attr__( 'About %s', THEMENAME ), get_the_author() );
									?></h4>


							</div>
							<!-- /Post Author -->
						<?php endif; ?>


					</div>
					<!-- /Single Blog Post -->



					<!-- Related Articles -->
					<div class="related-articles">

						<h3 class="animate-onscroll"><?php // esc_html_e( 'Related Articles', THEMENAME );
														?></h3>

						<div class="row">
							<?php
							$category = candidat_theme_get_post_category($post_id);
							$esclude_post = $post_id;

							if ($sidebar_position == 'full') {
								candidat_theme_the_related_post(4, $category, $esclude_post, 'col-lg-3 col-md-3 col-sm-6');
							} else {
								candidat_theme_the_related_post(3, $category, $esclude_post, 'col-lg-4 col-md-4 col-sm-4');
							}
							?>
						</div>

					</div>
					<!-- /Related Articles -->
				</div>

		<?php } else {
				echo " Sorry, you need subscription to access and read this article ! If you are a registered User, buy a new subscription to read this article, else register and buy a pack";
			}
		endwhile; // end of the loop.
		?>


	</section>
	<!-- /Section -->

</section>


<?php get_footer(); ?>