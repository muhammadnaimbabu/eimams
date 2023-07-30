<?php 
require_once("kv_email_functions.php");
require_once("kv_dashboard_fn.php");
require_once("kv_footer_widget.php");
require_once("ajax_file.php");
require_once("kv_job_fns.php");
require_once("limit-login-attempts.php");
require_once("kv-paypal.php");

global $current_user;
wp_get_current_user();

/*****************************************************
* Check whether the employer is Private or not 
*****************************************************/
function has_private_employer($id){
	
	if(get_user_meta($id, 'private_employer', true) == 'yes')
		return true;
	else 
		return false;
}

/*****************************************************
* Wordpress get any users role
*****************************************************/
function kv_is_user_in_role( $user_id, $role  ) {
    //return in_array( $role, get_user_roles_by_user_id( $user_id ) );	
	$user_meta=get_userdata($user_id);
	$user_roles=$user_meta->roles;
	return $user_roles[0];
}

/*****************************************************
* Newsletter Subscribers email existence checking 
*****************************************************/
function eimams_has_email_subscriber($email){
	global $wpdb;
	$sub_table = $wpdb->prefix.'newsletter';
	return $wpdb->get_var("SELECT id FROM $sub_table WHERE email = '".$email."' LIMIT 1");
}


/*****************************************************
*merge two taxonomies
*****************************************************/
function kv_merged_taxonomy_dropdown( $taxonomy, $taxonomy2, $selected_id=null ) {
	$terms = get_terms( $taxonomy, array('hide_empty' => false) );
	$terms2 = get_terms( $taxonomy2, array('hide_empty' => false)  );
	$final_res = '';
	if ( $terms ) {
		$final_res .= '<select name="merged_taxonomy" class="merged_taxonomy form-control" id="merged_taxonomy" >';
		if($selected_id == null)
			$final_res .= '<option value="-1">Select category</option> ';
		$final_res .= '<optgroup label="Special Job">';
		foreach ( $terms as $term ) {
			$final_res .= '<option type="'.$taxonomy.'" value="'.esc_attr( $term->term_id ).'"  '.(($term->term_id == $selected_id) ? 'selected' : '').'>'. esc_html( $term->name ).'</option>';
		}
		$final_res .=' </optgroup><optgroup label="General Job">';
		if($terms2){
			foreach ( $terms2 as $term2 ) {
				$final_res .='<option type="'.$taxonomy2.'" value="'.esc_attr( $term2->term_id ).'" '.(($term2->term_id == $selected_id) ? 'selected' : '').'>'.esc_html( $term2->name ).'</option>';
			}
		}
		$final_res .= '</optgroup></select>';	
	}
	return $final_res;
}



/*****************************************************
*Hide links to other users except us
*****************************************************/
function remove_menus(){

	global  $current_user;

	if (is_user_logged_in()) {
	    wp_get_current_user();
	    $user_info = get_userdata($current_user->ID);

	    if ($current_user->ID != 1 && $current_user->ID != 3 && $current_user->ID != 4) {
	        // content
	         remove_meta_box('dashboard_right_now', 'dashboard', 'normal');   // Right Now
	         remove_meta_box('dashboard_activity', 'dashboard', 'normal');
	         remove_action( 'admin_notices', 'update_nag', 3 );
	         remove_menu_page( 'themes.php' );           //Appearance
			 remove_menu_page( 'plugins.php' );          //Plugins
			 remove_menu_page( 'tools.php' );            //Tools
			 remove_menu_page( 'edit.php' );          
			 remove_submenu_page( 'index.php', 'update-core.php' );
			 remove_menu_page( 'options-general.php' );  //Settings
			 remove_menu_page( 'WP-Optimize' );
			 remove_menu_page( 'backwpup' );
	    }
	}
  
}
add_action( 'admin_menu', 'remove_menus' );

function remove_admin_bar_links() {
    global $wp_admin_bar;
    global  $current_user;

	if (is_user_logged_in()) {
	    wp_get_current_user();
	    $user_info = get_userdata($current_user->ID);

	    if (in_array('administrator', $user_info->roles) && ($current_user-> ID != 1 || $current_user->ID != 2)) {
			    $wp_admin_bar->remove_menu('wp-logo');          // Remove the WordPress logo
			    $wp_admin_bar->remove_menu('about');            // Remove the about WordPress link
			    $wp_admin_bar->remove_menu('wporg');            // Remove the WordPress.org link
			    $wp_admin_bar->remove_menu('documentation');    // Remove the WordPress documentation link
			    $wp_admin_bar->remove_menu('support-forums');   // Remove the support forums link
			    $wp_admin_bar->remove_menu('feedback');         // Remove the feedback link
			  //  $wp_admin_bar->remove_menu('site-name');        // Remove the site name menu
			  //  $wp_admin_bar->remove_menu('view-site');        // Remove the view site link
			    $wp_admin_bar->remove_menu('updates');          // Remove the updates link
			 //   $wp_admin_bar->remove_menu('comments');         // Remove the comments link
			    $wp_admin_bar->remove_menu('new-content');      // Remove the content link
			    $wp_admin_bar->remove_menu('w3tc');             // If you use w3 total cache remove the performance link
			    $wp_admin_bar->remove_menu('backwpup');             // If you use w3 total cache remove the performance link
    	}
	}
 //   $wp_admin_bar->remove_menu('my-account');       // Remove the user details tab
}
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );
/*****************************************************
* Get job seeker details from Job seeker table
*****************************************************/
function kv_get_jobseeker_details($id) {
	global $wpdb; 
	$jobseeker_tbl = $wpdb->prefix.'jobseeker';
	$jobseeker_info = $wpdb->get_row("SELECT * FROM $jobseeker_tbl WHERE wp_usr_id = ".$id, ARRAY_A);
	return $jobseeker_info;
}

/*****************************************************
*Registering a New Post Status - Expired
*****************************************************/
function kv_expired_post_status(){
	register_post_status( 'expired', array(
		'label'                     => _x( 'Expired', 'job' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>' ),
	) );
}
add_action( 'init', 'kv_expired_post_status' );

/*************************************************
*User Deactivation Link on Back end users Table
**************************************************/
function kv_admin_deactivate_link($actions, $user_object) {
	if(kv_get_user_status($user_object->ID) == 0)
		$actions['deactivate_user'] = "<a href='" . admin_url( "admin.php?page=email&amp;action=deactivate_user&amp;user=$user_object->ID") . "'>" . __( 'Deactivate', 'kvc' ) . "</a>";
	elseif(kv_get_user_status($user_object->ID) == 1 || kv_get_user_status($user_object->ID) == 2)
		$actions['activate_user'] = "<a href='" . admin_url( "admin.php?page=email&amp;action=activate_user&amp;user=$user_object->ID") . "'>" . __( 'Activate', 'kvc' ) . "</a>";
	
	$user_role =array_keys($user_object->caps);
	if($user_role[0] == 'employer')
	$actions['edit_his_posts'] = "<a href='" . admin_url( "edit.php?post_type=job&author=$user_object->ID") . "'>" . __( 'Jobs', 'kvc' ) . "</a>";
	
	if($user_object->ID == 1 || $user_object->ID == 2) 
		unset($actions['delete']);
	
	return $actions;
}
add_filter('user_row_actions', 'kv_admin_deactivate_link', 10, 2);

/*****************************************
*Get User Status Activate/Deactivate
****************************************/
function kv_get_user_status($user_id=null){

	global $current_user, $wpdb;
	
	wp_get_current_user();
	if($user_id == null)
		$user_id = $current_user->ID; 
	return $wpdb->get_var( "SELECT user_status FROM $wpdb->users WHERE ID=".$user_id );
}

/*****************************************
*Get Deactivated User List
****************************************/
function kv_get_deactivated_users_list() {
	global$wpdb; 
	$deactivated_users = array();
	$users_list = $wpdb->get_results( "SELECT ID FROM $wpdb->users WHERE user_status=1" ); 
	
	foreach( $users_list as $userss) 
		$deactivated_users[] = $userss->ID;
		
	return $deactivated_user_list = join(',',$deactivated_users); 
}

################################################################################
// Validate the date
################################################################################
function kv_validateDate($myDateString){
    return (bool)strtotime($myDateString);
}

/*********************************************
*Delete the Attached files with Attachment ID
**********************************************/
function  kv_delete_attached_files($post_id){
	global $wpdb; 
	$meta = wp_get_attachment_metadata( $post_id );
		
	$file = get_attached_file( $post_id );
	
	$uploadpath = wp_upload_dir();
	
	if ( ! empty($meta['thumb']) ) {
		if (! $wpdb->get_row( $wpdb->prepare( "SELECT meta_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attachment_metadata' AND meta_value LIKE %s AND post_id <> %d", '%' . $wpdb->esc_like( $meta['thumb'] ) . '%', $post_id)) ) {
			$thumbfile = str_replace(basename($file), $meta['thumb'], $file);
			$thumbfile = apply_filters( 'wp_delete_file', $thumbfile );
			@ unlink( path_join($uploadpath['basedir'], $thumbfile) );
		}
	}

	// Remove intermediate and backup images if there are any.
	if ( isset( $meta['sizes'] ) && is_array( $meta['sizes'] ) ) {
		foreach ( $meta['sizes'] as $size => $sizeinfo ) {
			$intermediate_file = str_replace( basename( $file ), $sizeinfo['file'], $file );
			$intermediate_file = apply_filters( 'wp_delete_file', $intermediate_file );
			@ unlink( path_join( $uploadpath['basedir'], $intermediate_file ) );
		}
	}

	if ( is_array($backup_sizes) ) {
		foreach ( $backup_sizes as $size ) {
			$del_file = path_join( dirname($meta['file']), $size['file'] );
			$del_file = apply_filters( 'wp_delete_file', $del_file );			
			@ unlink( path_join($uploadpath['basedir'], $del_file) );
		}
	}
	wp_delete_file( $file );	
}

// Front End menu walker class
class candidate_Nav_Walker extends Walker_Nav_Menu {

	function check_current($classes) {
		return preg_match('/(current[-_])|active|dropdown/', $classes);
	  }

	  function start_lvl(&$output, $depth = 0, $args = array()) {
		  if($depth == 0) {
			$output .= "\n<ul class=\"DropMenu\">\n";
		  }else  {
			  $output .= "\n<ul>\n";
		  }
	  }

  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    $item_html = '';
    parent::start_el($item_html, $item, $depth, $args);
	if ($item->title == 'Home' && ($depth === 0)) {
		$item->title = 'Home1';
	}
	
	$item_html = str_replace('current_page_item', ' current-menu-item', $item_html);
	$item_html = str_replace('current-menu-parent', ' current-menu-item', $item_html);
	$item_html = str_replace('current-menu-ancestor', ' current-menu-item', $item_html);

    $output .= $item_html;
  }

  function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
    $element->is_dropdown = !empty($children_elements[$element->ID]);

    if ($element->is_dropdown) {
      if ($depth === 0) {
        $element->classes[] = '';
      } elseif ($depth === 1) {
        $element->classes[] = '';
      }
    }
    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
  }
}



if ( ! function_exists( 'candidat_theme_roots_nav_menu_args' ) ) {
	function candidat_theme_roots_nav_menu_args($args = '') {
		$roots_nav_menu_args['container'] = false;
		if (!$args['items_wrap']) {
			$roots_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
		}
		if (current_theme_supports('firmasite-top-navbar')) {
			$roots_nav_menu_args['depth'] = 0;
		}
		if (!$args['walker']) {
			$roots_nav_menu_args['walker'] = new candidate_Nav_Walker();
		}
		return array_merge($args, $roots_nav_menu_args);
	}
}
add_filter('wp_nav_menu_args', 'candidat_theme_roots_nav_menu_args');

/*****************************************
// Breadcrumps 
****************************************/	
if ( ! function_exists( 'candidat_the_breadcrumbs' ) ) {    
	function candidat_the_breadcrumbs() {
	 
			global $post;
			
			echo '<p class="breadcrumb">';
			
			if (!is_home()) {	 
				echo "<a href='". esc_url(home_url('/')). "'> Home </a>";
	 
				if (is_category() || is_single()) {	 
					// echo ' / ';					
					if ( get_post_type() == 'post' ) {
						$cats = get_the_category( $post->ID );						
		     
						foreach ( $cats as $cat ){
							//if($kv<= $cat_count)
							echo ' / ';  // modified here
							$category_link = get_category_link( $cat->term_id );						
							echo '<a href="'. esc_url($category_link) .'">'. esc_attr($cat->cat_name) .'</a>';							       
							
						}
					}
					
					if (is_single()) {
					   echo ' / '.esc_attr(get_the_title());
					}
				} elseif (is_page()) {
	 
					if($post->post_parent){
						$anc = get_post_ancestors( $post->ID );
						$anc_link = get_page_link( $post->post_parent );
	 
						foreach ( $anc as $ancestor ) {
							$output = ' / <a href="'. esc_url($anc_link) .'">'. esc_attr(get_the_title($ancestor)) .'</a> / ';
						}	 
						echo $output;
						echo esc_attr(get_the_title());
	 
					} else {
						echo ' / ';
						echo esc_attr(get_the_title());
					}
				}
			}
		elseif (is_tag()) {single_tag_title();}
		elseif (is_day()) {echo"Archive: "; the_time('F jS, Y'); }
		elseif (is_month()) {echo"Archive: "; the_time('F, Y'); }
		elseif (is_year()) {echo"Archive: "; the_time('Y'); }
		elseif (is_author()) {echo"Author's archive: "; }
		elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "Blogarchive: "; echo'';}
		elseif (is_search()) {echo"Search results: "; }
		
		echo '</p>';
	}
}

	

/*****************************************
* Getcomments of Help and support
****************************************/		
add_action( 'walker_nav_menu_start_el', 'eimams_get_help_and_support_comment', 10, 4 );
function eimams_get_help_and_support_comment( $output, $item, $depth, $args ){
	$count_val = get_current_help_and_support_count() ; 
	$count_applied_resumes = get_current_read_applied_resumes() ; 
   if( in_array( 'bubblecount', (array) $item->classes ) ){
	   if($count_val >0)
			$output .= '<span class="unread"><span class="count">'.$count_val.'</span></span>';
		else 
			$output .= '<span class="unread" style="height: 50px;"></span>';
	}
	if( in_array( 'appliedresumescount', (array) $item->classes ) ){
	   if($count_applied_resumes >0)
			$output .= '<span class="unread"><span class="count">'.$count_applied_resumes.'</span></span>';
		else 
			$output .= '<span class="unread" style="height: 50px;"></span>';
	}	
	
    return $output;
}


/*****************************************
* Get Theme post category
****************************************/
if ( ! function_exists( 'candidat_theme_get_post_category' ) ) { 
	function candidat_theme_get_post_category($id = null){
		$categories = get_the_terms( $id, 'category' );
		$res = '';
		if(!empty($categories)){
			foreach ( $categories as $val ) {
				$res .= $val->slug;
				$res .= ', ';
			}
		}
		return  $res;
	}
  }
  
  
  if ( ! function_exists( 'candidat_theme_the_related_post' ) ) {   
	function candidat_theme_the_related_post ($post_num = 4, $category_post, $esclude_post, $post_class='col-lg-4 col-md-4 col-sm-4'){ 
		$post_id = '';
		$post_class = $post_class;
		global $post;
		$tmp_post = $post;
		$args = array( 'orderby' => 'menu_order',
					   'category_name' => $category_post,					   
					   'exclude' => $esclude_post,
					   'numberposts' => $post_num);
		$myposts = get_posts($args);

		foreach( $myposts as $post ) : setup_postdata($post);
		$urlThumb='';
		?>
		<div class="<?php echo esc_attr($post_class); ?>">
			
			<!-- Blog Post -->
			<div class="blog-post animate-onscroll">
				
				<div class="post-image">
				<?php if( has_post_thumbnail() ) { 
				the_post_thumbnail('latest-post'); 
				} else { ?>
				<img src="<?php echo get_template_directory_uri() ?>/img/media/media1-medium.jpg" alt="">
				<?php } ?>
				</div>
				
				<h4 class="post-title"><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h4>
				
				<div class="post-meta">
					<span>by <?php the_author_posts_link(); ?></span>
					<span><?php  the_time('M d Y'); ?></span>
				</div>
				
			</div>
			<!-- /Blog Post -->
			
		</div> <?php 		
		endforeach;
		$post = $tmp_post; 
	}  
} 

/*****************************************
* Get Current User Role
****************************************/
function kv_get_current_user_role(){
	$current_usr_rle = ''; 
	global $current_user, $wp_roles, $wpdb;
	wp_get_current_user();

	foreach ( $wp_roles->role_names as $role => $name ) :
		if ( current_user_can( $role ) )
			$current_usr_rle =  $role;
	endforeach;	
	return $current_usr_rle; 
}

################################################################################
// login url
################################################################################
function kv_login_url() {
	return get_site_url(). '/login/'; 
}

################################################################################
// Registration URL
################################################################################
function kv_registration_url() {
	return get_site_url(). '/register-new/'; 
}

################################################################################
// Validate the url
################################################################################
function tg_validate_url() {
	global $post;
	$page_url = esc_url(get_permalink( $post->ID ));
	$urlget = strpos($page_url, "?");
	if ($urlget === false) {
		$concate = "?";
	} else {
		$concate = "&";
	}
	return $page_url.$concate;
}

/*****************************************
*Last Login Time
****************************************/
add_action('wp_login', 'set_last_login');

function set_last_login($login) {
	//$user = get_userdatabylogin($login);
	$user = get_user_by( 'login', $login );
	$curent_login_time = get_user_meta(	$user->ID , 'current_login', true);
	//add or update the last login value for logged in user
	if(!empty($curent_login_time)){
		update_user_meta( $user->ID, 'last_login', $curent_login_time );
		update_user_meta( $user->ID, 'current_login', current_time('mysql') );
	}else {
		update_user_meta( $user->ID, 'current_login', current_time('mysql') );
		update_user_meta( $user->ID, 'last_login', current_time('mysql') );
	}
}

/*****************************************
* Get Last Login Date
****************************************/
function get_last_login($user_id) {
   $last_login = get_user_meta($user_id, 'last_login', true);

   $date_format = get_option('date_format') . ' ' . get_option('time_format');

   
	if(wp_is_mobile()) {
		$the_last_login = date("M j, y, g:i a", strtotime($last_login));  
	}else {
		$the_last_login = mysql2date($date_format, $last_login, false);
	}
   return $the_last_login;
}

/*****************************************
*Check Job Seeker CV and CV processing works
****************************************/
function eimams_has_current_user_cv($user_id) {
	global $wpdb; 
	$subactive_tbl = $wpdb->prefix.'jobseeker';
	return $wpdb->get_var(" SELECT cv_info FROM ".$subactive_tbl." WHERE wp_usr_id=".$user_id); 
}

/*****************************************
*Delete user informations from other tables
****************************************/
add_action( 'delete_user', 'kv_delete_user_info' );

function kv_delete_user_info( $user_id) {
	global $wpdb;
	
	// preventing admin user account deleting
	if($user_id == 1 || $user_id == 2)
		die(' Sorry, you can\'t delete this accounts');
		
	$user = new WP_User( $user_id );
	$role= wp_sprintf_l( '%l', $user->roles );
	if($role = 'job_seeker'){
		$job_seeker_tbl  = $wpdb->prefix.'jobseeker';
		$applied_jobs  = $wpdb->prefix.'applied_jobs';
		$notification= $wpdb->prefix.'newsletter';
		
		if(eimams_has_current_user_cv($user_id) != '' || eimams_has_current_user_cv($user_id) != null)
			wp_delete_attachment( eimams_has_current_user_cv($user_id));
			
		$wpdb->delete( $job_seeker_tbl, array( 'wp_usr_id' => $user_id ) );
		$wpdb->delete( $applied_jobs, array( 'job_seeker_id' => $user_id ) );
		$wpdb->delete( $notification, array( 'wp_user_id' => $user_id ) );

		$existing_img = get_user_meta($user_id,'user_image', true); 
		if( $existing_img['file']!= null)
			unlink ($existing_img['file']);
		
	}
	$subscription_tbl = $wpdb->prefix.'jbs_subactive';
	$wpdb->delete( $subscription_tbl, array( 'wp_user_id' => $user_id ) );
}

/*****************************************************
*Deactivate user informations from other tables
*****************************************************/
add_action( 'deactivate_user', 'kv_deactivate_user_info' );

function kv_deactivate_user_info( $user_id) {
	global $wpdb;
	
	echo " Summa"; 
}

/*****************************************
*Eimams Admin page functions
****************************************/
function kv_eimams_functions() {
	
	
}

/****************************************
* Eimams email functions
*****************************************/
function kv_email_eimams_functions(){
	global $email_header, $email_footer, $wpdb;
	$users_table = $wpdb->prefix.'users';
	/*****************************************
	*Deactivate user informations from other tables
	****************************************/
	if(isset($_GET['action']) && $_GET['action']== 'deactivate_user'){
		$user_id = $_GET['user'];
		$user_info = get_userdata($user_id);
		
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "deactivate_him") {
			$wpdb->update( $wpdb->users, array( 	'user_status' 	=>  2), array( 'ID' 	=> 	$user_id )	);
			if(isset($_POST['email_body'])) 
				$email_body = 	email_header(). nl2br($_POST['email_body']) .kv_email_footer() ;	
				
			$header = 'From: '. __('Admin', 'kvc') .' <info@eimams.com>' . '<br>';
			
			
			
			$kv_mail_report = wp_mail($user_info->user_email, 'You are Deactivated from Eimams due to violating our terms', $email_body, $header);
			
			wp_safe_redirect(admin_url('users.php'));
		} ?>
		<form method="POST">
			<table cellpadding="0" border="0" class="form-table">
				<tr><td colspan="2"> <h2>Deactivate user : <?php echo $user_info->display_name; ?></h2> </td> </tr>
							
				<tr> <td> Reason: </td> 	<td align="left">  <?php $args = array("textarea_name" => "email_body", "textarea_name" => "email_body", "textarea_rows" => "22", "teeny" => true, "media_buttons" => true , "quicktags" =>false);
				wp_editor( $pre_msg, "email_body", $args ); ?>  </td></tr>
				<input type="hidden" name="action" value="deactivate_him" />
				<tr><td colspan="2" align="center">	  <input type="submit" value="Send Message" name="submit" class="button"> </td> </tr>				
			</table>
		</form>
		
	<?php }
	/*****************************************
	*Activate user informations from other tables
	****************************************/
	elseif(isset($_GET['action']) && $_GET['action']== 'activate_user'){
		$user_id = $_GET['user'];
		$user_info = get_userdata($user_id);
		
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "activate_him") {
			$wpdb->update( $wpdb->users, array( 	'user_status' 	=>  0), array( 'ID' 	=> 	$user_id )	);
			if(isset($_POST['email_body'])) 
				$email_body = 	email_header(). nl2br($_POST['email_body']) .kv_email_footer() ;	
				
			$header = 'From: '. __('Admin', 'kvc') .' <info@eimams.com>' . '<br>';
						
			
			$kv_mail_report = wp_mail($user_info->user_email, 'You are Activated from Eimams', $email_body, $header);		
			
			wp_safe_redirect(admin_url('users.php'));
			
		} ?>
		<form method="POST">
			<table cellpadding="0" border="0" class="form-table">
				<tr><td colspan="2"> <h2>Deactivate user : <?php echo $user_info->display_name; ?></h2> </td> </tr>
							
				<tr> <td> Reason: </td> 	<td align="left">  <?php $args = array("textarea_name" => "email_body", "textarea_name" => "email_body", "textarea_rows" => "22", "teeny" => true, "media_buttons" => true , "quicktags" =>false);
				wp_editor( $pre_msg, "email_body", $args ); ?>  </td></tr>
				<input type="hidden" name="action" value="activate_him" />
				<tr><td colspan="2" align="center">	  <input type="submit" value="Send Message" name="submit" class="button"> </td> </tr>				
			</table>
		</form>
		
	<?php }else  {
		kv_admin_email();
	}
}

/*****************************************
*Get notification details using id
****************************************/
function get_notification_details_through_id($id) {
	global $wpdb; 
	$notifications_tbl = $wpdb->prefix.'notifications';
	return $wpdb->get_row("SELECT * FROM ".$notifications_tbl." WHERE id=".$id." LIMIT 1");

}

/*****************************************
*Get lasts list of active notifications
****************************************/
function get_latest_notifications_for_current_user($user_id, $role){
	global $wpdb; 
	$notifications_tbl = $wpdb->prefix.'notifications';
	if($role == 'job_seeker'){
		$seek_role = 'Job Seeker';
	} elseif($role == 'employer'){
		$seek_role = 'Employer';
	}
	$sql_query = "SELECT Message FROM ".$notifications_tbl." WHERE status='Active' AND (user_group='". $seek_role."' OR user_group='Both' OR (user_group='None' AND selected_users LIKE '%".$user_id."%')) ORDER BY id DESC";
	return $wpdb->get_results($sql_query, ARRAY_A);
}

/*****************************************
*Post Reference search 
****************************************/
function custom_search_query( $query ) {
    $custom_fields = array(
        // put all the meta fields you want to search for here
        "employer_ref"
    );
    $searchterm = $query->query_vars['s'];

    $query->query_vars['s'] = "";

    if ($searchterm != "") {
        $meta_query = array('relation' => 'OR');
        foreach($custom_fields as $cf) {
            array_push($meta_query, array(
                'key' => $cf,
                'value' => $searchterm,
                'compare' => 'LIKE'
            ));
        }
        $query->set("meta_query", $meta_query);
    };
}
add_filter( "pre_get_posts", "custom_search_query");


/*****************************************
*get user Subscription Info
****************************************/
function kv_get_user_newsletter_sub_info( $user_email=null ) {
	global $current_user, $wpdb; 
	$tbl_name = $wpdb->prefix.'newsletter';
	wp_get_current_user();
	if($user_email ==  null) 
		$user_email = $current_user->user_email;
		
	return $wpdb->get_row("SELECT * FROM $tbl_name WHERE email = $user_email");
}


/*****************************************
*get user Subscription Info through ID
****************************************/
function kv_get_user_newsletter_sub_info_frm_ID( $user_id=null ) {
	global $current_user, $wpdb; 
	$tbl_name = $wpdb->prefix.'newsletter';
	wp_get_current_user();
	if($user_id ==  null) 
		$user_id = $current_user->ID;
		
	return $wpdb->get_row("SELECT * FROM $tbl_name WHERE wp_user_id = $user_id");
}

/*****************************************
*get user Subscription Info through Uniquie ID
****************************************/
function kv_get_user_newsletter_sub_info_frm_unit_id( $unit_id=null ) {
	global $current_user, $wpdb; 
	$tbl_name = $wpdb->prefix.'newsletter';
	wp_get_current_user();
	if($unit_id !=  null) 		
		return $wpdb->get_row("SELECT * FROM $tbl_name WHERE unit_id = '".$unit_id."'");
	else
		return '' ; 
}


/*****************************************
* user Has Subscription Info
****************************************/
function kv_user_has_newsletter_sub_info( $user_email=null ) {
	global $current_user, $wpdb; 
	$tbl_name = $wpdb->prefix.'newsletter';
	wp_get_current_user();
	if($user_email ==  null) 
		$user_email = $current_user->user_email;
		
	return $wpdb->get_var("SELECT id FROM $tbl_name WHERE email = '".$user_email."'");
}

/*****************************************
* user unique id from email
****************************************/
function kv_get_user_unique_id_from_email( $user_email=null ) {
	global $current_user, $wpdb; 
	$tbl_name = $wpdb->prefix.'newsletter';
	wp_get_current_user();
	if($user_email ==  null) 
		$user_email = $current_user->user_email;
		
	return $wpdb->get_var("SELECT unit_id FROM $tbl_name WHERE email = '".$user_email."'");
}


/*****************************************
* check Unique ID Exist...
****************************************/
function kv_check_user_unique_existance( $uni_id=null ) {
	global $wpdb; 
	$tbl_name = $wpdb->prefix.'newsletter';		
	return $wpdb->get_var("SELECT COUNT(*) FROM $tbl_name WHERE unit_id = '".$uni_id."' LIMIT 1");
}


/***********************************************
*User dashboard Notifications from Admin Panel
************************************************/
/*
function kv_eimams_notification_from_admin() {
		global $wpdb; 
		$notifications_tbl = $wpdb->prefix.'notifications';
		
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "activate_him") {
			
			if(isset($_POST['msg_notify'])) {
				$msg_notify = 	$_POST['msg_notify'] ;	
				$wpdb->insert( $notifications_tbl,  array( 	'Message' 	=>  $msg_notify )	);
			}
			
		} ?>
		<form method="POST">
			<table cellpadding="0" border="0" class="form-table">
				<tr><td colspan="2"> <h2> User Notifications : </h2> </td> </tr>
				<!-- <tr> <td> Subject: </td>    <td> <input type="text" align="left" name="subj" size="80%" value="<?php //echo $subject ;?>" ></td></tr>      -->			
				<tr> <td> Notification Message: </td> 	<td align="left">  <?php $args = array("textarea_name" => "msg_notify", "textarea_name" => "msg_notify", "textarea_rows" => "22", "teeny" => true, "media_buttons" => true , "quicktags" =>false);
				wp_editor( $pre_msg, "msg_notify", $args ); ?>  </td></tr>
				<input type="hidden" name="action" value="activate_him" />
				<tr><td colspan="2" align="center">	  <input type="submit" value="Send Message" name="submit" class="button"> </td> </tr>				
			</table>
		</form>
		
	<?php 
}
*/
?>
