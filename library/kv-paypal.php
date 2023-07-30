<?php
/**
 * KvPayPal is the class that handles ALL of the plugin functionality,
 * and helps us avoid name collisions
 */
 
function hashCall ($args) {
	$KvPayPal = KvPayPal::getInstance();
	return $KvPayPal->hashCall($args);
}

function paypalFramework_legacy_function() {
	if ( !function_exists('hash_call') ) {

		function hash_call($methodName, $nvpStr) {
			_deprecated_function(__FUNCTION__, '0.1', 'KvPayPal::hashCall()');
			$nvpStr = wp_parse_args( $nvpStr );
			$nvpStr['METHOD'] = $methodName;
			$nvpStr = array_map('urldecode', $nvpStr);
			$KvPayPal = KvPayPal::getInstance();
			return $KvPayPal->hashCall($nvpStr);
		}
	}
}

class KvPayPal {
	
	private $_settings;
	
	static $instance = false;
	
	private $_optionsName = 'kvcodes-paypal';
	
	private $_optionsGroup = 'KvPayPal';
	
	private $_endpoint = array(
		'sandbox'	=> 'https://api-3t.sandbox.paypal.com/nvp',
		'live'		=> 'https://api-3t.paypal.com/nvp'
	);
	
	private $_url = array(
		'sandbox'	=> 'https://www.sandbox.paypal.com/webscr',
		'live'		=> 'https://www.paypal.com/webscr'
	);

	private $_listener_query_var		= 'paypal_listener';

	private $_listener_query_var_value	= 'IPN';

	private $_currencies = array();
	
	private function __construct() {
		$this->_getSettings();
		$this->_fixDebugEmails();

		$this->_currencies = array(
			'AUD'	=> __( 'Australian Dollar', 'kvcodes' ),
			'CAD'	=> __( 'Canadian Dollar', 'kvcodes' ),
			'CZK'	=> __( 'Czech Koruna', 'kvcodes' ),
			'DKK'	=> __( 'Danish Krone', 'kvcodes' ),
			'EUR'	=> __( 'Euro', 'kvcodes' ),
			'HKD'	=> __( 'Hong Kong Dollar', 'kvcodes' ),
			'HUF'	=> __( 'Hungarian Forint', 'kvcodes' ),
			'ILS'	=> __( 'Israeli New Sheqel', 'kvcodes' ),
			'JPY'	=> __( 'Japanese Yen', 'kvcodes' ),
			'MXN'	=> __( 'Mexican Peso', 'kvcodes' ),
			'NOK'	=> __( 'Norwegian Krone', 'kvcodes' ),
			'NZD'	=> __( 'New Zealand Dollar', 'kvcodes' ),
			'PLN'	=> __( 'Polish Zloty', 'kvcodes' ),
			'GBP'	=> __( 'Pound Sterling', 'kvcodes' ),
			'SGD'	=> __( 'Singapore Dollar', 'kvcodes' ),
			'SEK'	=> __( 'Swedish Krona', 'kvcodes' ),
			'CHF'	=> __( 'Swiss Franc', 'kvcodes' ),
			'USD'	=> __( 'U.S. Dollar', 'kvcodes' )
		);

		add_action( 'admin_init', array($this,'registerOptions') );
		add_action( 'admin_menu', array($this,'adminMenu') );
		add_action( 'wp_ajax_nopriv_paypal_listener', array( $this, 'listener' ) );
		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
		add_filter( 'query_vars', array( $this, 'addPaypalListenerVar' ) );
		
		if ( 'on' == $this->_settings['legacy_support'] )
			add_action( 'init', 'paypalFramework_legacy_function' );
	}

	public static function getInstance() {
		if ( !self::$instance )
			self::$instance = new self;
		return self::$instance;
	}

	private function _getSettings() {
		if (empty($this->_settings))
			$this->_settings = get_option( $this->_optionsName );
		if ( !is_array( $this->_settings ) )
			$this->_settings = array();

		$defaults = array(
			'sandbox'			=> 'sandbox',
			'username-sandbox'	=> '',
			'password-sandbox'	=> '',
			'signature-sandbox'	=> '',
			'username-email'	=> '',
			'username-live'		=> '',
			'password-live'		=> '',
			'signature-live'	=> '',
			'version'			=> '58.0',
			'currency'			=> 'USD',
			'debugging'			=> 'on',
			'debugging_email'	=> '',
			'legacy_support'	=> 'off',
		);
		$this->_settings = wp_parse_args( $this->_settings, $defaults );
	}

	public function getSetting( $settingName, $default = false ) {
		if (empty($this->_settings))
			$this->_getSettings();

		if ( isset($this->_settings[$settingName]) )
			return $this->_settings[$settingName];
		else
			return $default;
	}

	public function registerOptions() {
		register_setting( $this->_optionsGroup, $this->_optionsName );
	}

	public function adminMenu() {
		$page = add_options_page( __( 'PayPal Settings', 'kvcodes' ), __( 'PayPal', 'kvcodes' ), 'manage_options', 'KvPayPal', array( $this, 'options' ) );		
	}

	private function _prepRequest($req) {
		$defaults = array(
			'VERSION'		=> $this->_settings['version'],
			'PWD'			=> $this->_settings["password-{$this->_settings['sandbox']}"],
			'USER'			=> $this->_settings["username-{$this->_settings['sandbox']}"],
			'SIGNATURE'		=> $this->_settings["signature-{$this->_settings['sandbox']}"],
			'CURRENCYCODE'	=> $this->_settings['currency'],
		);
		return wp_parse_args( $req, $defaults );
	}

	public function makeNVP( $reqArray, $sep = '&' ) {
		if ( !is_array($reqArray) )
			return $reqArray;
		return http_build_query( $reqArray, '', $sep );
	}

	public function hashCall( $args ) {
		$params = array(
			'httpversion' => '1.1',
			'body'		=> $this->_prepRequest($args),
			'sslverify' =>  false ,
			'timeout' 	=> 30,
		);

		// Send the request
		$resp = wp_remote_post( $this->_endpoint[$this->_settings['sandbox']], $params );

		// If the response was valid, decode it and return it.  Otherwise return a WP_Error
		if ( !is_wp_error($resp) && $resp['response']['code'] >= 200 && $resp['response']['code'] < 300 ) {
			// Used for debugging.
			$request = $this->_sanitizeRequest($params['body']);
			$message = __( 'Request:', 'kvcodes' );
			$message .= "\r\n".print_r($request, true)."\r\n\r\n";
			$message .= __( 'Response:', 'kvcodes' );
			$message .= "\r\n".print_r(wp_parse_args( $resp['body'] ), true)."\r\n\r\n";
			$this->_debug_mail( __( 'PayPal Framework - hashCall sent successfully', 'kvcodes' ), $message );
			return wp_parse_args($resp['body']);
		} else {
			$request = $this->_sanitizeRequest($params['body']);
			$message = __( 'Request:', 'kvcodes' );
			$message .= "\r\n".print_r($request, true)."\r\n\r\n";
			$message .= __( 'Response:', 'kvcodes' );
			$message .= "\r\n".print_r($resp, true)."\r\n\r\n";
			$this->_debug_mail( __( 'PayPal Framework - hashCall failed', 'kvcodes' ), $message );
			if ( !is_wp_error($resp) )
				$resp = new WP_Error('http_request_failed', $resp['response']['message'], $resp['response']);
			return $resp;
		}
	}

	private function _sanitizeRequest($request) {

		if ( $this->_settings['sandbox'] != 'sandbox' ) {
			if ( !empty( $request['ACCT'] ) )
				$request['ACCT']	= str_repeat('*', strlen($request['ACCT'])-4) . substr($request['ACCT'], -4);
			if ( !empty( $request['EXPDATE'] ) )
				$request['EXPDATE']	= str_repeat('*', strlen($request['EXPDATE']));
			if ( !empty( $request['CVV2'] ) )
				$request['CVV2']	= str_repeat('*', strlen($request['CVV2']));
		}
		return $request;
	}

	public function sendToExpressCheckout($args) {
		$args['cmd'] = '_express-checkout';
		$nvpString = $this->makeNVP($args);
		wp_redirect($this->_url[$this->_settings['sandbox']] . "?{$nvpString}");
		exit;
	}

	public function template_redirect() {
		// Check that the query var is set and is the correct value.
		if ( get_query_var( $this->_listener_query_var ) == $this->_listener_query_var_value )
			$this->listener();
	}

	public function listener() {
		$_POST = stripslashes_deep($_POST);
		// Try to validate the response to make sure it's from PayPal
		if ($this->_validateMessage())
			$this->_processMessage();

		// Stop WordPress entirely
		exit;
	}

	public function getUrl() {
		return $this->_url[$this->_settings['sandbox']];
	}

	public function _fixDebugEmails() {
		$this->_settings['debugging_email'] = preg_split('/\s*,\s*/', $this->_settings['debugging_email']);
		$this->_settings['debugging_email'] = array_filter($this->_settings['debugging_email'], 'is_email');
		$this->_settings['debugging_email'] = implode(',', $this->_settings['debugging_email']);
	}

	private function _debug_mail( $subject, $message ) {
		// Used for debugging.
		if ( $this->_settings['debugging'] == 'on' && !empty($this->_settings['debugging_email']) )
			wp_mail( $this->_settings['debugging_email'], $subject, $message );
	}

	private function _validateMessage() {
		// Set the command that is used to validate the message
		$_POST['cmd'] = "_notify-validate";

		// We need to send the message back to PayPal just as we received it
		$params = array(
			'httpversion' => '1.1',
			'body' => $_POST,
			'sslverify' => false,
			'timeout' 	=> 30,
			'user-agent'  => 'eimams; eimams.m1xn.com'
		);

		// Send the request
		$resp = wp_remote_post( $this->_url[$this->_settings['sandbox']], $params );

		

		// Put the $_POST data back to how it was so we can pass it to the action
		unset( $_POST['cmd'] );
		$message = __('URL:', 'kvcodes' );
		$message .= "\r\n".print_r($this->_url[$this->_settings['sandbox']], true)."\r\n\r\n";
		$message .= __('Options:', 'kvcodes' );
		$message .= "\r\n".print_r($this->_settings, true)."\r\n\r\n";
		$message .= __('Response:', 'kvcodes' );
		$message .= "\r\n".print_r($resp, true)."\r\n\r\n";
		$message .= __('Post:', 'kvcodes' );
		$message .= "\r\n".print_r($_POST, true);
		$message .= "\r\n".print_r($params, true);
		
		//update_option( 'paypal_return', serialize($resp));
		
		// If the response was valid, check to see if the request was valid
		if ( !is_wp_error($resp) && $resp['response']['code'] >= 200 && $resp['response']['code'] < 300 && (strcmp( $resp['body'], "VERIFIED") == 0)) {

			//update_option( 'paypal_return', "before" );
			kv_handle_ipn ($_POST); 
						
			
			$this->_debug_mail( __( 'IPN Listener Test - Validation Succeeded', 'kvcodes' ), $message );
			return true;
		} else {
			// If we can't validate the message, assume it's bad
			$this->_debug_mail( __( 'IPN Listener Test - Validation Failed', 'kvcodes' ), $message );
			return false;
		}
	}

	public function addPaypalListenerVar($public_query_vars) {
		$public_query_vars[] = $this->_listener_query_var;
		return $public_query_vars;
	}

	private function _processMessage() {
		do_action( 'paypal-ipn', $_POST );
		$actions = array( 'paypal-ipn' );
		$subject = sprintf( __( 'IPN Listener Test - %s', 'kvcodes' ), '_processMessage()' );
		if ( !empty($_POST['txn_type']) ) {
			do_action("paypal-{$_POST['txn_type']}", $_POST);
			$actions[] = "paypal-{$_POST['txn_type']}";
		}
		$message = sprintf( __( 'Actions thrown: %s', 'kvcodes' ), implode( ', ', $actions ) );
		$message .= "\r\n\r\n";
		$message .= sprintf( __( 'Passed to actions: %s', 'kvcodes' ), "\r\n" . print_r($_POST, true) );
		$this->_debug_mail( $subject, $message );
	}
	
	
	public function options() {
		?><style> 

		.large-text{
			width:99%;
		}
		.regular-text{
			width:25em;
		}
		#wp_paypal_framework span.help {
			display:inline-block;
			background:url('<?php echo  get_template_directory_uri();?>/img/help.png') no-repeat;
			width:12px;
			height:13px;
			border:none;
			text-indent:100%;
			overflow:hidden;
		}
		#wp_paypal_framework span.help:hover {
			background-position:0 -13px;
			cursor:pointer;
		}</style> 
		<script type="text/javascript">
		jQuery( function( $ ) {
			$( '#wp_paypal_framework span.help' ).click(function(){
				$( this ).next().toggle();
			});
		});
		</script>
		<div class="wrap">
			<h2><?php _e( 'PayPal Options', 'kvcodes' ); ?></h2>
			<form action="options.php" method="post" id="wp_paypal_framework">
				<?php settings_fields( $this->_optionsGroup ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_username-email">
								<?php _e( 'PayPal Email:', 'kvcodes' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[username-email]" value="<?php echo esc_attr( $this->_settings['username-email'] ); ?>" id="<?php echo $this->_optionsName; ?>_username-email" class="regular-text code" />
							
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_username-live">
								<?php _e( 'PayPal Live API Username:', 'kvcodes' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[username-live]" value="<?php echo esc_attr( $this->_settings['username-live'] ); ?>" id="<?php echo $this->_optionsName; ?>_username-live" class="regular-text code" />
							<?php $this->_show_help( 'live' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_password-live">
								<?php _e('PayPal Live API Password:', 'kvcodes') ?>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[password-live]" value="<?php echo esc_attr( $this->_settings['password-live'] ); ?>" id="<?php echo $this->_optionsName; ?>_password-live" class="regular-text code" />
							<?php $this->_show_help( 'live' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_signature-live">
								<?php _e( 'PayPal Live API Signature:', 'kvcodes' ) ?>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[signature-live]" value="<?php echo esc_attr($this->_settings['signature-live']); ?>" id="<?php echo $this->_optionsName; ?>_signature-live" class="regular-text code" />
							<?php $this->_show_help( 'live' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_username-sandbox">
								<?php _e('PayPal Sandbox API Username:', 'kvcodes') ?>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[username-sandbox]" value="<?php echo esc_attr($this->_settings['username-sandbox']); ?>" id="<?php echo $this->_optionsName; ?>_username-sandbox" class="regular-text code" />
							<?php $this->_show_help( 'sandbox' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_password-sandbox">
								<?php _e('PayPal Sandbox API Password:', 'kvcodes') ?>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[password-sandbox]" value="<?php echo esc_attr($this->_settings['password-sandbox']); ?>" id="<?php echo $this->_optionsName; ?>_password-sandbox" class="regular-text code" />
							<?php $this->_show_help( 'sandbox' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_signature-sandbox">
								<?php _e('PayPal Sandbox API Signature:', 'kvcodes') ?>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[signature-sandbox]" value="<?php echo esc_attr($this->_settings['signature-sandbox']); ?>" id="<?php echo $this->_optionsName; ?>_signature-sandbox" class="regular-text code" />
							<?php $this->_show_help( 'sandbox' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e('PayPal Sandbox or Live:', 'kvcodes') ?>
						</th>
						<td>
							<input type="radio" name="<?php echo $this->_optionsName; ?>[sandbox]" value="live" id="<?php echo $this->_optionsName; ?>_sandbox-live"<?php checked('live', $this->_settings['sandbox']); ?> />
							<label for="<?php echo $this->_optionsName; ?>_sandbox-live"><?php _e('Live', 'kvcodes'); ?></label><br />
							<input type="radio" name="<?php echo $this->_optionsName; ?>[sandbox]" value="sandbox" id="<?php echo $this->_optionsName; ?>_sandbox-sandbox"<?php checked('sandbox', $this->_settings['sandbox']); ?> />
							<label for="<?php echo $this->_optionsName; ?>_sandbox-sandbox"><?php _e('Use Sandbox (for testing only)', 'kvcodes'); ?></label><br />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_currency">
								<?php _e('Default Currency:', 'kvcodes') ?>
							</label>
						</th>
						<td>
							<select id="<?php echo $this->_optionsName; ?>_currency" class="postform" name="<?php echo $this->_optionsName; ?>[currency]">
								<option value=''><?php _e( 'Please Choose Default Currency', 'kvcodes' ); ?></option>
								<?php foreach ( $this->_currencies as $code => $currency ) { ?>
								<option value='<?php echo esc_attr($code); ?>'<?php selected($code, $this->_settings['currency']); ?>><?php echo esc_html( $currency ); ?></option>
								<?php } ?>
							</select>
							<small>
								<?php _e( "This is just the default currency for if one isn't specified.", 'kvcodes' ); ?>
							</small>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_version">
								<?php _e('PayPal API version:', 'kvcodes') ?>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[version]" value="<?php echo esc_attr($this->_settings['version']); ?>" id="<?php echo $this->_optionsName; ?>_version" class="small-text" />
							<small>
								<?php echo sprintf( __( "This is the default version to use if one isn't specified.  It is usually safe to set this to the <a href='%s'>most recent version</a>.", 'kvcodes' ), 'http://developer.paypal-portal.com/pdn/board/message?board.id=nvp&thread.id=4475' ); ?>
							</small>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e('Debugging Mode:', 'kvcodes') ?>
						</th>
						<td>
							<input type="radio" name="<?php echo $this->_optionsName; ?>[debugging]" value="on" id="<?php echo $this->_optionsName; ?>_debugging-on"<?php checked('on', $this->_settings['debugging']); ?> />
							<label for="<?php echo $this->_optionsName; ?>_debugging-on"><?php _e('On', 'kvcodes'); ?></label><br />
							<input type="radio" name="<?php echo $this->_optionsName; ?>[debugging]" value="off" id="<?php echo $this->_optionsName; ?>_debugging-off"<?php checked('off', $this->_settings['debugging']); ?> />
							<label for="<?php echo $this->_optionsName; ?>_debugging-off"><?php _e('Off', 'kvcodes'); ?></label><br />
							<small>
								<?php _e( 'If this is on, debugging messages will be sent to the E-Mail address set below.', 'kvcodes' ); ?>
							</small>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_debugging_email">
								<?php _e('Debugging E-Mail:', 'kvcodes') ?>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[debugging_email]" value="<?php echo esc_attr($this->_settings['debugging_email']); ?>" id="<?php echo $this->_optionsName; ?>_version" class="regular-text" />
							<small>
								<?php _e( 'This is a comma separated list of E-Mail addresses that will receive the debug messages.', 'kvcodes' ); ?>
							</small>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e('Legacy hash_call() support:', 'kvcodes') ?>
						</th>
						<td>
							<input type="radio" name="<?php echo $this->_optionsName; ?>[legacy_support]" value="on" id="<?php echo $this->_optionsName; ?>_legacy_support-on"<?php checked('on', $this->_settings['legacy_support']); ?> />
							<label for="<?php echo $this->_optionsName; ?>_legacy_support-on"><?php _e('On', 'kvcodes'); ?></label><br />
							<input type="radio" name="<?php echo $this->_optionsName; ?>[legacy_support]" value="off" id="<?php echo $this->_optionsName; ?>_legacy_support-off"<?php checked('off', $this->_settings['legacy_support']); ?> />
							<label for="<?php echo $this->_optionsName; ?>_legacy_support-off"><?php _e('Off', 'kvcodes'); ?></label><br />
							<small>
								<?php echo sprintf( __( 'The new function for sending NVP API calls to PayPal is %1$s.  If your scripts still use the old %2$s and you don\'t want to update them, enable this.  <em>This could conflict with an existing %2$s function if you have it defined elsewhere.</em>', 'kvcodes'), 'hashCall()', 'hash_call()' ); ?>
								<?php echo sprintf( __( 'The new function for sending NVP API calls to PayPal is %1$s.  If your scripts still use the old %2$s and you don\'t want to update them, enable this.  <em>This could conflict with an existing %2$s function if you have it defined elsewhere.</em>', 'kvcodes'), 'hashCall()', 'hash_call()' ); ?>
							</small>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e('PayPal IPN Listener URL:', 'kvcodes'); ?>
						</th>
						<td>
							<?php echo add_query_arg( array( 'action' => 'paypal_listener' ), site_url('ajax') ); ?>
							<?php $this->_show_help( 'listener' ); ?>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" name="Submit" value="<?php _e('Update Options &raquo;', 'kvcodes'); ?>" />
				</p>
			</form>
		</div> <?php
	}

	private function _show_help( $help ) {
		echo '<span class="help" title="' . __( 'Click for help', 'kvcodes' ) . '">' . __( 'Help', 'kvcodes' ) . '</span>';
		switch ( $help ) {
			case 'live':
				?>
					<ol class="hide-if-js">
						<li>
							<?php echo sprintf( __('You must have a PayPal business account.  If you do not have one, <a href="%s">sign up for one</a>.', 'kvcodes'), 'https://www.paypal.com/us/mrb/pal=TJ287296FD8KW'); ?>
						</li>
						<li>
							<?php echo sprintf( __('You must have a PayPal Website Payment Pro.  If you do not have one, <a href="%s">sign up for it</a>.', 'kvcodes'), 'https://www.paypal.com/us/cgi-bin/webscr?cmd=_wp-pro-overview'); ?>
						</li>
						<li>
							<?php echo sprintf( __("If you will be doing any recurring payments, you must have PayPal's Direct Payments Recurring Payments.  If you do not have it set up, please <a href='%s'>set it up</a>.", 'kvcodes' ), 'https://www.paypal.com/cgi-bin/webscr?cmd=xpt/cps/general/DPRPLaunch-outside'); ?>
						</li>
						<li>
							<?php _e('Lastly, you need to generate new API Credentials: In your PayPal account go to "My Account" -> "Profile" -> "Request API credentials" -> "PayPal API" -> "Set up PayPal API credentials and permissions".  If asked, you want to request an "API signature" not a certificate.  All the data that you are given should easily fit in this form.', 'kvcodes'); ?>
						</li>
					</ol>
				<?php
				break;
			case 'sandbox':
				?>
					<p class="hide-if-js">
						<?php echo sprintf(__('You must have a <a href="%s">PayPal sandbox account</a>.', 'kvcodes'), 'https://developer.paypal.com/'); ?>
					</p>
				<?php
				break;
			case 'listener':
				?>
					<div class="hide-if-js">
						<p><?php _e('To set this in your PayPal account:', 'kvcodes'); ?></p>
						<ol>
							<li>
								<?php _e('Click <strong>Profile</strong> on the <strong>My Account</strong> tab.', 'kvcodes'); ?>
							</li>
							<li>
								<?php _e('Click <strong>Instant Payment Notification Preferences</strong> in the Selling Preferences column.', 'kvcodes'); ?>
							</li>
							<li>
								<?php _e("Click <strong>Edit IPN Settings</strong> to specify your listener's URL and activate the listener.", 'kvcodes'); ?>
							</li>
							<li>
								<?php _e('Copy/Paste the URL shown above into the Notification URL field.', 'kvcodes'); ?>
							</li>
							<li>
								<?php _e('Click Receive IPN messages (Enabled) to enable your listener.', 'kvcodes'); ?>
							</li>
							<li>
								<?php _e('Click <strong>Save</strong>.', 'kvcodes'); ?>
							</li>
							<li>
								<?php _e("You're Done!  If you want, you can click <strong>Back to Profile Summary</strong> to return to the Profile after activating your listener.", 'kvcodes'); ?>
							</li>
						</ol>
					</div>
				<?php
				break;
		}
	}	
}

$KvPayPal = KvPayPal::getInstance(); ?>