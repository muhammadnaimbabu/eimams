<?php 

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Kv_Jobseeker_Subscriptions extends WP_List_Table {

    function __construct(){
        global $status, $page;               
        
        parent::__construct( array(
            'singular'  => 'subscription_pack',     
            'plural'    => 'subscription_packs',    
            'ajax'      => false       
        ) );        
    }

    function column_default($item, $column_name){
        switch($column_name){
            case 'pack_name':
            case 'role':
            case 'duration':
            case 'period':
            case 'price':
            case 'status':
            case 'per_post':
            case 'left_c':
                return $item[$column_name];
            default:
                return print_r($item,true); 
        }
    }

    function column_pack_name($item){        
        
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&edit_id=%s&role=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id'], $item['role']),
            'email'      => sprintf('<a href="?page=%s&action=%s&pack_id=%s&role=%s">E-Mail</a>',$_REQUEST['page'],'email',$item['id'], $item['role']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&delete_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['pack_name'],           
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
			'pack_name'	=>__('Name'),  
			'role'		=>__('Role'),  
			'duration'	=>__('Duration'),  
			'period'	=>__('Periods'),  
			'price'		=>__('Price'),
			'status'	=>__('Status'),
			'per_post'	=>__('Per Post'),
			'left_c'	=>__('Left Count'),
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array('title',false),     //true means it's already sorted
            'rating'    => array('rating',false),
            'director'  => array('director',false)
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
        $notifications_tbl = $wpdb->prefix.'jbs_subpack';
        if( 'delete'===$this->current_action() ) {
			//$_POST['subscription_pack']
			foreach($_POST['subscription_pack'] as $single_val){
				if(check_pack_has_purchased($single_val == null))
					$wpdb->delete( $notifications_tbl, array( 'id' => (int)$single_val ) );				
        	}
			$redirect_url =  get_admin_url( null, 'admin.php?page=subscription_pack' );
			wp_safe_redirect($redirect_url); 
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries
		$database_name = $wpdb->prefix.'jbs_subpack' ;
        $per_page = 10;
        $query = "SELECT *, ABS(left_count) AS left_c FROM $database_name ORDER BY id DESC";
			
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
} //Class


function kv_jobseeker_subscriptions(){
	if(isset($_GET['add_new']) && $_GET['add_new'] == 'yes' ) { 
		require_once("jbs_subscription_edit.php");

	} else if(isset($_GET['edit_id'])  && $_GET['edit_id'] != NULL) {
		require_once("jbs_subscription_edit.php");

	}

     else if(isset($_GET['pack_id'])  && $_GET['pack_id'] != NULL) {
        require_once("jbs_subscription_email.php");

    }else if(isset($_GET['delete_id']) && $_GET['delete_id'] != NULL) {
		global $wpdb; 
		$del_id = trim($_GET['delete_id']);
		$database_name = $wpdb->prefix.'jbs_subpack' ;
		if(check_pack_has_purchased($del_id) == null){
			$wpdb->delete( $database_name, array( 'id' => (int)$del_id ) );
			$redirect_url =  get_admin_url( null, 'admin.php?page=subscription_pack' );
			wp_safe_redirect($redirect_url); 
			exit;
		}else{
			$redirect_url =  get_admin_url( null, 'admin.php?page=subscription_pack&cant_delete=yes' );
			wp_safe_redirect($redirect_url); 
			exit;
		}
					
 
	}   else { 
		$mydownloads = new Kv_Jobseeker_Subscriptions(); 
		echo '</pre><div class="wrap">
				<h2>Subscription Packs <a href="'."http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI'].'&add_new=yes&role=employer" class="add-new-h2">Add New Employer Pack</a>
				<a href="'."http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI'].'&add_new=yes&role=jobseeker" class="add-new-h2">Add New Jobseeker Pack</a></h2>'; 
		if(isset($_GET['cant_delete']) && $_GET['cant_delete'] == 'yes') {
			echo "<div class='error'><p>You can't Delete Active Pack </p></div>";	
		}
		echo '<form method="post" >' ; 
		$mydownloads->prepare_items(); 
		$mydownloads->display(); 
		echo '</form></div>'; 
	}
}
?>