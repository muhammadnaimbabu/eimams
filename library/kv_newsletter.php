<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Eimams_subscribers_list extends WP_List_Table {

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
            case 'email':
            case 'date':
            case 'common':          
            case 'unit_id':          
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_email($item){       
        $actions = array(
            'email'     => sprintf('<a href="?page=%s&action=%s&email_id=%s">E-mail</a>',$_REQUEST['page'],'email',$item['id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&delete_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['email'],          
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
            'email'=>__('Date'),  
            'date'=>__('Date'),  
			'common'=>__('Common Alert'), 			
			'unit_id'=>__('Unique ID')
        );
        return $columns;
    }
 
   public function get_sortable_columns() {
        $sortable_columns = array(
            'email'   => array('wp_user_id',false),     //true means it's already sorted
            'date'    => array('date',false),
            'common'  => array('common',false)
        );
        return $sortable_columns;
    }

   public function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete',
            'email'     => 'Email'
        );
        return $actions;
    }

   public function process_bulk_action() {
   		global $wpdb; 
        $notifications_tbl = $wpdb->prefix.'newsletter';

        if( 'delete'===$this->current_action() ) {
        	foreach($_POST['notification'] as $single_val){
        		$wpdb->delete( $notifications_tbl, array( 'id' => (int)$single_val ) );				
        	}
			$redirect_url =  get_admin_url( null, 'admin.php?page=subscribers' );
			wp_safe_redirect($redirect_url); 
            wp_die('Items deleted (or they would be if we had items to delete)!');
        } 
		if( 'email'===$this->current_action() ) {			
				$result_email_ar = implode("-",$_POST['notification']);
			$redirect_url =  get_admin_url( null, 'admin.php?page=eimams&ids='.$result_email_ar  );
			wp_safe_redirect($redirect_url); 		

            wp_die('  ');
        }  		
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries
		$database_name = $wpdb->prefix.'newsletter' ;
        $per_page = 10;
        $query = "SELECT * FROM $database_name ORDER BY id DESC";
			
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


function kv_news_letter_for_common(){
	global $wpdb; 
	$notifications_tbl = $wpdb->prefix.'newsletter';
		
	if(isset($_GET['add_new']) && $_GET['add_new'] == 'true' ) { 
		wp_die("Nothing To add "); 		
	} else if(isset($_GET['email_id'])  && $_GET['email_id'] != NULL) {
		$result_email = $wpdb->get_var("SELECT email FROM ". $notifications_tbl  ." WHERE id=".$_GET['email_id']);
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "activate_him") {			
			if(isset($_POST['msg_notify'])) {
				$msg_notify 	= 	email_header(). nl2br($_POST['msg_notify']) .kv_email_footer()  ;	
				$subject 		= 	$_POST['subject'] ;	
				$header = "From: No-Reply <no-reply@eimams.com> \r\n";
				add_filter ('wp_mail_content_type', 'kv_mail_content_type');			
				$kv_mail_report = wp_mail($result_email, $subject, $msg_notify, $header);
				remove_filter ('wp_mail_content_type', 'kv_mail_content_type');
				
				$redirect_url =  get_admin_url( null, 'admin.php?page=subscribers' );
				wp_safe_redirect($redirect_url); 				
			}			
		} 
		  ?>
		<form method="POST">
			<table cellpadding="0" border="0" class="form-table">
				<tr> <td colspan="2"> <h2> User Newsletter : </h2> </td> </tr>															
				<tr> <td> Selected User: </td>    	<td> <input type="text" align="left" name="user_email" size="80%" value="<?php echo $result_email; ?>"   ></td></tr>  			
				<tr> <td> Subject: </td>    	<td> <input type="text" align="left" name="subject" size="80%" value=""  ></td></tr>  			
				<tr> <td> Notification email: </td> <td align="left">  <?php $args = array("textarea_name" => "msg_notify", "textarea_name" => "msg_notify", "textarea_rows" => "22", "teeny" => true, "media_buttons" => true , "quicktags" =>false);
				wp_editor( '', "msg_notify", $args ); ?>  </td></tr>				
				<input type="hidden" name="action" value="activate_him" />
				<tr><td colspan="2" align="left">	  <input type="submit" value="Send Email" name="submit" class="button"> </td> </tr>				
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
		echo '<form method="post">';
		$mydownloads = new Eimams_subscribers_list(); 
		echo '</pre><div class="wrap"><h2>Subscribers <!--<a href="'."http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI'].'&add_new=true" class="add-new-h2">Add New</a>  --></h2>'; 
		$mydownloads->prepare_items(); 
		$mydownloads->display(); 
		echo '</div></form>'; 
	}
}



/****************************************************************
*	Function for subscriptions
*****************************************************************/
class Eimams_Queued_mails extends WP_List_Table {

    function __construct(){
        global $status, $page;                
       
        parent::__construct( array(
            'singular'  => 'newsletter',  
            'plural'    => 'newsletters',   
            'ajax'      => false      
        ) );        
    }

    function column_default($item, $column_name){
        switch($column_name){
            case 'type':
            case 'subject':
            case 'message':
            case 'status':         
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_type($item){       
        $actions = array(
            'edit'     => sprintf('<a href="?page=%s&action=%s&edit_id=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&delete_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['type'],          
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
            'type'=>__('Type of Users Receive'),  
            'subject'=>__('Subject'),  
            'message'=>__('Message'),  
			'status'=>__('Status')
        );
        return $columns;
    }
 
    function get_sortable_columns() {
        $sortable_columns = array(
            'type'   => array('type',false),     //true means it's already sorted
            'message'    => array('message',false),
            'status'  => array('status',false)
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
		 global $wpdb; //This is used only if making any database queries
		$database_name = $wpdb->prefix.'mailing_queue' ;
		
        if( 'delete'===$this->current_action() ) {        	
			foreach($_POST['newsletter'] as $single_val){
        		$wpdb->delete( $database_name, array( 'id' => (int)$single_val ) );				
        	}
			$redirect_url =  get_admin_url( null, 'admin.php?page=newsletter' );
			wp_safe_redirect($redirect_url); 
            wp_die('Items deleted (or they would be if we had items to delete)!');
        } 			
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries
		$database_name = $wpdb->prefix.'mailing_queue' ;
        $per_page = 10;
        $query = "SELECT * FROM $database_name ORDER BY id DESC";
			
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

function kv_compose_newsletter_mails() {
	global $wpdb; 
	$db_table_name= $wpdb->prefix.'mailing_queue'; 
	$newsletter_table= $wpdb->prefix.'newsletter'; 
	
	if(isset($_GET['add_new']) && $_GET['add_new'] == 'true' ) { 
		$start_id = $wpdb->get_var("SELECT id FROM  ".$newsletter_table." ORDER BY id LIMIT 1"); 
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "activate_him") {
			if(stripslashes(trim($_POST['msg_notify'])) != '' && stripslashes(trim($_POST['subject'])) != '')
				$insrted_id = $wpdb->insert($db_table_name, array('type' => $_POST['type'], 'subject' => $_POST['subject'], 'message' => $_POST['msg_notify'], 'start_id' => $start_id,  'status' => 'Queued'));
			else
				$error = "Please check the missing fields" ; 
		}
		?>
		<div class="error"><p>Please Don't edit, if the status is processing. which will collapse email contents </p></div>
			<form method="POST">	
				<?php if(isset($error))	echo '<div class="error"> <p> '.$error.'</p> </div>'; ?>			
				<table cellpadding="0" border="0" class="form-table">
					<tr><td colspan="2"> <h2> Newsletter : </h2> </td> </tr>
					<tr> <td colspan="2"> <?php if(isset($insrted_id)) echo '<p style="border-left:5px solid green;padding:10px 20px;background:#fff;">Mail Queued Successfully</p>'; ?> </td> </tr>
					<tr><td> User Group: </td> <td> 
						<select name="type" > 
									<option value="Common" > Common </option> 
									<option value="Common Without Users" > Common Without Users </option> 
									<option value="Job Seekers" > Job Seekers </option> 
									<option value="Employers" > Employers </option> 
									<option value="Unsubscribed" > Unsubscribed </option> 
						</select>
					</td> </tr>	
					<tr> <td> Subject </td> <td> <input type="text" name="subject" value=" "  style="width:90%" /> </td> </tr>
					<tr> <td> Notification email: </td> 	<td align="left">  <?php $args = array("textarea_name" => "msg_notify", "textarea_name" => "msg_notify", "textarea_rows" => "22", "teeny" => true, "media_buttons" => true , "quicktags" =>false);
					wp_editor('', "msg_notify", $args ); ?>  </td></tr>
					
					<input type="hidden" name="action" value="activate_him" />
					<tr><td colspan="2" align="left">	  <input type="submit" value="Send Mail" name="submit" class="button button-primary"> </td> </tr>				
				</table>
			</form>
		<?php 
	} else if(isset($_GET['edit_id'])  && $_GET['edit_id'] != NULL) {
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "activate_him") {
			if(stripslashes(trim($_POST['msg_notify'])) != '' && stripslashes(trim($_POST['subject'])) != '')
				$insrted_id = $wpdb->update($db_table_name, array('type' => $_POST['type'], 'subject' => $_POST['subject'], 'message' => $_POST['msg_notify']), array('id' => $_GET['edit_id']));
			else
				$error = "Please check the missing fields" ; 
		}
		$result = $wpdb->get_row("SELECT * FROM ". $db_table_name. " WHERE id=".$_GET['edit_id']); ?>
		<div class="error"><p>Please Don't edit, if the status is processing. which will collapse email contents </p></div>
		<form method="POST">	
		<?php if(isset($error))	echo '<div class="error"> <p> '.$error.'</p> </div>'; ?>
			
				<table cellpadding="0" border="0" class="form-table">
					<tr><td colspan="2"> <h2> Newsletter : </h2> </td> </tr>
					<tr> <td colspan="2"> <?php if(isset($insrted_id)) echo '<p class="success" >Mail Queued Successfully</p>'; ?> </td> </tr>
					<tr><td> User Group: </td> <td> 
						<select name="type" > 
									<option value="Common" <?php if($result->type == 'Common') echo ' selected'; ?> > Common </option> 
									<option value="Common Without Users" <?php if($result->type == 'Common Without Users') echo ' selected'; ?> > Common Without Users </option> 
									<option value="Job Seekers" <?php if($result->type == 'Job Seekers') echo ' selected'; ?> > Job Seekers </option> 
									<option value="Employers" <?php if($result->type == 'Employers') echo ' selected'; ?> > Employers </option> 
									<option value="Unsubscribed" <?php if($result->type == 'Unsubscribed') echo ' selected'; ?> > Unsubscribed </option> 
						</select>
					</td> </tr>	
					<tr> <td> Subject </td> <td> <input type="text" name="subject" value="<?php echo $result->subject; ?>" style="width:90%" /> </td> </tr>
					<tr> <td> Notification email: </td> 	<td align="left">  <?php $args = array("textarea_name" => "msg_notify", "textarea_name" => "msg_notify", "textarea_rows" => "22", "teeny" => true, "media_buttons" => true , "quicktags" =>false);
					wp_editor($result->message, "msg_notify", $args ); ?>  </td></tr>
					
					<input type="hidden" name="action" value="activate_him" />
					<tr><td colspan="2" align="left">	  <input type="submit" value="Update Mail" name="submit" class="button button-primary"> </td> </tr>				
				</table>
			</form>
	<?php 
	}else if(isset($_GET['delete_id']) && $_GET['delete_id'] != NULL) {
		$del_id = trim($_GET['delete_id']);	
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "activate_him") {
			$wpdb->delete( $db_table_name, array( 'id' => (int)$del_id ) );
			$redirect_url =  get_admin_url( null, 'admin.php?page=newsletter' );
			wp_safe_redirect($redirect_url); 
			exit;
		}?>	<form method="POST">			
			<p> Are you sure, Do you want to delete this notification?. </p> 
				<input type="hidden" name="action" value="activate_him" />
				<input type="submit" value="Confirm Delete" name="submit" class="button"> 
		</form>
	<?php 		
	} else { 
		echo '<form method="post">';
		$mydownloads = new Eimams_Queued_mails(); 
		echo '</pre><div class="wrap"><h2>Newsletters<a href="'."http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI'].'&add_new=true" class="add-new-h2">Add New</a></h2><br><div class="error"><p>Please Don\'t edit, if the status is processing. which will collapse email contents </p></div>'; 
		$mydownloads->prepare_items(); 
		$mydownloads->display(); 
		echo '</div></form>'; 
	}
}
?>