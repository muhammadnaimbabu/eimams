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
	kv_header(); 
	if($post_slug == 'job-view' && !isset($_GET['job_id'])) { 
		wp_die( ' This is not a valid Page <a href='.site_url('posted-jobs').' > Click Here</a> to Posted Jobs ', 404 ); 
	}
	if(isset($_GET['job_id'])){
		$post_author_id = get_post_field( 'post_author', $_GET['job_id'] );
		//echo $post->post_author;
		if( $post_author_id != $current_user->ID) { ?>
		<style> 
		body { 	max-width: 100% !important; } 
		</style>
		<?php	get_template_part( '404' );
			wp_die();
		}
	}
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

<div class="job-list-left col-xs-10">
<p>Ref: <?php echo get_post_meta( get_the_ID(), 'employer_ref', true ); ?> </p>
<p> Job Title : <?php echo get_the_title(); ?> </p>
<p>Company/Organisation Name:<?php echo get_post_meta( get_the_ID(), 'company_name', true ); ?> </p>
<p> Location:
	 <?php
	$term_list = wp_get_post_terms($post->ID, 'zone', array("fields" => "names"));
	echo $term_list[0];
	?>
</p>
<?php $sal_amount=get_post_meta( get_the_ID(), 'sal_amount', true ); 
  if($sal_amount){?>

<p>Salary: &pound; 
	<?php echo $sal_amount; ?> 
	 <?php $term_list = wp_get_post_terms($post->ID, 'sal_prd', array("fields" => "names"));
	
	if(isset($term_list[0])){ ?> per <?php  echo $term_list[0];} ?> 
	
	<?php $term_list = wp_get_post_terms($post->ID, 'sal_optn', array("fields" => "names"));
	if(isset($term_list[0])){ ?> OR <?php  echo $term_list[0];} ?> </p>
	
<p> Posted on <?php $start_date=get_post_meta( get_the_ID(), 'ad_start_date', true ); echo $start_date; 
  $close_date=get_post_meta( get_the_ID(), 'ad_close_date', true ); if($close_date) { ?> , closes on <?php echo $close_date; } ?></p>
<?php } ?>
</div> 

<div class="job-list-right col-xs-2">
	<div class="logo"> 
	<?php 
	if ( has_post_thumbnail() ) {
	echo get_the_post_thumbnail( get_the_ID(), array( 200, 200) );
}
else {
	echo '';
} 
	?></div><br>
</div>

<div style="clear:both"></div>

 
<div class="job-details_view col-xs-12">
    
<table width="100%">
  <tbody> 
 <tr>  <th>Descripton</th>   <th>   Details </th> </tr>
  
 <?php $company_name_details =  get_post_meta( get_the_ID(), 'company_name', true ); 
  if(trim($company_name_details) != ''){
    echo '<tr> <td> Company Name : </td> <td> '.$company_name_details.'</td> </tr>';  
  }
  $company_description=get_post_meta( get_the_ID(), 'company_description', true );
  if(trim($company_description) != '' || trim($company_description) != null ){
    echo '<tr> <td>Company Description: </td> <td>'.$company_description.'</td> </tr>';
  } 
    $address1=get_post_meta( get_the_ID(), 'address1', true ); 
  if(trim($address1) != ''){ echo '<tr>  <td> Address 1: </td>   <td>'. $address1.'</td>  </tr>'; }
  $address2=get_post_meta( get_the_ID(), 'address2', true ); 
  if(trim($address2) != ''){ echo '<tr>  <td> Address 2: </td>   <td>'. $address2.'</td>  </tr>'; }
  $state_pro_reg=get_post_meta( get_the_ID(), 'state_pro_reg', true ); 
  if(trim($state_pro_reg) != ''){ echo '<tr>  <td> State/Province/Region: </td>   <td>'. $state_pro_reg.'</td>  </tr>'; }
  $city=get_post_meta( get_the_ID(), 'city', true ); 
  if(trim($city) != ''){ echo '<tr>  <td> City: </td>   <td>'. $city.'</td>  </tr>'; }
  $country_list = wp_get_post_terms($post->ID, 'zone', array("fields" => "names"));
  if($country_list[0] != ''){  
    echo '<tr> <td>Country: </td> <td> '.$country_list[0].'</td> </tr>';
  }
  
  $website=get_post_meta( get_the_ID(), 'website', true ); 
  if(trim($website) != ''){ echo '<tr>  <td> Website: </td>   <td>'. $website.'</td>  </tr>'; } 
    echo ' <tr>   <td colspan="2" > <h2 style="font-size:22px;margin:0">Job Description:</h2>';
    the_content(); 
    echo '</td></tr>';
      $ad_close_date=strtotime(get_post_meta( get_the_ID(), 'ad_close_date', true ));
      $ad_close_date=date('d-M-Y', $ad_close_date);
    if($ad_close_date){
    echo '<tr><td>Closing Date:</td> <td>'.$ad_close_date.'</td></tr>';
  } 
  $in_start_date=strtotime(get_post_meta( get_the_ID(), 'in_start_date', true )); $in_start_date = date('d-M-Y', $in_start_date);
  if($in_start_date){
    echo '<tr> <td>Interview/Start Date :</td> <td> '. $in_start_date.' </td> </tr>';
   } 
   echo '<tr>  <td>Job Position: </td><td>';
   $term_list = wp_get_post_terms($post->ID, 'job_category', array("fields" => "names"));
  echo $term_list[0].' </td></tr><tr>  <td>Gender: </td> <td>';
  echo get_post_meta( get_the_ID(), 'gender', true );
  echo '</td></tr> ';
  $no_of_vacancies =  get_post_meta($post->ID, 'no_of_vacancy', true); 
  if(trim($no_of_vacancies) != '' && $no_of_vacancies > 0 ){
  echo '<tr> <td> Number of Vacancies : </td> <td> '.$no_of_vacancies.' </td> </tr> ' ;   
  } ?>
  <tr>
    <td>Qualification: </td>
    <td><?php $term_list = wp_get_post_terms($post->ID, 'qualification', array("fields" => "names"));
  if($term_list[0] == 'Other')
    $echo_thing =  $term_list[0].' ('. get_post_meta( get_the_ID(), 'custom_qualification', true ).')'; 
  else
    $echo_thing =  $term_list[0];
  echo $echo_thing; ?></td>
  </tr>
   
  <tr>
    <td>Years of Exprerience: </td>
    <td><?php $term_list = wp_get_post_terms($post->ID, 'yr_of_exp', array("fields" => "names"));
  echo $term_list[0]; ?></td>
  </tr>
    <?php $experience_details =  get_post_meta($post->ID, 'experience_details', true); 
  if(trim($experience_details) != '' ){
    echo '<tr> <td> Experience Details : </td> <td> '.$experience_details.' </td> </tr> ' ;   
  } 
  $madhab_list = wp_get_post_terms($post->ID, 'madhab', array("fields" => "names"));
  if($madhab_list[0] != ''){  
    echo '<tr> <td>Madhab/School of Law: </td> <td> '.$madhab_list[0].'</td> </tr>';
  }else{
    $Shiamadhab_list = wp_get_post_terms($post->ID, 'Shiamadhab', array("fields" => "names"));
    if($Shiamadhab_list[0] != ''){  
      echo '<tr> <td>Madhab/School of Law - Shia: </td> <td>'.$Shiamadhab_list[0].'</td> </tr>';
    } 
  }
  $aqeeda_list = wp_get_post_terms($post->ID, 'aqeeda', array("fields" => "names"));
  if($aqeeda_list[0] != ''){  
    echo '<tr>  <td>Aqeeda/Belief: </td> <td>'. $aqeeda_list[0].'</td> </tr>';
  } else{   
    $Shiaaqeeda_list = wp_get_post_terms($post->ID, 'Shiaaqeeda', array("fields" => "names"));
    if($Shiaaqeeda_list[0] != ''){  
      echo '<tr> <td>Aqeeda/Belief - Shia: </td> <td>'. $Shiaaqeeda_list[0].'</td></tr>';
    } 
  }
  $sal_amount =  get_post_meta($post->ID, 'sal_amount', true);  
  $sal_period = wp_get_post_terms($post->ID, 'sal_prd', array("fields" => "names"));
  $sa_option = wp_get_post_terms($post->ID, 'sal_optn', array("fields" => "names"));
 if (($sal_amount != '' &&  $sal_period[0] != '') || $sa_option[0] !=  ''){ ?> 
 <tr>
    <td>Salary : </td>
    <td><?php if($sal_amount != '') { echo $sal_amount .' Per '. $sal_period[0]; } else { echo $sal_option[0]; } ?></td>
  </tr>
<?php } ?>  
  <tr>
    <td>Language: </td>
    <td><?php 
    $summa_lan = get_the_terms($post->ID, 'languages');   
    $length_summa_lan = count ($summa_lan);   
    if($summa_lan) {
      foreach($summa_lan as $summa1) {
        echo  $summa1->name;        
        if ($length_summa_lan > 1) { echo ', '; }       
        $length_summa_lan--;
      } 
    }
?></td>
  </tr>
  <tr><td>Eligible to work in: </td>
    <td><?php  $term_id =  get_post_meta( get_the_ID(), 'eligible_work_in', true );  $term = get_term( $term_id, 'zone' ); 
  echo $term->name; ?></td>
  </tr>  
   <?php $work_time=get_post_meta( get_the_ID(), 'work_time', true ); 
  if(trim($work_time)){
    echo '<tr> <td>Work Time: </td> <td>'.$work_time.'</td> </tr>';
    } 
   $hours_per_week=get_post_meta( get_the_ID(), 'hours_per_week', true ); 
  if(trim($hours_per_week)){
    echo '<tr> <td>Hours Per Week: </td> <td>'. $hours_per_week.'</td> </tr>';
    } 
    $manual_application_method=get_post_meta( get_the_ID(), 'manual_application_method', true );
  if($manual_application_method){
    echo '<tr>  <td>Manual Application Method: </td> <td>'.$manual_application_method.'</td></tr>';
    } 
  $pension_provision= get_post_meta( get_the_ID(), 'pension_provision', true );
  if($pension_provision){
    echo '<tr><td>Pension Provision: </td> <td>'. $pension_provision.'</td></tr>';
  } 
  
  $monitoring_equality= get_post_meta( get_the_ID(), 'monitoring_equality', true );
  if(trim($monitoring_equality) != ''){
    echo '<tr><td>Monitoring Equality: </td> <td>'. $monitoring_equality.'</td></tr>';
  } 
  
  $equality_statement= get_post_meta( get_the_ID(), 'equality_statement', true );
  if(trim($equality_statement) != ''){
    echo '<tr><td>Equality Statement: </td> <td>'. $equality_statement.'</td></tr>';
  } 
  
   $confidential=get_post_meta( get_the_ID(), 'confidential', true );
   if(trim($confidential) !=  '') {
    echo '<tr> <td>Confidential: </td> <td>'. $confidential.'</td> </tr>';
  } 

  $accomodation=get_post_meta( get_the_ID(), 'accomodation', true );
  if(trim($accomodation) != '' ){
    echo '<tr> <td>Accomodation: </td> <td>'.$accomodation.'</td> </tr>';
   } 
  $accomodation_details =  get_post_meta($post->ID, 'accomodation-details', true); 
  if($accomodation_details != '' ){
  echo '<tr> <td colspan="2"> <h3 style="margin: 0; " >Accomodation Details :</h3>  <br> '.$accomodation_details.' </td> </tr> ' ;    
  } 

 $dbs=get_post_meta( get_the_ID(), 'dbs', true );
  if($dbs == 'yes'){?>
  <tr>
    <td>Legal Check : </td>
    <td><?php echo get_post_meta( get_the_ID(), 'dbs_description', true ).'<br>';  
    $dbs_file_url=  wp_get_attachment_url( get_post_meta( get_the_ID(), 'dbs_file', true ));
    if( $dbs_file_url != null  || $dbs_file_url != '')
      echo '<a class="view_resume" data-fancybox-type="iframe" href="'. site_url( 'view-resume' ).'/?attach_id='.get_post_meta( get_the_ID(), 'dbs_file', true ).'"> View File </a>'; 
    else 
      echo 'No View File '; 
      ?></td>
  </tr>
  <?php } 

  $other_information=get_post_meta( get_the_ID(), 'other_information', true );
  if(trim($other_information) != ''){
    echo '<tr>  <td>Other Information: </td>  <td>'. $other_information.'</td> </tr> ';
  } ?>
    
</tbody></table>  


</div>

<!-- ############################################################### -->
<br>
<?php }	?>
</div>
         
               
                

	<style> 
	.width_30 { width: 30%; } 
	
	</style> 
</div>
	
<?php 
	kv_footer();
} else {
	wp_redirect( kv_login_url() ); exit; 
}
?>