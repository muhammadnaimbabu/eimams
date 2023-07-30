<?php 
/**
 * Template Name: Payment Process
 */ 
 
 if(isset($_GET['donation_yes'])) {
	if(!isset($_POST['amount']) && isset($_GET['amount']) && $_GET['amount']  > 0 )
		$_POST['amount'] = $_GET['amount'];

	
	if(isset($_POST['amount']) && $_POST['amount'] != 0)
		$kv_pack_price = $_POST['amount'];
	else
		$kv_pack_price = (isset($_GET['custom_amt']) ? $_GET['custom_amt'] : $_POST['custom_amt']);
	 
	$kv_pack_name = (isset($_GET['name_of_org']) ? $_GET['name_of_org'] : (isset($_POST['name_of_org']) ? $_POST['name_of_org'] : 'Eimams Job site')); 
	$kv_pack_id = '10000';
	
	$custom = '';
	$return_url =site_url('donate-today').'?donation=yes';
 }  else { 
 
	$theme_root = dirname(__FILE__);
	require_once($theme_root."/../library/user-backend-main.php");
	$kv_current_role=kv_get_current_user_role();
	global $current_user, $wp_roles, $wpdb;
	wp_get_current_user();
	if(is_user_logged_in()) {
		kv_header(); 
		$kv_custom = '&status=Active';
		if(isset($_GET['pack_id'])) {
			$pack_id=$_GET['pack_id'];
		}
		if($kv_current_role == 'employer'){
			if((isset($_GET['pack_id']) && isset($_GET['job_id'])) || (isset($_GET['pack_id']) && isset($_GET['jobs_id'])) ) {			
				if(isset($_GET['job_id'])){
					$job_id=$_GET['job_id'];
					$return_url=site_url('posted-jobs')."?status=updated";
				}	
				else{
					$job_id=$_GET['jobs_id'];
					$return_url=site_url('posted-jobs')."?status=added";
				}
			}
			$kv_custom .= '&job_id='. $job_id;
		}elseif($kv_current_role == 'job_seeker'){		
			$return_url = site_url('subscription?subscribed=yes'); 
			$kv_custom .= '';
		}
		$pack_tble = $wpdb->prefix.'jbs_subpack'; 
		$sub_active_tbl = $wpdb->prefix. 'jbs_subactive' ; 
		$pack = $wpdb->get_row( "SELECT * FROM ".$pack_tble."  WHERE id=".$pack_id." and role='".$kv_current_role."'");
		$sub_start_date = kv_get_sub_start_date($current_user->ID); 
		$pack_id = $wpdb->get_row( "SELECT * FROM ".$sub_active_tbl." WHERE wp_user_id=".$current_user->ID ." AND pack_id=".$pack_id, ARRAY_A); 
		$end_of_pack_date = kv_get_end_date_for_pack($pack->id, $sub_start_date, true); 
		
		$custom ='pack_id='.$_GET['pack_id'].'&wp_user_id='. $current_user->ID.'&date_subscribed='.$sub_start_date.'&subend_date='.$end_of_pack_date.'&per_post='.$pack->per_post.$kv_custom;
		//kv_footer();
		if($pack->left_count <0 )
			$custom .= '&left_count='.$pack->left_count;
			
			$kv_pack_name = $pack->pack_name; 
			$kv_pack_id = $pack->id;
			$kv_pack_price = $pack->price;
			
		//echo $custom.'___________'. $sub_start_date.'____'.$pack_id.'___________'.$end_of_pack_date;
	} else {
		wp_redirect( kv_login_url() ); exit; 
	}

}

?><body>
	<div class="container">
	<p style="text-align:center;color: #227ABE;font: 14px Georgia,Arial;font-weight: bold;">Please wait, your post job is being processed... <br/>
    You will be redirected to the payment page shortly </p>
	<p style="text-align:center;font-size: 13px !important;"><br/>If you are not automatically redirected to the payment page <br/> within 5 seconds...<br/></p>
	<div style="padding-bottom:20px;">
	<center>
	<form id="gateway_form" method="POST" name="gateway_form" action="https://www.paypal.com/cgi-bin/webscr">
		<input type="hidden" name="rm" value="1"/>
		<input type="hidden" name="cmd" value="_cart"/>
		<input type="hidden" name="charset" value="utf-8"/>
		<input type="hidden" name="business" value="<?php /*echo 'XXU987WD8USFE';*/ echo '8TFU4YF3PXSBW'; ?>"/>
		<input type="hidden" name="currency_code" value="GBP"/>
		<input type="hidden" name="notify_url" value="<?php echo site_url(); ?>/ajax?action=paypal_listener"/>
		<input type="hidden" name="item_name_1" value="<?php echo $kv_pack_name; ?>" />
		<input type="hidden" name="item_number_1" value="<?php echo $kv_pack_id; ?>" />
		<input type="hidden" name="amount_1" value="<?php echo $kv_pack_price; ?>" />
		<input type="hidden" name="quantity_1" value="1"/>
		<input type="hidden" name="custom" value="<?php echo $custom; ?>"/>
		<input type="hidden" name="return" value="<?php echo $return_url; ?>"/>
		<input type="hidden" name="upload" value="1"/>
		<input type="submit" class="eStore_checkout_click_here_button" value="Click Here">
	</form>
	</center>
	</div>
	</div>
	<script type="text/javascript">				
		var user_agt = navigator.userAgent.toLowerCase();
		if (user_agt.indexOf("msie") != -1 || user_agt.indexOf("firefox") != -1){
			document.forms['gateway_form'].submit();
			var spinner_img_src = document.getElementById('eStore_spinner').src;
			document.getElementById('eStore_spinner').src = spinner_img_src;
		}else{
			setTimeout("document.forms['gateway_form'].submit()",500);
		}		
		</script>	
</body>

<style>
	.container {	max-width: 500px;	margin-left: auto;	margin-right: auto;  background: #eee;	margin-top: 20px; }
	#wrapper {     background: #FFF;	}
	.eStore_checkout_click_here_button {background-color: #EFF1F2;	color: #4f4f4f;	border-radius: 15px;	border: 1px solid #ccc;	box-shadow: 1px 1px 1px #DDDDDD;padding: 4px 12px;	cursor: pointer;}
</style>	