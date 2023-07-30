<?php 

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Kv_JobSeeker_Resumes extends WP_List_Table {

    function __construct(){
        global $status, $page;               
        
        parent::__construct( array(
            'singular'  => 'resume',     
            'plural'    => 'resumes',    
            'ajax'      => false       
        ) );        
    }

    function column_default($item, $column_name){
        switch($column_name){
            case 'dp':
            case 'jobsee_name':
            case 'email':
            case 'phone':
            case 'category':
            case 'madhab':
            case 'aqeeda':
            case 'status':
                return $item[$column_name];
            default:
                return print_r($item,true); 
        }
    }

    function column_jobsee_name($item){        
        
        $actions = array(
            'view'      => sprintf('<a href="?page=%s&action=%s&job_seeker_id=%s&backend=yes">View</a>',$_REQUEST['page'],'view',$item['id']),
           // 'delete'    => sprintf('<a href="?page=%s&action=%s&delete_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['jobsee_name'],           
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
            'cb'        	=> '<input type="checkbox" />', //Render a checkbox instead of text			 
			'jobsee_name'	=>__('Name'),  
			'email'			=>__('email'),  
			'phone'			=>__('Phone'),  
			'category'		=>__('Job Classifications'),  
			'madhab'		=>__('Madhab'),
			'aqeeda'		=>__('Aqeeda'),
			'dp'			=>__('Profle'), 
			'status'		=>__('Status')
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'jobsee_name'     => array('jobsee_name',false),     //true means it's already sorted
            'madhab'    => array('madhab',false),
            'aqeeda'  => array('aqeeda',false)
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
        
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries
		
		$jobseekers_list = get_users( 'role=job_seeker' );
		$data = array();
		foreach ( $jobseekers_list as $job_seeker ){
			 $single_jobsee_info = array();
			 $single_jobsee_info['id'] =  $job_seeker->ID; 
			 $single_jobsee_info['email'] =  $job_seeker->user_email; 
			 $get_user_image = get_user_meta($job_seeker->ID, 'user_image', true);
			 if(isset($get_user_image['url']) && $get_user_image['url'] != null)
				$single_jobsee_info['dp'] = '<img src="'.$get_user_image['url'].'?'.uniqid().'" style="width:64px;float: left;" />';
			 else
				$single_jobsee_info['dp'] = ''; 
			 $single_jobsee_info['jobsee_name'] =  $job_seeker->display_name; 
			$jobseeker_details = kv_get_jobseeker_details($job_seeker->ID);
			if(isset($jobseeker_details['phone'])){
				$single_jobsee_info['phone'] = $jobseeker_details['phone'];
			} else
				$single_jobsee_info['phone'] = ''; 
			
			if(isset($jobseeker_details['category'])) { 
				$cate_ = get_term_by( 'id', $jobseeker_details['category'], 'job_category');   
				$single_jobsee_info['category'] = $cate_->name;  
			} else
				$single_jobsee_info['category'] = ''; 
			
			if(isset($jobseeker_details['madhab']) && $jobseeker_details['madhab'] !=  -1 ) { 
				$madhab_ = get_term_by( 'id', $jobseeker_details['madhab'], 'madhab');   
				$single_jobsee_info['madhab'] = $madhab_->name;  
			} elseif(isset($jobseeker_details['madhab_shia']) && $jobseeker_details['madhab_shia'] != -1) {
                $madhab_ = get_term_by( 'id', $jobseeker_details['madhab_shia'], 'Shiamadhab');  
				$single_jobsee_info['madhab'] = $madhab_->name; 
            }else{
                $single_jobsee_info['madhab'] = ''; 
            }
				
			if(isset($jobseeker_details['aqeeda'])&& $jobseeker_details['aqeeda'] !=  -1) { 
				$aqeeda_ = get_term_by( 'id', $jobseeker_details['aqeeda'], 'aqeeda');   
				$single_jobsee_info['aqeeda'] = $aqeeda_->name;  
			}elseif(isset($jobseeker_details['aqeeda_shia']) && $jobseeker_details['aqeeda_shia'] != -1) {
                $aqeeda_ = get_term_by( 'id', $jobseeker_details['aqeeda_shia'], 'Shiaaqeeda');  
                $single_jobsee_info['aqeeda'] = $aqeeda_->name; 
            } else
				$single_jobsee_info['aqeeda'] = ''; 
				
			if($job_seeker->user_status == 0) { 				
				$single_jobsee_info['status'] = 'Active';  
			} elseif($job_seeker->user_status == 1) { 				
				$single_jobsee_info['status'] = 'Deactivated by Self';  
			}else
				$single_jobsee_info['status'] = 'Deactivated';
				
			$data[] = $single_jobsee_info; 
		} 
			
		
        $per_page = 10;
      
			
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
         
        $this->_column_headers = array($columns, $hidden, $sortable);        

        $this->process_bulk_action();

      //  $data =  $wpdb->get_results($query, ARRAY_A );

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

function kv_jobseeker_resumes(){
	 if(isset($_GET['job_seeker_id'])  && $_GET['job_seeker_id'] != NULL) {
		//require_once("../template/view-resume.php");
		echo '</pre><div class="wrap"><h2>View Profile</h2>'; 
		require_once __DIR__ . '/../template/view-resume.php';
	}else if(isset($_GET['delete_id']) && $_GET['delete_id'] != NULL) {
		global $wpdb; 
		$del_id = trim($_GET['delete_id']);
		$database_name = $wpdb->prefix.'jbs_subpack' ;
		$wpdb->delete( $database_name, array( 'id' => (int)$del_id ) );
		$redirect_url =  get_admin_url( null, 'admin.php?page=subscription' );
		wp_safe_redirect($redirect_url); 
		exit; 
	} else { 
		$mydownloads = new Kv_JobSeeker_Resumes(); 
		echo '</pre><div class="wrap"><h2>Resumes <!--<a href="'."http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI'].'&add_new=yes" class="add-new-h2">Add New</a>  --></h2>'; 
		$mydownloads->prepare_items(); 
		$mydownloads->display(); 
		echo '</div>'; 
	}
}

?>