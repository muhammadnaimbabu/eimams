<?php
global $current_user, $wp_roles, $wpdb;
$job_seeker_table = $wpdb->prefix.'jbs_subpack';
$kv_errors = array();
if(isset($_GET['edit_id']))
	$edit_id=$_GET['edit_id'];
else
	$edit_id=0;
if($edit_id!=0){
	$edit_pack = $wpdb->get_row("SELECT * FROM " . $job_seeker_table . " WHERE id =".$edit_id , ARRAY_A);
}
else{

	$fields = array(
				'pack_name',				
				'duration',
				'period',
				'price',
				'type_of_pack',
				'left_count',
				'per_post',
				'status'
			);
	foreach ($fields as $field) 
		if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';

	$edit_pack=array( 'pack_name' => $posted['pack_name'],'role' => 'employer', 'duration' => $posted['duration'], 'price' => $posted['price'],  'left_count' => $posted['left_count'],'per_post' => $posted['per_post'],'period' => $posted['period'],'status' => $posted['status']  );
}

if(isset($_GET['role']) && $_GET['role'] == 'employer') {
		
	if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['submit_form'])) {
		
		$fields = array(
					'pack_name',				
					'duration',
					'period',
					'price',
					'type_of_pack',
					'per_post',
					'left_count',
					'status'
				);
		foreach ($fields as $field) {
			if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
		}
		if ($posted['pack_name'] != null )
			$pack_name =  $posted['pack_name'];
		else 
			array_push($kv_errors, __('<b>Notice</b>: Please enter your Subscription Pack Name.', 'kv_project'));	

		if($posted['type_of_pack'] == 0)	{
			if ($posted['duration'] == null || (int)$posted['duration'] <1 )
				array_push($kv_errors, __('<b>Notice</b>:  Please enter Duration.', 'kv_project'));

			if ($posted['period'] == '-1' || $posted['period'] =='0' )
				array_push($kv_errors, __('<b>Notice</b>:  Please enter Period.', 'kv_project'));
		}else {
			if($posted['per_post'] == null || $posted['per_post'] <= 0)
				array_push($kv_errors, __('<b>Notice</b>:  Please enter Total Posts.', 'kv_project'));
		}
		
		if ($posted['price'] != null )
			$price =  $posted['price'];
		else 
			array_push($kv_errors, __('<b>Notice</b>:  Please enter Price.', 'kv_project'));
		
		$errors = array_filter($kv_errors);

		if (empty($errors)) {
			if($posted['type_of_pack'] == 0){			
				$posted['per_post']=0;
			}else {
				$posted['period']='';
				$posted['duration']=0;
			}
			
			if ($edit_id!=0 && $edit_id!='' ) {	
				$wpdb->update( $job_seeker_table, 
					array( 					
						'pack_name' 		=>  $posted['pack_name'],
						'role' 				=>  'employer',
						'duration'			=>  $posted['duration'],
						'period' 			=>  $posted['period'],
						'price' 			=>  $posted['price'],
						'per_post' 			=>  $posted['per_post'],
						'left_count' 		=>  0-$posted['left_count'],
						'status' 			=>  $posted['status'],
						), array( 'id' 		=> 	$edit_id ));
			} else{			
				
				$wpdb->insert( $job_seeker_table, 
					array( 
						'pack_name' 		=>  $posted['pack_name'],
						'role' 				=>  'employer',
						'duration'			=>  $posted['duration'],
						'period' 			=>  $posted['period'],
						'price' 			=>  $posted['price'],
						'per_post' 			=>  $posted['per_post'],
						'left_count' 		=>  0-$posted['left_count'],
						'left_offer'		=>  'yes',
						'status' 			=>  $posted['status']
						));
			}
			$redirect_url =  get_admin_url( null, 'admin.php?page=subscription_pack' );
			wp_safe_redirect($redirect_url); 
		}
	}?>

	<div class="wrap">
	<h2>Add New Employer Packs</h2>
	<?php 
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
	<th scope="row"><label for="blogname">Pack Name <span style="color:red">*</span></label></th>
	<td><input name="pack_name" style="width: 30%;" type="text" id="pack_name" value="<?php echo $edit_pack["pack_name"]; ?>" size="40" class="regular-text" aria-required="true"></td>
	</tr>

	<tr><th scope="row"><label for="siteurl">Price(&pound)<span style="color:red">*</span></label></th>
	<td><input name="price"  style="width: 30%;" id="price" type="text" value="<?php echo $edit_pack["price"];?>" size="40" aria-required="true"></td>
	</tr>
	<tr> 
	<th scope="row"><label for="type_of_pack">  Type <span style="color:red">*</span> </label></th>
	<td> <select name="type_of_pack" id="type_of_pack">
			<option value="0"> Duration </option>
			<option value="1"> Post Count </option>
		</select>

	<tr>
	<th scope="row"><label id="duration_lbl">Duration<span style="color:red">*</span></label></th>
	<td> 
	<div id="duration_field">
	<input name="duration" style="width: 10%;" id="tag-name" type="number" min="1" max="31" value="<?php echo $edit_pack["duration"]; ?>" size="40" aria-required="true">
		<select name="period" id="period" style="width: 20%;" class="postform">
			<option value="0">None</option>
			<option class="level-0" value="Days" <?php if($edit_pack["period"] == 'Days') 		echo ' selected'; ?> >Days</option>
			<option class="level-0" value="Weeks" <?php if($edit_pack["period"] == 'Weeks') 	echo ' selected'; ?> >Weeks</option>
			<option class="level-0" value="Months" <?php if($edit_pack["period"] == 'Months')	echo ' selected'; ?> >Months</option>
			<option class="level-0" value="Years" <?php if($edit_pack["period"] == 'Years')		echo ' selected'; ?> >Years</option>
		</select>
		</div>
		<div id="post_count_field" >
			<input name="per_post"  style="width: 30%;" id="per_post" type="text" value="<?php echo $edit_pack["per_post"]; ?>" size="40" aria-required="true">
		</div>
		</td>
	</tr>
	<!--
	<tr><th scope="row"><label for="siteurl">Total Post</label></th>
	<td></td>
	</tr>
	-->
	<tr><th scope="row"><label for="siteurl">Set Count <span style="color:red">*</span></label></th>
	<td><input name="left_count"  style="width: 30%;" id="price" type="text" value="<?php echo abs($edit_pack["left_count"]);?>" size="40" aria-required="true"></td>
	</tr>
	
	
	<tr><th scope="row"><label for="siteurl">Status</label></th>
	<td><select name="status" style="width: 30%;" id="Status" class="postform">
			<option class="level-0" value="Active" <?php if($edit_pack["status"] == 'Active') echo ' selected'; ?> >Active</option>
			<option class="level-0" value="Inactive" <?php if($edit_pack["status"] == 'Inactive') echo ' selected'; ?> >Inactive</option>
		</select>		
		<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>"></td>
	</tr>

	</tbody></table>

	<p class="submit"><input type="submit" name="submit_form" id="submit" class="button button-primary" value="Save Changes"></p></form>

	</div>
	<script> 
	jQuery(function(){
		jQuery("#post_count_field").hide();
		jQuery('select#type_of_pack').on('change', function() {
			var selected_optn=  this.value; 
			//alert(jQuery("label#duration_lbl").html());
			if(selected_optn == 0 ){
				jQuery("label#duration_lbl").html("Duration<span style='color:red'>*</span>");
				jQuery("#post_count_field").hide();
				jQuery("#duration_field").show();
			}
			else{
				jQuery("label#duration_lbl").html("Total Posts<span style='color:red'>*</span>");
				jQuery("#post_count_field").show();
				jQuery("#duration_field").hide();
			}
		});
	});
	</script>
<?php } 
else { 
	if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['submit_form'])) {
	
		$fields = array(
					'pack_name',				
					'duration',
					'period',
					'price',				
					'left_count',				
					'status'
				);
		foreach ($fields as $field) {
			if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
		}
		if ($posted['pack_name'] != null )
			$pack_name =  $posted['pack_name'];
		else 
			array_push($kv_errors, __('<b>Notice</b>: Please enter your Subscription Pack Name.', 'kv_project'));		
		if ($posted['duration'] != null && $posted['duration'] > 0)
			$duration =  $posted['duration'];
		else 
			array_push($kv_errors, __('<b>Notice</b>:  Please enter Duration.', 'kv_project'));
				
		if ($posted['period'] != -1 )
			$period =  $posted['period'];
		else 
			array_push($kv_errors, __('<b>Notice</b>:  Please enter Period.', 'kv_project'));	
		
		if ($posted['price'] != null )
			$price =  $posted['price'];
		else 
			array_push($kv_errors, __('<b>Notice</b>:  Please enter Price.', 'kv_project'));
		
		$errors = array_filter($kv_errors);

		
		if (empty($errors)) {
			if ($edit_id!=0 && $edit_id!='' ) {	
				$wpdb->update( $job_seeker_table, 
					array( 					
						'pack_name' 		=>  $posted['pack_name'],
						'role' 				=>  'job_seeker',
						'duration'			=>  $posted['duration'],
						'period' 			=>  $posted['period'],
						'price' 			=>  $posted['price'],
						'left_count' 		=>  0-$posted['left_count'],
						'per_post' 			=>  0,
						'status' 			=>  $posted['status'],
						), array( 'id' 		=> 	$edit_id ));
			} else{
				
					
				$wpdb->insert( $job_seeker_table, 
					array( 
						'pack_name' 		=>  $posted['pack_name'],
						'role' 				=>  'job_seeker',
						'duration'			=>  $posted['duration'],
						'period' 			=>  $posted['period'],
						'price' 			=>  $posted['price'],
						'left_count' 		=>  0-$posted['left_count'],
						'left_offer'		=>  'yes',
						'per_post' 			=>  0,
						'status' 			=>  $posted['status']
						));
			}
			$redirect_url =  get_admin_url( null, 'admin.php?page=subscription_pack' );
			wp_safe_redirect($redirect_url); 
		}
	}?>

	<div class="wrap">
	<h2>Add New Jobseeker Packs</h2>
	<?php 
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
	<th scope="row"><label for="blogname">Pack Name <span style="color:red">*</span></label></th>
	<td><input name="pack_name" style="width: 30%;" type="text" id="pack_name" value="<?php echo $edit_pack["pack_name"]; ?>" size="40" class="regular-text" aria-required="true"></td>
	</tr>

	<tr><th scope="row"><label for="siteurl">Price(&pound)<span style="color:red">*</span></label></th>
	<td><input name="price"  style="width: 30%;" id="price" type="text" value="<?php echo $edit_pack["price"];?>" size="40" aria-required="true"></td>
	</tr>

	<tr>
	<th scope="row"><label for="siteurl">Duration<span style="color:red">*</span></label></th>
	<td><input name="duration" style="width: 10%;" id="tag-name" type="number" min="1" max="31" value="<?php echo $edit_pack["duration"]; ?>" size="40" aria-required="true">
		<select name="period" id="period" style="width: 20%;" class="postform">
			<option value="-1">None</option>
			<option class="level-0" value="Days" <?php if($edit_pack["period"] == 'Days') 		echo ' selected'; ?> >Days</option>
			<option class="level-0" value="Weeks" <?php if($edit_pack["period"] == 'Weeks') 	echo ' selected'; ?> >Weeks</option>
			<option class="level-0" value="Months" <?php if($edit_pack["period"] == 'Months')	echo ' selected'; ?> >Months</option>
			<option class="level-0" value="Years" <?php if($edit_pack["period"] == 'Years')		echo ' selected'; ?> >Years</option>
		</select></td>
	</tr>
	<tr><th scope="row"><label for="siteurl">Set Count <span style="color:red">*</span></label></th>
	<td><input name="left_count"  style="width: 30%;" id="price" type="text" value="<?php echo abs($edit_pack["left_count"]);?>" size="40" aria-required="true"></td>
	</tr>
	
	<tr><th scope="row"><label for="siteurl">Status</label></th>
	<td><select name="status" style="width: 30%;" id="Status" class="postform">
			<option class="level-0" value="Active" <?php if($edit_pack["status"] == 'Active') echo ' selected'; ?> >Active</option>
			<option class="level-0" value="Inactive" <?php if($edit_pack["status"] == 'Inactive') echo ' selected'; ?> >Inactive</option>
		</select>		
		<input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>"></td>
	</tr>

	</tbody></table>

	<p class="submit"><input type="submit" name="submit_form" id="submit" class="button button-primary" value="Save Changes"></p></form>

	</div><?php 
}
?>
