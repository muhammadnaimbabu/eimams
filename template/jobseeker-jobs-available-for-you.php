<?php 
/**
 * Template Name: Jobs Available for you
 */ 
$theme_root = dirname(__FILE__);
require_once($theme_root."/../library/user-backend-main.php");
$kv_current_role=kv_get_current_user_role();
global $current_user, $wp_roles, $wpdb;
global $paged;
if(empty($paged)) $paged = 1;
wp_get_current_user();
$job_seeker_table = $wpdb->prefix.'jobseeker';
if(is_user_logged_in() && $kv_current_role == 'job_seeker') {
	kv_header(); 
	kv_top_nav();
	kv_leftside_nav();
	kv_section_start();	?> 	
	<div id="page-inner">
		<div class="row">
            <div class="col-md-12">
                <h2>Jobs Available </h2>   
                <h5>Welcome to eImams , Love to see you back. </h5>
            </div>
        </div>  		
		
<style> 
	.width_30 { width: 30%; } 
	.pagination {
		clear:both; 
		padding:20px 0;
		position:relative;
		font-size:11px;
		line-height:13px;
	}

	.pagination span, .pagination a {
		display:block;
		float:left;
		margin: 2px 2px 2px 0;
		padding:6px 9px 5px 9px;
		text-decoration:none;
		width:auto;
		color:#fff;
		background: #555;
	}

	.pagination a:hover{
		color:#fff;
		background: #3279BB;
	}

	.pagination .current{
		padding:6px 9px 5px 9px;
		background: #3279BB;
		color:#fff;
	}
	
.pagination span, .pagination a {
    display: block;
    float: left;
    margin: 2px 2px 2px 0;
    padding: 6px 9px 5px 9px;
    text-decoration: none;
    width: auto;
    color: #979FDF;
    background: #FFFFFF;
    border: 1px solid #E5E5E5;
}

.job-no {padding:0px;}
.job-no h1{ font-size:25px; 	color:#009688 ; 	text-align:center; 	font-family:Tahoma, Geneva, sans-serif;}
.job-details { text-transform:capitalize}
.job-list { position:relative;min-height:120px;float:right;padding:5px;background:#fff;margin-bottom:10px;box-shadow:0px 0px 5px 2px #ddd;}
.job-list p { margin:0}
.job-list-left {  position:relative;  float:left;	}
.job-list-right {  position:relative;  float:right;  margin-top:10px; width:98%; margin-right:15px; }
.job-list-right { background:#fff ; background:#F3F3F3; padding-bottom:10px; border-top:2px solid #fff; border-bottom:2px solid #fff ; transition:all 400ms }
.job-list-right:hover {  background:#f9fbf8 ;/* border-top:2px solid #b4d6a3; border-bottom:2px solid #b4d6a3 ;*/ }
.job-header-wrap { min-height:150px; padding:30px 10px;  }

span.job-title { margin:5px 20px 15px 10px; font-size:18px ; font-weight:bold; display:inline-block }
span.job-type-full-time { padding:6px 12px ;background:#f1630d; color:#fff; display:inline-block }
span.job-type-part-time { padding:6px 12px ;background:#186fc9; color:#fff; display:inline-block }
span.job-type-cover { padding:6px 12px ;background:#4bbad5; color:#fff; display:inline-block }
span.job-type-replacement { padding:6px 12px ;background:#86ca43; color:#fff; display:inline-block }
span.job-type-short-terms { padding:6px 12px ;background:#3f6501; color:#fff; display:inline-block }

span.company-name { margin:5px ; display:inline-block}
span.location { margin:5px; display:inline-block }
span.posting-date { margin:5px ; display:inline-block}

.total-job-count { font-size:22px ; font-weight:bold; color:#002d74; margin-bottom:20px; text-align:center}
.panel { background-color:#fff; }

.button-full-details { margin:10px 15px 20px 0 }
.button-applied-online { margin:10px 0 20px 15px }
.panel-default > .panel-heading {    color: #333;    background-color: #f5f5f5; 	background-color:#fff;    border-color: #ddd; }

.panel-body {    padding: 15px 25px;}

.table-striped > tbody > tr:nth-child(odd) > td { background-color:#b0ccd7; }
.table-striped > tbody > tr:nth-child(even) > td {    background-color:#d7e5dd; }
.table-striped > tbody > tr:nth-child(odd) > th  {  background-color:#3f7b98} 

.table-striped  th { color:#eee }					
.divider {    display: block;    margin: 5px 0;     border-top:none !important;}

.border_full_time:hover {  border-top:2px solid #f1630d; border-bottom:2px solid #f1630d;}
.border_part_time:hover {  border-top:2px solid #186fc9; border-bottom:2px solid #186fc9;}
.border_cover:hover {  border-top:2px solid #4bbad5; border-bottom:2px solid #4bbad5;}
.border_replacement:hover {  border-top:2px solid #86ca43; border-bottom:2px solid #86ca43;}
.border_short_terms:hover {  border-top:2px solid #3f6501; border-bottom:2px solid #3f6501;}


/*  button style  */

a.button:hover, button:hover, a.button.active-button, button.active-button {
    background: #63b2f5;
    color: #fff;
    border-top-color: #7cc5f8;
    border-bottom-color: #579dd9;
    text-decoration: none;
}
.button-applied-online {
    margin: 10px 0 20px 15px;
}
a.button, button {
    background: #e2eaf2;
    border: none;
    color: #274472;
    text-transform: uppercase;
    display: inline-block;
    padding: 6px 20px;
    font-size: 13px;
    border-radius: 3px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-top: 1px solid #f3f7fa;
    border-bottom: 1px solid #bfc8d7;
    transition: background 0.3s, color 0.2s, border 0.3s;
    -webkit-transition: background 0.3s, color 0.2s, border 0.3s;
    -moz-transition: background 0.3s, color 0.2s, border 0.3s;
}

</style>	

<?php

if('POST' == $_SERVER['REQUEST_METHOD']) {

	global $wpdb;
		$jobseeker_msg = trim($_POST['jobseeker_msg']);
		$job_id = trim($_POST['job_id']);
		$show_contact = trim($_POST['show_contact']);
		$notify_email = trim($_POST['notify_email']);
		
		$tbl_name = $wpdb->prefix."applied_jobs"; 
		//echo $job_id; 
		$wpdb->insert($tbl_name, array( 
			'job_id' => $job_id, 
			'job_seeker_id' => $current_user->ID, 
			'job_seeker_cover_msg' => $jobseeker_msg, 
			'status' => 0, 
			'job_status' => 0, 
		)); 
		employer_notification_new_user_applied($wpdb->insert_id);
		wp_safe_redirect(trim($_POST['current_url']));
}


  if(kv_check_jobseeker_sub_expire($current_user->ID) || $enable_subscription == 'no' ) {
	$jobseeker_details = kv_get_jobseeker_details($current_user->ID);
	$category = $jobseeker_details['category'];
	$qualification = $jobseeker_details['qualification'];
	$types = $jobseeker_details['type'];
	$usr_yr_of_exp = $jobseeker_details['yr_or_exp'];
	$usr_madhab = $jobseeker_details['madhab'];
	$usr_aqeeda = $jobseeker_details['aqeeda'];
	$usr_language = $jobseeker_details['language'];
	$usr_zone = $jobseeker_details['location'];
	$gender = $jobseeker_details['gender'];

	$pref_sal_prd = $jobseeker_details['pref_sal_prd'];
	$pref_sal_optn = $jobseeker_details['pref_sal_optn'];
		
		
	if(($gender != null)){			
		$meta_key_array =  array(           
				'key' => 'gender',
				'value' => $gender     
		); 
	}
	$args = array(
			'post_type' => 'job', 
			/*'meta_key' => (isset($gender)) ?  'gender'  : '' ,
			'meta_value' => (isset($gender)) ?  $gender  : '' ,			
			*/
			'tax_query' => array(		

		($category != -1) ? array(
					'taxonomy' => 'job_category',
					'field'    => 'term_id',
					'terms'    => $category
				): '' ,
	/*	($qualification != -1) ? array(
					'taxonomy' => 'qualification',
					'field'    => 'term_id',
					'terms'    => $qualification
				): '',
		($types != -1) ? array(
					'taxonomy' => 'types',
					'field'    => 'term_id',
					'terms'    => $types
				): '',
		/*($usr_yr_of_exp != -1) ? array(
					'taxonomy' => 'yr_of_exp',
					'field'    => 'term_id',
					'terms'    => $usr_yr_of_exp
				): '',*/
	/*	($usr_madhab != -1) ? array(
					'taxonomy' => 'madhab',
					'field'    => 'term_id',
					'terms'    => $usr_madhab
				): '',
		($usr_aqeeda != -1) ? array(
					'taxonomy' => 'aqeeda',
					'field'    => 'term_id',
					'terms'    => $usr_aqeeda
				): '',
		($usr_language != -1) ? array(
					'taxonomy' => 'languages',
					'field'    => 'term_id',
					'terms'    => $usr_language
				): '',
		($usr_zone != -1) ? array(
					'taxonomy' => 'zone',
					'field'    => 'term_id',
					'terms'    => $usr_zone
				): '',
	/*	($pref_sal_prd != -1) ? array(
					'taxonomy' => 'sal_prd',
					'field'    => 'term_id',
					'terms'    => $pref_sal_prd
				): '' ,
		($pref_sal_optn != -1) ? array(
					'taxonomy' => 'sal_optn',
					'field'    => 'term_id',
					'terms'    => $pref_sal_optn
				): '',	*/
			),
			'post_status' =>array('publish'),
			'paged'=> $paged
		);
/*
		$to_amount=$jobseeker_details['pref_sal_end'];
		$from_amount=$jobseeker_details['pref_sal_bgn'];
	if($from_amount != '' &&  $to_amount != '') {		
			
			$args['meta_query'] = array(
					array(
					'key' 	  => 'sal_amount',
					'value'   => array( $from_amount, $to_amount),
					'type'    => 'numeric',
					'compare' => 'BETWEEN',
				),
			);
	}else if(isset($from_amount)){					
			
			$args['meta_query'] = array(
					array(
					'key' 	  => 'sal_amount',
					'value'   =>  $from_amount, 
					'type'    => 'numeric',
					'compare' => '>=',
				),
			);
		
	}else if(isset($to_amount)){				
			
			$args['meta_query'] = array(
					array(
					'key' 	  => 'sal_amount',
					'value'   =>  $to_amount, 
					'type'    => 'numeric',
					'compare' => '<=',
				),
			);		
	}*/
if(kv_get_user_status() == 1 || kv_get_user_status() == 2) {
		echo '<div> Your Profile is deactivated!.</div>';							
} else { 
//print_r($args);
$query = new WP_Query( $args );
echo '<div class="row" >	' ; 
	while ( $query->have_posts() ) {
		$query->the_post(); $term_list = wp_get_post_terms($post->ID, 'types', array("fields" => "names")); 
   
  	if ($term_list[0]== 'Full Time') { $colorclass = 'border_full_time'; }
 	if ($term_list[0]== 'Part Time') { $colorclass='border_part_time';	} 
 	if ($term_list[0]== 'Cover') { $colorclass='border_cover';	} 
 	if ($term_list[0]== 'Replacement') { $colorclass='border_replacement';	} 
 	if ($term_list[0]== 'Short Terms') { $colorclass='border_short_terms';	} 	   ?>
 


<div class="job-list-right <?php echo $colorclass; ?>  col-xs-12">  


<div class="job-header-wrap">

<div class="job-header-wrap-inner">

	<div class="job-listing-logo col-sm-2"> 
                <?php 	if ( has_post_thumbnail() ) {
                    echo get_the_post_thumbnail( get_the_ID(), array( 200, 200) );
                }else {	echo "<img src='".get_template_directory_uri()."/images/default-dp-eimams.png' style='width: 100%;'/>";} 	?>
    </div>
    
    <div class="job-listing-header-right col-sm-10">
    
    <div>
        <span class="job-title">  <?php echo get_the_title(); ?>  </span>   
       
           <?php $term_list = wp_get_post_terms($post->ID, 'types', array("fields" => "names")); 
		   
		           if ($term_list[0]== 'Full Time') {   echo ' <span class="job-type-full-time">'.$term_list[0].'</span>';  }
  		           if ($term_list[0]== 'Part Time') {   echo ' <span class="job-type-part-time">'.$term_list[0].'</span>';  }
     			   if ($term_list[0]== 'Cover') {   echo ' <span class="job-type-cover">'.$term_list[0].'</span>';  }
				   if ($term_list[0]== 'Replacement') {   echo ' <span class="job-type-replacement">'.$term_list[0].'</span>';  }		
 				   if ($term_list[0]== 'Short Terms') {   echo ' <span class="job-type-short-terms">'.$term_list[0].'</span>';  }				      
		   ?>
    </div>
    
    <div>
    	<span class="company-name"><i class="fa fa-briefcase"></i> &nbsp; <?php echo get_post_meta( get_the_ID(), 'company_name', true ); ?> </span>
    	<span class="location"> <i class="fa fa-map-marker"></i> &nbsp; <?php	$term_list = wp_get_post_terms($post->ID, 'zone', array("fields" => "names"));  echo $term_list[0];  ?>   </span>
    	<span class="posting-date"> <i class="fa fa-calendar"></i> &nbsp; Posted on 
					<?php 
						$start_date=strtotime(get_post_meta( get_the_ID(), 'ad_start_date', true )); echo $start_date =date('d-M-Y', $start_date); 
					  	$close_date=strtotime(get_post_meta( get_the_ID(), 'ad_close_date', true )); if($close_date) { ?> , closes on <?php echo $close_date = date('d-M-Y', $close_date); } 
					  ?>
         </span>
    
    </div>
    
    </div>
        
</div>

</div>


<div style="float:left">
<!--   <button class="button-applied-online" >Applied Online</button> -->
     
                

                <?php if(kv_get_user_status()== 0) {
                
                if(has_jobseeker_applied_this_job(get_the_ID(), $current_user->ID)){ 
							$apply_mtd = get_post_meta(get_the_ID(), 'how_to_apply', true);
						if($apply_mtd == 'apply_online' ){
							 echo '<div class="apply-job-button"><button class="button-applied-online" >Apply Online</button></div>';
						}else if($apply_mtd == 'manual_mtd') {
							 echo '<div class="apply_manual"><button class="button-applied-online"> Apply Manually</button></div> ';						
						}
					?>
                  
                <?php } else echo ' You are already Applied for it'; 
                
                } else echo '<ul class="errors"> <li> Please Activate your profile to apply for this job!</li> </ul>';  ?>
   
         	<div id="apply-job-button-content" style="display:none;margin-left:15px;">
			 <?php if(kv_check_jobseeker_sub_expire($current_user->ID) || $enable_subscription == 'no' ) { ?> 
             

				<form method="post" action="<?php echo site_url('jobs-listing'); ?>" >
					<input type="hidden" name="current_url" value="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI']; ?>" > 
                     <textarea  style="min-width:300px"class="form-control" placeholder="Write your cover message to Employer" name="jobseeker_msg" ></textarea>
					 <input type="hidden" name="job_id"  value="<?php echo get_the_ID();?>" >
                     
                    <button type="submit" class="btn btn-primary">Submit</button>
				</form>
 
              
				 <?php } else { ?>
                <div style="color:red;"> 
                    <p> Your subscription has expired .So Please <a class="button" href="<?php echo site_url('subscription'); ?>">Add  your subscription. </a></p>
                </div>
      
			<?php }  ?>
            </div>
                      
            <div id="apply_manual_content" style="display:none;margin-left:15px;">
				 <?php if(kv_check_jobseeker_sub_expire($current_user->ID) || $enable_subscription == 'no' ) { 				 
                    echo "<div style='background:#eee;border:1px solid #ddd; padding:5px;margin-bottom:10px;border-radius:4px;'>".nl2br(get_post_meta(get_the_ID(), 'manual_apply_details', true)); ?>
					<?php 	$ids= maybe_unserialize(get_post_meta(get_the_ID(), 'app_form', true));
				if(!empty($ids)){
					foreach($ids as $id){
						echo '<br /><a  style="margin-top:10px;display:inline-block;"  href="'.wp_get_attachment_url( $id ).'" > <input type="button" class="btn btn-primary" name="download_application" value="Download"> </a>'; 
					}
				}?>	</div>            
				 <?php } else { ?>
                    <div style="color:red;"> 
                        <p> Your subscription has expired .So Please <a class="button" href="<?php echo site_url('subscription'); ?>">Add  your subscription. </a></p>
                    </div>
			<?php }  ?> 

        </div>
           

     
</div>
  

<div style="float:right">
     <button class="read-more button-full-details" >Full details of this job &gt;&gt;</button> 
     <button class="hide-more button-full-details" >Hide details of this job &gt;&gt;</button>   
</div>
  


<div class="job-details col-xs-12">
 <?php if(is_user_logged_in()){
   if(kv_check_jobseeker_sub_expire($current_user->ID) || $enable_subscription == 'no' ) { ?>   
   
 <div class="table-responsive"> 
	
<table class="table table-striped table-bordered">
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
	  echo '<tr><td>Monnitoring Equality: </td> <td>'. $monitoring_equality.'</td></tr>';
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
	echo '<tr> <td colspan="2"> <h3 style="margin: 0; " >Accomodation Details :</h3>'.$accomodation_details.' </td> </tr> ' ;	  
  } 

 $dbs=get_post_meta( get_the_ID(), 'dbs', true );
    	echo '<tr> <td> Legal Check </td> <td>'.$dbs.'</td> </tr>';
	if($dbs == 'yes'){ ?>
  <tr>
    <td colspan="2" > 
    <?php echo get_post_meta( get_the_ID(), 'dbs_description', true ).'<br>';  
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


<?php }else { ?>
	<div style="color:red;"> 
	<p> Your subscription has expired .So Please <a class="btn btn-info" href="<?php echo site_url('subscription'); ?>">Add  your subscription </a></p>
	</div>
	 
	<?php }
	} else{?>
	<div style="color:red;"> 
	<p><a class="btn btn-info" href="<?php echo site_url('jobseeker-sign-up'); ?>">Create your profile now </a></p>
	</div>
	 
	<?php
	}?>  
</div>	


</div>  <!--  job-list-right -->    
        

<div style="clear:both"></div>


<!-- ########################  Job Details ##########################  -->

<div class="row" style="margin-bottom:10px;">  </div><?php  } 
	}?>  
  </div> <!-- end of panel group -->
	<div class="row" >
<div class="job-list-right col-xs-2" style="width:100%; display:none">
<?php if ( $query->max_num_pages > 1 ) { echo '	<div class="numeric-pagination">'. candidat_pagenavi().'</div>'; } ?>
</div>
</div>


<script type="text/javascript">

	jQuery(document).ready(function(){
		jQuery('#apply-job-button-content').hide();
		
		
		jQuery('.apply-job-button').click(function(e){
			e.preventDefault();
			main_div = $(this).parent(). parent().parent().children('#apply-job-button-content').toggle();	
		});
		
	});

</script>


					
</div>					

<script type="text/javascript">

$(function(){
	$(".job-details").hide(); 
	$(".hide-more").hide(); 
	$(".read-more").click(function(){ 
		$(".job-details").hide(); 
		$(".hide-more").hide(); 
		$(".read-more").show(); 
		$(this).parent().next(".job-details").slideDown(); 			
		$(this).next(".hide-more").show(); 
		$(this).hide();		
	});	

	$(".hide-more").click(function(){ 
		$(this).parent().next(".job-details").hide(); 			
		$(this).prev(".read-more").show(); 
		$(this).hide();		
	});	
	
	
	
		// apply job button
		jQuery('#apply-job-button-content').hide();
		jQuery('#apply_manual_content').hide();
		
		jQuery('.apply-job-button').click(function(e){
			e.preventDefault();
			$(this).next('#apply-job-button-content').toggle();	
		});
		jQuery('.apply_manual').click(function(e){
			e.preventDefault();
			$(this).next().next('#apply_manual_content').toggle();	
		});

	
	
});
 
</script>

    
 <?php 
}
	kv_footer();
} else {
	wp_redirect( kv_login_url() ); exit; 
}
?>	