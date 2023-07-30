<?php 
/******************************************************
*										              *
*			Post Types for Resume and Jobs 		      *
*										              *
******************************************************/
add_action('init' , 'kv_post_type'); 
function kv_post_type() {
	    
	register_post_type( 'job',
				array('labels' => array(
					'name' => __( 'Jobs', '1stopwebsolution' ),
					'singular_name' => __( 'Jobs', '1stopwebsolution' ),
					'add_new' => __( 'Add New', '1stopwebsolution' ),
					'add_new_item' => __( 'Add New Job', '1stopwebsolution' ),
					'edit' => __( 'Edit', '1stopwebsolution' ),
					'edit_item' => __( 'Edit Job', '1stopwebsolution' ),
					'new_item' => __( 'New Job', '1stopwebsolution' ),
					'view' => __( 'View Jobs', '1stopwebsolution' ),
					'view_item' => __( 'View Job', '1stopwebsolution' ),
					'search_items' => __( 'Search Jobs', '1stopwebsolution' ),
					'not_found' => __( 'No jobs found', '1stopwebsolution' ),
					'not_found_in_trash' => __( 'No jobs found in trash', '1stopwebsolution' ),
					'parent' => __( 'Parent Job', '1stopwebsolution' ),
                ),
                'description' => __( 'This is where you can create new job listings on your site.', '1stopwebsolution' ),
                'public' => true,
                'show_ui' => true,
                'capability_type' => 'post',
                'publicly_queryable' => true,
                'exclude_from_search' => false,
                'menu_position' => 6,
                'menu_icon' =>  'dashicons-analytics', // get_stylesheet_directory_uri() . '/images/icons/job.png',
                'hierarchical' => false,
                'rewrite' => array( 'slug' => 'job', 'with_front' => false ), /* Slug set so that permalinks work when just showing post name */
                'query_var' => true,
                'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky' ),
            )
    );

	
	//tickets - Help  and Support 
	 register_post_type( 'tickets', 
						array( 'labels' => array( 
								'name' => __( 'Help & Support' ), 
								'add_new' => '',
								'singular_name' => __( 'Help & Support' ) ), 
							'public' => true, 
							'has_archive' => true, 
							'menu_position' => 8,
							'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ))
						);

    register_post_type( 'speakers-artist',array( 'labels' => array('name' => __( 'Speakers And Artists' ), 'singular_name' => __( 'Speaker And Artist' ) ), 'public' => true,
                              
                'menu_position' => 12,
                'menu_icon' =>  'dashicons-analytics', // get_stylesheet_directory_uri() . '/images/icons/job.png',               
                'supports' => array( 'title', 'editor', 'custom-fields', 'thumbnail')));

     // register the newCategory taxonomy
    register_taxonomy( 'speaker_or_artist',
            array( 'speakers-artist', 'speak_art_request'),
            array('hierarchical' => true,                    
                    'labels' => array(
                            'name' => __('Categories', '1stopwebsolution'),
                            'singular_name' => __( 'Category', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Category', '1stopwebsolution'),
                            'all_items' => __( 'All Categories', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Category', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Category:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Category', '1stopwebsolution'),
                            'update_item' => __( 'Update Category', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Category', '1stopwebsolution'),
                            'new_item_name' => __( 'New Category Name', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => 'saterm', 'hierarchical' => true ),
            )
    );

    register_taxonomy( 'type_of_event',
            array( 'speak_art_request'),
            array('hierarchical' => true,                    
                    'labels' => array(
                            'name' => __( 'Type of Events', '1stopwebsolution'),
                            'singular_name' => __( 'Type of Event', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Type of Events', '1stopwebsolution'),
                            'all_items' => __( 'All Type of Events', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Type of Event', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Type of Event:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Type of Event', '1stopwebsolution'),
                            'update_item' => __( 'Update Type of Event', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Type of Event', '1stopwebsolution'),
                            'new_item_name' => __( 'New Type of Event Name', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => 'typeofevent', 'hierarchical' => true ),
            )
    );


    register_taxonomy( 'type_of_performance',
            array( 'speak_art_request'),
            array('hierarchical' => true,                    
                    'labels' => array(
                            'name' => __( 'Type of Performances', '1stopwebsolution'),
                            'singular_name' => __( 'Type of Performance', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Type of Performances', '1stopwebsolution'),
                            'all_items' => __( 'All Type of Performances', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Type of Performance', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Type of Performance:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Type of Performance', '1stopwebsolution'),
                            'update_item' => __( 'Update Type of Performance', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Type of Performance', '1stopwebsolution'),
                            'new_item_name' => __( 'New Type of Performance Name', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => 'typeofperformance', 'hierarchical' => true ),
            )
    );

    register_post_type( 'speak_art_request',array( 'labels' => array('name' => __( 'Event/Performance Requests' ), 'singular_name' => __( 'Event/Performance  Request' ) ), 'public' => true,
                'show_ui' => true,
                'capability_type' => 'post',
                'publicly_queryable' => true,
                'exclude_from_search' => false,
                'menu_position' => 13,
                'menu_icon' =>  'dashicons-analytics', 
                'hierarchical' => false,               
                'query_var' => true,
                'supports' => array( 'title', 'editor', 'custom-fields', 'thumbnail')));

	
// register the new ticket category
	register_taxonomy( 'ticketcat',
            array( 'tickets'),
            array('hierarchical' => true,                    
                    'labels' => array(
                            'name' => __( 'Categories', '1stopwebsolution'),
                            'singular_name' => __( 'Categories', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Categories', '1stopwebsolution'),
                            'all_items' => __( 'All Categories', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Category', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Category:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Categories', '1stopwebsolution'),
                            'update_item' => __( 'Update Category', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Category', '1stopwebsolution'),
                            'new_item_name' => __( 'New Category', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                   
					'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => 'qualification', 'hierarchical' => true ),
            )
    ); 
	
	// FAQ posts
	register_post_type( 'faqs',
				array( 'labels' => array( 
								'name' => __( 'FAQ' ), 
								'singular_name' => __( 'FAQ' ) ), 
							'public' => true, 
							'has_archive' => true, 
							'menu_position' => 9,
							'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ))
						);


	
	 // register the newQualification taxonomy
	register_taxonomy( 'qualification',
            array( 'job','resume'),
            array('hierarchical' => true,                    
                    'labels' => array(
                            'name' => __( 'Job Qualifications', '1stopwebsolution'),
                            'singular_name' => __( 'Job Qualification', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Job Qualifications', '1stopwebsolution'),
                            'all_items' => __( 'All Job Qualifications', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Job Qualification', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Job Qualification:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Job Qualification', '1stopwebsolution'),
                            'update_item' => __( 'Update Job Qualification', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Job Qualification', '1stopwebsolution'),
                            'new_item_name' => __( 'New Job Qualification Name', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
					'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => 'qualification', 'hierarchical' => true ),
            )
    ); 
	
	 // register the newCategory taxonomy
	register_taxonomy( 'job_category',
            array( 'job','resume'),
            array('hierarchical' => true,                    
                    'labels' => array(
                            'name' => __( 'Special Job Categories', '1stopwebsolution'),
                            'singular_name' => __( 'Special Job Category', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Special Job Categories', '1stopwebsolution'),
                            'all_items' => __( 'All Special Job Categories', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Special Job Category', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Special Job Category:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Special Job Category', '1stopwebsolution'),
                            'update_item' => __( 'Update Special Job Category', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Special Job Category', '1stopwebsolution'),
                            'new_item_name' => __( 'New Special Job Category Name', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
					'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => 'job-category', 'hierarchical' => true ),
            )
    );
	
	 // register the newCategory taxonomy
	register_taxonomy( 'gen_job_category',
            array( 'job','resume'),
            array('hierarchical' => true,                    
                    'labels' => array(
                            'name' => __( 'General Job Categories', '1stopwebsolution'),
                            'singular_name' => __( 'General Job Category', '1stopwebsolution'),
                            'search_items' =>  __( 'Search General Job Categories', '1stopwebsolution'),
                            'all_items' => __( 'All General Job Categories', '1stopwebsolution'),
                            'parent_item' => __( 'Parent General Job Category', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent General Job Category:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit General Job Category', '1stopwebsolution'),
                            'update_item' => __( 'Update General Job Category', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New General Job Category', '1stopwebsolution'),
                            'new_item_name' => __( 'New General Job Category Name', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
					'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => 'gen-job-category', 'hierarchical' => true ),
            )
    );

    // register the new job type taxonomy
    register_taxonomy( 'types',
            array( 'job'),
            array('hierarchical' => true,
                    'labels' => array(
                            'name' => __( 'Job Types', '1stopwebsolution'),
                            'singular_name' => __( 'Job Type', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Job Types', '1stopwebsolution'),
                            'all_items' => __( 'All Job Types', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Job Type', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Job Type:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Job Type', '1stopwebsolution'),
                            'update_item' => __( 'Update Job Type', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Job Type', '1stopwebsolution'),
                            'new_item_name' => __( 'New Job Type Name', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
					'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => 'job-types', 'hierarchical' => true ),
            )
    );

    // register the Year of Experiences taxonomy
    register_taxonomy( 'yr_of_exp',
            array( 'job'),
            array('hierarchical' => true,
                    'labels' => array(
                            'name' => __( 'Year of Experiences', '1stopwebsolution'),
                            'singular_name' => __( 'Year of Experience', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Year of Experiencess', '1stopwebsolution'),
                            'all_items' => __( 'All Year of Experiencess', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Year of Experiences', '1stopwebsolution'),
                            'parent_item_colon' => __( 'ParentYear of Experiences:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Year of Experiences', '1stopwebsolution'),
                            'update_item' => __( 'Update Year of Experiences', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Year of Experiences', '1stopwebsolution'),
                            'new_item_name' => __( 'New Year of Experiences', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
					'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => 'yr-of-exp'),
                    
            )
    );

    // register the Madhab taxonomy
    register_taxonomy( 'madhab',
            array( 'job'),
            array('hierarchical' => true,
                    'labels' => array(
                            'name' => __( 'Madhab/School', '1stopwebsolution'),
                            'singular_name' => __( 'Madhab/School', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Madhab/Schools', '1stopwebsolution'),
                            'all_items' => __( 'All Madhab/Schools', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Madhab/School', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Madhab/School:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Madhab/School', '1stopwebsolution'),
                            'update_item' => __( 'Update Madhab/School', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Madhab/School', '1stopwebsolution'),
                            'new_item_name' => __( 'New Madhab/School', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array( 'slug' => 'madhab-school' ),
            )
    );
	
	// register the Aqeeda taxonomy
    register_taxonomy( 'aqeeda',
            array( 'job'),
            array('hierarchical' => true,
                    'labels' => array(
                            'name' => __( 'Aqeedas', '1stopwebsolution'),
                            'singular_name' => __( 'Aqeeda', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Aqeedas', '1stopwebsolution'),
                            'all_items' => __( 'All Aqeedas', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Aqeeda', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Aqeeda:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Aqeeda', '1stopwebsolution'),
                            'update_item' => __( 'Update Aqeeda', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Aqeeda', '1stopwebsolution'),
                            'new_item_name' => __( 'New Aqeeda', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array( 'slug' => 'aqeeda' ),
            )
    );
	
	
	/**********************************************
		Shia Denominations 
	***********************************************/
	  // register the Madhab taxonomy
    register_taxonomy( 'Shiamadhab',
            array( 'job'),
            array('hierarchical' => true,
                    'labels' => array(
                            'name' => __( 'Madhab/School-Shia', '1stopwebsolution'),
                            'singular_name' => __( 'Madhab/School', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Madhab/Schools', '1stopwebsolution'),
                            'all_items' => __( 'All Madhab/Schools', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Madhab/School', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Madhab/School:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Madhab/School', '1stopwebsolution'),
                            'update_item' => __( 'Update Madhab/School', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Madhab/School', '1stopwebsolution'),
                            'new_item_name' => __( 'New Madhab/School', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array( 'slug' => 'shia-madhab-school' ),
            )
    );
	
	// register the Aqeeda taxonomy
    register_taxonomy( 'Shiaaqeeda',
            array( 'job'),
            array('hierarchical' => true,
                    'labels' => array(
                            'name' => __( 'Aqeedas-Shia', '1stopwebsolution'),
                            'singular_name' => __( 'Aqeeda', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Aqeedas', '1stopwebsolution'),
                            'all_items' => __( 'All Aqeedas', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Aqeeda', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Aqeeda:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Aqeeda', '1stopwebsolution'),
                            'update_item' => __( 'Update Aqeeda', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Aqeeda', '1stopwebsolution'),
                            'new_item_name' => __( 'New Aqeeda', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array( 'slug' => 'shia-aqeeda' ),
            )
    );
	
	// register the Languages taxonomy
    register_taxonomy( 'languages',
            array( 'job','resume'),
            array('hierarchical' => true,
                    'labels' => array(
                            'name' => __( 'Languages', '1stopwebsolution'),
                            'singular_name' => __( 'Language', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Languages', '1stopwebsolution'),
                            'all_items' => __( 'All Languages', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Language', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Language:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Language', '1stopwebsolution'),
                            'update_item' => __( 'Update Language', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Language', '1stopwebsolution'),
                            'new_item_name' => __( 'New Language', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array( 'slug' => 'languages' ),
            )
    );
	
	// register the Languages taxonomy
    register_taxonomy( 'zone',
            array( 'job', 'speak_art_request', 'speakers-artist'),
            array('hierarchical' => true,
                    'labels' => array(
                            'name' => __( 'Country', '1stopwebsolution'),
                            'singular_name' => __( 'Country', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Countries', '1stopwebsolution'),
                            'all_items' => __( 'All Countries', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Country', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Country:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Country', '1stopwebsolution'),
                            'update_item' => __( 'Update Country', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Country', '1stopwebsolution'),
                            'new_item_name' => __( 'New Country', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array( 'slug' => 'zone' ),
            )
    );
	
	// register the Salary Period taxonomy
    register_taxonomy( 'sal_prd',
            array( 'job'),
            array('hierarchical' => true,
                    'labels' => array(
                            'name' => __( 'Salary Period', '1stopwebsolution'),
                            'singular_name' => __( 'Salary Periods', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Salary Periods', '1stopwebsolution'),
                            'all_items' => __( 'All Salary Periods', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Salary Periods', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Salary Periods:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Salary Periods', '1stopwebsolution'),
                            'update_item' => __( 'Update Salary Periods', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Salary Periods', '1stopwebsolution'),
                            'new_item_name' => __( 'New Salary Periods', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array( 'slug' => 'salary-period' ),
            )
    );
	
	// register the Salary Option taxonomy
    register_taxonomy( 'sal_optn',
            array( 'job'),
            array('hierarchical' => true,
                    'labels' => array(
                            'name' => __( 'Salary Option', '1stopwebsolution'),
                            'singular_name' => __( 'Salary Option', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Salary Options', '1stopwebsolution'),
                            'all_items' => __( 'All Salary Options', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Salary Option', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Salary Option:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Salary Option', '1stopwebsolution'),
                            'update_item' => __( 'Update Salary Option', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Salary Option', '1stopwebsolution'),
                            'new_item_name' => __( 'New Salary Option', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array( 'slug' => 'salary-option' ),
            )
    );
	
		
	 // register the marketing areas 
	register_taxonomy( 'marketing_area',
            array( 'post'),
            array('hierarchical' => true,                    
                    'labels' => array(
                            'name' => __( 'Marketing Areas', '1stopwebsolution'),
                            'singular_name' => __( 'Marketing Area', '1stopwebsolution'),
                            'search_items' =>  __( 'Search Marketing Areas', '1stopwebsolution'),
                            'all_items' => __( 'All Marketing Areas', '1stopwebsolution'),
                            'parent_item' => __( 'Parent Marketing Area', '1stopwebsolution'),
                            'parent_item_colon' => __( 'Parent Marketing Area:', '1stopwebsolution'),
                            'edit_item' => __( 'Edit Marketing Area', '1stopwebsolution'),
                            'update_item' => __( 'Update Marketing Area', '1stopwebsolution'),
                            'add_new_item' => __( 'Add New Marketing Area', '1stopwebsolution'),
                            'new_item_name' => __( 'New Marketing Area Name', '1stopwebsolution')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
					'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => 'marketing_area', 'hierarchical' => true ),
            )
    ); 
}

/*****************************************
*Jobs Master table column field customization
****************************************/

add_filter('manage_edit-job_columns', 'kv_edit_columns');
function kv_edit_columns($columns){
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'ref' => __('Reference', '1stopwebsolution'),
		'title' => __('Job Title', '1stopwebsolution'),
		'author' => __('Job Author', '1stopwebsolution'),
		'qualification' => __('Qualification', '1stopwebsolution'),
		'types' => __('Job Type', '1stopwebsolution'),
		'madhab' => __('Madhab', '1stopwebsolution'),
		'aqeeda' => __('Aqeeda', '1stopwebsolution'), 
		'languages' => __('Languages', '1stopwebsolution'),
		'zone' => __('Zone', '1stopwebsolution'),
		'date' => __('Date', '1stopwebsolution'),
	);
	return $columns;    
}

/*****************************************
 * tickets custom fields
******************************************/
add_filter('manage_edit-tickets_columns', 'kv_edit_columns_tickets');

function kv_edit_columns_tickets($columns){

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Ticket Title', '1stopwebsolution'),
		'author' => __('User', '1stopwebsolution'),
		'ticketcat' => __('Ticket Category', '1stopwebsolution'),
		'author_role' => __('User Role', '1stopwebsolution'),
		'comments ' => __('Comments', '1stopwebsolution'),
		'date' => __('Date', '1stopwebsolution'),
	);
	return $columns;
}

add_filter('manage_edit-speak_art_request_columns', 'kv_edit_columns_speak_art_request');

function kv_edit_columns_speak_art_request($columns){

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title', '1stopwebsolution'),        
        'categoryartspk' => __('Category', '1stopwebsolution'),        
        'person_id' => __('Speaker/Artist', '1stopwebsolution'),
        'date' => __('Date', '1stopwebsolution'),
    );
    return $columns;
}

add_filter('manage_edit-speakers-artist_columns', 'kv_edit_columns_speaker_artists');

function kv_edit_columns_speaker_artists($columns){

    $columns = array(
        'cb' => '<input type="checkbox" />',        
        'title' => __('Speaker/Artist', '1stopwebsolution'),
        'speaker_or_artist' => __('Type', '1stopwebsolution'),
        'thumbnail' => __('Photo', '1stopwebsolution'),
        'date' => __('Date', '1stopwebsolution'),
    );
    return $columns;
}

function disable_new_posts() {
// Hide sidebar link
global $submenu;
unset($submenu['edit.php?post_type=tickets'][10]);

// Hide link on listing page
if (isset($_GET['post_type']) && $_GET['post_type'] == 'tickets') {
    echo '<style type="text/css">
    #favorite-actions, .add-new-h2, .tablenav, .wrap .page-title-action { display:none; }
    </style>';
}
}
add_action('admin_menu', 'disable_new_posts');

/*****************************************
 * Custom functions to get features for custome fields in each posts
******************************************/
function kv_custom_columns($column){
	global $post;
	$custom = get_post_custom();
	switch ($column) {
		case 'company':
			if ( isset($custom['_Company'][0]) && !empty($custom['_Company'][0]) ) :
				if ( isset($custom['_CompanyURL'][0]) && !empty($custom['_CompanyURL'][0]) ) echo '<a href="'.$custom['_CompanyURL'][0].'">'.$custom['_Company'][0].'</a>';
				else echo $custom['_Company'][0];
			endif;
		break;
		
		case 'qualification' :
			$post_type= get_post_type($post->ID );
			$term_list = wp_get_post_terms($post->ID, 'qualification', array("fields" => "all"));
			echo '<a href="'.admin_url( 'edit.php?post_type=' . $post_type ) . '&qualification='.$term_list[0]->slug.'" > '. $term_list[0]->name.' </a>';
		break;
		case 'types' :
			$post_type= get_post_type($post->ID );
			$term_list = wp_get_post_terms($post->ID, 'types', array("fields" => "all"));
			echo '<a href="'.admin_url( 'edit.php?post_type=' . $post_type ) . '&types='.$term_list[0]->slug.'" > '. $term_list[0]->name.' </a>';
		break;
		case 'madhab' :
			$post_type= get_post_type($post->ID );
			$term_list = wp_get_post_terms($post->ID, 'madhab', array("fields" => "all"));
			echo '<a href="'.admin_url( 'edit.php?post_type=' . $post_type ) . '&madhab='.$term_list[0]->slug.'" > '. $term_list[0]->name.' </a>';
		break;
		case 'aqeeda' :
			$post_type= get_post_type($post->ID );
			$term_list = wp_get_post_terms($post->ID, 'aqeeda', array("fields" => "all"));
			echo '<a href="'.admin_url( 'edit.php?post_type=' . $post_type ) . '&aqeeda='.$term_list[0]->slug.'" > '. $term_list[0]->name.' </a>';
		break;
		case 'languages' :
			$post_type= get_post_type($post->ID );
			$term_list = wp_get_post_terms($post->ID, 'languages', array("fields" => "all"));
			echo '<a href="'.admin_url( 'edit.php?post_type=' . $post_type ) . '&languages='.$term_list[0]->slug.'" > '. $term_list[0]->name.' </a>';
		break;
		case 'zone' :
			$post_type= get_post_type($post->ID );
			$term_list = wp_get_post_terms($post->ID, 'zone', array("fields" => "all"));
			echo '<a href="'.admin_url( 'edit.php?post_type=' . $post_type ) . '&zone='.$term_list[0]->slug.'" > '. $term_list[0]->name.' </a>';
		break;
		case 'ticketcat' :
			$post_type= get_post_type($post->ID );
			$term_list = wp_get_post_terms($post->ID, 'ticketcat', array("fields" => "all"));
			echo '<a href="'.admin_url( 'edit.php?post_type=' . $post_type ) . '&ticketcat='.$term_list[0]->slug.'" > '. $term_list[0]->name.' </a>';
		break;

        case 'speaker_or_artist' :
            $post_type= get_post_type($post->ID );
            $term_list = wp_get_post_terms($post->ID, 'speaker_or_artist', array("fields" => "all"));
            echo '<a href="'.admin_url( 'edit.php?post_type=' . $post_type ) . '&speaker_or_artist='.$term_list[0]->slug.'" > '. $term_list[0]->name.' </a>';
        break;
	
		 case 'categoryartspk' :
           $person_id = get_post_meta($post->ID, 'person_id', true); 
            if($person_id != null) { 
			$post_type= get_post_type($person_id);
                $term_list = wp_get_post_terms($person_id, 'speaker_or_artist', array("fields" => "all"));
				echo '<a href="'.admin_url( 'edit.php?post_type=' . $post_type ) . '&speaker_or_artist='.$term_list[0]->slug.'" > '. $term_list[0]->name.' </a>';
            }
        break;

		case 'author_role' :
			$get_author_role = get_userdata($post->post_author);
			echo implode(', ', $get_author_role->roles); 
		break;

        case 'person_id' :
            $person_id = get_post_meta($post->ID, 'person_id', true); 
            if($person_id != null) { 
                $title_person =  get_the_title($person_id);
				$term_list = wp_get_post_terms($post->ID, 'speaker_or_artist', array("fields" => "all"));
                echo '<a href="'.admin_url().'post.php?post='.$person_id.'&action=edit" > '.$title_person.'</a>'; 
            }
        break;

		case 'ref' :			  
			echo get_post_meta($post->ID, 'employer_ref', true); 
		break;
	}
}
add_action('manage_posts_custom_column',  'kv_custom_columns');

// add a logo column to the edit jobs screen
function kv_job_logo_column($cols) {
    $cols['logo'] = __('Logo', '1stopwebsolution');
    return $cols;
}
//add_filter('manage_edit-job_columns', 'kv_job_logo_column');


// add a thumbnail column to the edit posts screen
function kv_post_thumbnail_column($cols) {
    $cols['thumbnail'] = __('Thumbnail', '1stopwebsolution');
    return $cols;
}
add_filter('manage_posts_columns', 'kv_post_thumbnail_column');


// go get the attached images for the logo and thumbnail columns
function kv_thumbnail_value($column_name, $post_id) {

    if (('thumbnail' == $column_name) || ('logo' == $column_name)) {

        if (has_post_thumbnail($post_id)) echo get_the_post_thumbnail($post_id, array( 71, 61));

    }
}
add_action('manage_posts_custom_column', 'kv_thumbnail_value', 10, 2); 

function prefix_teammembers_metaboxes( ) {
   global $wp_meta_boxes;
   add_meta_box('postfunctiondiv', __('Application Options'), 'prefix_teammembers_metaboxes_html', 'job', 'normal', 'high');
}
add_action( 'add_meta_boxes_job', 'prefix_teammembers_metaboxes' );

//Meta box for the posts add newand edits page.
function prefix_teammembers_metaboxes_html()  {
    global $post;
    $custom = get_post_custom($post->ID);
    $pension_provision = isset($custom["pension_provision"][0])?$custom["pension_provision"][0]:'';
    $confidential = isset($custom["confidential"][0])?$custom["confidential"][0]:'';
    $accomodation = isset($custom["accomodation"][0])?$custom["accomodation"][0]:'';
    $accomodation_details = isset($custom["accomodation-details"][0])?$custom["accomodation-details"][0]:'';
    $dbs = isset($custom["dbs"][0])?$custom["dbs"][0]:'';
    $no_of_vacancy = isset($custom["no_of_vacancy"][0])?$custom["no_of_vacancy"][0]:'';
    $company_name = isset($custom["company_name"][0]) ? $custom["company_name"][0] : '' ;
    $company_description = isset($custom["company_description"][0]) ? $custom["company_description"][0] : '' ;
    $custom_qualification = isset($custom["custom_qualification"][0]) ? $custom["custom_qualification"][0] : '' ;
    $address1 = isset($custom["address1"][0]) ? $custom["address1"][0] : '' ;
		$address2 = isset($custom["address2"][0]) ? $custom["address2"][0] : '' ;
		$city = isset($custom["city"][0]) ? $custom["city"][0] : '' ;
	$state_pro_reg = isset($custom["state_pro_reg"][0]) ? $custom["state_pro_reg"][0] : '' ;
    $ad_start_date = isset($custom["ad_start_date"][0]) ? $custom["ad_start_date"][0] : '' ;
    $ad_close_date = isset($custom["ad_close_date"][0]) ? $custom["ad_close_date"][0] : '' ;
    $eligible_work_in = isset($custom["eligible_work_in"][0]) ? $custom["eligible_work_in"][0] : '' ;
    $employer_ref = isset($custom["employer_ref"][0]) ? $custom["employer_ref"][0] : '' ;
    $equality_statement = isset($custom["equality_statement"][0]) ? $custom["equality_statement"][0] : '' ;
    $experience_details = isset($custom["experience_details"][0]) ? $custom["experience_details"][0] : '' ;
    $gender = isset($custom["gender"][0]) ? $custom["gender"][0] : '' ;
    $hours_per_week = isset($custom["hours_per_week"][0]) ? $custom["hours_per_week"][0] : '' ;
    $how_to_apply = isset($custom["how_to_apply"][0]) ? $custom["how_to_apply"][0] : '' ;
    $manual_apply_details = isset($custom["manual_apply_details"][0]) ? $custom["manual_apply_details"][0] : '' ;
    $in_start_date = isset($custom["in_start_date"][0]) ? $custom["in_start_date"][0] : '' ;
    $monitoring_equality = isset($custom["monitoring_equality"][0]) ? $custom["monitoring_equality"][0] : '' ;
    $sal_amount = isset($custom["sal_amount"][0]) ? $custom["sal_amount"][0] : '' ;    
    $website = isset($custom["website"][0]) ? $custom["website"][0] : '' ;
    $work_time = isset($custom["work_time"][0]) ? $custom["work_time"][0] : '' ;
    $other_informations = isset($custom["other_informations"][0])?$custom["other_informations"][0]:''; ?> 
	<div id="kv_job_post_meta" style="padding: 3px">
	<table> 
	<tr> <td> <label>Company Name:</label> </td> <td>  <input type="text" name="company_name" value="<?php echo $company_name; ?>"> </td> </tr>
	<tr> <td><label>Website:</label> </td> <td>  <input type="text" name="website" value="<?php echo $website; ?>"></td> </tr>
	<tr> <td><label>Company Description:</label> </td> <td>  <textarea name="company_description"><?php echo $company_description; ?>  </textarea>	</td> </tr>
	<tr> <td><label>Address 1:</label> </td> <td>  <textarea name="address1"><?php echo $address1; ?>  </textarea>	</td> </tr>
	<tr> <td><label>Address 2:</label> </td> <td>  <textarea name="address2"><?php echo $address2; ?>  </textarea>	</td> </tr>
	<tr> <td> <label>City:</label> </td> <td>  <input type="text" name="city" value="<?php echo $city; ?>">	</td> </tr>
	<tr> <td> <label>State :</label> </td> <td>  <input type="text" name="state_pro_reg" value="<?php echo $state_pro_reg; ?>">	</td> </tr>  
	
	<tr> <td> <label>Reference :</label> </td> 	<td> <input type="text" name="employer_ref" value="<?php echo $employer_ref; ?>"></td> </tr>
	<tr> <td> <label>Advertisement Start Date:</label> </td> 	<td> <input type="text" name="ad_start_date" value="<?php echo $ad_start_date; ?>"></td> </tr>
	<tr> <td> <label>Advertisement Close Date:</label> </td> 	<td> <input type="text" name="ad_close_date" value="<?php echo $ad_close_date; ?>"></td> </tr>
	<tr> <td> <label>Interview Start Date:</label> </td> 	<td> <input type="text" name="in_start_date" value="<?php echo $in_start_date; ?>"></td> </tr>	
	<tr> <td> <label>Gender:</label> </td> <td> <label> <input name="gender" type="radio" value="male" <?php  if($gender == 'male' ) { echo 'checked'; } ?>> Male </label> <label style="margin-left: 60px; " > <input name="gender" type="radio" value="female" <?php  if($gender == 'female' ) { echo 'checked'; } ?>> Female</label><label style="margin-left: 60px; " > <input name="gender" type="radio" value="any" <?php  if($gender == 'any' ) { echo 'checked'; } ?>> Any </label>
	<tr> <td> <label>Number of Vacancy:</label> </td> <td> <input type="text" name="no_of_vacancy" value="<?php echo $no_of_vacancy; ?>"></td> </tr>	
	<tr> <td> <label>Experience Details:</label></td> <td>  <textarea  name="experience_details" style="width:100%; height:50px;"><?php  echo $experience_details; ?> </textarea> </td> </tr>	
	<tr> <td> <label>Salary Amount:</label> </td> 	<td> <input type="text" name="sal_amount" value="<?php echo $sal_amount; ?>"></td> </tr>
	<tr> <td> <label>Hours Per Week:</label> </td> 	<td> <input type="text" name="hours_per_week" value="<?php echo $hours_per_week; ?>"></td> </tr>	
	<tr> <td> <label>Work time:</label> </td> <td>  <input type="text" name="work_time" value="<?php echo $work_time; ?>">	</td> </tr>		
	
	<tr> <td>	<label>How to Apply :</label> </td> <td> <label> <input name="how_to_apply" type="radio" value="apply_online" <?php  if($how_to_apply == 'apply_online' ) { echo 'checked'; } ?> >  Apply through eimams  </label> <label style="margin-left: 60px; "> <input name="how_to_apply" type="radio" value="manual_mtd" <?php  if($how_to_apply == 'manual_mtd' ) { echo 'checked'; } ?> >  Apply Manually</label> <?php if($how_to_apply == 'manual_mtd' ) { ?><br> 
	<textarea  name="manual_apply_details" style="width:100%; height:50px;"><?php  echo $manual_apply_details; ?> </textarea>

<?php } ?></td> </tr>
	<tr> <td> <label>Pension Provision:</label> </td> <td> <select name="pension_provision" >
			<option <?php  if($pension_provision == 'no' ) { echo 'selected'; } ?> value="no" > No  </option>
			<option <?php  if($pension_provision == 'yes' ) { echo 'selected'; } ?> value = "yes"> Yes  </option>
			<option <?php  if($pension_provision == 'Not Applicable' ) { echo 'selected'; } ?> value="Not Applicable" > Not Applicable </option>
		</select></td> </tr>
		
	<tr> <td> <label>Monitoring / Equality:</label> </td> 	<td> <select name="monitoring_equality" >
			<option <?php  if($monitoring_equality == 'no' ) { echo 'selected'; } ?> value="no" > No  </option>
			<option <?php  if($monitoring_equality == 'yes' ) { echo 'selected'; } ?> value="yes"> Yes  </option>
			<option <?php  if($monitoring_equality == 'Not Applicable' ) { echo 'selected'; } ?> value="Not Applicable" > Not Applicable </option>
		</select></td> </tr>
		
	<tr> <td> <label>Equality Statement:</label> </td> 	<td> <select name="equality_statement" >
			<option <?php  if($equality_statement == 'no' ) { echo 'selected'; } ?> value="no" > No  </option>
			<option <?php  if($equality_statement == 'yes' ) { echo 'selected'; } ?> value="yes" > Yes  </option>
			<option <?php  if($equality_statement == 'Not Applicable' ) { echo 'selected'; } ?> value="Not Applicable" > Not Applicable </option>
		</select></td> </tr>
	<tr> <td> <label>Eligible to work in: </label> </td> <td> <?php 
			$eligible_work = wp_dropdown_categories( array( 'show_option_none'=> 'Select Country' ,'echo' => 0, 'taxonomy' => 'zone','selected' => $eligible_work_in, 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0, 'orderby'            => 'name', 'order'      => 'ASC' ) );
			$eligible_work = str_replace( "name='cat' id=", "name='eligible_work_in' id=", $eligible_work );
			echo $eligible_work; ?></td> </tr>
	
	<tr> <td> <label>Custom Qualification:</label> </td> <td>  <input type="text" name="custom_qualification" value="<?php echo $custom_qualification; ?>">	</td> </tr>
		
	<tr> <td> <label>legal Check Requirement(dbs):</label> </td> 
		<td> <select name="dbs" >
			<option <?php  if($dbs == 'no' ) { echo 'selected'; } ?> value="no" > No  </option>
			<option <?php  if($dbs == 'yes' ) { echo 'selected'; } ?> value="yes" > Yes  </option>
			<option <?php  if($dbs == 'Not Applicable' ) { echo 'selected'; } ?> value="Not Applicable" > Not Applicable </option>
		</select></td> </tr>
		
	<tr> <td> <label>Confidential:</label> </td> <td> <label> <input name="confidential" type="radio" value="yes" <?php  if($confidential == 'yes' ) { echo 'checked'; } ?>> Yes</label> <label style="margin-left: 60px; " ><input name="confidential" type="radio" value="no" <?php  if($confidential == 'no' ) { echo 'checked'; } ?>> No </label>	</td> </tr>
	<tr> <td> <label>Accomodation:</label> </td> <td> <label> <input name="accomodation" type="radio" value="yes" <?php  if($accomodation == 'yes' ) { echo 'checked'; } ?> > Yes </label> <label style="margin-left: 60px; "> <input name="accomodation" type="radio" value="no" <?php  if($accomodation == 'no' ) { echo 'checked'; } ?>> No </label>	<br>
	<textarea  name="accomodation-details" style="width:100%; height:50px;"><?php  echo $accomodation_details; ?> </textarea></td> </tr>	

	<tr> <td> <label>Other Informations:</label></td> <td>  <textarea  name="other_informations" style="width:100%; height:50px;"><?php  echo $other_informations; ?> </textarea>	</td> </tr>		
	</table>
	
	<style> 
		#kv_job_post_meta table select, #kv_job_post_meta table input[type="text"],  #kv_job_post_meta table textarea, #kv_job_post_meta table { width: 100%; } 
		#kv_job_post_meta table td {  line-height: 4em; } 
	</style>
	<?php
}

//Custom fileds save functions
function kv_job_save_post(){
    if(empty($_POST)) return; //why is prefix_teammembers_save_post triggered by add new? 
    global $post;
    update_post_meta($post->ID, "pension_provision", $_POST["pension_provision"]);
    update_post_meta($post->ID, "confidential", $_POST["confidential"]);
    update_post_meta($post->ID, "accommodation", $_POST["accommodation"]);
    update_post_meta($post->ID, "no_of_vacancy", $_POST["no_of_vacancy"]);
    update_post_meta($post->ID, "company_name", $_POST["company_name"]);
    update_post_meta($post->ID, "company_description", $_POST["company_description"]);
    update_post_meta($post->ID, "custom_qualification", $_POST["custom_qualification"]);
    update_post_meta($post->ID, "other_informations", $_POST["other_informations"]); 
    update_post_meta($post->ID, "dbs", $_POST["dbs"]); 
    update_post_meta($post->ID, "address1", $_POST["address1"]); 
    update_post_meta($post->ID, "address2", $_POST["address2"]); 
    update_post_meta($post->ID, "city", $_POST["city"]); 
    update_post_meta($post->ID, "state_pro_reg", $_POST["state_pro_reg"]); 
    update_post_meta($post->ID, "ad_start_date", $_POST["ad_start_date"]); 
    update_post_meta($post->ID, "ad_close_date", $_POST["ad_close_date"]); 
    update_post_meta($post->ID, "eligible_work_in", $_POST["eligible_work_in"]); 
    update_post_meta($post->ID, "employer_ref", $_POST["employer_ref"]); 
    update_post_meta($post->ID, "equality_statement", $_POST["equality_statement"]); 
    update_post_meta($post->ID, "experience_details", $_POST["experience_details"]); 
    update_post_meta($post->ID, "gender", $_POST["gender"]); 
    update_post_meta($post->ID, "hours_per_week", $_POST["hours_per_week"]); 
    update_post_meta($post->ID, "how_to_apply", $_POST["how_to_apply"]); 
    update_post_meta($post->ID, "manual_apply_details", $_POST["manual_apply_details"]); 
    update_post_meta($post->ID, "in_start_date", $_POST["in_start_date"]); 
    update_post_meta($post->ID, "monitoring_equality", $_POST["monitoring_equality"]); 
    update_post_meta($post->ID, "sal_amount", $_POST["sal_amount"]); 
    update_post_meta($post->ID, "website", $_POST["website"]); 
    update_post_meta($post->ID, "work_time", $_POST["work_time"]); 
    update_post_meta($post->ID, "other_informations", $_POST["other_informations"]);   
    update_post_meta($post->ID, "accomodation-details", $_POST["accomodation-details"]);   
	
}    

add_action( 'save_post_job', 'kv_job_save_post' );  
function prefix_teammembers1_save_post(){
    if(empty($_POST)) return; //why is prefix_teammembers_save_post triggered by add new? 
    global $post;
    update_post_meta($post->ID, "name", $_POST["name"]);
    update_post_meta($post->ID, "gender", $_POST["gender"]);
    update_post_meta($post->ID, "address_1", $_POST["address_1"]);
    update_post_meta($post->ID, "address_2", $_POST["address_2"]);
    update_post_meta($post->ID, "post_code", $_POST["post_code"]); 
    update_post_meta($post->ID, "telephone_no", $_POST["telephone_no"]); 
    update_post_meta($post->ID, "from", $_POST["from"]); 
    update_post_meta($post->ID, "to", $_POST["to"]); 
    update_post_meta($post->ID, "per", $_POST["per"]);   
    update_post_meta($post->ID, "other", $_POST["other"]); 
}    
add_action( 'save_post_prefix-teammembers1', 'prefix_teammembers1_save_post' );  

/****************************************************************
*** 		page private or public settings
*****************************************************************/

add_action( 'add_meta_boxes', 'post_options_metabox' );

// backwards compatible
add_action( 'admin_init', 'post_options_metabox', 1 );

/* Do something with the data entered */
add_action( 'save_post', 'save_post_options' );

/**
 *  Adds a box to the main column on the Post edit screen
 * 
 */
function post_options_metabox() {
    add_meta_box( 'post_options', __( 'Post Options' ), 'post_options_code', array('post','page'), 'normal', 'high' );
}

/**
 *  Prints the box content
 */
function post_options_code( $post ) { 
    wp_nonce_field( dirname( __FILE__ ), $post->post_type . '_noncename' );
    $page_type = get_post_meta( $post->ID, 'page_type', true) ? get_post_meta( $post->ID, 'page_type', true) : 1; ?>
    <h2><?php _e( 'Page Type (Private or Public)' ); ?></h2>
    <div class="alignleft">
        <input id="meta_default" type="radio" name="page_type" value="private"<?php checked( 'private', $page_type );   echo ( $page_type == 'private' )?' checked="checked"' : ''; ?> /> <label for="meta_default" class="selectit"><?php _e( 'Private' ); ?></label><br />
        <input id="show_meta" type="radio" name="page_type" value="public"<?php checked( 'public', $meta_info );   echo ( $page_type == 'public' )?' checked="checked"' : ''; ?> /> <label for="show_meta" class="selectit"><?php _e( 'Public' ); ?></label><br />
          </div>
    <div class="alignright">
        <span class="description"><?php _e( 'Whether the page needs subscription to read or not.' ); ?></span>
    </div>
    <div class="clear"></div>
    <hr /><?php
}

/** 
 * When the post is saved, saves our custom data 
 */
function save_post_options( $post_id ) {
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times
  if ( !wp_verify_nonce( @$_POST[$_POST['post_type'] . '_noncename'], dirname( __FILE__ ) ) )
      return;

  // Check permissions
  if ( !current_user_can( 'edit_post', $post_id ) )
     return;

  // OK, we're authenticated: we need to find and save the data
  if( 'page' == $_POST['post_type'] ) {
      if ( !current_user_can( 'edit_post', $post_id ) ) {
          return;
      } else {
          update_post_meta( $post_id, 'page_type', $_POST['page_type'] );
      }
  } 
}



/****************************************************************
***    Speakers And Artist List  settings
*****************************************************************/

add_action( 'add_meta_boxes', 'speakers_options_metabox' );

// backwards compatible
add_action( 'admin_init', 'speakers_options_metabox', 1 );

/* Do something with the data entered */
add_action( 'save_post', 'save_speaker_options' );

/**
 *  Adds a box to the main column on the Post edit screen
 * 
 */
function speakers_options_metabox() {
    add_meta_box( 'post_options', __( 'Speaker Artists Options' ), 'speaker_options_code', array('speakers-artist'), 'normal', 'high' );
}

/**
 *  Prints the box content
 */
function speaker_options_code( $post ) { 
    wp_nonce_field( dirname( __FILE__ ), $post->post_type . '_noncename' );
   // $page_type = get_post_meta( $post->ID, 'page_type', true) ? get_post_meta( $post->ID, 'page_type', true) : 1; 

    $custom = get_post_custom($post->ID);
    $designation = isset($custom["designation"][0])?$custom["designation"][0]:'';
    $email = isset($custom["email"][0])?$custom["email"][0]:'';  ?>    
    <div id="postcustomstuff"> 
        <table><thead>
            <tr><th class="left">Name</th>  <th>Value</th></tr>
            </thead>
            <tbody>
            <tr><td class="left" style="padding-left:2%">Designation</td>
            <td><textarea name="designation" rows="2" cols="30"><?php echo $designation; ?></textarea></td>
            </tr>
            <tr><td class="left" style="padding-left:2%">E-mail </td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" placeholder=" Enter the Speaker or  artist's email"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <?php

}

/** 
 * When the post is saved, saves our custom data 
 */
function save_speaker_options( $post_id ) {
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times
  if ( !wp_verify_nonce( @$_POST[$_POST['post_type'] . '_noncename'], dirname( __FILE__ ) ) )
      return;

  // Check permissions
  if ( !current_user_can( 'edit_post', $post_id ) )
     return;

  // OK, we're authenticated: we need to find and save the data
  if( 'speakers-artist' == $_POST['post_type'] ) {
     // if ( !current_user_can( 'edit_post', $post_id ) ) {
       //   return;
      //} else {
          update_post_meta( $post_id, 'email', $_POST['email'] );
          update_post_meta( $post_id, 'designation', $_POST['designation'] );

     // }
  } 

}
?>