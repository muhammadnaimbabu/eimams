<?php 
/**
 * Template Name: View resume
 */ 
 global $wpdb; 
 
 $tabl_name = $wpdb->prefix.'applied_jobs';
  $min_hegiht= '';
 if(!isset($_GET['backend'])){  ?>
 <script src="<?php echo get_template_directory_uri();?>/js/jquery-1.11.0.min.js"></script>
 <?php }else{
		 $min_hegiht= 'min-height="500px"';
	}?>
 
 <link href="<?php echo get_template_directory_uri();?>/assets/css/bootstrap.css" rel="stylesheet" />

 <!-- FONTAWESOME STYLES-->
<link href="<?php echo get_template_directory_uri();?>/assets/css/font-awesome.css" rel="stylesheet" />
        		
<script src="<?php echo get_template_directory_uri();?>/assets/js/bootstrap.min.js"></script>
	<script>
	//$(document).ready(function() {
	//var iframeContent = $('#cv_full').contents().find('iframe').contents().find('div[aria-label=Pop-out]');
	//	console.log(iframeContent);
		//iframeContent.hide();
	//});
	</script>
    
    <style type="text/css">
	  
	#popup-tabmenu	a {		color: #666;	text-decoration: none;		}
	  
   	#popup-tabmenu	.nav-tabs {		border-bottom: 0px solid #ddd;		}
	
	#popup-tabmenu	.nav {	padding-left: 0;	margin-bottom: 0;	margin-left:-10px; margin-left:0;	list-style: none;}
				
	#popup-tabmenu	.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
			color: #666;cursor: pointer;/*	background-color: #fff;	background-color:#AF4040;!important;*/
			border: 1px solid #ddd;/*	padding:10px 55px;	*/ border-bottom-color: transparent;	border-radius:8px 8px 0px 0px;	padding-bottom:15px;background:#eee none;}

	#popup-tabmenu	.nav > li > a {	position: relative;	display: block;	}
		
	#popup-tabmenu	.nav-tabs > li > a {	color:#666;
		/*	margin-right: 2px;*/
			line-height: 1.42857143;
			border: 1px solid transparent;
 
			border-radius:8px 8px 8px 8px;
			/*border-radius: 6px 6px 0 0;*/
			
			moz-box-shadow: inset 0px 1px 0px 0px #ffffff;
			-webkit-box-shadow: inset 0px 1px 0px 0px #ffffff;
			box-shadow: inset 0px 1px 0px 0px #ffffff;
			
			background: -webkit-gradient( linear, left top, left bottom, color-stop(0.05, #fdfdfd), color-stop(1, #e3e3e5) );
			background: -moz-linear-gradient( center top, #fdfdfd 5%, #e3e3e5 100% );
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fdfdfd', endColorstr='#e3e3e5');
			background-color: #ededed;
			padding:10px 45px;	
		}
				
	#popup-tabmenu		.nav-tabs > li > a:hover {	background:#eee none;	color:#666;	}
	#popup-tabmenu	.nav-tabs > li.active .tab-content { margin-top:-20px;}
		
/* tab content  */
		
	#popup-tabmenu .tab-content  {	
			background-color:#eee;	
			border: 1px solid #ddd;		
			border-radius:6px;	
			border-top-left-radius:0px;
			margin-top:-15px; 
			min-height:600px;
		}
	 
	#popup-tabmenu .tab-content > .tab-pane {margin: 10px 10px 10px 10px;  padding-bottom: 20px;   padding-top: 20px;}
	#popup-tabmenu .tab-content h2 {margin: 0px; 		margin-left: 25px;	margin-bottom: 10px;color: #777;margin-left:25px;  }
	#popup-tabmenu .fa { margin-right:5px}
		
	#popup-tabmenu .tab-content li { 
			margin:10px 0px ; 
			
			-moz-box-shadow: inset 0px 1px 0px 0px #ffffff;
			-webkit-box-shadow: inset 0px 1px 0px 0px #ffffff;
			box-shadow: inset 0px 1px 0px 0px #ffffff;
			
			background: -webkit-gradient( linear, left top, left bottom, color-stop(0.05, #fdfdfd), color-stop(1, #e3e3e5) );
			background: -moz-linear-gradient( center top, #fdfdfd 5%, #e3e3e5 100% );
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fdfdfd', endColorstr='#e3e3e5');
			background-color: #ededed;
		
			-moz-border-radius: 6px;
			-webkit-border-radius: 6px;
			border-radius: 6px;
			border: 1px solid #ddddde;
		
			padding: 15px 10px;
			margin: 0 30px 5px 0;
			color: #666;
			font-weight: bold;
			list-style:none;
				
	}	
		/*		
		.tab-content > .active {
				display: block;
			}
			.tab-content > .tab-pane {
				display: none;
			}*/	
	</style>   
    
<?php 
		//} 
 if(isset($_GET['job_seeker_id'])){
	$job_seeker_id = $_GET['job_seeker_id'];
	$user_info = get_userdata($job_seeker_id);
	$jobseeker_details = kv_get_jobseeker_details($job_seeker_id);
	$url = wp_get_attachment_url( eimams_has_current_user_cv($job_seeker_id) ); 
	if(isset($jobseeker_details['dbs_file']) && $jobseeker_details['dbs_file'] > 0 )
		$dsb_url = wp_get_attachment_url( $jobseeker_details['dbs_file']);
	?>
 
 	 <div id="popup-tabmenu">		
		<!-- Nav tabs -->
	<?php
		// Employer Profile view page 
	if((isset($_GET['apply_id']) && $_GET['apply_id'] >0) || isset($_GET['backend'])) { 
		
		if(isset($_GET['apply_id'])){
			$apply_id = $_GET['apply_id'];
	
			$wpdb->update( $tabl_name, array('status' => 1), array('id' => $apply_id ));
			$msg_jobseeker=$wpdb->get_var("SELECT job_seeker_cover_msg FROM ".$tabl_name." WHERE id ={$apply_id}"); 
		}?>
		<ul class="nav nav-tabs" role="tablist">
		  <li class="active">  <a href="#home" role="tab" data-toggle="tab">	 <i class="fa fa-home"></i> Profile	  </a>  </li>
		  <li><a href="#cv_full" role="tab" data-toggle="tab">  <i class="fa fa-user"></i> CV	  </a>	  </li>		
		<?php  if(isset($dsb_url) && $dsb_url != '' ) 
			echo '<li><a  href="#dbs_file" role="tab" data-toggle="tab" > Legal Check </a> </li>'; 
		
		$ids= maybe_unserialize(get_user_meta($job_seeker_id, 'certificates_form', true));
		if(!empty($ids)){
			$vj= 1;
			foreach($ids as $id){
				echo '<li><a  href="#'.$id.'" role="tab" data-toggle="tab" > Qualification '.$vj++.' </a> </li>'; 
			}
		} 
		 ?>		  
		</ul>
		
		<!-- Tab panes -->
		<div class="tab-content">
		  <div class="tab-pane fade active in" id="home">
			  <h2><?php echo $user_info->display_name;?> </h2>
				<ul>
					<?php 
					if(isset($jobseeker_details['address1'])) echo  '<li> Address 1: '.$jobseeker_details['address1'].' </li>'; 
					if(isset($jobseeker_details['address2']) && $jobseeker_details['address2'] != '') echo  '<li> Address 2: '.$jobseeker_details['address2'].' </li>'; 
					if(get_user_meta($user_info->ID, 'city', true ) ) echo  '<li> City: '.get_user_meta($user_info->ID, 'city' , true).' </li>'; 
					if(get_user_meta($user_info->ID, 'state_pro_reg', true ) ) echo  '<li> State/Province/region: '.get_user_meta($user_info->ID, 'state_pro_reg' , true).' </li>'; 
					if(isset($jobseeker_details['post_code']) && $jobseeker_details['post_code']  != '') echo  '<li> Zip/Post Code: '.$jobseeker_details['post_code'].' </li>'; 
					if(isset($jobseeker_details['location']) && $jobseeker_details['location']  > 0) { $cate_ = get_term_by( 'id', $jobseeker_details['location'], 'zone');   echo  '<li> Country: '.$cate_->name.' </li>';  }  ?>
					<li> E-mail: <?php echo $user_info->user_email; ?> </li>
					 <?php if(isset($jobseeker_details['phone'])) echo  '<li> Phone: '.$jobseeker_details['phone'].' </li>'; 					
					
					if(isset($jobseeker_details['category']) && $jobseeker_details['category']  > 0) { $cate_ = get_term_by( 'id', $jobseeker_details['category'], 'job_category');  if($cate_){  echo  '<li> Job Classifications: '.$cate_->name.' </li>'; } else{ $cate_ = get_term_by( 'id', $jobseeker_details['category'], 'gen_job_category'); echo  '<li> Job Classifications: '.$cate_->name.' </li>'; }  } 
					if(isset($jobseeker_details['gender']) && $jobseeker_details['gender']  != '') echo  '<li> Gender : '.$jobseeker_details['gender'].' </li>'; 
					if(isset($jobseeker_details['qualification']) && $jobseeker_details['qualification']  > 0) {   $cate_ = get_term_by( 'id', $jobseeker_details['qualification'], 'qualification');  echo  '<li> Qualification : '.$cate_->name.' </li>'; }
					if(isset($jobseeker_details['type']) && $jobseeker_details['type']  > 0) {   $cate_ = get_term_by( 'id', $jobseeker_details['type'], 'types');  echo  '<li> Type : '.$cate_->name.' </li>'; }
					if(isset($jobseeker_details['yr_of_exp']) && $jobseeker_details['yr_of_exp']  > 0) {   $cate_ = get_term_by( 'id', $jobseeker_details['yr_of_exp'], 'yr_of_exp');  echo  '<li> Experience : '.$cate_->name.' </li>'; }
					if(isset($jobseeker_details['madhab']) && $jobseeker_details['madhab']  > 0) {   $cate_ = get_term_by( 'id', $jobseeker_details['madhab'], 'madhab');  echo  '<li> Madhab/School of Law : '.$cate_->name.' </li>'; }
					if(isset($jobseeker_details['madhab_shia']) && $jobseeker_details['madhab_shia']  > 0) {   $cate_ = get_term_by( 'id', $jobseeker_details['madhab_shia'], 'Shiamadhab');  echo  '<li> Madhab/School of Law : '.$cate_->name.' </li>'; }
					if(isset($jobseeker_details['aqeeda']) && $jobseeker_details['aqeeda']  > 0) {   $cate_ = get_term_by( 'id', $jobseeker_details['aqeeda'], 'aqeeda');  echo  '<li> Aqeeda/Belief : '.$cate_->name.' </li>'; }
					if(isset($jobseeker_details['aqeeda_shia']) && $jobseeker_details['aqeeda_shia']  > 0) {   $cate_ = get_term_by( 'id', $jobseeker_details['aqeeda_shia'], 'Shiaaqeeda');  echo  '<li> Aqeeda/Belief : '.$cate_->name.' </li>'; }
					
					//if(!empty($user_languages = get_user_meta($user_info->ID, 'languages', false ) )){  echo  '<li> languages: '; foreach($user_languages[0] as $lang){  $cate_ = get_term_by( 'id', $lang[0], 'languages');  echo $cate_->name.',';  } echo ' </li>'; }
					
					$jobseeker_languages = get_user_meta( $user_info->ID, 'languages', false );
					if(!empty($jobseeker_languages)){										
						$final_array = array_values( $jobseeker_languages[0] );
											//print_r($final_array);
						echo  '<li> Language : '; 
						foreach($final_array as $single_lan){
							$cate_ = get_term_by( 'id', $single_lan, 'languages'); 
							echo $cate_->name.', '; 
						}
						echo ' </li>'; 
					} 						
					//if(isset($jobseeker_details['zone'])) {   $cate_ = get_term_by( 'id', $jobseeker_details['zone'], 'zone');  echo  '<li> Country : '.$cate_->name.' </li>'; }
					if(isset($jobseeker_details['pref_sal_bgn']) || isset($jobseeker_details['pref_sal_end']) ) {   
						 echo  '<li> Salary : '. $jobseeker_details['pref_sal_bgn'].' To '.$jobseeker_details['pref_sal_end'];
						if((int)$jobseeker_details['pref_sal_prd'] > 0 ) {
							$category_ = get_term_by( 'id', $jobseeker_details['pref_sal_prd'], 'sal_prd'); 							
							echo  ' Per '. $category_->name;
						}
						if($jobseeker_details['pref_sal_optn'] !=  -1 ){
							$categry_ = get_term_by( 'id', $jobseeker_details['pref_sal_optn'], 'sal_optn'); 
							echo  ' Or '. $categry_->name; }
						echo ' </li>'; 
					}
					if(!empty($msg_jobseeker)) echo '<li> Message from Job Seeker: '. $msg_jobseeker. '</li> '; 
					if($jobseeker_details['dbs_description'] != '' ) echo '<li> Legal Check : '. $jobseeker_details['dbs_description']. '</li> '; 
					?>
				</ul>	
		  </div>
		  <div class="tab-pane fade" id="cv_full">
			<?php  if( $url != null  || $url != '')
			          echo '<iframe src="https://docs.google.com/viewer?url='.$url.'&embedded=true&hl=en"  width="100%" height="100%" '. $min_hegiht.' max-height="700" style="border: none;height:900px;"></iframe>'; 
					else
					  	echo "<h1 style='text-align:center;font-size:45px;margin-top:50px;'>There is no CV uploaded here</h1>" ;  ?>
		  </div> 
			
			<?php  $ids= maybe_unserialize(get_user_meta($job_seeker_id, 'certificates_form', true));
		if(!empty($ids)){
			foreach($ids as $id){ 
				$at_url = wp_get_attachment_url($id); 
				$ext = pathinfo($at_url, PATHINFO_EXTENSION);
				 ?>
				 <div class="tab-pane fade" id="<?php echo $id; ?>">
			 <?php  if($ext == 'jpeg' || $ext == 'jpg' || $ext == 'bmp' || $ext == 'png'){
			 		echo '<img src="'.$at_url.'" '. $min_hegiht.' style="border: none;max-height:900px; max-width: 100%;">';
				} else {
					echo '<iframe src="https://docs.google.com/viewer?url='.$at_url.'&embedded=true&hl=en"  width="100%" height="100%" '. $min_hegiht.' max-height="700"  style="border: none;height:900px;"></iframe>'; ?>  
			<?php } ?>
			</div> <?php
			}
		} 
		
		if(isset($dsb_url) && $dsb_url != '' ) {?>
				 <div class="tab-pane fade" id="dbs_file">
			 <?php $ext = pathinfo($dsb_url, PATHINFO_EXTENSION);
			  if($ext == 'jpeg' || $ext == 'jpg' || $ext == 'bmp' || $ext == 'png'){
			 		echo '<img src="'.$dsb_url.'" '. $min_hegiht.' style="border: none;max-height:900px; max-width: 100%;">';
				} else { 
					echo '<iframe src="https://docs.google.com/viewer?url='.$dsb_url.'&embedded=true&hl=en"  width="100%" height="100%" '. $min_hegiht.' max-height="700"  style="border: none;height:900px;"></iframe>'; ?>
		 <?php } ?>			
		</div>
		<?php
		}		 // Jobseeker Profile popup CV
} else { 
		if( $url != null  || $url != ''){
			$ext = pathinfo($url, PATHINFO_EXTENSION);
			  if($ext == 'jpeg' || $ext == 'jpg' || $ext == 'bmp' || $ext == 'png'){
			 		echo '<img src="'.$url.'" '. $min_hegiht.' style="border: none;max-height:900px; max-width: 100%;">';
				} else 
			echo '<iframe src="https://docs.google.com/viewer?url='.$url.'&embedded=true&hl=en"  width="100%" height="100%" '. $min_hegiht.' max-height="700"  style="border: none;height:900px;"></iframe>'; 
		}
		else
			echo '<h3> No File Selected!. </h3>'; 
	}
}?>
	</div>

<?php 

if(isset($_GET['attach_id'])){
		$attachment_url=  wp_get_attachment_url( $_GET['attach_id']);
		if($attachment_url != null || $attachment_url != ''){
			$ext = pathinfo($attachment_url, PATHINFO_EXTENSION);
			  if($ext == 'jpeg' || $ext == 'jpg' || $ext == 'bmp' || $ext == 'png'){
			 		echo '<img src="'.$attachment_url.'" '. $min_hegiht.' style="border: none;max-height:900px; max-width: 100%;">';
				} else 
			echo '<iframe src="https://docs.google.com/viewer?url='.$attachment_url.'&embedded=true&hl=en"  width="100%" height="100%" '. $min_hegiht.' max-height="700" max-width: 800px" style="border: none;"></iframe>'; 
		}else
			echo '<h3> No File Selected!. </h3>'; 
			 
	}?>