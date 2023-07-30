<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class sub_active extends WP_List_Table {

    function __construct(){
        global $status, $page;                
       
        parent::__construct( array(
            'singular'  => 'subscription_pack_active',  
            'plural'    => 'subscription_packs_active',   
            'ajax'      => false      
        ) );        
    }

    function column_default($item, $column_name){
        switch($column_name){
            case 'user_login':
            case 'role':
            case 'date_subscribed':
            case 'pack_name':
            case 'end_date':
            case 'status':
            case 'per_post':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_user_login($item){       
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&edit_id=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
          /*  'refund'      => sprintf('<a href="?page=%s&action=%s&txn_id=%s">Refund</a>',$_REQUEST['page'],'refund',$item['id']),*/
            'delete'    => sprintf('<a href="?page=%s&action=%s&delete_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['user_login'],          
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
            'user_login'=>__('User Name'),  
            'role'=>__('Role'),  
			'date_subscribed'=>__('Date Subscribed'),  
			'pack_name'=>__('Pack Name'),
			'end_date'=>__('End Date'),
			'status'=>__('Status'),
			'per_post'=>__('Per Post')
        );
        return $columns;
    }
 
    function get_sortable_columns() {
        $sortable_columns = array(
            'user_login'     => array('wp_user_id',false),     //true means it's already sorted
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
        $jbs_subactive_tbl = $wpdb->prefix.'jbs_subactive';
        if( 'delete'===$this->current_action() ) {
			//$_POST['subscription_pack_active']
			foreach($_POST['subscription_pack_active'] as $single_val){
				if(check_pack_is_active($single_val) != 'Active')
					$wpdb->delete( $jbs_subactive_tbl, array( 'id' => (int)$single_val ) );				
        	}
			$redirect_url =  get_admin_url( null, 'admin.php?page=sub_active' );
			wp_safe_redirect($redirect_url); 
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }

    function prepare_items() {
        global $wpdb;
		$database_name = $wpdb->prefix.'jbs_subactive' ;
		$databasepack_name = $wpdb->prefix.'jbs_subpack' ;
		$db_users = $wpdb->prefix.'users' ;
        $per_page = 10;
		$query="SELECT act.status, usr.user_login, act.date_subscribed, act.end_date, pack.pack_name,pack.role,act.per_post, act.id
		FROM ".$database_name." as act
		INNER JOIN ".$db_users." as usr ON act.wp_user_id= usr.ID 
		LEFT OUTER JOIN ".$databasepack_name." as pack ON act.pack_id=pack.id ORDER BY act.id DESC";
	
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


function kv_jobseeker_subscriptions_active(){
	if(isset($_GET['add_new']) && $_GET['add_new'] == 'true' ) { 
		require_once("jbs_subactive_edit.php");
	} else if(isset($_GET['edit_id'])  && $_GET['edit_id'] != NULL) {
		require_once("jbs_subactive_edit.php");
	}else if(isset($_GET['delete_id']) && $_GET['delete_id'] != NULL) {
		global $wpdb; //This is used only if making any database queries
		$del_id = trim($_GET['delete_id']);
		$database_name = $wpdb->prefix.'jbs_subactive' ;
		$wpdb->delete( $database_name, array( 'id' => (int)$del_id ) );
		$redirect_url =  get_admin_url( null, 'admin.php?page=sub_active' );
		wp_safe_redirect($redirect_url); 
		exit;
	}  else if(isset($_GET['txn_id'])  && $_GET['txn_id'] != NULL) {
        require_once("kv-paypal-refund.php");
    }else { 
   
		$mydownloads = new sub_active(); 
		echo '</pre><div class="wrap"><h2> User Subscriptions <a href="'."http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI'].'&add_new=true" class="add-new-h2">Add New</a></h2>'; 

        if(isset($_GET['refund']) ){
            if( $_GET['status'] == "Success")
                 echo '<div class="kv-success"><p>Amount Refunded Successfully</p> </div>';
            else
                echo '<div class="kv-error"><p>Error Refunding Amount</p> </div>';
        }
               

		echo '<form method="post" >'; 
		$mydownloads->prepare_items(); 
		$mydownloads->display(); 
		echo '</form></div>'; 
	}
}

function kv_styles_for_msg() { ?>
<style>
.kv-error, .kv-success {

    margin:20px auto;
    padding:10px;
	border-radius:5px;
    color: #dd2200;
    text-align:justify;

	/*-webkit-box-shadow: 0px 0px 15px 2px rgba(0,0,0,0.75);
	-moz-box-shadow: 0px 0px 15px 2px rgba(0,0,0,0.75);
	box-shadow: 0px 0px 15px 2px rgba(0,0,0,0.75);*/
}

.kv-error {
		background-color: #FAFFBD;
		border: 1px solid #DAAAAA;
		color: #D8000C;
		
	}

.kv-success { 		
		background-color: #BBF6E2;
		border: 1px solid #6ADE95;
	}
	</style>
<?php }
add_action("admin_enqueue_scripts", "kv_styles_for_msg");

?>
