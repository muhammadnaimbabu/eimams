<?php
require_once("user-backend-main.php");
global $current_user, $wp_roles, $wpdb;
$job_seeker_table = $wpdb->prefix.'jbs_subactive';
	$today=date('Y-m-d');
if(isset($_GET['edit_id']))
	$edit_id=$_GET['edit_id'];
else
	$edit_id=0; 

$kv_errors = array();
	
if($edit_id!=0){
	$edit_pack = $wpdb->get_row("SELECT * FROM " . $job_seeker_table . " WHERE id =".$edit_id , ARRAY_A);
//	print_r($edit_pack);
}
else
	$edit_pack=array( 'wp_user_id' => '','per_post' => 0, 'date_subscribed' => '', 'pack_id' => '','end_date' => $today,'status' => ''  );

if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['submit_form'])) {
	$errors = new WP_Error();	
	$fields = array(
				'wp_user_id',
				'date_subscribed',
				'pack_id',
				'end_date',
				'per_post',
				'status'	
	);
	foreach ($fields as $field) {
		if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
	}
	//print_r($posted);
	//exit(0);
	if ($posted['wp_user_id'] != -1 )
		$wp_user_id =  $posted['wp_user_id'];
	else 
		array_push($kv_errors,  __('<strong>Notice</strong>: Please enter your Subscription Pack Name.', 'kv_project'));
		
	if ($posted['date_subscribed'] != null )
		$date_subscribed =  $posted['date_subscribed'];
	else 
		array_push($kv_errors,  __('<strong>Notice</strong>: Please enter date_subscribed.', 'kv_project'));
		
	if ($posted['pack_id'] != -1 )
		$pack_id =  $posted['pack_id'];
	else 
		array_push($kv_errors,  __('<strong>Notice</strong>: Please enter Pack ID.', 'kv_project'));		
		
	if ($posted['end_date'] != null )
		$end_date =  $posted['end_date'];
	else 
		array_push($kv_errors,  __('<strong>Notice</strong>: Please enter End Date.', 'kv_project'));

	$errors = array_filter($kv_errors);

	if (empty($errors)) { 
		if ($edit_id!=0 && $edit_id!='' ) {	
			$wpdb->update( $job_seeker_table, 
				array( 					
					'wp_user_id' 		=>  $posted['wp_user_id'],
					'date_subscribed'	=>  $posted['date_subscribed'],
					'pack_id' 			=>  $posted['pack_id'],
					'end_date' 			=>  $posted['end_date'],
					'per_post' 			=>  $posted['per_post'],
					'status' 			=>  $posted['status']
					), array( 'id' 		=> 	$edit_id ));
		}else{	
			$wpdb->insert( $job_seeker_table, 
				array( 
					'wp_user_id' 		=>  $posted['wp_user_id'],
					'date_subscribed'	=>  $posted['date_subscribed'],
					'pack_id' 			=>  $posted['pack_id'],
					'end_date' 			=>  $posted['end_date'],
					'per_post' 			=>  $posted['per_post'],
					'status' 			=>  $posted['status']
				)
			);
		}
		wp_safe_redirect(admin_url(). 'admin.php?page=sub_active');
	}
} ?>


<div class="wrap">
<?php if(isset($_GET['edit_id'])){
	echo '<h2>Edit User subscription </h2>';
} else
	echo '<h2>Add New Subscriber </h2>';

 if(!empty($errors)) {
		echo '<div class="error">';
		foreach ($kv_errors as $error) {
			echo '<p>'.$error.'</p>';
		}
		echo '</div>';
} ?>

<form method="post" action="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI']; ?>" novalidate="novalidate">
<input type="hidden" name="option_page" value="general"><input type="hidden" name="action" value="update"><input type="hidden" id="_wpnonce" name="_wpnonce" value="fd8704f148"><input type="hidden" name="_wp_http_referer" value="/wp-admin/options-general.php">
<table class="form-table">
<tbody><tr>
<th scope="row"><label for="blogname">User Name <span style="color:red">*</span></label></th>
<td><select style="width: 30%;" name="wp_user_id" id="wp_user_id">
			<option id="user_name_id" value="-1">Select a User Name</option>
	<?php 
	
	$roles = array('job_seeker','employer');
$users = array();
foreach ($roles as $role) {
    $args = array('role'=>$role);
    $usersofrole = get_users($args);	
    $users = array_merge($usersofrole,$users);
} 	foreach ( $users as $user ) {	
	echo '<option value="'.$user->ID.'"';
	if($user->ID==$edit_pack['wp_user_id']){echo "selected";}
	echo '  data-role="'.$user->roles[0].'" >'.$user->user_login.'</option>';
} ?> </select>  <?php kv_is_user_in_role(8, 'job_seeker'); ?></td>
</tr>
<tr>
<th scope="row"><label for="blogname">Start Date<span style="color:red">*</span></label></th>
<td><input data-date-format="yyyy-mm-dd" name="date_subscribed" style="width: 30%;" class="example-datepicker datepicker" id="start_date" type="text" value="<?php echo $edit_pack["date_subscribed"]; ?>" size="40" aria-required="true"></td>
</tr>

<tr>
<th scope="row"><label for="blogname">Pack Name <span style="color:red">*</span></label></th>
<td><?php 	$sub_pack_name = $wpdb->get_results( "SELECT * FROM eimam_jbs_subpack where status='Active'");  
				//print_r($sub_pack_name); 
				echo '<select style="width: 30%;" name="pack_id" id="pack_name">';  
				echo '<option id="pack_name_id" value="-1">Select a Pack Name</option>';
				foreach ( $sub_pack_name as $pack ) {
			echo '<option id="pack_name_id" value="'.$pack->id.'"';
			if((int)$edit_pack['pack_id']==(int)$pack->id ) { echo "selected";}
			echo '>'.$pack->pack_name.'</option>';
				} echo '</select>'; ?> </td>
</tr>
<?php if($edit_id==0){ ?>
		<tr class="job_post_th">
		<th scope="row"><label for="blogname">Job Post <span style="color:red">*</span></label></th>
		<td><input  name="per_post"  style="width: 30%;" id="per_post" type="text" value="<?php $edit_pack["per_post"]; ?>" size="40" aria-required="true">
			<input name="end_date" type="hidden" value="<?php echo $today; ?>"></td>
		</tr>

		<tr class="end_date" >
		<th scope="row"><label for="blogname">End Date <span style="color:red">*</span></label></th>
		<td><input data-date-format="yyyy-mm-dd" class="datepicker" name="end_date"  style="width: 30%;" id="e_date" type="text" value="<?php echo $edit_pack["end_date"]; ?>" size="40" aria-required="true"></td>
		</tr>

<?php } else{
		 ?>

		<tr class="alloc_post" >
			<th scope="row" ><label for="blogname">Job Post <span style="color:red">*</span></label></th>
			<td><input  name="per_post"  style="width: 30%;" id="per_post" type="text" value="<?php echo $edit_pack["per_post"]; ?>" size="40" aria-required="true">
			<input name="end_date" type="hidden" value="<?php echo $today; ?>"></td>
			</tr>
			
		<tr class="end_date" >
		<th scope="row"><label for="blogname">End Date <span style="color:red">*</span></label></th>
		<td><input data-date-format="yyyy-mm-dd" class="datepicker" name="end_date"  style="width: 30%;" id="e_date" type="text" value="<?php echo $edit_pack["end_date"]; ?>" size="40" aria-required="true"></td>
		</tr>
<?php  } ?>

<tr>
<th scope="row"><label for="blogname">Status <span style="color:red">*</span></label></th>
<td><select name="status" style="width: 30%;" id="Status" class="postform">
				<option class="level-0" value="Active" <?php if($edit_pack["status"] == 'Active') echo ' selected'; ?> >Active</option>
				<option class="level-0" value="Discontinued" <?php if($edit_pack["status"] == 'Discontinued') echo ' selected'; ?> >Discontinued</option>
				<option class="level-0" value="Changed" <?php if($edit_pack["status"] == 'Changed') echo ' selected'; ?> >Changed</option>
				<option class="level-0" value="Renewed" <?php if($edit_pack["status"] == 'Renewed') echo ' selected'; ?> >Renewed</option>
				<option class="level-0" value="Expired" <?php if($edit_pack["status"] == 'Expired') echo ' selected'; ?> >Expired</option>
			</select><input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>"> </td>
</tr>
	
</tbody></table>
<?php if($edit_id > 0 && $edit_pack['per_post']!=0){ 
			echo '<style> 
				.alloc_post { display: table-row; } 
				.end_date { display: none; } 
				</style>'; 
		}else { 
			echo '<style> 
				.alloc_post { display: none; } 
				.end_date { display: table-row; } 
				</style>';
		}
		?>
<p class="submit"><input type="submit" name="submit_form" id="submit" class="button button-primary" value="Save Changes"></p></form>

</div>	

	
<script>
ajax_url = location.protocol + "//" +document.domain+'/ajax';
jQuery(function(){ 
    jQuery(".datepicker").datepicker();
  });
jQuery(document).ready(function(){

	jQuery("#wp_user_id").on("change", function(e){
		var Usr_role = jQuery(this).find(':selected').data('role');
		if(Usr_role == 'job_seeker'){
			jQuery(".job_post_th").css("display" , "none");
		}else{
			jQuery(".job_post_th").css('display' ,'table-row');
		}
		jQuery.ajax({
			type:"POST",
			url: ajax_url,
			data: {
				  action: "get_rolebased_packs",
				  role: Usr_role
			},
			success:function(data){				
				var dataArray = JSON.parse(data);
				//console.log(data);
				jQuery("#pack_name option").remove();
				jQuery('#pack_name').append(jQuery('<option>', { 
						value: '-1',
						text : 'Select a Pack Name' 
					}));
				jQuery.each(dataArray, function (key, value) {
					//alert(value.id);
					jQuery('#pack_name').append(jQuery("<option></option>").attr("value",value.id).text(value.name)); 
				});				
			},
			error: function(errorThrown){
				   console.log(errorThrown);
			} 
		});
	});
	jQuery("#pack_name").on('change',function(){
		var id=jQuery(this).val();	
		var s_date=jQuery('#start_date').val();	
		jQuery.ajax({
			type:"POST",
			url: ajax_url,
			data: {
				  action: "get_enddate",
				  pack_id: id,
				  s_date:s_date
			},
			success:function(data){
				
				if(isNaN(data)){
					//alert(data+'srgserg');
					jQuery("#e_date").val(data);
					jQuery(".end_date").css("display", "table-row");
					jQuery(".alloc_post").css("display", "none");
				}
				else{
					//alert(data+'fghjfrtjdrt');
					jQuery("#per_post").val(data);
					jQuery(".end_date").css("display", "none");
					jQuery(".alloc_post").css("display", "table-row");
				}
			},
			error: function(errorThrown){
				   console.log(errorThrown);
			} 
		});
	});
});
</script>
<style>
#ui-datepicker-div{display:none !important;}
</style>