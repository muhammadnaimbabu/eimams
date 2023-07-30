<?php 

/**
Template Name: Pricing List
*/

get_header();
 $sidebar_class = 'col-lg-12 col-md-12 col-sm-12';
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
        <h2> Pricing List for Employers </h2>
        
        
        
        
        
        
        
        
        
        
        
        
        
                <h2> Pricing List for Jobseekers </h2>
        

			
                
		</section>
		<!-- /Section -->
		
	</section>
    
 <style type="text/css">
 
 ul li { list-style:circle}
 
 </style>
    
<?php
get_footer(); 

?>