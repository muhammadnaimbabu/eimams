<?php

/************************************
 *	back end email function		*
 ************************************/

// add_action('wp_mail_failed', function ($error) {
// 	error_log($error->get_error_message());
// });
/*-----------------------------------------------------------------------------------*/
# SMTP Setup
/*-----------------------------------------------------------------------------------*/
// add_action('phpmailer_init', 'mailer_config', 10, 1);
// function mailer_config($phpmailer)
// {
// 	$phpmailer->Host = 'smtp.sendgrid.net';
// 	$phpmailer->Port = 465; // could be different
// 	$phpmailer->Username = "apikey"; // if required
// 	$phpmailer->Password   = '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';                               //SMTP password
// 	$phpmailer->SMTPAuth = true; // if required
// 	// $phpmailer->SMTPSecure = 'ssl'; // enable if required, 'tls' is another possible value

// 	$phpmailer->IsSMTP();
// 	$phpmailer->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
// }



/*****************************************
 *  PayPal Refund Notification to User
 ****************************************/
function kv_refund_a_transaction($id, $payer_email)
{
	$active_table = get_packactiveinfo_using_id($id);

	$get_user = get_userdata($active_table->wp_user_id);

	$dashurl = trailingslashit(site_url('subscription'));

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$mailto = $get_user->user_email . "," . $payer_email;

	$subject = sprintf(__('Your ' . $pack_name . ' Purchase Refunded Back from %s', 'kvc'), $blogname);

	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	// Message
	$message  = email_header();

	$message .= " Your pack xxx been deactivated and the fee of £xxx has been refunded minus an admisatration charge of xxx 
to your account.";

	$message .= kv_email_footer($mailto);

	wp_mail($mailto, $subject, $message, $headers);
}


/*****************************************
 *  Mail Mime Type
 ****************************************/
function kv_mail_content_type($content_type)
{
	return 'text/html';
}
add_filter('wp_mail_content_type', 'kv_mail_content_type');


if (!defined('PHP_EOL')) define('PHP_EOL', strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? "\r\n" : "\n");

function email_header()
{
	return   ' <html xmlns="http://www.w3.org/1999/xhtml"><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Welcome to eimams</title>
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,700,700italic,800,800italic|Open+Sans+Condensed:700">
		<style type="text/css">
		body 			{	background-color: #ffffff;	margin: 0px;	padding: 0px;	text-align: center;	width: 100%;}
		html 			{	width: 100%; }
		.contentbg		{	background-color: #ffffff;	}
		img 			{	border:0px;		outline:none;	text-decoration:none;	display:block;	}

		@media only screen and (max-width:640px)	{
			body{width:auto!important;}
			table[class=main]{width:446px !important;}
			table[class=logo]{width:100% !important;}
			table[class=righttop-details]{width:100% !important;}
			table[class=social-top]{width:100% !important;}
			td[class=viewonline-field]{padding: 10px 0px 0px 0px !important;}
			td[id=viewonline]{text-align:center !important;}
			td[class=productspace]{display:none !important;}
			td[class=productbox]{width:100% !important; display:block;}
			td[class=product-hidebox]{height:40px !important; display:block !important;}
			td[class=subcontentbox]{width:100% !important; display:block;}
			td[class=subcontent-space]{height:20px !important;}
			td[class=bottomicon]{width:127px !important;}
			table[class=bottomiconbox]{width:100% !important;}
			td[class=bottomiconhidebox]{height:6px !important; display:block !important;}
			table[class=icon-bottom]{width:100% !important;}
			td[class=hidebox]{height:15px !important; display:block !important;}
			td[class=hidebox-footer]{height:10px !important; display:block !important;}
			td[class=footerbox]{width:100% !important; display:block !important;}
		}

		@media only screen and (max-width:450px)	{
			body{width:auto!important;}
			table[class=main]{width:320px !important;}
			table[class=logo]{width:100% !important;}
			table[class=righttop-details]{width:100% !important;}
			table[class=social-top]{width:100% !important;}
			td[class=viewonline-field]{padding: 10px 0px 0px 0px !important;}
			td[id=viewonline]{text-align:center !important;}
			td[class=productspace]{display:none !important;}
			td[class=productbox]{width:100% !important; display:block;}
			td[class=product-hidebox]{height:40px !important; display:block !important;}
			td[class=subcontentbox]{width:100% !important; display:block;}
			td[class=subcontent-space]{height:20px !important;}
			td[class=bottomicon]{width:127px !important;}
			table[class=bottomiconbox]{width:100% !important;}
			td[class=bottomiconhidebox]{height:6px !important; display:block !important;}
			table[class=icon-bottom]{width:100% !important;}
			td[class=hidebox]{height:15px !important; display:block !important;}
			td[class=hidebox-footer]{height:10px !important; display:block !important;}
			td[class=footerbox]{width:100% !important; display:block !important;}
		}
		</style>

		<!-- Internet Explorer fix -->
		<!--[if IE]>
		<style type="text/css">

		@media only screen and (max-width:640px){
			td[class=productbox]{width:100% !important; float:left; display:block;}
			td[class=subcontentbox]{width:100% !important; float:left; display:block;}
			td[class=footerbox]{width:100% !important; float:left; display:block;}
		}

		@media only screen and (max-width:450px){
			td[class=productbox]{width:100% !important; float:left; display:block;}
			td[class=subcontentbox]{width:100% !important; float:left; display:block;}
			td[class=footerbox]{width:100% !important; float:left; display:block;}
		}

		</style>
		<![endif]-->
		<!-- / Internet Explorer fix -->

		<!-- Outlook -->
		<!--[if gte mso 9]>
		<style type="text/css">
		.righttop-details{width:250px !important;}
		.icon-bottom{width:286px !important;}
		.bottomiconbox{width:290px !important;}
		</style>
		<![endif]-->

		<!--[if gte mso 10]>
		<style type="text/css">
		.righttop-details{width:250px !important;}
		.icon-bottom{width:286px !important;}
		.bottomiconbox{width:290px !important;}
		</style>
		<![endif]-->

		<!--[if gte mso 15]>
		<style type="text/css">
		.righttop-details{width:250px !important;}
		.icon-bottom{width:286px !important;}
		.bottomiconbox{width:290px !important;}
		</style>
		<![endif]-->
		<!-- / Outlook -->
		</head>

		<body>

		<!--Table Start-->
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contentbg">
		  <tbody><tr>
			<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tbody><tr>
				<td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tbody><tr>
					<td align="center" valign="top">
					
		   <!--Header Start-->
					<table width="610" border="0" cellspacing="0" cellpadding="0" class="main">
			  <tbody><tr>
				<td width="5">&nbsp;</td>
				<td valign="top" style="padding-bottom:20px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tbody><tr>
					<td valign="top">
					
					<!--Logo Start-->
					<table width="250" border="0" align="left" cellpadding="0" cellspacing="0" class="logo">
					  <tbody><tr>
						<td align="center" valign="top"><table width="250" border="0" cellspacing="0" cellpadding="0">
						  <tbody><tr>
							<td align="center" valign="top" bgcolor="" style="padding:5px 5px 0px 5px;"><a href="' . site_url() . '" target="_blank"><img editable="true" mc:edit="logo" src="' . get_template_directory_uri() . '/img/logo-email-template.png" width="200" alt="logo" style="display:block; border: none;"></a></td>
						  </tr>
						</tbody></table></td>
					  </tr>
					</tbody></table>
					<!--Logo End-->
					
					<table width="290" border="0" align="right" cellpadding="0" cellspacing="0" class="righttop-details">
					  <tbody><tr>
						<td align="right" style="padding-top:35px;">
						
						<!--Social Top Start-->
						<table width="245" border="0" cellspacing="0" cellpadding="0" class="social-top">
						  <tbody><tr>
							<td align="center"><table width="245" border="0" cellspacing="0" cellpadding="0">
							  <tbody><tr>
								<td><a href="#" target="_blank"><img editable="true" mc:edit="social-1" src="' . get_template_directory_uri() . '/img/social-icon-1.png" width="45" height="45" alt="social icon" style="display:block; border: none;"></a></td>
								<td width="5">&nbsp;</td>
								<td><a href="#" target="_blank"><img editable="true" mc:edit="social-2" src="' . get_template_directory_uri() . '/img/social-icon-2.png" width="45" height="45" alt="social icon" style="display:block; border: none;"></a></td>
								<td width="5">&nbsp;</td>
								<td><a href="#" target="_blank"><img editable="true" mc:edit="social-3" src="' . get_template_directory_uri() . '/img/social-icon-3.png" width="45" height="45" alt="social icon" style="display:block; border: none;"></a></td>
								<td width="5">&nbsp;</td>
							
							  </tr>
							</tbody></table></td>
						  </tr>
						</tbody></table>
						<!--Social Top End-->
						
						</td>
					  </tr>    
					  
					  <tr>
						<td valign="top" style="padding-top:8px; padding-left:0px;" class="viewonline-field">
						
						<!--View Online Start-->
					
						<!--View Online End-->
						
						</td>
					  </tr>
					</tbody></table>
					</td>
				  </tr>
				</tbody></table></td>
				<td width="5">&nbsp;</td>
			  </tr>
			</tbody></table>
		 
		 <!-- ####################  Header End  ################################# -->
					
					</td>
				  </tr>
				  <tr>
					<td valign="top" bgcolor="#006ac1">
					
					<!--Heading Text Start-->
				<table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="main">
				  <tbody><tr>
					<td width="5">&nbsp;</td>
					<td valign="top" mc:edit="heading-text" style="font-family: \'Open Sans\', sans-serif; font-size: 30px; font-weight: normal; color: #ffffff; line-height: 36px; text-align:center; padding:18px 25px 25px 25px;"><singleline>Welcome to eimams</singleline></td>
					<td width="5">&nbsp;</td>
				  </tr>
				</tbody></table>
				<!--Heading Text End-->
					
					</td>
				  </tr>
				</tbody></table></td>
			  </tr>
			  <tr>
				<td valign="top"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="main">
				  <tbody><tr>
					<td width="5">&nbsp;</td>
					<td valign="top" style="padding:20px 0px 30px 0px; text-align: justify" >
					
					<!--Content Start-->';
}

function kv_email_footer($email_id = null)
{
	if (kv_get_user_unique_id_from_email($email_id) != null) {
		$unsubscribe_link = 'If you wish to optout from our newsletters,please <a href="' . site_url('unsubscribe') . '?uni_id=' . kv_get_user_unique_id_from_email($email_id) . '" targe="_blank" >Unsubscribe</a> here.';
	} else
		$unsubscribe_link = ' ';
	return  ' </td>
              </tr>
			  <tr> 
			  <td colspan="2" style="text-align:left;">
			  <div style="font-size:18px;"> Regards </div> 
			  <a style="font-size:18px;" href="' . get_site_url() . '" >eimams</a>
			  <br> <br>
			  </td> </tr>
            </tbody></table>
            <!--Content End-->
            
            </td>
            <td width="5">&nbsp;</td>
          </tr>
        </tbody></table></td>
      </tr>
      <tr>
        <td valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody><tr>
            <td valign="top" bgcolor="#006ac1">
            
            <!--Icon Bottom Start-->
        <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="main">
          <tbody><tr>
            <td width="5">&nbsp;</td>
            <td valign="top" style="padding:20px 0px 20px 0px;"><table width="300" border="0" align="left" cellpadding="0" cellspacing="0" class="icon-bottom">
              <tbody><tr>
                <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody><tr>
                    <td width="28" align="right" valign="top"><img editable="true" mc:edit="bottom-icon-1" src="' . get_template_directory_uri() . '/img/icon-7.png" width="60" alt="icon" style="display:block; border: none;margin-top:-5px;"></td>
                    <td align="left" valign="top" mc:edit="see-all" style="padding:0px 15px;"><singleline><a style="font-family: \'Open Sans\', sans-serif; font-size: 24px; font-weight: normal; color: #ffffff; text-decoration: none;" href="#" target="_blank">07507 653582</a></singleline></td>
                  </tr>
                </tbody></table></td>
              </tr>
            </tbody></table>
              <table width="300" border="0" align="right" cellpadding="0" cellspacing="0" class="icon-bottom">
                <tbody><tr>
                  <td class="hidebox" style="display:none;"></td>
                </tr>
                <tr>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody><tr>
                      <td width="28" align="right" valign="top" style="padding-top:6px;"><img editable="true" mc:edit="bottom-icon-2" src="' . get_template_directory_uri() . '/img/footer-icon-2.png" width="33" height="22" alt="icon" style="display:block; border: none;"></td>
                      <td align="left" valign="top" mc:edit="subscribe" style="padding-left:15px;"><singleline><a href="' . get_site_url() . '/login"  style="font-family:\'Open Sans\', sans-serif; font-size: 24px; font-weight: normal; color: #ffffff; text-decoration: none;" href="#" target="_blank">Subscribe Now!</a></singleline></td> 
                    </tr>
                  </tbody></table></td>
                </tr>
              </tbody></table></td>
            <td width="5">&nbsp;</td>
          </tr>
        </tbody></table>
        <!--Icon Bottom End-->
        
        </td>
          </tr>
          <tr>
            <td valign="top" bgcolor="#569ce3">
            
            <!--Footer Start-->
    <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="main">
      <tbody><tr>
        <td width="5">&nbsp;</td>
        <td valign="top" style="padding:21px 0px 33px 0px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody><tr>
            <td width="50%" valign="top" class="footerbox">
            
            
            <!--Address Contact Start-->
        <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
          <tbody><tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
              <tr>
                <td valign="top" mc:edit="address-contact" style="font-family: \'Open Sans\', sans-serif; font-size: 13px; font-weight: normal; color: #ffffff; line-height: 18px; text-align:left; padding-top:0px;"><singleline>
              If you find it difficult to complete the online form due to language barriers or any other issues, <br>' . $unsubscribe_link . '
               Please contact us to help you to complete the online form on the following details: 
                  <p style="font-family: \'Open Sans\', sans-serif; font-size: 13px; font-weight: normal; color: #000; text-decoration: none; text-align:center;" href="#">07507 653582 or e-mail: <span style="color:#eee;">admin@eimams.com</span></p></singleline>

                  <p align="center"> © eimams 2018 - All Rights Reserved </p>
                  
                  </td>
              </tr>
              </tbody></table></td>
          </tr>
        </tbody></table>
        <!--Address Contact End-->            
            
            </td>
            
          </tr>
        </tbody></table></td>
        <td width="5">&nbsp;</td>
      </tr>
    </tbody></table>
    <!--Footer End-->
            
            </td>
          </tr>
        </tbody></table>
        </td>
      </tr>
    </tbody></table>
  

<!--Table End-->

</body></html>';
}

/************************************************
 *	first Mail for Newsletter Subscribers	*
 *************************************************/
function kv_newsletter_subscription_first_email($mail_id)
{

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$headers = 'From: ' . __('Admin', 'kvc') . ' <admin@eimams.com>' . '<br>';

	$subject  = sprintf(__('Welcome to %s - Happy Welcome to get Our Newsletters:'), $blogname);

	$message  = email_header();

	$message .= sprintf(__('Hi , <br> 
					You are subscribed to our newsletter and to receive notifications from us. If you submit this by error then use the link to unsubscribe. ')) . '<br>' . '<br>';

	$message .= sprintf(__('<br> E-mail: %s'), $mail_id) . '<br>';

	$message .= kv_email_footer($mail_id);


	wp_mail($mail_id, $subject, $message, $headers);
}

/************************************************
 *	unsubscribe Mail for Newsletter Subscribers	*
 *************************************************/
function kv_newsletter_unsubscription_email($mailto, $common, $job_alert, $all_alert)
{

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$headers = 'From: ' . __('Admin', 'kvc') . ' <admin@eimams.com>' . '<br>';

	$subject = 'You are Subscription Status';

	$message  = email_header() . 'Hi , ';

	if (isset($_POST['common_alert']) && $_POST['common_alert'] == 1) {

		$message .= sprintf(__('<br> 
					You are Subscribed to our Common Newsletter and to Receive Promotional Offers.')) . '<br>' . '<br>';
	} else {
		//$subject = ' You are Unsubscribed from Common Alerts !'; 
		$message .= sprintf(__(' <br> 
					You are Unsubscribed to our Common Newsletter and to Receive Promotional Offers.')) . '<br>' . '<br>';
	}
	if (isset($_POST['job_alert']) && $_POST['job_alert'] == 1) {
		//$subject =' You are Subscribed For Job Alerts!'; 
		$message .= sprintf(__('<br> 
					You are Subscribed to our Job Alerts and to Newly Posted Vacancies, that matches your profile and Newsletters.')) . '<br>' . '<br>';
	} else {
		//$subject = ' You are Unsubscribed from Job Alerts !';
		$message .= sprintf(__(' <br> 
					You are Unsubscribed to our Job Alerts and to Newly Posted Vacancies, that matches your profile and Newsletters.')) . '<br>' . '<br>';
	}
	if (isset($_POST['status_alert']) && $_POST['status_alert'] == 1) {
		//$subject = 'You are Subscribed For All Alerts!'; 
		$message .= sprintf(__(' <br> 
					You are Subscribed to all of our promotional and Newsletter Emails.')) . '<br>' . '<br>';
	} else {
		//$subject = 'You are Unsubscribed from All Alerts !'; 		
		$message .= sprintf(__(' <br> 
					You are Unsubscribed From all of our promotional and Newsletter Emails.')) . '<br>' . '<br>';
	}

	$message .= sprintf(__('<br> E-mail: %s'), $mailto) . '<br>';

	$message .= kv_email_footer($mailto);

	wp_mail($mailto, $subject, $message, $headers);
}


/******************************************
 *		New User Registration		*
 ******************************************/
add_action('user_registration', 'kv_new_user_notification');

function kv_new_user_notification($user_id, $plaintext_pass = '')
{

	$user = get_userdata($user_id);

	$user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$headers = 'From:' . get_bloginfo('admin_email') .  "\r\n";

	$subject  = sprintf(__('Welcome to %s:'), $blogname);

	$message  = email_header();

	$message .= sprintf(__('Your User Credentials Are here:')) . '<br>' . '<br>';
	$message .= sprintf(__('Username: %s'), $user_login) . '<br>' . '<br>';
	$message .= sprintf(__('E-mail: %s'), $user_email) . '<br><br>';
	$message .= sprintf(__('Password: %s', 'kvc'), $plaintext_pass) . '<br><br>';
	$message .= '-------------------------------------------';
	$message .= "We are happy to welcome you to the eimams Family as a member. We have one more step to verify your email. <br>Please click the link below, if the link does not open, then copy the link and paste it directly on your browser";
	$key = wp_generate_password(25, false);
	add_user_meta($user_id, 'verification', $key);
	$message .= '<a href="' . kv_login_url() . "?action=verify&verify=$key&usr_ID=" . rawurlencode($user_login) . '" > ' . kv_login_url() . "?action=verfiy&verify=$key&usr_ID=" . rawurlencode($user_login) . "</a><br><br>";

	$message .= kv_email_footer($user_email);

	wp_mail($user_email, $subject, $message, $headers);
}

/******************************************
 *	ReSend validation code and link		*
 ******************************************/
function kv_Resend_validation_mail($user_id)
{

	$user = get_userdata($user_id);

	$user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$headers = 'From:' . get_bloginfo('admin_email') .  "\r\n";

	$message  = email_header();

	$subject  = sprintf(__('Resending E-mail Verification From %s:'), $blogname);
	$message  .= sprintf(__('You requested to resend the validation email again. So please follow the link and verify your email address'));
	$key = wp_generate_password(25, false);
	update_user_meta($user_id, 'verification', $key);
	$message .= '<a href="' . kv_login_url() . "?action=verify&verify=$key&usr_ID=" . rawurlencode($user_login) . '"> Click Here</a>';

	$message .= kv_email_footer($user_email);
	wp_mail($user_email, $subject, $message, $headers);
}

/******************************************
 *		New User Verified			      *
 ******************************************/
function user_verified_email($user_id)
{

	$user = get_userdata($user_id);
	//print_r($user); 
	$user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$headers = 'From: ' . __('Admin', 'kvc') . ' <admin@eimams.com>' . '<br>';
	// send the site admin an email everytime a new user registers
	// if (get_option($app_abbr.'_nu_admin_email') == 'yes') {

	// ok let's send the new user an email

	$subject  = sprintf(__('Email Verification Confirmed -  %s:'), $blogname);

	$message  = email_header();
	$message .= sprintf(__('Welcome to %s:'), $blogname) . '<br>' . '<br>';
	$message .= sprintf(__('Congrats, Your email address has been verified successfully !<br>'));
	$message .= kv_email_footer($user_email);

	wp_mail($user_email, $subject, $message, $headers);
}


/******************************************
 *		User Subscription ended		 *
 ******************************************/
add_action('user_subscription_ended', 'kv_user_subscription_ended_email', 10, 1);
function kv_user_subscription_ended_email($id)
{

	$active_table = get_packactiveinfo_using_id($id);
	//echo $active_table->pack_id; 
	$pack_name = get_packname_using_id($active_table->pack_id);
	$get_user = get_userdata($active_table->wp_user_id);
	$siteurl = trailingslashit(get_option('home'));
	$dashurl = trailingslashit(site_url('subscription'));

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$mailto = $get_user->user_email;

	$subject = sprintf(__('Your ' . $pack_name . ' access subscription has expired on %s', 'kvc'), $blogname);

	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	// Message
	$message  = email_header();
	$message .= sprintf(__('Hi %s,', 'kvc'), $get_user->display_name) . '<br>' . '<br>';
	$message .= sprintf(__('Your Pack : %s', 'kvc'), $pack_name) . '<br>' . '<br>';
	$message .= sprintf(__('Date Activated: %s,', 'kvc'), $active_table->date_subscribed) . '<br>' . '<br>';
	$message .= sprintf(__('Date Expired: %s,', 'kvc'), $active_table->end_date) . '<br>' . '<br>';
	$message .= sprintf(__('Your subscription  ' . $pack_name . ' has just expired. To continue browsing  on %s you need to subscribe.', 'eimams'), $blogname) . '<br>' . '<br>';
	$message .= '<br> <a href="' . $dashurl . '" > ' . $dashurl . '</a> <br>' .  '<br>';

	$message .= sprintf(__('We have few suggestions for you to select the right Pack.', 'eimams'), $blogname) . '<br>' . '<br>';

	$message .= kv_email_footer($mailto);
	wp_mail($mailto, $subject, $message, $headers);
}

/******************************************
 *		Expire and  Renew newpack	      *
 ******************************************/
add_action('user_subscription_ended_renew', 'kv_user_subscription_ended_renew', 10, 2);
function kv_user_subscription_ended_renew($old, $new)
{

	$expired_pack = get_packactiveinfo_using_id($old);
	//echo $active_table->pack_id; 
	$pack_name = get_packname_using_id($expired_pack->pack_id);
	$get_user = get_userdata($expired_pack->wp_user_id);
	$siteurl = trailingslashit(get_option('home'));
	$dashurl = trailingslashit(site_url('subscription'));

	$new_pack = get_packactiveinfo_using_id($new);
	//echo $active_table->pack_id; 
	$new_pack_name = get_packname_using_id($new_pack->pack_id);

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$mailto = $get_user->user_email;

	$subject = sprintf(__('Your ' . $pack_name . ' access subscription has expired on %s', 'kvc'), $blogname);

	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	// Message
	$message  = email_header();
	$message .= sprintf(__('Hi %s,', 'kvc'), $get_user->display_name) . '<br>' . '<br>';
	$message .= sprintf(__('Expired Pack: %s', 'kvc'), $pack_name) . '<br>' . '<br>';
	$message .= sprintf(__('Activation Date: %s', 'kvc'), $expired_pack->start_date) . '<br>' . '<br>';
	$message .= sprintf(__('Your current subscription  <b>' . $pack_name . '</b> has just expired. You can continue browsing on %s by Using the follwoing pack which is active now.', 'eimams'), $blogname) . '<br>' . '<br>';

	$message .= __('----------------------------------------------') . '<br>';

	$message .= sprintf(__('Your New Pack Name: %s', 'kvc'), $new_pack_name) . '<br>' . '<br>';
	$message .= sprintf(__('Activation Date: %s', 'kvc'), $new_pack->start_date) . '<br>' . '<br>';

	if ($new_pack->per_post != 0)
		$message .= sprintf(__('Your New Pack Has %s Posts Left,', 'kvc'), $new_pack->per_post) . '<br>' . '<br>';
	else
		$message .= sprintf(__('Expiry Date: %s', 'kvc'), $new_pack->end_date) . '<br>' . '<br>';

	$message .= __('----------------------------------------------') . '<br>';
	$message .= '<br> <a href="' . $dashurl . '" > ' . $dashurl . '</a> <br>' .  '<br>';

	//$message .= sprintf(__('We have few suggestions for you to select the right Pack.', 'eimams'), $blogname) . '<br>' . '<br>';

	$message .= kv_email_footer($mailto);
	wp_mail($mailto, $subject, $message, $headers);
}

/******************************************
 *	Subscription Expire in Days	        *
 ******************************************/
function kv_user_subscription_expire_inday($id, $days)
{

	$active_table = get_packactiveinfo_using_id($id);
	//echo $active_table->pack_id; 
	$pack_name = get_packname_using_id($active_table->pack_id);
	$get_user = get_userdata($active_table->wp_user_id);
	$siteurl = trailingslashit(get_option('home'));
	$dashurl = trailingslashit(site_url('subscription'));

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$mailto = $get_user->user_email;

	$subject = sprintf(__('Your ' . $pack_name . ' access subscription is going to expire in %d days on %s', 'kvc'), $days, $blogname);

	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	// Message
	$message  = email_header();
	$message .= sprintf(__('Hi %s,', 'kvc'), $get_user->display_name) . '<br>' . '<br>';
	$message .= sprintf(__('Expiring Pack Name: %s', 'kvc'), $pack_name) . '<br>' . '<br>';
	$message .= sprintf(__('Start Date: %s', 'kvc'), $active_table->date_subscribed) . '<br>' . '<br>';
	$message .= sprintf(__('End Date: %s', 'kvc'), $active_table->end_date) . '<br>' . '<br>';
	$message .= sprintf(__('Your subscription  ' . $pack_name . ' has just ' . $days . ' days. To continue uninterrupted Browsing on %s you need to subscribe to a new Pack.', 'eimams'), $blogname) . '<br>' . '<br>';
	$message .= '<br> <a href="' . $dashurl . '" > ' . $dashurl . '</a> <br>' .  '<br>';

	$message .= sprintf(__('We have few suggestions for you to select the right Pack.', 'eimams'), $blogname) . '<br>' . '<br>';

	$message .= kv_email_footer($mailto);
	wp_mail($mailto, $subject, $message, $headers);
}

/******************************************
 *		Subscription Expire in Posts      *
 ******************************************/
function kv_user_subscription_expire_inpost($id, $posts)
{

	$active_table = get_packactiveinfo_using_id($id);
	//echo $active_table->pack_id; 
	$pack_name = get_packname_using_id($active_table->pack_id);
	$get_user = get_userdata($active_table->wp_user_id);
	$siteurl = trailingslashit(get_option('home'));
	$dashurl = trailingslashit(site_url('subscription'));


	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$mailto = $get_user->user_email;

	$subject = sprintf(__('Your ' . $pack_name . ' subscription will expire in %d Jobs on %s', 'kvc'), $posts, $blogname);

	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	// Message
	$message  = email_header();
	$message .= sprintf(__('Hi %s,', 'kvc'), $get_user->display_name) . '<br>' . '<br>';
	$message .= sprintf(__('Your Pack : %s', 'kvc'), $pack_name) . '<br>' . '<br>';
	$message .= sprintf(__('Start Date: %s', 'kvc'), $active_table->date_subscribed) . '<br>' . '<br>';
	$message .= sprintf(__('Your subscription  ' . $pack_name . ' has just ' . $posts . ' jobs. To continue uninterrupted Browsing on %s you need to subscribe to a new Pack.', 'eimams'), $blogname) . '<br>' . '<br>';
	$message .= '<br> <a href="' . $dashurl . '" > ' . $dashurl . '</a> <br>' .  '<br>';

	$message .= sprintf(__('We have few suggestions for you to select the right Pack.', 'eimams'), $blogname) . '<br>' . '<br>';

	$message .= kv_email_footer($mailto);

	wp_mail($mailto, $subject, $message, $headers);
}


/******************************************
 *		Admin email Function		      *
 ******************************************/
function kv_admin_email()
{
	global $wpdb;
	$notifications_tbl = $wpdb->prefix . 'newsletter';
	if (isset($_GET['ids'])) {
		$to_array = explode("-", $_GET['ids']);
		$result_email_ar = array();
		foreach ($to_array as $single_val) {
			$result_email_ar[] = $wpdb->get_var("SELECT email FROM " . $notifications_tbl  . " WHERE id=" . $single_val);
		}
	}

	if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) &&  $_POST['action'] == "kv_send_mail") {

		if (isset($_POST['receiver_email']))
			$receiver_email_id = esc_attr($_POST['receiver_email']);
		if (isset($_POST['subj']))
			$email_subject = esc_attr($_POST['subj']);

		if (isset($_POST['email_body']))
			$email_body = 	email_header() . nl2br($_POST['email_body']);

		/*$header = 'From:' . get_bloginfo('admin_email').  "\r\n";
	if(isset($_POST['cc']) && filter_var(esc_attr($_POST['cc']), FILTER_VALIDATE_EMAIL) === true) 
		$headers .= 'Cc: ' . esc_attr($_POST['cc']) . "\r\n";
	if(isset($_POST['bcc']) && filter_var($_POST['bcc'], FILTER_VALIDATE_EMAIL) === true) 
		$headers .= 'Bcc: ' . trim($_POST['bcc']) . "\r\n";*/

		/*$header = "From:  Varadha <kvcodes@gmail.com> \r\n";
//Multiple CC can be added, if we need (comma separated);
$header .= "Cc: vkvaradha@gmail.com \r\n";
//Multiple BCC, same as CC above;
$header .= "Bcc: kvvaradhafeb14@gmail.com \r\n"; */

		$header = 'From:' . get_bloginfo('admin_email') .  "\r\n";
		if (isset($_POST['cc']))
			$header .= "Cc: " . esc_attr($_POST['cc']) . "\r\n";
		if (isset($_POST['bcc']))
			$header .= "Bcc: " . esc_attr($_POST['bcc']) . "\r\n";

		if (strpos($receiver_email_id, ','))
			$receiver_email_id = explode(',', $receiver_email_id);

		if (is_array($receiver_email_id)) {
			//print_r($receiver_email_id);
			foreach ($receiver_email_id as $receiver_id) {
				$email_body_final =  $email_body . kv_email_footer($receiver_id);
				$kv_mail_report = wp_mail($receiver_id, $email_subject, $email_body_final, $header);
			}
		} else {
			$email_body .= kv_email_footer($receiver_email_id);
			$kv_mail_report = wp_mail($receiver_email_id, $email_subject, $email_body, $header);
		}
	}
?>
	<form method="POST">
		<table cellpadding="0" border="0" class="form-table">
			<tr>
				<td colspan="2">
					<h2>Compose E-Mail</h2>
				</td>
			</tr>
			<?php if (isset($success) && $success != '') {
				echo '<tr><td colspan="2"  > <h4 style=" border: 1px solid green; background-color:#CFF8E6; padding: 10px; width: 50%; ">' . $success . '</h4> </td> </tr>';
			}
			if (isset($to_array) && $to_array != null) {
				echo '<tr> <td> To: </td> <td> ' . implode(",", $result_email_ar) . '</td><p class="description" id="tagline-description">Use Comma(",") and add more emails with it..</p> </tr>';
			} else { 	?>
				<tr>
					<td> To: </td>
					<td>
						<div id="single_email"> <input type="email" align="left" multiple name="receiver_email" size="80%" value="<?php echo (isset($usr_email) ? $usr_email : ''); ?>"> </div>
						<p class="description" id="tagline-description">Use Comma(",") and add more emails with it.</p>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td> CC: </td>
				<td> <input type="text" align="left" name="cc" size="80%" value="<?php echo (isset($cc) ? $cc : ''); ?>"></td>
			</tr>
			<tr>
				<td> BCC: </td>
				<td> <input type="text" align="left" name="bcc" size="80%" value="<?php echo (isset($bcc) ? $bcc : ''); ?>"></td>
			</tr>
			<tr>
				<td> Subject: </td>
				<td> <input type="text" align="left" name="subj" size="80%" value="<?php echo (isset($subject) ? $subject : ''); ?>"></td>
			</tr>
			<tr>
				<td> Message: </td>
				<td align="left"> <?php $args = array("textarea_name" => "email_body", "textarea_name" => "email_body", "textarea_rows" => "22", "teeny" => true, "media_buttons" => true, "quicktags" => false);
									if (isset($pre_msg))
										wp_editor($pre_msg, "email_body", $args);
									else
										wp_editor('', "email_body", $args);

									?> </td>
			</tr>
			<input type="hidden" name="action" value="kv_send_mail" />
			<tr>
				<td colspan="2" align="center"> <input type="submit" value="Send Message" name="submit" class="button"> </td>
			</tr>
		</table>
	</form>
<?php
}


/*****************************************
 *Test Email with Cron job function
 ****************************************/
function test_email_with_cron_job_fn()
{

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$mailto = 'vkvaradha@gmail.com';
	$subject = sprintf(__('You have received an Email from eimams for cron job  testing  %s', 'kvc'), $blogname);
	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	$message  = email_header();

	// Here we have to write content of applied job email
	$message .= kv_email_footer($mailto);
	// ok let's send the email
	wp_mail($mailto, $subject, $message, $headers);
}
/*****************************************
 *Applied notification  ( Employerl)
 ****************************************/
function employer_notification_new_user_applied($inserted_id)
{
	global $wpdb;
	$applied_jobs_tbl = $wpdb->prefix . "applied_jobs";
	$applied_job = $wpdb->get_row("SELECT * FROM " . $applied_jobs_tbl . " WHERE id=" . $inserted_id . " LIMIT 1");

	//Job Informations
	$job_info    = get_post($applied_job->job_id);
	$job_title = stripslashes($job_info->post_title);
	$job_author = stripslashes(get_the_author_meta('display_name', $job_info->post_author));
	$job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
	$job_status = stripslashes($job_info->post_status);
	$job_slug = stripslashes($job_info->guid);

	//AppliedUser informations
	$job_see_info = kv_get_jobseeker_details($applied_job->job_seeker_id);
	$job_see_user_info = get_userdata($applied_job->job_seeker_id);

	$siteurl = trailingslashit(get_option('home'));
	$dashurl = trailingslashit(get_permalink(site_url('posted-jobs')));

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$mailto = $job_author_email;
	$subject = sprintf(__('You have received an application to your Job Ad  "%s"', 'kvc'), $job_title);
	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	$message  = email_header();

	$location =  get_term_by('id', $job_see_info['location'], 'zone');
	$qualification =  get_term_by('id', $job_see_info['qualification'], 'qualification');
	$category =  get_term_by('id', $job_see_info['category'], 'job_category');
	$madhab =  get_term_by('id', $job_see_info['madhab'], 'madhab');
	$aqeeda =  get_term_by('id', $job_see_info['aqeeda'], 'aqeeda');


	// Here we have to write content of applied job email
	$message .= 'Hi ' . $job_author . ',<br>
		You have received an Application in response to your job posted on eimams, "' . $job_title . '" <br>
		<h5> Application details </h5> 
		<h6> Message</h6> 
		--------------------------------------------------------------<br>
		Name			:	' . $job_see_user_info->display_name . '<br>
		Email			:	' . $job_see_user_info->user_email . '<br>
		Phone			:	' . $job_see_info['phone'] . '<br>
		Category		:	' . $category->name . '<br>
		Qualification	:	' . $qualification->name . '<br>
		Location		:	' . $location->name . '<br>
		Madhab			:	' . $madhab->name . '<br>
		Aqeeda			:	' . $aqeeda->name . '<br>
		-------------------------------------------------------------<br>
		You can manage all your responses from here <a href="' . site_url('applied-resumes') . '" > Applied Resumes </a>
		
		You can View the full details on eimams. Thank you for using eimams and good luck with your Ad. ';

	$message .= kv_email_footer($mailto);
	wp_mail($mailto, $subject, $message, $headers);
}



/*****************************************
 *Comment notification  ( Ticket Reply Mail)
 ****************************************/
function editing_comment_mail_content($msg, $comment_id)
{
	global $posts;
	$comment = get_comment($comment_id);
	$post    = get_post($comment->comment_post_ID);

	$notify_message  = email_header();
	$notify_message  .= sprintf(__('You have a reply for the ticket of  "%s" on eimams '), $post->post_title) . "\r\n";
	/* translators: 1: comment author, 2: author IP, 3: author domain */
	$notify_message  .= "\r\n";

	$notify_message .= sprintf(__('Message: %s'), $comment->comment_content) . "\r\n";

	$notify_message  .= "\r\n";

	$notify_message .= __('You can see all comments on this post here:') . "\r\n";
	$notify_message .= ' <a href="' . site_url('help-and-support') . '?ticket_id=' . $comment->comment_post_ID . '"> Click here to reply </a>' . "\r\n";

	$notify_message  .= kv_email_footer();

	/* translators: 1: blog name, 2: post title */
	$subject = sprintf(__('[%1$s] Comment: "%2$s"'), $blogname, $post->post_title);

	return $notify_message;
}

add_action('comment_notification_text', 'editing_comment_mail_content', 10, 2);


add_action('comment_notification_subject', 'editing_comment_mail_subject', 10, 2);

function editing_comment_mail_subject($subject, $comment_id)
{
	global $posts;
	$comment = get_comment($comment_id);
	$post    = get_post($comment->comment_post_ID);

	$subject = sprintf(__(' You have a reply for the Support of "%s" on eimams '), $post->post_title);
	return $subject;
}


// New Job Posted (owner) - pending
function kv_owner_new_job_pending($post_id)
{

	$job_info = get_post($post_id);

	$job_title = stripslashes($job_info->post_title);
	$job_author = stripslashes(get_the_author_meta('display_name', $job_info->post_author));
	$job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
	$job_status = stripslashes($job_info->post_status);
	$job_slug = stripslashes($job_info->guid);

	$siteurl = site_url('job-view') . '?job_id=' . $post_id;
	$dashurl = trailingslashit(site_url('posted-jobs'));

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$mailto = $job_author_email;
	$subject = sprintf(__('Your Job Submission on %s', 'kvc'), $blogname);
	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	$message  = email_header();

	$message .= sprintf(__('Hi %s,', 'kvc'), $job_author) . '<br>' . '<br>';
	$message .= sprintf(__('Thank you for your recent submission! Your job listing has been submitted for review and will not appear live on our site until it has been approved. Below you will find a summary of your job listing on the %s website.', 'kvc'), $blogname) . '<br>' . '<br>';
	$message .= __('Job Details', 'kvc') . '<br>';
	$message .= __('-----------------') . '<br>';
	$message .= __('Title: ', 'kvc') . '<a href="' . $siteurl . '">' . $job_title . '</a><br>';
	$message .= __('Author: ', 'kvc') . $job_author . '<br>';
	$message .= __('-----------------') . '<br>' . '<br>';
	$message .= __('You may check the status of your job(s) at anytime by logging into the "Posted Jobs" page.', 'kvc') . '<br>';
	$message .= '<br> <a href="' . $dashurl . '" > ' . $dashurl . '</a> <br>' .  '<br>';

	$message .= kv_email_footer($mailto);

	if (wp_mail($mailto, $subject, $message, $headers))
		echo "Job Owner email sending failed";
	else
		echo "Job Owner email sending failed";
}


// when a job's status changes, send the job owner an email
function kv_notify_job_owner_email($new_status, $old_status, $post)
{
	global $wpdb;

	$job_info = get_post($post->ID);

	if ($job_info->post_type == 'job') :

		$job_title = stripslashes($job_info->post_title);
		$job_author_id = $job_info->post_author;
		$job_author = stripslashes(get_the_author_meta('display_name', $job_info->post_author));
		$job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
		$job_status = stripslashes($job_info->post_status);
		$job_slug = stripslashes($job_info->guid);
		$job_view_url = site_url('job-view') . '?job_id=' . $post->ID;
		$mailto = $job_author_email;

		$siteurl = trailingslashit(get_option('home'));
		$dashurl = trailingslashit(site_url('posted-jobs'));

		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

		$message  = email_header();
		$send_mail = "no";
		// if the job has been approved send email to ad owner only if owner is not equal to approver
		// admin approving own jobs or job owner pausing and reactivating ad on his dashboard don't need to send email
		if ($old_status == 'pending' && $new_status == 'publish' && get_current_user_id() != $job_author_id) {
			$send_mail = "yes";
			//$get_alloc_post=kv_subscribe_email_to_reduce_perpost($job_author_id); 
			//echo $get_alloc_post;
			Kv_jobAlert_toSubscribed_jobseekers($post->ID);
			$subject = __('Your Job Has Been Approved', 'kvc');
			$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

			$message .= sprintf(__('Hi %s,', 'kvc'), $job_author) . '<br>' . '<br>';
			$message .= sprintf(__('Your job listing, "%s" has been approved and is now live on our site.', 'kvc'), $job_title) . '<br>' . '<br>';

			$message .= __('You can view your job by clicking on the following link:', 'kvc') . '<br>';
			$message .=  '<br>' . '<a href="' . $job_view_url . '" >' . $job_view_url . '</a><br>' . '<br>';

			// if the job has expired, send an email to the job owner only if owner is not equal to approver. This will only trigger if the 30 day option is hide
		} elseif ($old_status == 'publish' && $new_status == 'expired') {
			$send_mail = "yes";
			$subject = __('Your Job Has Expired', 'kvc');
			$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

			$message .= sprintf(__('Hi %s,', 'kvc'), $job_author) . '<br>' . '<br>';
			$message .= sprintf(__('Your job listing, "%s" has expired.', 'kvc'), $job_title) . '<br>' . '<br>';

			$message .= __('If you would like to relist your job, please visit the "Posted Jobs" page and click the "Edit it" link.', 'kvc') . '<br>';
			$message .=  '<br>' . '<a href="' . $job_view_url . '" >' . $job_view_url . '</a><br>' . '<br>';
		}

		$message .= kv_email_footer($mailto);
		if ($send_mail == "yes") {
			wp_mail($mailto, $subject, $message, $headers);
		}

	//elseif ($job_info->post_type=='speak_art_request') :

	endif;
}

add_action('transition_post_status', 'kv_notify_job_owner_email', 10, 3);


// Edited Jobs that require moderation
function kv_admin_mail_edited_job_pending($post_id)
{

	$job_info = get_post($post_id);

	$job_title = stripslashes($job_info->post_title);
	$job_author = stripslashes(get_the_author_meta('display_name', $job_info->post_author));
	$job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
	$job_status = stripslashes($job_info->post_status);
	$job_slug = stripslashes($job_info->guid);
	$adminurl = admin_url("post.php?action=edit&post=$post_id");

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$mailto = get_option('admin_email');
	$headers = 'From: ' . __('Eimams Admin', 'kvc') . ' <admin@eimams.com>' . '<br>';
	$subject = __('Edited Job Pending Approval', 'kvc') . ' [' . $blogname . ']';

	// Message
	$message  = email_header();
	$message .= __('Dear Admin,', 'kvc') . '<br>' . '<br>';
	$message .= sprintf(__('The following job listing has just been edited on your %s website.', 'kvc'), $blogname) . '<br>' . '<br>';
	$message .= __('Job Details', 'kvc') . '<br>';
	$message .= __('-----------------') . '<br>';
	$message .= __('Title: ', 'kvc') . $job_title . '<br>';
	$message .= __('Author: ', 'kvc') . $job_author . '<br>';
	$message .= __('-----------------') . '<br>' . '<br>';
	$message .= __('Preview Job: ', 'kvc') . $job_slug . '<br>';
	$message .= sprintf(__('Edit Job: %s', 'kvc'), $adminurl) . '<br>' . '<br>' . '<br>';

	$message .= kv_email_footer($mailto);

	wp_mail($mailto, $subject, $message, $headers);
}

// Jobs that require moderation (non-paid)
function kv_admin_mail_new_job_pending($post_id)
{

	$job_info = get_post($post_id);

	$job_title = stripslashes($job_info->post_title);
	$job_author = stripslashes(get_the_author_meta('user_login', $job_info->post_author));
	$job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
	$job_status = stripslashes($job_info->post_status);
	$job_slug = stripslashes($job_info->guid);
	$adminurl = admin_url("post.php?action=edit&post=$post_id");

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	// $mailto = get_option('admin_email');
	$mailto = array("admin@eimams.com", "info@eimams.com");
	$headers = 'From:' . get_bloginfo('admin_email') .  "\r\n";
	$post_type = get_post_type($post_id);
	$message  = email_header();
	//$message .= $post_type; 
	if ($post_type == 'job') {
		$subject = __('New Job Pending Approval', 'kvc') . ' [' . $blogname . ']';
		// Message	
		$message .= __('Dear Admin,', 'kvc') . '<br>' . '<br>';
		$message .= sprintf(__('The following job listing has just been submitted on your %s website.', 'kvc'), $blogname) . '<br>' . '<br>';
		$message .= __('Job Details', 'kvc') . '<br>';
		$message .= __('-----------------') . '<br>';
		$message .= __('Title: ', 'kvc') . $job_title . '<br>';
		$message .= __('Author: ', 'kvc') . $job_author . '<br>';
		$message .= __('-----------------') . '<br>' . '<br>';
		$message .= __('Preview Job: ', 'kvc') . $job_slug . '<br>';
		$message .= sprintf(__('Edit Job: %s', 'kvc'), $adminurl) . '<br>' . '<br>' . '<br>';
	} elseif ($post_type == 'speak_art_request') {
		$subject = __('New Request Pending Approval', 'kvc') . ' [' . $blogname . ']';
		// Message	
		$message .= __('Dear Admin,', 'kvc') . '<br>' . '<br>';
		$message .= sprintf(__('The following Request listing has just been submitted on your %s website.', 'kvc'), $blogname) . '<br>' . '<br>';
		$message .= __('Request Details', 'kvc') . '<br>';
		$message .= __('-----------------') . '<br>';
		$message .= __('Title: ', 'kvc') . $job_title . '<br>';
		//$message .= __('Author: ', 'kvc') . $job_author . '<br>';
		$message .= __('-----------------') . '<br>' . '<br>';
		$message .= __('Preview Request: ', 'kvc') . $job_slug . '<br>';
		$message .= sprintf(__('Edit Request: %s', 'kvc'), $adminurl) . '<br>' . '<br>' . '<br>';
	}
	$message .= kv_email_footer($mailto);
	wp_mail($mailto, $subject, $message, $headers);
}

function kv_forgot_password_reset_email($user_input)
{
	global $wpdb;
	$user_data = get_user_by('email', $user_input);
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;

	$key = $wpdb->get_var("SELECT user_activation_key FROM $wpdb->users WHERE user_login ='" . $user_login . "'");
	if (empty($key)) {
		//generate reset key
		$key = wp_generate_password(20, false);
		$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
	}
	$message  = email_header();

	$message .= __('Someone requested that the password be reset for the following account:') . "<br><br><br>";
	$message .= get_option('siteurl') . "<br><br>";
	$message .= sprintf(__('Username: %s'), $user_login) . "<br><br><br>";
	$message .= __('If this was a error, just ignore this email as no action will be taken.') . "<br><br>";
	$message .= __('To reset your password, visit the following address:') . "<br><br>";
	$message .= '<a href="' . tg_validate_url() . "action=reset_pwd&key=$key&login=" . rawurlencode($user_login) . '" > ' . tg_validate_url() . "action=reset_pwd&key=$key&login=" . rawurlencode($user_login) . "</a><br><br>";

	$message .= kv_email_footer($mailto);
	if ($message && !wp_mail($user_email, 'Password Reset Request', $message)) {
		$msg = false;
	} else $msg = true;

	return $msg;
}


function kv_rest_setting_password($reset_key, $user_login, $user_email, $ID)
{

	$new_password = wp_generate_password(7, false); //you can change the number 7 to whatever length needed for the new password
	wp_set_password($new_password, $ID);
	//mailing the reset details to the user
	$message  = email_header();
	$message .= __('Your new password for the account at:') . "<br><br>";
	$message .= get_bloginfo('name') . "<br><br>";
	$message .= sprintf(__('Username: %s'), $user_login) . "<br><br>";
	$message .= sprintf(__('Password: %s'), $new_password) . "<br><br>";
	$message .= __('You can now login with your new password at: ') . '<a href="' . get_option('siteurl') . "/login" . '" >' . get_option('siteurl') . "/login" . "</a> <br><br>";

	$message .= kv_email_footer($mailto);


	if ($message && !wp_mail($user_email, 'Your New Password to login into eimams', $message)) {
		$msg = false;
	} else {
		$msg = true;
		$redirect_to = get_site_url() . "/login?action=reset_success";
		wp_safe_redirect($redirect_to);
		exit();
	}


	return $msg;
}
//
// New Subscription Posted (owner) - pending
add_action('user_subscription_started', 'kv_owner_new_subscription');
function kv_owner_new_subscription($sub_active_id)
{
	global  $wpdb;
	$active_tbl_array = get_packactiveinfo_using_id($sub_active_id);
	$pack_tbl_array = get_all_packdetails_using_id($active_tbl_array->pack_id);
	$pack_name_from_id = get_packname_using_id($active_tbl_array->pack_id);
	$user_details = get_userdata($active_tbl_array->wp_user_id);
	//$pack_name=get_packname_using_id($pack_id);
	$pack_duration = $pack_tbl_array['duration'];
	$pack_period = $pack_tbl_array['period'];
	$pack_price = $pack_tbl_array['price'];

	$siteurl = trailingslashit(get_option('home'));
	$dashurl = trailingslashit(site_url('subscription'));

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$mailto = $user_details->user_email;
	$subject = sprintf(__('Your Subscription on %s With Details', 'kvc'), $blogname);
	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';
	// Message
	$message  = email_header();
	$message .= sprintf(__('Hi %s,', 'kvc'), $user_details->display_name) . '<br>' . '<br>';
	$message .= sprintf(__('Your access subscription has just been activated. You can now browse on %s.', 'kvc'), $blogname) . '<br>' . '<br>';
	$message .= __('Pack Name: ', 'kvc') . $pack_name_from_id . '<br>';

	$message .= __('Pack Price: ', 'kvc') . $pack_price . '<br>';
	if ($active_tbl_array->per_post == 0) {
		$message .= __('Pack Duration: ', 'kvc') . $pack_duration . '  ' . $pack_period . '<br>';
		$message .= __('Pack Start date: ', 'kvc') . $active_tbl_array->start_date . '<br>';
		$message .= __('Pack End date: ', 'kvc') . $active_tbl_array->end_date . '<br>';
	} else
		$message .= __('Per Post: ', 'kvc') . $active_tbl_array->per_post . '<br>';

	$message .= __('-----------------') . '<br>' . '<br>';
	$message .= $dashurl . '<br>' . '<br>' . '<br>' . '<br>';

	$message .= kv_email_footer($mailto);

	// ok let's send the email


	wp_mail($mailto, $subject, $message, $headers);


	//for admin (add new subcription notification mail)

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$mailto_admin = get_option('admin_email');
	$headers = 'From: ' . __(' Admin', 'kvc') . ' <admin@eimams.com>' . '<br>';
	$subject = __('New  Subscription', 'kvc') . ' [' . $blogname . ']';

	// Message
	$message  = email_header();
	$message .= __('Dear Admin,', 'kvc') . '<br>' . '<br>';
	$message .= sprintf(__('The following user has just Purchased a New Subscription to access your %s website.', 'kvc'), $blogname) . '<br>' . '<br>';
	$message .= __('User Details', 'kvc') . '<br>';
	$message .= __('-----------------------------------------------') . '<br>';
	$message .= __('User Name: ', 'kvc') . $user_details->display_name . '<br>';
	$message .= __('User ID: ', 'kvc') . $user_details->ID . '<br>';


	$message .= __('Subscription Details', 'kvc') . '<br>';
	$message .= __('-----------------------------------------------') . '<br>';
	$message .= __('Pack Name: ', 'kvc') . $pack_name_from_id . '<br>';
	$message .= __('Pack Price: ', 'kvc') . $pack_price . '<br>';
	if ($active_tbl_array->per_post == 0) {
		$message .= __('Pack Duration: ', 'kvc') . $pack_duration . '  ' . $pack_period . '<br>';
		$message .= __('Pack Start date: ', 'kvc') . $active_tbl_array->start_date . '<br>';
		$message .= __('Pack End date: ', 'kvc') . $active_tbl_array->end_date . '<br>';
	} else
		$message .= __('Per Post: ', 'kvc') . $active_tbl_array->per_post . '<br>';

	$message .= __('Status: ', 'kvc') . $active_tbl_array->status . '<br>';
	$message .= __('-----------------------------------------------') . '<br>' . '<br>';
	$message .= $dashurl . '<br>' . '<br>' . '<br>' . '<br>';

	$message .= kv_email_footer($mailto_admin);
	// ok let's send the email

	wp_mail($mailto_admin, $subject, $message, $headers);
}


// Job will expire soon
function kv_owner_job_expiring_soon($post_id, $days_remaining)
{

	$job_info = get_post($post_id);

	$days_text = '';

	if ($days_remaining == 1) $days_text = '1' . __(' day', 'kvc');
	else $days_text = $days_remaining . __(' days', 'kvc');

	$job_title = stripslashes($job_info->post_title);
	$job_author = stripslashes(get_the_author_meta('user_login', $job_info->post_author));
	$job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
	$job_status = stripslashes($job_info->post_status);
	$job_slug = stripslashes($job_info->guid);

	$siteurl = trailingslashit(get_option('home'));
	$dashurl = trailingslashit(get_permalink(get_option('kv_dashboard_page_id')));

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$mailto_user = $job_author_email;
	$subject = sprintf(__('Your Job Submission on %s expires in %s', 'kvc'), $blogname, $days_text);
	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	// Message
	$message  = email_header();
	$message .= sprintf(__('Hi %s,', 'kvc'), $job_author) . '<br>' . '<br>';
	$message .= sprintf(__('Your job listing is set to expire in %s', 'kvc'), $days_text) . '<br>' . '<br>';
	$message .= __('Job Details', 'kvc') . '<br>';
	$message .= __('-----------------') . '<br>';
	$message .= __('Title: ', 'kvc') . $job_title . '<br>';
	$message .= __('Author: ', 'kvc') . $job_author . '<br>';
	$message .= __('-----------------') . '<br>' . '<br>';
	$message .= __('You may check the status of your job(s) at anytime by logging into the "My Jobs" page.', 'kvc') . '<br>';
	$message .= $dashurl . '<br>' . '<br>' . '<br>' . '<br>';

	$message .= $kv_email_footer($mailto);
	// ok let's send the email

	wp_mail($mailto, $subject, $message, $headers);
	/*
	// expire soon notification for admin
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $mailto_admin = get_option('admin_email');
    $headers = 'From: '. __('Admin', 'kvc') .' <admin@eimams.com>' . '<br>';
    $subject = sprintf(__('Your Subscription Submission on %s expires in %s','kvc'), $blogname, $days_text);

    // Message
    $message  = __('Dear Admin,', 'kvc') . '<br>' . '<br>';
    $message .= sprintf(__('Your Subscription is set to expire in %s', 'kvc'), $days_text) . '<br>' . '<br>';
	$message .= __('Subscription Details', 'kvc') . '<br>'; 
    $message .= __('-----------------') . '<br>';
     $message .= __('-----------------') . '<br>' . '<br>';
    $message .= $dashurl . '<br>' . '<br>' . '<br>' . '<br>';
    $message .= __('Regards,', 'kvc') . '<br>' . '<br>';
    $message .= sprintf(__('Your %s Team', 'kvc'), $blogname) . '<br>';
    $message .= $siteurl . '<br>' . '<br>' . '<br>' . '<br>';

   $message  = email_header();

	$message .= __('Regards,', 'kvc') . '<br>' . '<br>';
	$message .= sprintf(__('Your %s Team', 'kvc'), $blogname) . '<br>';
	$message .= $siteurl . '<br>' . '<br>' . '<br>' . '<br>';
	$message .= email_footer(); 
			// ok let's send the email
			
	wp_mail($mailto, $subject, $message, $headers);
    */
}


/****************************************************
 ** 		Email for the payment notification 		*
 *****************************************************/

function kv_payment_status_notification_mail($wp_user_id, $payer_email, $payer_id, $txn_id, $payment_status, $fname, $lname, $payment_gross)
{

	$user_details = get_userdata($wp_user_id);

	$mailto = $user_details->user_email;

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$subject = sprintf(__('Your Payment has been received ', 'kvc'));

	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	$message  = email_header();

	// Here we have to write content of applied job email
	$message .= 'Hi ' . $user_details->display_name . ',<br>
		Here is your transaction details<br>		
		<h6> Details</h6> 
		--------------------------------------------------------------<br>
		Name			:	' . $fanme . '  ' . $lname . '<br>
		Email			:	' . $payer_email . '<br>
		Payer ID		:	' . $payer_id . '<br>
		Transaction ID	:	' . $txn_id . '<br>
		Payment Status	:	' . $payment_status . '<br>
		Amount			:	' . $payment_gross . '<br>
		-------------------------------------------------------------<br>
		You can manage all your subcriptions from here <a href="' . site_url('subscription') . '" > Subscriptions </a>
		
		You can view the full details on eimams. Thank you for using eimams and good luck with your Ad. ';

	$message .= kv_email_footer($mailto);
	wp_mail($mailto, $subject, $message, $headers);
}


function kv_notify_ticket_to_admin_email($post_id)
{
	global $wpdb;

	$ticket = get_post($post_id);

	$job_title = stripslashes($ticket->post_title);
	$job_author_id = $ticket->post_author;
	$job_author = stripslashes(get_the_author_meta('display_name', $ticket->post_author));
	$job_author_email = stripslashes(get_the_author_meta('user_email', $ticket->post_author));
	$job_status = stripslashes($ticket->post_status);
	$job_slug = stripslashes($ticket->guid);
	$job_view_url = site_url('job-view') . '?ticket_id=' . $post_id;
	//$mailto = 'admin@eimams.com';
	$mailto = 'kvvaradha@gmail.com';

	$dashurl = admin_url() . 'edit.php?post_status=pending&post_type=tickets';

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$message  = email_header();

	$subject = __('A New Ticket Received', 'kvc');
	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	$message .= sprintf(__('Hi Admin,', 'kvc')) . '<br>' . '<br>';
	$message .= sprintf(__('You have a ticket, you need to support them by clicking here. "%s" has been approved and is now live on our site.', 'kvc'), $job_title) . '<br>' . '<br>';

	$message .=  '<br>' . '<a href="' . $dashurl . '" > Check all your Tickets</a><br>' . '<br>';

	$message .= kv_email_footer($mailto);

	wp_mail($mailto, $subject, $message, $headers);
}

function kv_userbackend_user_deactivation($user_id)
{
	$users_details = get_userdata($user_id);
	$mailto = $users_details->user_email;
	$message  = email_header();

	$dashurl = trailingslashit(site_url('profile-update'));

	$subject = __('Your Profile Deactivated', 'kvc');
	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	$message .= sprintf(__('Hi %s,', 'kvc'),  $user_details->display_name) . '<br>' . '<br>';
	$message .= sprintf(__('Your profile has been deactivated as requested by you. You can activate it any time by logging in to the dashboard. "%s" will wait for your reactivation.', 'kvc'), $job_title) . '<br>' . '<br>';

	$message .=  '<br>' . '<a href="' . $dashurl . '" > Click Here </a> to activate your profile again. <br>' . '<br>';

	$message .= kv_email_footer($mailto);

	wp_mail($mailto, $subject, $message, $headers);
}

function kv_userbackend_user_activation($user_id)
{
	$users_details = get_userdata($user_id);
	$mailto = $users_details->user_email;
	$message  = email_header();

	$dashurl = trailingslashit(site_url('profile-update'));

	$subject = __('Your Profile Activated', 'kvc');
	$headers = 'From: ' . sprintf(__('%s Admin', 'kvc'), $blogname) . ' <admin@eimams.com>' . '<br>';

	$message .= sprintf(__('Hi %s,', 'kvc'),  $user_details->display_name) . '<br>' . '<br>';
	$message .= sprintf(__('Your profile has been re-activated as per your request. You can deactivate it any time by logging in to dashboard.', 'kvc')) . '<br>' . '<br>';

	$message .=  '<br>' . '<a href="' . $dashurl . '" > Click Here </a> to activate your profile again. <br>' . '<br>';

	$message .= kv_email_footer($mailto);

	wp_mail($mailto, $subject, $message, $headers);
}

?>