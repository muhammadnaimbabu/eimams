<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Eimams_admin_notification extends WP_List_Table {

    function __construct(){
        global $status, $page;                
       
        parent::__construct( array(
            'singular'  => 'notification',  
            'plural'    => 'notifications',   
            'ajax'      => false      
        ) );        
    }

    function column_default($item, $column_name){
        switch($column_name){
            case 'Message':
            case 'user_group':
            case 'selected_users':          
            case 'status':          
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_Message($item){       
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&edit_id=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&delete_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['Message'],          
            /*$2%s*/ $this->row_actions($actions)
        );
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  
            /*$2%s*/ $item['id']                
        );
    }

    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'Message'=>__('Message'),  
            'user_group'=>__('User Group'),  
			'selected_users'=>__('Selected Users'), 			
			'status'=>__('Status')
        );
        return $columns;
    }
 
    function get_sortable_columns() {
        $sortable_columns = array(
            'Message'     => array('wp_user_id',false),     //true means it's already sorted
            'date_subscribed'    => array('date_subscribed',false),
            'pack_id'  => array('pack_id',false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action() {
        global $wpdb; 
        $notifications_tbl = $wpdb->prefix.'notifications';
        if( 'delete'===$this->current_action() ) {
			foreach($_POST['notification'] as $single_val){
        		$wpdb->delete( $notifications_tbl, array( 'id' => (int)$single_val ) );				
        	}
			$redirect_url =  get_admin_url( null, 'admin.php?page=notifications' );
			wp_safe_redirect($redirect_url); 
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }        
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries
		$database_name = $wpdb->prefix.'notifications' ;
        $per_page = 10;
        $query = "SELECT * FROM $database_name  ORDER BY id DESC";
			
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
         
        $this->_column_headers = array($columns, $hidden, $sortable);        

        $this->process_bulk_action();

        $data =  $wpdb->get_results($query, ARRAY_A );

        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
       // usort($data, 'usort_reorder');        

        $current_page = $this->get_pagenum();        
  
        $total_items = count($data);

        $data = array_slice($data,(($current_page-1)*$per_page),$per_page); 
  
        $this->items = $data;
  
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}//class


function kv_eimams_notification_from_admin(){
	global $wpdb; 
	$notifications_tbl = $wpdb->prefix.'notifications';
		
	if(isset($_GET['add_new']) && $_GET['add_new'] == 'true' ) { 
		
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "activate_him") {			
			if(isset($_POST['msg_notify'])) {
				$msg_notify 	= 	$_POST['msg_notify'] ;	
				$user_group 	= 	$_POST['user_group'] ;	
				$selected_users = 	$_POST['selected_users'] ;	
				$status 		= 	$_POST['status'] ;	
				$insert_id = $wpdb->insert( $notifications_tbl,  array( 	'Message' 	=>  $msg_notify , 'user_group' => $user_group, 'selected_users' => $selected_users, 'status' => $status)	);
				if($insert_id > 0 ){ 
					$redirect_url =  get_admin_url( null, 'admin.php?page=notifications' );
					wp_safe_redirect($redirect_url); 
				}
			}
			
		} ?>
		<form method="POST">
			<table cellpadding="0" border="0" class="form-table">
				<tr><td colspan="2"> <h2> User Notifications : </h2> </td> </tr>
				<tr><td> User Group: </td> <td> <select name="user_group" > 
												<option value="None"> None </option> 
												<option value="Both"> Both </option> 
												<option value="Job Seeker"> Job Seeker </option> 
												<option value="Employer"> Employer </option> 
												</select></td> </tr>												
				<tr> <td> Selected Users: </td>    <td> <input type="text" align="left" name="selected_users" size="80%" value="" placeholder="e.g: 2, 34, 23,45" ></td></tr>  			
				<tr> <td> Notification Message: </td> 	<td align="left">  <?php $args = array("textarea_name" => "msg_notify", "textarea_name" => "msg_notify", "textarea_rows" => "22", "teeny" => true, "media_buttons" => true , "quicktags" =>false);
				wp_editor( '', "msg_notify", $args ); ?>  </td></tr>
				<tr><td> Status: </td> <td> <select name="status" > 
												<option value="Active"> Active </option> 												
												<option value="Inactive"> Inactive </option> 
											</select></td> </tr>
				<input type="hidden" name="action" value="activate_him" />
				<tr><td colspan="2" align="left">	  <input type="submit" value="Add Notification" name="submit" class="button"> </td> </tr>				
			</table>
		</form>
	<?php 
	} else if(isset($_GET['edit_id'])  && $_GET['edit_id'] != NULL) {
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "activate_him") {			
			if(isset($_POST['msg_notify'])) {
				$msg_notify 	= 	$_POST['msg_notify'] ;	
				$user_group 	= 	$_POST['user_group'] ;	
				$selected_users = 	$_POST['selected_users'] ;	
				$status 		= 	$_POST['status'] ;	
				$wpdb->update( $notifications_tbl,  array( 'Message' 	=>  $msg_notify , 'user_group' => $user_group, 'selected_users' => $selected_users, 'status' => $status), array('id' => $_GET['edit_id'])	);
				$redirect_url =  get_admin_url( null, 'admin.php?page=notifications' );
				wp_safe_redirect($redirect_url); 				
			}			
		}
		$result = get_notification_details_through_id($_GET['edit_id']); 

		?>
		<form method="POST">
			<table cellpadding="0" border="0" class="form-table">
				<tr><td colspan="2"> <h2> User Notifications : </h2> </td> </tr>
				<tr><td> User Group: </td> <td> <select name="user_group" > 
												<option value="None" <?php if($result->user_group == 'none') echo ' selected'; ?> > None </option> 
												<option value="Both" <?php if($result->user_group == 'Both') echo ' selected'; ?> > Both </option> 
												<option value="Job Seeker" <?php if($result->user_group == 'Job Seeker') echo ' selected'; ?>> Job Seeker </option> 
												<option value="Employer" <?php if($result->user_group == 'Employer') echo ' selected'; ?>> Employer </option> 
												</select></td> </tr>												
				<tr> <td> Selected Users: </td>    <td> <input type="text" align="left" name="selected_users" size="80%" value="<?php echo $result->selected_users; ?>"  " placeholder="e.g: 2, 34, 23,45" ></td></tr>  			
				<tr> <td> Notification Message: </td> 	<td align="left">  <?php $args = array("textarea_name" => "msg_notify", "textarea_name" => "msg_notify", "textarea_rows" => "22", "teeny" => true, "media_buttons" => true , "quicktags" =>false);
				wp_editor( $result->Message, "msg_notify", $args ); ?>  </td></tr>
				<tr><td> Status: </td> <td> <select name="status" > 
												<option value="Active"  <?php if($result->status == 'Active') echo ' selected'; ?>> Active </option> 												
												<option value="Inactive"  <?php if($result->status == 'Inactive') echo ' selected'; ?>> Inactive </option> 
											</select></td> </tr>
				<input type="hidden" name="action" value="activate_him" />
				<tr><td colspan="2" align="left">	  <input type="submit" value="Update Notification" name="submit" class="button"> </td> </tr>				
			</table>
		</form>
	<?php 
	}else if(isset($_GET['delete_id']) && $_GET['delete_id'] != NULL) {
		$del_id = trim($_GET['delete_id']);	
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "activate_him") {
			$wpdb->delete( $notifications_tbl, array( 'id' => (int)$del_id ) );
			$redirect_url =  get_admin_url( null, 'admin.php?page=notifications' );
			wp_safe_redirect($redirect_url); 
			exit;
		}?>	<form method="POST">
			
			<p> Are you sure, Do you want to delete this notification?. </p> 
				<input type="hidden" name="action" value="activate_him" />
				<input type="submit" value="Confirm Delete" name="submit" class="button"> 
		</form>
<?php 		
	} else { 
		echo '<form  method="post" >'; 
		$mydownloads = new Eimams_admin_notification(); 
		echo '</pre><div class="wrap"><h2>Notifications<a href="'."http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI'].'&add_new=true" class="add-new-h2">Add New</a></h2>'; 
		$mydownloads->prepare_items(); 
		$mydownloads->display(); 
		echo '</div></form>'; 
	}
}
?>