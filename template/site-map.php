<?php 

/**
Template Name: Site Map
*/

get_header();
 $sidebar_class = 'col-lg-12 col-md-12 col-sm-12';

 //kv_owner_new_subscription(110);
 //echo eimams_has_email_subscriber('admin@kvcodes.com') ; 
// kv_schedule_emails_from_queue();
//echo $employer_signup_url = get_site_url().'/employer-sign-up/';
?>
      
   
<section id="content">	
			
			<!-- Page Heading -->
			<section class="section page-heading animate-onscroll">
				
				<div class="row">
					<div class="col-lg-9 col-md-9 col-sm-9">
						
						<h1><?php // echo esc_html(get_the_title()); ?></h1>		
						
						<?php //if(get_option('sense_show_breadcrumb') == 'show') { ?>
						<?php candidat_the_breadcrumbs(); ?>
						<?php //} ?>
	
					</div>

				</div>
				
			</section>
			<!-- Page Heading -->
	
		<!-- Section -->
		<section class="section full-width-bg gray-bg">
        
        
        <div align="center">    <img src="<?php echo get_template_directory_uri();?>/images/sitemap.png" alt="Site Map"> </div>
        	
       
                
					<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>	
				
                
                
                
		</section>
		<!-- /Section -->
		
	</section>
<?php
get_footer(); 

?>