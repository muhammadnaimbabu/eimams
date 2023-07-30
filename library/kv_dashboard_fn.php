<?php 

/*************************************************
* Job Post Count functions
**************************************************/
function kv_get_total_jobs_counts($user_id=null) {
	global $wpdb; 
	if($user_id == null)
		return $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='job' AND post_status != 'trash' ");
	else 
		return $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='job' AND post_status != 'trash' AND post_author=".$user_id);
}

function kv_get_pending_jobs_counts($user_id=null) {
	global $wpdb; 
	if($user_id == null)
		return $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='job' AND post_status = 'pending' ");
	else 
		return $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='job' AND post_status = 'pending' AND post_author=".$user_id);
}
function kv_get_published_jobs_counts($user_id=null) {
	global $wpdb; 
	if($user_id == null)
		return $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='job' AND post_status = 'publish' ");
	else 
		return $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='job' AND post_status = 'publish' AND post_author=".$user_id);
}

function kv_get_expired_jobs_counts($user_id=null) {
	global $wpdb; 
	if($user_id == null)
		return $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='job' AND post_status = 'expired' ");
	else 
		return $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='job' AND post_status = 'expired' AND post_author=".$user_id);
}

function kv_get_applied_jobs_counts($user_id) {
	global $wpdb; 
	$tbl_name = $wpdb->prefix."applied_jobs";
	return $wpdb->get_var( "SELECT COUNT(ID) FROM ".$tbl_name." WHERE job_seeker_id=".$user_id);
}


/*************************************************
* Job Post Count functions
**************************************************/
function kv_get_total_tickets_counts() {
	global $wpdb; 
	return $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='tickets' AND post_status != 'trash' ");
}

function kv_get_pending_tickets_counts() {
	global $wpdb; 
	return $wpdb->get_var( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='tickets' AND post_status = 'pending' ");
}

/*************************************************
*User Subscriptions count
**************************************************/
function kv_get_total_subscription_counts() {
	global $wpdb; 
	$Sub_table = $wpdb->prefix."jbs_subactive";
	return $wpdb->get_var( "SELECT COUNT(id) FROM ".$Sub_table);
}

function kv_get_total_subscription_pack_counts($role='job_seeker') {
	global $wpdb; 
	$blogusers = get_users( 'role='.$role );
	$users_ids = array();
	foreach ( $blogusers as $user ) {
		$users_ids[] =  $user->ID;
	}
	$Sub_table = $wpdb->prefix."jbs_subactive";
	return $wpdb->get_var( "SELECT COUNT(id) FROM ".$Sub_table." WHERE wp_user_id IN (" . implode(',', array_map('intval', $users_ids)) . ")");
	
	//$Sub_table = $wpdb->prefix."jbs_subpack";
	//return $wpdb->get_var( "SELECT COUNT(id) FROM ".$Sub_table." WHERE role='".$role."'");
}

function kv_get_total_active_subscription_counts($role='job_seeker') {
	global $wpdb; 
	$blogusers = get_users( 'role='.$role );
	$users_ids = array();
	foreach ( $blogusers as $user ) {
		$users_ids[] =  $user->ID;
	}
	$Sub_table = $wpdb->prefix."jbs_subactive";
	return $wpdb->get_var( "SELECT COUNT(id) FROM ".$Sub_table." WHERE status='Active' AND wp_user_id IN (" . implode(',', array_map('intval', $users_ids)) . ")");
}

function kv_get_total_expired_subscription_counts($role='job_seeker') {
	global $wpdb; 
	$blogusers = get_users( 'role='.$role );
	$users_ids = array();
	foreach ( $blogusers as $user ) {
		$users_ids[] =  $user->ID;
	}
	$Sub_table = $wpdb->prefix."jbs_subactive";
	return $wpdb->get_var( "SELECT COUNT(id) FROM ".$Sub_table." WHERE status='Expired' AND wp_user_id IN (" . implode(',', array_map('intval', $users_ids)) . ")");
}
function kv_get_total_yet2_active_subscription_counts($role='job_seeker') {
	global $wpdb; 
	$blogusers = get_users( 'role='.$role );
	$users_ids = array();
	foreach ( $blogusers as $user ) {
		$users_ids[] =  $user->ID;
	}
	$Sub_table = $wpdb->prefix."jbs_subactive";
	return $wpdb->get_var( "SELECT COUNT(id) FROM ".$Sub_table." WHERE status='Yet To Active' AND wp_user_id IN (" . implode(',', array_map('intval', $users_ids)) . ")");
}

function kv_get_total_expire_in3days_subscription_counts($role='job_seeker') {
	global $wpdb; 
	$blogusers = get_users( 'role='.$role );
	$users_ids = array();
	foreach ( $blogusers as $user ) {
		$users_ids[] =  $user->ID;
	}
	$Sub_table = $wpdb->prefix."jbs_subactive";
	return $wpdb->get_var( "SELECT COUNT(id) FROM ".$Sub_table." WHERE status='Active' AND end_date = DATE_ADD(CURDATE(), INTERVAL +3 DAY) AND wp_user_id IN (" . implode(',', array_map('intval', $users_ids)) . ")");
}


/*****************************************
* Show Pending Jobs post count on Admin Menu
****************************************/
add_filter( 'add_menu_classes', 'show_pending_number');
function show_pending_number( $menu ) {
    $type = "job";
    $status = "pending";
    $num_posts = wp_count_posts( $type, 'readable' );
    $pending_count = 0;
    if ( !empty($num_posts->$status) )
        $pending_count = $num_posts->$status;

    // build string to match in $menu array
    if ($type == 'post') {
        $menu_str = 'edit.php';
    // support custom post types
    } else {
        $menu_str = 'edit.php?post_type=' . $type;
    }

    // loop through $menu items, find match, add indicator
    foreach( $menu as $menu_key => $menu_data ) {
        if( $menu_str != $menu_data[2] )
            continue;
        $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
    }
    return $menu;
}

add_filter( 'add_menu_classes', 'show_pending_request');
function show_pending_request( $menu ) {
    $type = "speak_art_request";
    $status = "pending";
    $num_posts = wp_count_posts( $type, 'readable' );
    $pending_count = 0;
    if ( !empty($num_posts->$status) )
        $pending_count = $num_posts->$status;

    // build string to match in $menu array
    if ($type == 'post') {
        $menu_str = 'edit.php';
    // support custom post types
    } else {
        $menu_str = 'edit.php?post_type=' . $type;
    }

    // loop through $menu items, find match, add indicator
    foreach( $menu as $menu_key => $menu_data ) {
        if( $menu_str != $menu_data[2] )
            continue;
        $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
    }
    return $menu;
}


/*****************************************
* Show Pending Jobs post count on Admin Menu
****************************************/
add_filter( 'add_menu_classes', 'show_ticket_pending_number');
function show_ticket_pending_number( $menu ) {
    $type = "tickets";
    $status = "pending";
    $num_posts = wp_count_posts( $type, 'readable' );
    $pending_count = 0;
    if ( !empty($num_posts->$status) )
        $pending_count = $num_posts->$status;

    // build string to match in $menu array
    if ($type == 'post') {
        $menu_str = 'edit.php';
    // support custom post types
    } else {
        $menu_str = 'edit.php?post_type=' . $type;
    }

    // loop through $menu items, find match, add indicator
    foreach( $menu as $menu_key => $menu_data ) {
        if( $menu_str != $menu_data[2] )
            continue;
        $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
    }
    return $menu;
}

/*************************************************
*User Subscriptions Details
**************************************************/
function kv_get_deashboard_subscription_info($user_id) {
	global $wpdb; 
	$Sub_table = $wpdb->prefix.'jbs_subactive';
	
	if($user_id != null){
		$resrows= $wpdb->get_results("SELECT * FROM ".$Sub_table." WHERE wp_user_id=".$user_id." ORDER BY CASE status
         WHEN 'Yet To Activate' THEN 1
         WHEN 'Active' THEN 2
         WHEN 'Expired' THEN 3
         END ASC LIMIT 3" );
		echo '<div class="col-md-12 col-sm-12 col-xs-12">
				 
				  <div class="small-box">
				  <div class="inner" style="background-color:#fff;" > <p style="color:#666" > <span style="float: left;" >Subscription</span> <span style="text-align: center" >Date of Expire</span> <span style="float:right;" >Status</span></p> </div>'; 
		foreach ( $resrows as $resrow ) {
			//echo $resrow->id;
			if($resrow->per_post != 0 )
				$end_details = $resrow->per_post.' Job Posts';
			else
				$end_details = $resrow->end_date.'';
				
			if($resrow->status == 'Yet To Active')
				echo '<div class="inner bg-aqua">  <p > <span style="float: left;">'.get_packname_using_id($resrow->pack_id).'</span> <span style="text-align: center" >'.$end_details.'</span> <span style="float:right;" >'.$resrow->status.'</span></p>  </div>';
			else if($resrow->status == 'Active' && (isset($expiring_soon) && $expirng_soon == true)	)	
				echo '<div class="inner bg-yellow">  <p> <span style="float: left;">'.get_packname_using_id($resrow->pack_id).'</span> <span style="text-align: center" >'.$end_details.'</span> <span style="float:right;" >'.$resrow->status.'</span></p>  </div>';
			else if($resrow->status == 'Active')		
				echo '<div class="inner bg-green">   <p> <span style="float: left;">'.get_packname_using_id($resrow->pack_id).'</span> <span style="text-align: center" >'.$end_details.'</span> <span style="float:right;" >'.$resrow->status.'</span></p>  </div>';
			else if($resrow->status == 'Expired')		
				echo '<div class="inner bg-red">    <p> <span style="float: left;">'.get_packname_using_id($resrow->pack_id).'</span> <span style="text-align: center" >'.$end_details.'</span> <span style="float:right;" >'.$resrow->status.'</span></p> </div>    '; 
		}
		echo ' <a href="'.site_url('subscription').'" style="background: rgba(0,0,0,0.1);" class="small-box-footer bg-green">
					  More info <i class="fa fa-arrow-circle-right"></i>
					</a>
				  </div>
				</div>' ; 
	}
	else {
		echo 'no user associated with it';
	}		
}
