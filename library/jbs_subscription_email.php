<?php
global $current_user, $wp_roles, $wpdb;
$job_seeker_table = $wpdb->prefix.'jbs_subpack';
$kv_errors = array();
if(isset($_GET['pack_id']))
	$pack_id=$_GET['pack_id'];
else
	$pack_id=0;

$kv_errors = array();

if($pack_id!=0){

	$email_pack = $wpdb->get_row("SELECT * FROM " . $job_seeker_table . " WHERE id =".$pack_id , ARRAY_A);

	//var_dump($email_pack);
	
	$role = $_GET['role'];

	if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['submit_form'])) {

		if (isset($_POST['email_or_user'])) {
			$posted['email_or_user'] = stripslashes(trim($_POST['email_or_user'])); 
			if (filter_var($posted['email_or_user'], FILTER_VALIDATE_EMAIL)) { //if its email, check it for registered user
			  	$user = get_user_by('email', $posted['email_or_user']);
			  //	print_r($user);
			  	if($user){  // if registered user
			  		$form_to = 'paypal';
			  		$mailto = $user->user_email;
			  		
			  		if($role != $user->roles[0])
			  			array_push($kv_errors,  __('<strong>Notice</strong>: User Role  does not match with pack.', 'kv_project'));
			  	}else { // non registered user

			  		if($role == 'job_seeker')
			  			$form_to = get_site_url().'/jobseeker-sign-up';
			  		elseif($role == 'employer')
			  			$form_to = get_site_url().'/employer-sign-up';
			  		else
			  			array_push($kv_errors,  __('<strong>Notice</strong>: User Role  does not exist to send email.', 'kv_project'));
			  		$mailto = $posted['email_or_user'];
			  	}
			}else { //  if its not email, than check it for User Id and username
				if(is_numeric($posted['email_or_user'])){
					$user = get_user_by('ID', $posted['email_or_user']);
					
				  	if($user){  
				  		$form_to = 'paypal';
				  		$mailto = $user->user_email;
				  		if($role != $user->roles[0])
			  			array_push($kv_errors,  __('<strong>Notice</strong>: User Role  does not match with pack.', 'kv_project'));
				  	}
				}else{
					$user = get_user_by('login', $posted['email_or_user']);
				  	if($user){  
				  		$form_to = 'paypal';
				  		$mailto = $user->user_email;
				  		if($role != $user->roles[0])
			  			array_push($kv_errors,  __('<strong>Notice</strong>: User Role  does not match with pack.', 'kv_project'));
				  	}
				}
			}
		}
		else {
			array_push($kv_errors,  __('<strong>Notice</strong>: Please enter Email or Username or ID.', 'kv_project'));
		}

		$errors = array_filter($kv_errors);

		if (empty($errors)) { 

		    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

		    $subject = sprintf(__('You Have Got a Special '.$email_pack['pack_name'].' from %s','kvc'), $blogname);
		 
		    $headers = 'From: '. sprintf(__('%s Admin', 'kvc'), $blogname).'<info@eimams.com>'.'<br>';
		 
		    //Message
		    $message  = email_header();
		   
		    $message .='<style type="text/css">
			@import url("http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css");
			@import url("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
			@import url("http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&subset=latin,latin-ext");
				
				/* COMMON PRICING STYLES */
				     .panel.price { border:1px solid #ddd; border-top:none}
					.panel.price,
					.panel.price>.panel-heading{
						border-radius:0px;
						 -moz-transition: all .3s ease;
						-o-transition:  all .3s ease;
						-webkit-transition:  all .3s ease;
					}
					.panel.price:hover{
						box-shadow: 0px 0px 30px rgba(0,0,0, .2);
					}
					.panel.price:hover>.panel-heading{
						box-shadow: 0px 0px 30px rgba(0,0,0, .2) inset;
					}			
							
					.panel.price>.panel-heading{
						box-shadow: 0px 5px 0px rgba(50,50,50, .2) inset;
						text-shadow:0px 3px 0px rgba(50,50,50, .6);
					}
						
					.price .list-group-item{
						border-bottom-:1px solid rgba(250,250,250, .5);
					}
					
					.panel.price .list-group-item:last-child {
						border-bottom-right-radius: 0px;
						border-bottom-left-radius: 0px;
					}
					.panel.price .list-group-item:first-child {
						border-top-right-radius: 0px;
						border-top-left-radius: 0px;
					}
					
					.price .panel-footer {
						color: #fff;
						border-bottom:0px;
						background-color:  rgba(0,0,0, .1);
						box-shadow: 0px 3px 0px rgba(0,0,0, .3);
					}			
					
					.panel.price .btn{
						box-shadow: 0 -1px 0px rgba(50,50,50, .2) inset;
						border:0px;
					}	
			
					
					/* blue panel */		
					
					.price.panel-blue>.panel-heading {
						color: #fff;
						background-color: #608BB4;
						border-color: #78AEE1;
						border-bottom: 1px solid #78AEE1;
					}			
						
					.price.panel-blue>.panel-body {
						color: #fff;
						background-color: #73A3D4;
					}					
					
					.price.panel-blue>.panel-body .lead{
							text-shadow: 0px 3px 0px rgba(50,50,50, .3);
					}			
					.price.panel-blue .list-group-item {
						color: #333;
						background-color: rgba(50,50,50, .01);
						font-weight:600;
						text-shadow: 0px 1px 0px rgba(250,250,250, .75);
					}		
					ul{
						list-style:  none;
					}
					.panel-footer inupt:hover { cursor:pointer}				
					
			</style>
			
			<p style="text-align:center;font-size:18px;"> Below is the payment link as per your requirement. Press the buy now button which will take you to the payment gateway.</p>'; 

		//email_or_user
		if($form_to == 'paypal'){
			$message .= '<form id="gateway_form" method="POST" name="gateway_form" action="https://www.paypal.com/cgi-bin/webscr">
							
								<input type="hidden" name="rm" value="1"/>
								<input type="hidden" name="cmd" value="_cart"/>
								<input type="hidden" name="charset" value="utf-8"/>
								<input type="hidden" name="business" value="8TFU4YF3PXSBW"/>
								<input type="hidden" name="currency_code" value="GBP"/>
								<input type="hidden" name="notify_url" value="'.site_url().'/ajax?action=paypal_listener"/>
								<input type="hidden" name="item_name_1" value="'.$email_pack['pack_name'].'" />
								<input type="hidden" name="item_number" value="'.$email_pack['id'].'" />
								<input type="hidden" name="item_number1" value="'.$email_pack['id'].'" />
								<input type="hidden" name="amount_1" value="'.$email_pack['price'].'" />
								<input type="hidden" name="quantity_1" value="1"/>
								<input type="hidden" name="custom" value="pack_id='.$email_pack['id'].'&wp_user_id='. $current_user->ID.'&date_subscribed='.$sub_start_date.'&per_post='.$email_pack['per_post'].'&status=Active"/>
								<input type="hidden" name="return" value="'.site_url("subscription").'?subscribed=yes"/>
								<input type="hidden" name="upload" value="1"/>
								<!-- PRICE ITEM -->
								
								<div class="panel price panel-blue" >
									<div class="panel-heading  text-center">
											<h3 style="text-align:center;font-size:32px;padding-top:10px;">Custom Payment</h3>
									</div>
									<div class="panel-body text-center">
										<p class="lead" style="font-size:40px;text-align:center"><strong>&pound;'.$email_pack['price'].'</strong></p>
									</div>

									<div align="center" class="panel-footer" style="background:none;box-shadow:none;padding:10px;">
										<input style="padding:15px 35px;background:#006ac1;color:#fff" type="submit" class="btn" value="Buy Now">
									</div>
								</div>
								<!-- /PRICE ITEM -->
								
								</form> ';

				}else{
					$message .= '<form method="POST" name="gateway_form" action="'.$form_to.'">						
								
								<input type="hidden" name="submit_pack_id" value="'.$email_pack['id'].'" />
						
								<input type="hidden" name="usr_email" value="'.$posted['email_or_user'].'"/>
								<!-- PRICE ITEM -->
								
								<div class="panel price panel-blue" >
									<div class="panel-heading  text-center">
									
									</div>
									<div class="panel-body text-center">
										<p class="lead" style="font-size:40px"><strong>&pound;'.$email_pack['price'].'</strong></p>
									</div>

									<div class="panel-footer">
									<input type="submit" class="btn btn-lg btn-block panel-blue " value="Buy Now">
									
									</div>
								</div>
								<!-- /PRICE ITEM -->							
						</form> ';
				}

			$message .= kv_email_footer($mailto);

	//var_dump($message.'-----'.$mailto.'------'.$subject.'----'.$headers.'---'.$form_to );
			 if( wp_mail($mailto, $subject, $message, $headers))
				$success = "yes";   //".";
			else 
				$error ="yes";  //""; 
		}
	}
	?>


	<div class="wrap">
	<h2>Email A Pack </h2>
	<?php 
	 if(!empty($errors)) {
			echo '<div class="error">';
			foreach ($kv_errors as $error) {
				echo '<p>'.$error.'</p>';
			}
			echo '</div>';
	}
	if(isset($success) && empty($errors))
		echo '<div class="error" style="border-left-color:#547967; padding:7px;" >Special Pack Subscription sent to the users email successfully </div> ';
	if(isset($error))
		echo '<div class="error" >Failed to send it.Try again later or contact developer for the issues!. </div> '; ?>
	<form method="post" action="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI']; ?>" novalidate="novalidate">
	<input type="hidden" name="option_page" value="general"><input type="hidden" name="action" value="update"><input type="hidden" id="_wpnonce" name="_wpnonce" value="fd8704f148"><input type="hidden" name="_wp_http_referer" value="/wp-admin/options-general.php">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row" style="width: 280px !important;"><label>Email or User ID or Username <span style="color:red">*</span></label></th>
					<td><input name="email_or_user" type="text" value="" aria-required="true"></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="submit_form" id="submit" class="button button-primary" value="Save Changes"></p>
	</form><?php
}else{
	wp_die(" No Pack Id Selected.");
}



?>