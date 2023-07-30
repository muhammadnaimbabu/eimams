<?php 
/**
 * Template Name: Job view
 */ 
$theme_root = dirname(__FILE__);
require_once($theme_root."/../library/user-backend-main.php");
$kv_current_role=kv_get_current_user_role();
if(is_user_logged_in() && $kv_current_role == 'employer') {
	global $post,$current_user,$wp_roles, $wpdb;
    wp_get_current_user();
	$job_details = array();
	$job_details['user_email'] = $current_user->user_email; 
	$job_details['user_displayname'] = $current_user->display_name;  
    $post_slug=$post->post_name; 
	 
	if($post_slug == 'job-view' && !isset($_GET['job_id'])) { 
		wp_die( ' This is not a valid Page <a href='.site_url('posted-jobs').' > Click Here</a> to Posted Jobs ', 404 ); 
	}
	if(isset($_GET['job_id'])){
		$post_author_id = get_post_field( 'post_author', $_GET['job_id'] );
		//echo $post->post_author;
		if( $post_author_id != $current_user->ID) {  ?>
		<style> 
		body { 	max-width: 100% !important; } 
		</style>
		<?php	get_template_part( '404' );
			wp_die();
		}
	}
	kv_header();
	kv_top_nav();
	kv_leftside_nav();
	kv_section_start();	?>
	
	<div id="page-inner">
            
                <div class="row">
                    <div class="col-md-12">
                     <h2>View Job </h2>   
                        <h5>Welcome eImams , Love to see you back. </h5>
                    </div>
                </div> 
<div class="row" > 


</div> 
<?php 
if(isset($_GET['job_id'])){
	$view_id = $_GET['job_id'];
}

if($view_id){
$post=get_post($view_id);
?>
                
<div class="job-list col-xs-12">



<div class="job-list-left col-sm-10 col-xs-12">

<div style="margin:50px 0 10px 0"><button class="btn-lg btn-info">Ref: <?php echo get_post_meta( get_the_ID(), 'employer_ref', true ); ?> </button> </div>

<div style="margin:20px 0">
<div class="job-list-item"><label> Job Title : </label> <?php echo get_the_title(); ?> </div>
<div class="job-list-item"><label> Company/Organisation Name: </label><?php echo get_post_meta( get_the_ID(), 'company_name', true ); ?> </div>

<div class="job-list-item"><label> Description: </label><?php echo $post->post_content; ?> </div>

<div class="job-list-item"><label>Location: </label>
	 <?php
	$term_list = wp_get_post_terms($post->ID, 'zone', array("fields" => "names"));
	echo $term_list[0];
	?>
</div>

<?php $sal_amount=get_post_meta( get_the_ID(), 'sal_amount', true ); 
  if($sal_amount){?>

    <div class="job-list-item"> 
        <label> Salary  : </label> &pound;
        <?php echo $sal_amount; ?> 
         <?php $term_list = wp_get_post_terms($post->ID, 'sal_prd', array("fields" => "names"));
        
        if(isset($term_list[0])){ ?> per <?php  echo $term_list[0];} ?> 
        
        <?php $term_list = wp_get_post_terms($post->ID, 'sal_optn', array("fields" => "names"));
        if(isset($term_list[0])){ ?> OR <?php  echo $term_list[0];} ?> 
    </div>
  <?php } ?>
  
<div class="job-list-item"> 
	<label>Posted On: </label> <?php $start_date=strtotime(get_post_meta( get_the_ID(), 'ad_start_date', true )); echo $start_date =date('d-M-Y', $start_date); ?>
</div>

<div class="job-list-item"> 
  <?php 
  	$close_date=strtotime(get_post_meta( get_the_ID(), 'ad_close_date', true )); $close_date = date('d-M-Y', $close_date);
    if($close_date) { ?><label> Closes On: </label> <?php echo $close_date; } 
   ?>
</div>

  <?php  $job_duties=get_post_meta( get_the_ID(), 'job_duties', true );
	  if($job_duties){?>  
		<div class="job-list-item">   
			<label>Job Details:</label> <?php echo $job_duties; ?> 
		 </div>   
  
  <?php }

   $company_description=get_post_meta( get_the_ID(), 'company_description', true );
    if($company_description){?>
    <div class="job-list-item"> 
    <label>Company Description: </label> <?php echo $company_description; ?>
    </div>
   <?php }

	$in_start_date=strtotime(get_post_meta( get_the_ID(), 'in_start_date', true )); $in_start_date=date('d-M-Y', $in_start_date);
	if($in_start_date){?>
    <div class="job-list-item"> 
    <label> Interview Start Date:  </label><?php echo $in_start_date; ?>
    </div>
  <?php } ?>


<div class="job-list-item"> 
<label> Job Position:   </label> <?php $term_list = wp_get_post_terms($post->ID, 'job_category', array("fields" => "names"));
			if($term_list[0]){	echo $term_list[0];
			}else{$term_list = wp_get_post_terms($selected_id, 'gen_job_category', array("fields" => "ids"));	
				echo $term_list[0];
			} ?>
</div>


  
<div class="job-list-item"> 
<label>Gender:  </label> <?php echo get_post_meta( get_the_ID(), 'gender', true ); ?>
</div>



<div class="job-list-item"> 
<label>Qualification:  </label> <?php $term_list = wp_get_post_terms($post->ID, 'qualification', array("fields" => "names"));
	echo $term_list[0]; ?>
</div>


<div class="job-list-item"> 
<label>Type:  </label> <?php $term_list = wp_get_post_terms($post->ID, 'types', array("fields" => "names"));
	echo $term_list[0]; ?>
</div>


<div class="job-list-item"> 
<label> Years of Exprerience:  </label> <?php $term_list = wp_get_post_terms($post->ID, 'yr_of_exp', array("fields" => "names"));
	echo $term_list[0]; ?>
</div>


<div class="job-list-item"> 
<label> Madhab/School of Law: </label> <?php $term_list = wp_get_post_terms($post->ID, 'madhab', array("fields" => "names"));
	echo $term_list[0]; ?>
</div>


<div class="job-list-item"> 
<label>Aqeeda/Belief:  </label> <?php $term_list = wp_get_post_terms($post->ID, 'aqeeda', array("fields" => "names"));
	echo $term_list[0]; ?>
</div>



<div class="job-list-item"> 
<label>Language:  </label> <?php 
$summa_lan = get_the_terms($post->ID, 'languages');
if($summa_lan) {
	$length_summa_lan = count ($summa_lan);
	foreach($summa_lan as $summa1) {
		echo  $summa1->name;	
		if ($length_summa_lan > 1) { echo ', '; }	
		$length_summa_lan--;	
	}
}
?>
</div>


   <?php $work_time=get_post_meta( get_the_ID(), 'work_time', true ); 
    if($work_time){?>
        <div class="job-list-item"> 
        <label> Work Time:  </label><?php echo $work_time; ?>
        </div>
    <?php } 

  $hours_per_week=get_post_meta( get_the_ID(), 'hours_per_week', true ); 
  if($hours_per_week){?> 
    
        <div class="job-list-item"> 
        <label>Hours Per Week:  </label> <?php echo $hours_per_week;  ?>
        </div>
   <?php }
   
 
 $min_age=get_post_meta( get_the_ID(), 'minimum_age', true );
		  if($min_age){?>
            <div class="job-list-item"> 
            	<label> Minimum Age: </label> <?php echo $min_age; ?>
            </div>
  <?php } ?>

   <?php $manual_application_method=get_post_meta( get_the_ID(), 'manual_application_method', true );
  	if($manual_application_method){?>
        <div class="job-list-item"> 
	        <label> Manual Application Method: </label> <?php echo $manual_application_method; ?>
        </div>
   <?php } ?>
   
   
   
   
  <?php $pension_provision= get_post_meta( get_the_ID(), 'pension_provision', true );
  if($pension_provision){?>
        <div class="job-list-item"> 
	        <label> Pension Provision:  </label> <?php echo $pension_provision; ?>
        </div>
   <?php } ?>



    <?php $confidential=get_post_meta( get_the_ID(), 'confidential', true );
	if($confidential){?>
        <div class="job-list-item"> 
	        <label>Confidential:   </label> <?php echo $confidential; ?>
        </div>
  <?php } ?>


  <?php $dbs=get_post_meta( get_the_ID(), 'dbs', true );
  if($dbs == 'yes'){?>
  <tr>
    <td>DBS: </td>
    <td><?php echo get_post_meta( get_the_ID(), 'dbs_description', true ).'<br>';  
		$dbs_file_url=  wp_get_attachment_url( get_post_meta( get_the_ID(), 'dbs_file', true ));
		if( $dbs_file_url != null  || $dbs_file_url != '')
			echo '<a class="view_resume" data-fancybox-type="iframe" href="'. site_url( 'view-resume' ).'/?attach_id='.get_post_meta( get_the_ID(), 'dbs_file', true ).'"> View File </a>'; 
		else 
			echo 'No View File '; 
			?></td>
  </tr>
  <?php } 
  $accomodation=get_post_meta( get_the_ID(), 'accomodation', true );
  if($accomodation){?>
    <div class="job-list-item"> 
    <label>Accomodation: </label>     <?php echo $accomodation;  ?>
    </div>
  <?php } ?>


  <?php $other_information=get_post_meta( get_the_ID(), 'other_information', true );
  if($other_information){?>
    <div class="job-list-item"> 
	    <label> Other Information:  </label> <?php echo $other_information; ?>
    </div>
  <?php } ?>

   <?php $how_to_apply=get_post_meta( get_the_ID(), 'how_to_apply', true ); 
   if($how_to_apply){?>
        <div class="job-list-item"> 
        <label> How to apply: </label> <?php if($how_to_apply == 'apply_online'){ echo 'Apply Online';} else echo 'Manual method';  ?>
        </div>
   <?php } ?>


   <?php $website=get_post_meta( get_the_ID(), 'website', true ); 
  if($website){?>
  <div class="job-list-item"> 
    <label>Website: </label> <?php echo $website;  ?>
	</div>
   <?php } ?>


</div> </div>



<div style="clear:both"></div>


<!-- ############################################################### -->
<br>
<?php }	?>
</div>
         
               
                

	<style> 
	.width_30 { width: 30%; } 
	.job-list-item { 
		margin:0; 
		padding:8px 10px 7px 10px;
		border-top:1px solid #ccc;
		border-right:1px solid #ccc;
		border-left:4px solid #00a9e0;
	}
	
		.job-list-item:nth-child(odd) { background:#fafafa;  }
	
		.job-list-item:last-child { 
		margin:0; 
		background:#fafafa;
		border-top:1px solid #ccc;
		border-right:1px solid #ccc;
		border-left:4px solid #00a9e0;
		border-bottom:1px solid #ccc;
	}
	.job-list-item:nth-child(even) { background:#fff}


.logo img { max-width:100% !important; height:auto !important;  }

.job-list-right {
  position:relative;
  margin-top:10px;
}

@media screen and (max-width:1199px) {
	
	label {
    	display: inline-block;
    	margin-bottom: 5px;
    	font-weight: bold;
		width:200px;
}

}	

@media screen and (min-width:1200px) {
	
	label {
    	display: inline-block;
    	margin-bottom: 5px;
    	font-weight: bold;
		width:450px;
}

}
	
	</style> 
</div>
	
<?php 
	kv_footer();
} else {
	wp_redirect( kv_login_url() ); exit; 
}
?>