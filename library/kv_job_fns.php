<?php

/*****************************************
* Unread resumes of an employer
****************************************/	
function get_current_read_applied_resumes($read_count=null) {
	global $wpdb, $current_user;
	wp_get_current_user();
	$kv_current_role=kv_get_current_user_role();

	if(is_user_logged_in() && $kv_current_role == 'employer') {
		$post_ids_author = array();							
		$tabl_name = $wpdb->prefix.'applied_jobs';
		$args = array(
						'author'        =>  $current_user->ID,
						'orderby'       =>  'post_date',
						'order'         =>  'DESC',
						'post_type' 	=> 'job', 
						'post_status'	=>  'any',
						'posts_per_page' => -1
					);
		$current_user_posts = get_posts( $args );
		foreach( $current_user_posts as $post_ids) 
				$post_ids_author[] = $post_ids->ID;
		$ids = join(',',$post_ids_author);  
		if($read_count== null)
			$unread_query = "SELECT COUNT(*) FROM ".$tabl_name." WHERE job_id IN ($ids) AND status =0"; 
		else
			$unread_query = "SELECT COUNT(*) FROM ".$tabl_name." WHERE job_id IN ($ids)"; 
		if(!empty($ids))
			return  $wpdb->get_var($unread_query);  
		else
			return '' ;
	} 
}


/*****************************************
* Get Current Users Unread Ticket Count
****************************************/
function get_current_help_and_support_count(){
	global $current_user;
	wp_get_current_user();
	$tr_start =0; 
	$args = array(
				'post_type' => 'tickets', 
				'author' => $current_user->ID, 
				'post_status' => 'any'
			);	
	$myposts = get_posts( $args );
	foreach( $myposts as $post ) :  
		$args = array(	'post_id' => $post->ID);
		$comments = get_comments($args);										
		foreach($comments as $comment){			
			if($comment->user_id != $current_user->ID){
				if($comment->comment_karma == 0)
					$tr_start++;
			}											
		}
	endforeach;
	wp_reset_postdata();
	if($tr_start != 0)
		return $tr_start;
	else
		return '0';
}

/*****************************************
*Get Next Reference...
****************************************/
function get_next_reference(){
	$ref = get_option( 'next_reference' );
	$year = date("Y");
	if($ref == false){
		add_option( 'next_reference', '1', '', 'yes' );
		$ref= 1; 
	}
	return  'REF-'.$year.'-'.$ref;		
}

/*********************************************
*Save Next Employer Reference  for each job
**********************************************/
function save_next_ref($reference){	 
	$pos1 = strpos($reference, '-');
	$pos2 = strpos($reference, '-', $pos1 + strlen('-'));
	$reference=intval(substr($reference,$pos2+1));
	$reference = $reference+1;
	update_option( 'next_reference', $reference ); 
}

/*********************************************
*Whether the jobseeker applied  this job
**********************************************/
function has_jobseeker_applied_this_job($job_id, $jobseeker_id){
	global $wpdb; 
	$table_name= $wpdb->prefix.'applied_jobs';
	$count = $wpdb->get_var( "SELECT COUNT(*) FROM ".$table_name." WHERE job_id=".$job_id." AND job_seeker_id=".$jobseeker_id );
	if($count > 0)
		return false;
	else 
		return true;
}


/* job seeker available job and count */
function get_available_job_and_count() {

	global $current_user, $wp_roles, $wpdb; 
	wp_get_current_user();
	$job_seeker_table = $wpdb->prefix.'jobseeker';
	$query = $wpdb->get_row('SELECT * FROM ' . $job_seeker_table . ' WHERE wp_usr_id =' .$current_user->ID,ARRAY_A  );
	$category=$query['category'];
	$qualification=$query['qualification'];
	$type=$query['type'];
	
	$madhab=$query['madhab'];
	$aqeeda=$query['aqeeda'];
	$language=$query['language'];
	$location=$query['location'];
	//$pref_sal_prd=$query['pref_sal_prd'];
	//$pref_sal_optn=$query['pref_sal_optn'];
	//$gender=$query['gender'];
	$args = array(
		'post_type' => 'job', 
		/*($gender != null) ? array(
			//'meta_key'  => $gender
			'meta_key'   => 'gender',
			'meta_value' => $gender
		): '' ,*/
		'tax_query' => array(		

	($category != null) ? array(
				'taxonomy' => 'job_category',
				'field'    => 'term_id',
				'terms'    => $category
			): '' ,
			
		/*	($qualification != null) ? array(
				'taxonomy' => 'qualification',
				'field'    => 'term_id',
				'terms'    => $qualification
			): '',
			($type != null) ? array(
				'taxonomy' => 'types',
				'field'    => 'term_id',
				'terms'    => $type
			): '',
			($madhab != null) ? array(
				'taxonomy' => 'madhab',
				'field'    => 'term_id',
				'terms'    => $madhab
			): '',
	($aqeeda != null) ? array(
				'taxonomy' => 'aqeeda',
				'field'    => 'term_id',
				'terms'    => $aqeeda
			): '',
	($language != null) ? array(
				'taxonomy' => 'languages',
				'field'    => 'term_id',
				'terms'    => $language
			): '',
	($location != null) ? array(
				'taxonomy' => 'zone',
				'field'    => 'term_id',
				'terms'    => $location
			): '',
/*	($pref_sal_prd != null) ? array(
				'taxonomy' => 'sal_prd',
				'field'    => 'term_id',
				'terms'    => $pref_sal_prd
			): '' ,
	($pref_sal_optn != null) ? array(
				'taxonomy' => 'sal_optn',
				'field'    => 'term_id',
				'terms'    => $pref_sal_optn
			): '',	*/
			),

		'post_status' =>array('publish')
		//'paged'=> $paged
	);
	//print_r($args);	
	$query = new WP_Query( $args );
	return $query;
}


?>