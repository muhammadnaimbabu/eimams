<?php

require_once('jbs_subactive.php');
require_once('jb_subscription.php');
require_once('kv_jobseeker_resumes.php');
require_once("kv_admin_notifications.php");
require_once("kv_newsletter.php");
global $current_user, $wpdb;

/*****************************************************
 *Show user ID on the users table.
 *****************************************************/
add_filter('manage_users_columns', 'kv_add_user_id_column');
function kv_add_user_id_column($columns)
{
	$columns['user_id'] = 'ID';
	return $columns;
}

add_action('manage_users_custom_column',  'kv_show_user_id_column_content', 10, 3);
function kv_show_user_id_column_content($value, $column_name, $user_id)
{
	$user = get_userdata($user_id);
	if ('user_id' == $column_name)
		return $user_id;
	return $value;
}

/*****************************************************
 *eimams Admin Menu Hook
 *****************************************************/
add_action('admin_menu', 'kv_admin_menu');

function kv_admin_menu()
{
	add_menu_page('Subscriptions', 'Subscriptions', 'manage_options', 'subscription', 'kv_master_subscriptions', 'dashicons-cart', 11);
	add_submenu_page('subscription', 'Subscription Pack', 'Subscription Pack ', 'manage_options', 'subscription_pack', 'kv_jobseeker_subscriptions');
	add_submenu_page('subscription', 'User Subscriptions', 'User Subscriptions', 'manage_options', 'sub_active', 'kv_jobseeker_subscriptions_active');
	//add_submenu_page( 'subscription', 'Employer Subscription', 'Employer ', 'manage_options', 'sub_actihgve', 'kv_employer_subscriptions' );
	//add_submenu_page( 'subscription', 'Employer Subs Active', 'Employer Active', 'manage_options', 'sub_actffive', 'employer_subs_active' );
	add_menu_page('Resumes', 'Resumes', 'manage_options', 'resumes', 'kv_jobseeker_resumes', 'dashicons-id-alt' /* get_stylesheet_directory_uri() . '/images/icons/resume.png'*/, 7);

	add_menu_page('eimams', 'eimams', 'manage_options', 'eimams', 'kv_eimams_functions', get_stylesheet_directory_uri() . '/img/favicon.ico', 4);
	add_submenu_page('eimams', 'E-Mail', 'E-Mail', 'manage_options',  'email',  'kv_email_eimams_functions');
	add_submenu_page('eimams', 'Mail', 'Mail', 'manage_options',  'mail',  'az_email_eimams_functions');
	add_submenu_page('eimams',	'Notifications',   'Notifications', 'manage_options',  'notifications',  'kv_eimams_notification_from_admin');
	add_submenu_page('eimams',	'Subscribers',   'Subscribers', 'manage_options',  'subscribers',  'kv_news_letter_for_common');
	add_submenu_page('eimams',	'Newsletter',   'Newsletter', 'manage_options',  'newsletter',  'kv_compose_newsletter_mails');
}

/*****************************************
 *Adding js and css files
 ****************************************/
// Jquery


//Date Picker
add_action('admin_init', 'kv_add_scripts');
function kv_add_scripts()
{
	$get_current_url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI'];
	if (strpos($get_current_url, 'sub_active')) {
		add_action('admin_enqueue_scripts', 'enqueue_date_picker');
		add_action('admin_print_styles', 'enqueue_date_picker_style'); ?>
		<style>
			#wpcontent {
				background: #f5f5f5;
			}
		</style><?php
			}
		}

		function enqueue_date_picker()
		{
			wp_enqueue_script('jquery');
			wp_enqueue_script('date_picker', get_template_directory_uri() . '/assets/js/bootstrap-datepicker.js');
		}

		function enqueue_date_picker_style()
		{
			wp_enqueue_style('Date-picker', get_template_directory_uri() . '/assets/css/datepicker.css');
			wp_enqueue_style('picker', get_template_directory_uri() . '/assets/css/bootstrap.css');
		}

		/*************************************************************
		 *Subscription page widget and its details of subscription.
		 *************************************************************/
		function kv_master_subscriptions()
		{

			global $wpdb; //This is used only if making any database queries
			$database_name = $wpdb->prefix . 'jbs_subpack';
			$query = "SELECT * FROM $database_name";
			$data =  $wpdb->get_results($query, ARRAY_A); //var_dump($data);
			$membersArray = get_users('role=job_seeker');

			// Array of WP_User objects.
			foreach ($membersArray as $bandMember) {
				//echo esc_html( $bandMember->nickname  );
			} ?>
	<div class="wrap">
		<h2>User Subscriptions </h2>
	</div>
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div id="postbox-container-1" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div id="kv_subscription_dashboard" class="postbox">
						<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Subscriptions</span><span class="toggle-indicator" aria-hidden="true"></span></button>
						<h2 class="hndle ui-sortable-handle"><span>Employer Subscriptions</span></h2>
						<div class="inside">
							<?php kv_employer_subscription_dashboard_fn(); ?>
						</div>
					</div>
				</div>
			</div>
			<div id="postbox-container-2" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div id="kv_subscription_dashboard" class="postbox">
						<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Subscriptions</span><span class="toggle-indicator" aria-hidden="true"></span></button>
						<h2 class="hndle ui-sortable-handle"><span>Job Seeker Subscriptions</span></h2>
						<div class="inside">

							<?php kv_job_seeker_subscription_dashboard_fn(); ?>

						</div>
					</div>
				</div>
			</div>

		</div>
	</div><?php

		}

		function employer_subs_active()
		{
		}

		function kv_employer_subscriptions()
		{
		}


		/*****************************************
		 * Admin dashboard widget functions
		 ****************************************/
		function kv_jobs_dashboard()
		{

			wp_add_dashboard_widget(
				'kv_marketing_areas_widget',         // Widget slug.
				'How did you hear about us',         // Title.
				'kv_marketing_areas_widget_fn' // Display function.
			);
			wp_add_dashboard_widget(
				'kv_jobs_dashboard',         // Widget slug.
				'Jobs Glance',         // Title.
				'kv_jobs_dashboard_fn' // Display function.
			);

			wp_add_dashboard_widget(
				'kv_job_seeker_subscription_dashboard',         // Widget slug.
				'Job seeker Subscriptions',         // Title.
				'kv_job_seeker_subscription_dashboard_fn' // Display function.
			);
			wp_add_dashboard_widget(
				'kv_semployer_ubscription_dashboard',         // Widget slug.
				'Employer Subscriptions',         // Title.
				'kv_employer_subscription_dashboard_fn' // Display function.
			);

			wp_add_dashboard_widget(
				'kv_users_dashboard',         // Widget slug.
				'Users',         // Title.
				'kv_users_dashboard_fn' // Display function.
			);
			wp_add_dashboard_widget(
				'kv_help_support_dashboard',         // Widget slug.
				'Help and Support',         // Title.
				'kv_help_support_dashboard_fn' // Display function.
			);
			wp_add_dashboard_widget(
				'kv_eimams_links_dashboard',         // Widget slug.
				'Eimams Links',         // Title.
				'kv_eimams_links_dashboard_fn' // Display function.
			);
			remove_meta_box('dashboard_primary', 'dashboard', 'side');   // WordPress blog
			remove_meta_box('dashboard_secondary', 'dashboard', 'side');   // Other WordPress News
			remove_meta_box('dashboard_quick_press', 'dashboard', 'side');  // Quick Press
			remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');  // Recent Drafts

		}


		function count_users_per_meta($meta_key, $meta_value)
		{
			global $wpdb;
			return $user_meta_query = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->usermeta WHERE meta_key='$meta_key' AND meta_value='$meta_value'");
			//return number_format_i18n($user_meta_query);
		}

		//add_action( 'wp_dashboard_setup', 'kv_marketing_areas_widget' );

		/**********************************************************************
		 * marketing area widget functions with active fuction variables
		 **********************************************************************/
		function kv_marketing_areas_widget_fn()
		{

			$taxonomies = get_terms('marketing_area', 'hide_empty=0');
			if ($taxonomies) {
				echo '<div class="main">
				<ul>';
				foreach ($taxonomies  as $taxonomy) {
			?>
			<li class="post-count"><a href="#"><?php echo count_users_per_meta('marketing_area', $taxonomy->term_id) . '  ' . $taxonomy->name; ?></a></li>
		<?php
				} ?>
		</ul>
		</div>
		<style>
			#kv_marketing_areas_widget li {
				width: 50%;
				float: left;
				margin-bottom: 10px;
			}

			#kv_marketing_areas_widget ul {
				margin: 0;
				overflow: hidden;
			}

			#kv_marketing_areas_widget .post-count a:before,
			#kv_marketing_areas_widget .post-count span:before {
				content: '\f109';
				font: 400 20px/1 dashicons;
				speak: none;
				display: block;
				float: left;
				margin: 0 5px 0 0;
				padding: 0;
				text-indent: 0;
				text-align: center;
				position: relative;
				-webkit-font-smoothing: antialiased;
				text-decoration: none !important;
			}

			#kv_marketing_areas_widget .page-count a:before,
			#kv_marketing_areas_widget .page-count span:before {
				content: '\f105';
				font: 400 20px/1 dashicons;
				speak: none;
				display: block;
				float: left;
				margin: 0 5px 0 0;
				padding: 0;
				text-indent: 0;
				text-align: center;
				position: relative;
				-webkit-font-smoothing: antialiased;
				text-decoration: none !important;
			}

			#kv_marketing_areas_widget li a:before,
			#kv_marketing_areas_widget li span:before {
				content: '\f159';
				font: 400 20px/1 dashicons;
				speak: none;
				display: block;
				float: left;
				margin: 0 5px 0 0;
				padding: 0;
				text-indent: 0;
				text-align: center;
				position: relative;
				-webkit-font-smoothing: antialiased;
				text-decoration: none !important;
			}
		</style> <?php
				}
			}

			add_action('wp_dashboard_setup', 'kv_jobs_dashboard');

			/**********************************************************************
			 * Jobs widget functions with active fuction variables
			 **********************************************************************/
			function kv_jobs_dashboard_fn()
			{ ?>
	<div class="main">
		<ul>
			<li class="post-count"><a href="edit.php?post_type=job"><?php echo kv_get_total_jobs_counts(); ?> Total Jobs</a></li>
			<li class="page-count"><a href="edit.php?post_status=pending&post_type=job"><?php echo kv_get_pending_jobs_counts(); ?> Pending Jobs</a></li>
			<li class="comment-count"><a href="edit.php?post_status=publish&post_type=job"><?php echo kv_get_published_jobs_counts(); ?> Published Jobs</a></li>
			<li class="comment-mod-count"><a href="edit.php?post_status=expired&post_type=job"><?php echo kv_get_expired_jobs_counts(); ?> Expired Jobs</a></li>
		</ul>
	</div>
	<style>
		#kv_jobs_dashboard li {
			width: 50%;
			float: left;
			margin-bottom: 10px;
		}

		#kv_jobs_dashboard ul {
			margin: 0;
			overflow: hidden;
		}

		#kv_jobs_dashboard .post-count a:before,
		#kv_jobs_dashboard .post-count span:before {
			content: '\f109';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}

		#kv_jobs_dashboard .page-count a:before,
		#kv_jobs_dashboard .page-count span:before {
			content: '\f105';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}

		#kv_jobs_dashboard li a:before,
		#kv_jobs_dashboard li span:before {
			content: '\f159';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}
	</style>
<?php }

			/*****************************************
			 * Links widget
			 ****************************************/
			function kv_eimams_links_dashboard_fn()
			{ ?>
	<div class="main">
		<ul>
			<li class="post-count"><a href="edit.php?post_type=job"> Jobs</a></li>
			<li class="page-count"><a href="edit.php?post_type=tickets"> Help And Support </a></li>
			<li class="comment-count"><a href="edit.php?post_type=faqs"> FAQs</a></li>
			<li class="comment-mod-count"><a href="admin.php?page=eimams"> Eimam Emails</a></li>
		</ul>
	</div>
	<style>
		#kv_eimams_links_dashboard li {
			width: 50%;
			float: left;
			margin-bottom: 10px;
		}

		#kv_eimams_links_dashboard ul {
			margin: 0;
			overflow: hidden;
		}

		#kv_eimams_links_dashboard .post-count a:before,
		#kv_eimams_links_dashboard .post-count span:before {
			content: '\f109';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}

		#kv_eimams_links_dashboard .page-count a:before,
		#kv_eimams_links_dashboard .page-count span:before {
			content: '\f105';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}

		#kv_eimams_links_dashboard li a:before,
		#kv_eimams_links_dashboard li span:before {
			content: '\f159';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}
	</style>
<?php }


			/*****************************************
			 *Subscription Wdiget
			 ****************************************/
			function kv_subscription_dashboard_fn()
			{ ?>
	<div class="main">
		<ul>
			<li class="post-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_subscription_counts(); ?> Total Subscriptions</a></li>
			<li class="page-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_active_subscription_counts(); ?> Active Subscribers</a></li>
			<li class="comment-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_expired_subscription_counts(); ?> Expired Subscribers</a></li>
			<li class="comment-mod-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_yet2_active_subscription_counts(); ?> Yet To Active</a></li>
			<li class="comment-mod-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_expire_in3days_subscription_counts(); ?> Expiring in 3 Days</a></li>
		</ul>
	</div>
	<style>
		#kv_subscription_dashboard li {
			width: 50%;
			float: left;
			margin-bottom: 10px;
		}

		#kv_subscription_dashboard ul {
			margin: 0;
			overflow: hidden;
		}

		#kv_subscription_dashboard .post-count a:before,
		#kv_subscription_dashboard .post-count span:before {
			content: '\f109';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}

		#kv_subscription_dashboard .page-count a:before,
		#kv_subscription_dashboard .page-count span:before {
			content: '\f105';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}

		#kv_subscription_dashboard li a:before,
		#kv_subscription_dashboard li span:before {
			content: '\f159';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}
	</style>
<?php }


			/*****************************************
			 *Subscription Wdiget
			 ****************************************/
			function kv_job_seeker_subscription_dashboard_fn()
			{ ?>
	<div class="main">
		<ul>
			<li class="post-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_subscription_pack_counts(); ?> Total Subscriptions</a></li>
			<li class="page-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_active_subscription_counts(); ?> Active Subscribers</a></li>
			<li class="comment-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_expired_subscription_counts(); ?> Expired Subscribers</a></li>
			<li class="comment-mod-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_yet2_active_subscription_counts(); ?> Yet To Active</a></li>
			<li class="comment-mod-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_expire_in3days_subscription_counts(); ?> Expiring in 3 Days</a></li>
		</ul>
	</div>
	<style>
		#kv_subscription_dashboard li {
			width: 50%;
			float: left;
			margin-bottom: 10px;
		}

		#kv_subscription_dashboard ul {
			margin: 0;
			overflow: hidden;
		}

		#kv_subscription_dashboard .post-count a:before,
		#kv_subscription_dashboard .post-count span:before {
			content: '\f109';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}

		#kv_subscription_dashboard .page-count a:before,
		#kv_subscription_dashboard .page-count span:before {
			content: '\f105';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}

		#kv_subscription_dashboard li a:before,
		#kv_subscription_dashboard li span:before {
			content: '\f159';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}
	</style>
<?php }

			/*****************************************
			 *Subscription Wdiget
			 ****************************************/
			function kv_employer_subscription_dashboard_fn()
			{ ?>
	<div class="main">
		<ul>
			<li class="post-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_subscription_pack_counts('employer'); ?> Total Subscriptions</a></li>
			<li class="page-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_active_subscription_counts('employer'); ?> Active Subscribers</a></li>
			<li class="comment-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_expired_subscription_counts('employer'); ?> Expired Subscribers</a></li>
			<li class="comment-mod-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_yet2_active_subscription_counts('employer'); ?> Yet To Active</a></li>
			<li class="comment-mod-count"><a href="admin.php?page=sub_active"><?php echo kv_get_total_expire_in3days_subscription_counts('employer'); ?> Expiring in 3 Days</a></li>
		</ul>
	</div>
	<style>
		#kv_subscription_dashboard li {
			width: 50%;
			float: left;
			margin-bottom: 10px;
		}

		#kv_subscription_dashboard ul {
			margin: 0;
			overflow: hidden;
		}

		#kv_subscription_dashboard .post-count a:before,
		#kv_subscription_dashboard .post-count span:before {
			content: '\f109';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}

		#kv_subscription_dashboard .page-count a:before,
		#kv_subscription_dashboard .page-count span:before {
			content: '\f105';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}

		#kv_subscription_dashboard li a:before,
		#kv_subscription_dashboard li span:before {
			content: '\f159';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}
	</style>
<?php }

			/*****************************************
			 *Users widget
			 ****************************************/
			function kv_users_dashboard_fn()
			{
				$users = count_users();
				//print_r($users);
?>
	<div class="main">
		<ul>
			<li class="post-count"><a href="users.php?role=job_seeker"><?php echo $users['avail_roles']['job_seeker']; ?> Job Seekers</a></li>
			<li class="page-count"><a href="users.php?role=employer"><?php echo $users['avail_roles']['employer']; ?> Employers</a></li>
		</ul>
	</div>
	<style>
		#kv_users_dashboard li {
			width: 50%;
			float: left;
			margin-bottom: 10px;
		}

		#kv_users_dashboard ul {
			margin: 0;
			overflow: hidden;
		}

		#kv_users_dashboard li a:before,
		#kv_users_dashboard li span:before {
			content: '\f110';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}
	</style> <?php
			}

			/*****************************************
			 *Help and Support Widget
			 ****************************************/
			function kv_help_support_dashboard_fn()
			{ ?>
	<div class="main">
		<ul>
			<li class="post-count"><a href="edit.php?post_type=job"><?php echo kv_get_total_tickets_counts(); ?> Total Tickets </a></li>
			<li class="page-count"><a href="edit.php?post_status=pending&post_type=page"><?php echo kv_get_pending_tickets_counts(); ?> New Tickets </a></li>
			<li class="comment-count"><a href="edit.php?post_status=publish&post_type=page"><?php $comments_count = wp_count_comments();
																							echo  $comments_count->moderated; ?> New Reply's </a></li>
		</ul>
	</div>
	<style>
		#kv_help_support_dashboard li {
			width: 50%;
			float: left;
			margin-bottom: 10px;
		}

		#kv_help_support_dashboard ul {
			margin: 0;
			overflow: hidden;
		}

		#kv_help_support_dashboard .post-count a:before,
		#kv_help_support_dashboard .post-count span:before {
			content: '\f109';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}

		#kv_help_support_dashboard .page-count a:before,
		#kv_help_support_dashboard .page-count span:before {
			content: '\f105';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}

		#kv_help_support_dashboard li a:before,
		#kv_help_support_dashboard li span:before {
			content: '\f159';
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			float: left;
			margin: 0 5px 0 0;
			padding: 0;
			text-indent: 0;
			text-align: center;
			position: relative;
			-webkit-font-smoothing: antialiased;
			text-decoration: none !important;
		}
	</style>
<?php
			}

			/*****************************************
			 *Widget glance items
			 ****************************************/
			add_filter('dashboard_glance_items', 'custom_glance_items', 10, 1);
			function custom_glance_items($items = array())
			{
				$post_types = array('job');

				foreach ($post_types as $type) {
					if (!post_type_exists($type)) continue;
					$num_posts = wp_count_posts($type);

					if ($num_posts) {

						$published = intval($num_posts->publish);
						$post_type = get_post_type_object($type);

						$text = _n('%s ' . $post_type->labels->singular_name, '%s ' . $post_type->labels->name, $published, 'your_textdomain');
						$text = sprintf($text, number_format_i18n($published));

						if (current_user_can($post_type->cap->edit_posts)) {
							$items[] = sprintf('<a class="%1$s-count" href="edit.php?post_type=%1$s">%2$s</a>', $type, $text) . "\n";
						} else {
							$items[] = sprintf('<span class="%1$s-count">%2$s</span>', $type, $text) . "\n";
						}
					}
				}
				return $items;
			}


			/********************************************
			 * I have tested mail Email page and try to  repair the page, But the page was not working
			 * after every changes, That's why I have created a new email Page, So that I can fixed the bug
			 * *****************************************
			 */

			function az_email_eimams_functions()
			{
				include TEMPLATEPATH . '/template/admin-send-mail.php';
			}

?>