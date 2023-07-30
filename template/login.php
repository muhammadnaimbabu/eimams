<?php

/**
 * Template Name:  Login
 */
$rand_captcha = rand(0, 9);


global $captcha_arr;

$captcha_arr = array(
	0 => array('2-1', '1'),
	1 => array('5-2', '3'),
	2 => array('7-4', '3'),
	3 => array('2+3', '5'),
	4 => array('9-3', '6'),
	5 => array('2+7', '9'),
	6 => array('3+4', '7'),
	7 => array('6+1', '7'),
	8 => array('8-3', '5'),
	9 => array('5-1', '4')
);


$theme_root = dirname(__FILE__);
require_once($theme_root . "/../library/user-backend-main.php");

if (isset($_GET['verify']) && isset($_GET['usr_ID'])) {
	$verify_key = $_GET['verify'];
	$user_i = $_GET['usr_ID'];
	$users = get_user_by('login', $user_i);
	$user_id = $users->ID;
	$verfiy_string = get_user_meta($user_id, 'verification', true);
	if ($verfiy_string == $verify_key) {
		update_user_meta($user_id, 'verification', 'yes');
		if (is_user_logged_in()) {
			$redirect_to = get_site_url() . "/dashboard?verified_email=yes";
		} else
			$redirect_to = get_site_url() . "/login?verified_email=yes";
		user_verified_email($user_id);
		wp_safe_redirect($redirect_to);
		exit();
	} else exit('Not a Valid Key.');
}

// success email validation
if (isset($_GET['resend_validation'])) {
	global $current_user;
	wp_get_current_user();
	get_header();
	kv_Resend_validation_mail($current_user->ID);
	echo '<div class="row">
			<div class="success-email-validation">
				Check your registered E-mail we have successfully sent Validation mail Again
			</div>
		</div>';
	get_footer();
	exit(0);
}


if (!is_user_logged_in()) {
	/** Login checking of emp and jobseeker
	 * Modified by Naim
	 * === Added Google captcha =====
	 */
	if (isset($_POST['login_Sbumit'])) {

		$secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
		$response = $_POST['g-recaptcha-response'];
		$remoteip = $_SERVER['REMOTE_ADDR'];
		$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
		$data = file_get_contents($url);
		$row = json_decode($data, true);


		if ($row['success'] == 'true') {
			$creds                  = array();
			$creds['user_login']    = stripslashes(trim($_POST['userName']));
			$creds['user_password'] = stripslashes(trim($_POST['passWord']));
			$redirect_to  =  trim($_POST['redirect_to']);
			$creds['remember']      = isset($_POST['rememberMe']) ? sanitize_text_field($_POST['rememberMe']) : '';
			//$redirect_to            = esc_url_raw( $_POST['redirect_to'] );

			$secure_cookie          = null;

			if ($redirect_to == '' || $redirect_to == NULL || $redirect_to == site_url('login') . '/?login_attempt=1')
				$redirect_to = get_site_url() . '/dashboard/';
			//echo $creds['remember'] .'___  '. $redirect_to;
			//exit;
			if (!force_ssl_admin()) {
				$user = is_email($creds['user_login']) ? get_user_by('email', $creds['user_login']) : get_user_by('login', sanitize_user($creds['user_login']));

				if ($user && get_user_option('use_ssl', $user->ID)) {
					$secure_cookie = true;
					force_ssl_admin(true);
				}
			}

			if (force_ssl_admin()) {
				$secure_cookie = true;
			}

			if (is_null($secure_cookie)) {
				$secure_cookie = false;
			}

			$user = wp_signon($creds, $secure_cookie);

			if ($secure_cookie && strstr($redirect_to, 'wp-admin')) {
				$redirect_to = str_replace('http:', 'https:', $redirect_to);
			}


			$employer_signup_url = site_url() . '/employer-sign-up';
			$jobseeker_signup_url = site_url() . '/jobseeker-sign-up';
			if (!is_wp_error($user)) {

				$user = new WP_User($user);
				$role = wp_sprintf_l('%l', $user->roles);
				if (site_url() == $redirect_to && $role == 'administrator')
					$redirect_urll = admin_url();
				elseif (site_url() == $redirect_to)
					$redirect_urll = site_url() . '/dashboard';
				elseif ($redirect_to == $employer_signup_url) {
					if ($role == 'administrator')
						$redirect_urll = admin_url();
					else
						$redirect_url1 = site_url() . '/dashboard';
				} elseif ($redirect_to == $jobseeker_signup_url) {
					if ($role == 'administrator')
						$redirect_urll = admin_url();
					else
						$redirect_url1 = site_url() . '/dashboard';
				} else
					$redirect_urll = $redirect_to;

				wp_safe_redirect($redirect_urll);
			} else {
				if ($user->errors) {
					$errors['invalid_user'] = __('<strong>ERROR</strong>: Invalid user or password.');
				} else {
					$errors['invalid_user_credentials'] = __('Please enter your username and password to login.', 'kvcodes');
				}
			}
		} else {
			echo "<script>alert('Please fill the captcha first.')</script>";
		}
	}


	/**
	 * Forget password button
	 */

	if (isset($_POST['forgot_pass_Sbumit'])) {
		if (isset($_POST['emailToreceive']) && empty($_POST['emailToreceive']))
			$errors['userName'] = __("<strong>ERROR</strong>: Username Shouldn't be empty.");
		else {
			$emailToreceive = $_POST['emailToreceive'];
			$user_input = esc_sql(trim($emailToreceive));

			if (strpos($user_input, '@')) {
				$user_data = get_user_by('email', $user_input);
				if (empty($user_data)) {
					$errors['invalid_email'] =  'Invalid E-mail address!';
				}
			} else {
				$user_data = get_user_by('email', $user_input);
				if (empty($user_data)) {
					$errors['invalid_usename'] = 'Invalid Username!';
				}
			}
			if (empty($errors)) {
				if (kv_forgot_password_reset_email($user_input)) {
					$success['reset_email'] = "We have just sent you an email with Password reset instructions.";
				} else {
					$errors['emailError'] =  "Email failed to send for some unknown reason.";
				}
				//emailing password change request details to the user
			}
		}
	}
	if (isset($_GET['action']) && $_GET['action'] == "reset_success") {
		$success['reset_success'] =  'You password has been changed.Check your email to login with your <strong>new password</strong>!';
	}

	// kv_login_header();

	if (isset($_GET['key']) && $_GET['action'] == "reset_pwd") {
		$reset_key = $_GET['key'];
		$user_login = $_GET['login'];
		$user_data = $wpdb->get_row("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = '" . $reset_key . "' AND user_login = '" . $user_login . "'");
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		if (!empty($reset_key) && !empty($user_data)) {
			if (kv_rest_setting_password($reset_key, $user_login, $user_email, $user_data->ID)) {
				$errors['emailError'] = "Email failed to sent for some unknown reason";
			} else {
				$redirect_to = get_site_url() . "/login?action=reset_success";
				wp_safe_redirect($redirect_to);
				exit();
			}
		} else exit('Not a Valid Key.');
	} else { ?>

		<!--   ##################   main content template start here  -->

		<?php get_header();  ?>
		<div class="bootstrap-wrapper">
			<div id="login-form">

				<div class="col-md-12">
					<?php
					if (isset($_GET['login_attempt'])) {
						$errors['invalid_user'] = __(

							'<div>
						 <span class="close">×</span>
						<strong>Error! </strong> Invalid user or password.
					</div>'
						);
					}

					if (!empty($errors)) {
						echo '<div class="error" style="max-width:600px;" ><ul>';
						echo '<li>' . limit_login_get_message() . '</li>';
						foreach ($errors as $err)
							echo '<li>' . $err . '</li>';
						echo '</ul></div>';
					}
					if (!empty($success)) {
						echo '<div class="success" ><ul>';
						foreach ($success as $suc)
							echo '<li>' . $suc . '</li>';
						echo '</ul></div>';
					}

					if (isset($_GET['verified_email']) && $_GET['verified_email'] == 'yes')
						echo '<div class="success" style="margin:20px auto; text-align:center;padding-left:0" > Congrats, Your email Verified Successfully ! </div> ';

					?>

					<br />

				</div>
				<div class="login">
					<section class="container">
						<form role="form" id="myForm" action="<?php echo get_the_permalink() ?>" method="post">

							<div class="login__contents">
								<h2 class="section__heading text-center form__heading">Welcome Back</h2>
								<p class="from__para text-center">Login to access your account</p>

								<div class="form__input">
									<input type="text" name="userName" id="" placeholder="Username" required>
								</div>

								<div class="form__input">
									<input type="password" name="passWord" id="" placeholder="Your Password" required>
								</div>


								<div class="flex__items">
									<label class="checkbox-inline">
										<input type="checkbox" name="rememberMe" value="true" /> Remember me
									</label>
									<span class="pull-right">
										<a href="" class="forget-password" id="forgot-password">Forget password ? </a>
									</span>
								</div>

								<div class="form-group" style="margin-top:10px;">
									<div id="">
										<div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
										<div>
											<label>
											</label>
										</div>
									</div>
								</div>


								<?php $referr =  wp_get_referer();
								$site_urll = site_url();
								$login_url = $site_urll . '/login/?login_attempt=1';

								if ($referr == $login_url || $referr == '' || $referr == null) {
									$referrer =  get_site_url();
								} else {
									$referrer =  wp_get_referer();
								}  ?>
								<input type="hidden" name="redirect_to" value="<?php echo $referrer; ?>">
								<input type="hidden" name="login_Sbumit" value="kv_yes">


								<div class="flex__items" style="margin-top:10px;">
									<input type="submit" value="Login Now" class="login-btn2">
									<span class="forget-password"> Not register ? <a class="forget-password" id="popup" href="">Click here </a> </span>
								</div>
							</div>

						</form>
					</section>
				</div>

			</div>
		</div>

		<script>
			window.onload = function() {
				var recaptcha = document.forms["myForm"]["g-recaptcha-response"];
				recaptcha.required = true;
				recaptcha.oninvalid = function(e) {
					// Show alert
					alert("Please complete the captcha first");
				}
			}
		</script>

		<div class="container" id="forgot_password">
			<div class="login__contents">
				<div class="panel-heading">
					<h2 class="section__heading form__heading">Forget Password?</h2>
					<p class="">Enter your email to receive new Password</p>

				</div>
				<div class="panel-body">
					<form role="form" action="<?php echo "http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']; ?>" method="post">
						<div class="form__input">
							<input type="text" name="emailToreceive" placeholder="Enter Your Email" required />
						</div>

						<div class="form-group">
							<span class="pull-right">
								<a href class="forget-password mb-2" id="have_id"> Back to Login? </a>
							</span>
						</div>
						<input type="hidden" name="forgot_pass_Sbumit" value="kv_yes">
						<input type="submit" class="login-btn2" value="Get Password">
					</form>
				</div>
			</div>
		</div>


		<style>
			#TopSignIn,
			#signUp-header-button {
				display: none;
			}

			#captcha {
				background: #f5e7e7;
				height: auto;
				padding: 10px
			}

			.captcha-addition {
				background: #fff;
				border: 1px solid #ccc;
				padding: 4px 13px;
				width: 50px;
				float: left;
			}

			.refresh-button {
				background: #fff;
				border: 1px solid #ccc;
				padding: 5px 6px 3px 7px;
				width: 30px;
				float: left;
				cursor: pointer;
				margin-left: 2px
			}

			.equal-sign {
				padding: 4px 5px;
				width: 20px;
				float: left;
			}

			.captcha-result {
				/*background:#fff; border:1px solid #ccc; padding:4px 13px; */
				width: 40px;
				float: left;
				min-height: 32px;
			}

			#captcha_value {
				border-radius: 0;
			}
		</style>
		<script>
			$('#kv_refresh').on('click', function(e) {
				//alert(ajax_url);
				e.preventDefault();
				//user_login = $("input[name=userName]").val();
				//user_pass = $("input[name=passWord]").val();
				//alert(requesting_url);
				$.ajax({
					type: "POST",
					url: ajax_url, // you need to provide ajax url here
					data: {
						action: "refresh_captcha"
					},
					success: function(sus_data) {
						var n = sus_data.indexOf(",");
						var res = sus_data.substring(0, n);
						var res_count = sus_data.substring(n + 1);
						//alert(res+'---'+sus_data+'----'+res_count);
						$("#captcha_val").val(res_count);
						$(".captcha-addition").html(res);
					},
					error: function(errorThrown) {
						//alert(errorThrown);
						console.log(errorThrown);
					}
				});
			});

			$('#popup').on('click', function(n) {
				n.preventDefault();

			})
		</script>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<?php get_footer();  ?>

<?php }
	kv_login_footer();
} else {
	if (wp_get_referer())
		wp_safe_redirect(wp_get_referer());
	else {
		$kv_current_role = kv_get_current_user_role();
		if ($kv_current_role == 'administrator')
			wp_safe_redirect(admin_url());
		else
			wp_safe_redirect(get_home_url());
	}
	exit;
} ?>