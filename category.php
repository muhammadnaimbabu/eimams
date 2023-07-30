<?php
get_header();
global $current_user, $kv_current_role;

wp_get_current_user();
$kv_current_role = kv_get_current_user_role();
?>

<section class="bootstrap-wrapper" id="content">

	<!-- Page Heading -->
	<section class="container">

		<?php candidat_the_breadcrumbs(); ?>

	</section>
	<!-- Page Heading -->

	<!-- Section -->
	<section class="container">

		<div class="max-post-content">

			<?php if (kv_check_jobseeker_sub_expire($current_user->ID) || $kv_current_role == 'administrator') {
				if (have_posts()) while (have_posts()) : the_post();
					$category = get_the_category();
					$format = 'standard';

					$title1 = get_the_title();
					if ($title1 == '')
						$title1 = 'No Title';



			?>

					<!-- Blog Post -->
					<div <?php post_class('blog-post animate-onscroll '); ?>>
						<?php
						$type = 'post-full';
						$post_id = $post->ID;
						$large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');

						if (has_post_thumbnail()) { ?>
							<div class="post-image">

								<?php if (has_post_thumbnail() && $format == 'standard') { ?>
									<?php the_post_thumbnail($type); ?>
								<?php } ?>

								<?php if (has_post_thumbnail() && $format == 'standard') { ?>
									<div class="media-hover">
										<div class="media-icons">
											<a href="<?php echo esc_url($large_image_url[0]); ?>" data-group="media-jackbox" class="jackbox media-icon"><i class="icons icon-zoom-in"></i></a>
											<a href="<?php echo esc_url(get_permalink()); ?>" class="media-icon"><i class="icons icon-link"></i></a>
										</div>
									</div>
								<?php } ?>

							</div>
						<?php } ?>

						<div class="post-content">



							<div class="post-header">
								<h2><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html($title1); ?></a></h2>
								<div class="post-meta">
									<span>by <?php the_author_posts_link(); ?></span>
									<span>in <?php echo get_the_category_list(', ', 'multiple', $post->ID); ?></span>
								</div>
							</div>

							<div class="post-exceprt">

								<?php if ($format != 'blockquote' && $format != 'link') { ?>
									<p><?php candidat_the_excerpt_max_charlength(40); ?></p>
								<?php } ?>

								<?php if ($format == 'blockquote') { ?>
									<blockquote class="iconic-quote"><?php candidat_the_excerpt_max_charlength(40); ?></blockquote>
								<?php } ?>

								<?php if ($format == 'link') { ?>
									<blockquote class="iconic-quote link-quote"><a href="<?php echo esc_url(get_meta_option('custom_link_meta_box', $post_id)); ?>"><?php candidat_the_excerpt_max_charlength(40); ?></a></blockquote>
								<?php } ?>

								<a href="<?php echo esc_url(get_permalink()); ?>" class="button read-more-button big button-arrow"><?php esc_html_e('Read More', THEMENAME); ?></a>

							</div>

						</div>

					</div>
		</div>
		<!-- /Blog Post -->

<?php
				endwhile;
			} else {
				echo " Ooops, you are not subscribed, you need to subscribe to access and read this article. If you are already a registered user, buy a new pack to proceed. ";
			} ?>

<div class="animate-onscroll">

	<div class="divider"></div>

	<?php if ($wp_query->max_num_pages > 1) { ?>
		<div class="numeric-pagination">
			<?php candidat_pagenavi(); ?>
		</div>
	<?php } ?>
</div>

	</section>
	<!-- /Section -->

</section>


<?php get_footer(); ?>