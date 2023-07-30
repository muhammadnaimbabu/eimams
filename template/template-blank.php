<?php 
/**
 * Template Name:  Blank 
 */ 
$theme_root = dirname(__FILE__);
require_once($theme_root."/../library/user-backend-main.php");
if(is_user_logged_in()) {
	kv_header(); 
	kv_top_nav();
	kv_leftside_nav();
	kv_section_start(); 
	$kv_current_role=kv_get_current_user_role();
	if( $kv_current_role == 'employer') {?>


<div id="page-inner">
            
                <div class="row">
                    <div class="col-md-12">
                     <h2>Template Blank</h2>   
                        <h5>Welcome to eImams , Love to see you back. </h5>
                    </div>
                </div> 
 

</div>
	
		
	
<?php }
	kv_footer();
} else {
	wp_redirect( kv_login_url() ); exit; 
}
?>