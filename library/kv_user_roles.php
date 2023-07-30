<?php 
function kv_user_rolesnew() {

	$buyer_cap= array(
		'read' => true,
		'upload_files' => true );
	$seller_cap =array(
		'read' => true,
		'edit_posts' => true,
		'read_pricate_posts' => true,
		'edit_published_posts' => true,
		'upload_files' => true,
		'delete_posts' => true,
		'moderate_commentes' =>true );
	
// Add new User Roles  for Project Site 

	$buyer_role_new = add_role('buyer_cap', 'Buyer' , $buyer_cap);
	$seller_role_new = add_role('seller_cap', ' Author/Seller', $seller_cap );
}
add_action('init', 'kv_user_rolesnew');


add_filter( 'avatar_defaults', 'customgravatar' );

function customgravatar ($avatar_defaults) {
	$myavatar = get_home_url('Template_directory') . '/images/new_default_avatar.png';
	$avatar_defaults[$myavatar] = "My Custom Logo";
	return $avatar_defaults;
}



?>