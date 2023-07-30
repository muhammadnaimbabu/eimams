<?php 
/** 
* Template Name: Unsubscribe
**/

get_header();

    if(isset($_GET['uni_id'])){
        $unique_id = stripslashes($_GET['uni_id']);
        $user_details = kv_get_user_newsletter_sub_info_frm_unit_id($unique_id); 
		if( $user_details) {
		
			if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['unsubscribe_me_from_it'])) {
				global $wpdb; 				

					$sub_table = $wpdb->prefix.'newsletter';
					$status = $wpdb->update($sub_table, array( 
											'common'	=>	$_POST['common_alert'],
											'jobalert'	=>	$_POST['job_alert'],
											'status' 	=> 	$_POST['status_alert']
									), array('id' => $user_details->id) );
					if($status>0){
						kv_newsletter_unsubscription_email(stripslashes($user_details->email), stripslashes($_POST['common_alert']), stripcslashes($_POST['job_alert']), stripcslashes(($_POST['status_alert'])) ); 
					}
			}
			 $user_details = '' ; 
			 $user_details = kv_get_user_newsletter_sub_info_frm_unit_id($unique_id); 	?>       
			
			<style type="text/css">
			
			.intro { text-align:center; font-size:18px; margin:20px 0; font-weight:bold }
			
			</style>
			
			<?php if(isset($_POST['common_alert']) || isset($_POST['job_alert']) || isset($_POST['status_alert'])) {
				echo '<div class="success" style="max-width:600px;" ><ul> ';
					
					if(isset($_POST['common_alert']) && $_POST['common_alert']== 1 ){
						echo '<li>	You are Subscribed From Common Alerts!. </li>'; 					
					} else {
						echo '<li> You are Unsubscribed from Common Alerts !. </li>'; 					
					} 
					if(isset($_POST['job_alert']) && $_POST['job_alert']== 1) {
						echo '<li > You are Subscribed For Job Alerts!. </li>'; 					
					}else {
						echo '<li> You are Unsubscribed from Job Alerts !. </li>'; 					
					} 
					if(isset($_POST['status_alert']) && $_POST['status_alert']== 1) {
						echo '<li> You are Subscribed For All Alerts!. </li>'; 					
					}else {
						echo '<li> You are Unsubscribed from All Alerts !. </li>'; 					
					} 
					echo '</ul> </div> ' ;
				}else {
						echo '<div class="success" style="max-width:600px;"><ul> <li> You are Unsubscribed from All Alerts !. </li></ul> </div>';
				}?>
	 <form class="form-horizontal" method="post" action="" >

			<p class="intro"> Please uncheck the right checkbox to remove you from the newsletters. </p>
			<div class="col-sm-12" align="center">
				 <ul>
					<li><h3 style="margin-left:-50px;color:red">Email Alert:</h3></li>
					<li> <label><input type="checkbox" id="job-alert-wage" <?php if($user_details->jobalert == 1) echo 'checked'; ?> name="job_alert" value="1"> Subscribed for Job Alert Emails </label></li>
					<li><label><input type="checkbox" id="job-alert-wage" <?php if($user_details->common == 1) echo 'checked'; ?> name="common_alert" value="1"> Subscribed for Common Newsletters and Special Offers</label></li>
					<li><label><input type="checkbox" id="job-alert-wage" <?php if($user_details->status == 1) echo 'checked'; ?> name="status_alert" value="1"> Subscribed for All Alert Emails </label></li>
				</ul>

				<div class="checkbox col-xs-12" style="margin-bottom:20px;">
					<input type="submit" class="btn btn-primary" name="unsubscribe_me_from_it" value=" Submit" >  
				</div>
		 </div>

		</form> 


  <?php }else {
			echo ' You are in the Wrong area. ' ; 
		}
  } else { 
       include( get_query_template( '404' ) );
      exit();
		
  }

get_footer();

?>