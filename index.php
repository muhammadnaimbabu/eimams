 <?php get_header('search'); 

if ((is_archive()) && is_post_type_archive( 'job')) {  
   get_template_part('archive-job');      
} 

 get_footer(); ?>