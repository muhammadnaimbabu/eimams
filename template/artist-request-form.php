<?php
 /** Template Name: Artist Request form */

//get_header();

	global $wpdb;
	$artist_sponser_table = $wpdb->prefix.'artist';
	if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['requestSubmit'])) {
		$fields = array(
                'PrimarySponsor',
				'PrimarySponsorURL',
				'phoneNumber',
				'DateofBirth',
				'postalAddress',
				'ZipCode',
				'SponsorCountry',
				'AdditionalSponsor',
				'PrimaryEventContact',
				'PrimaryPhoneNo',
				'PrimaryMobilePhoneNo',
				'primaryEmail',
				'SecondaryEventContact',
				'SecondaryPhoneNo',
				'SecondaryMobilePhoneNo',
				'secondary_contact_email',
				'event_description',
				'date_of_event', 
				'event_time',
				'eventDate',
				'location_of_event',
				'ZipCode2',
				'LocationCountry',
				'TypeOfEvent',
				'TypeOfPerformance',
				'title_of_performance',
				'why_artist_choosen',
				'list_of_artist',
				'ExpectedAttendance',
				'description_of_your_expected_audience',
				'suggested_artist_honorarium',
				'PaymentOption',
				'honorium_payment_options',
				'performance_recording',
				'speechRequest',
				'TravelArrangement',
				'FinancialResponse',
				'Cancellations',
				'AcceptHonorairum',
				'ContentOwnership',
				'person_id',
				'category'
		);	


   		foreach ($fields as $field) {
			if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
   		}
	
		
		$errors = new WP_Error();
	
		if ($posted['PrimarySponsor'] == null )
			$errors->add('empty_PrimarySponsor', __('<strong>Notice</strong>: Please enter Sponsor Name.', 'kv_project'));
		
		if ($posted['PrimarySponsorURL'] == null )		
			$errors->add('empty_PrimarySponsorURL', __('<strong>Notice</strong>: Please enter the Sponsors Url.', 'kv_project'));
	  
	    	
		if ($posted['phoneNumber'] == null )
			$errors->add('empty_phoneNumber', __('<strong>Notice</strong>: Please enter your Phone Number.', 'kv_project'));
		
			
		if ($posted['postalAddress'] == null )
			$errors->add('empty_postalAddress', __('<strong>Notice</strong>: Please enter the Postal Address.', 'kv_project'));
		
		//if ($posted['country'] == -1 )
			//$errors->add('empty_country', __('<strong>Notice</strong>: Please Select your country.', 'kv_project'));
		
		if ($posted['AdditionalSponsor'] == null )
			$errors->add('empty_AdditionalSponsor', __('<strong>Notice</strong>: Please enter Additional Sponsors.', 'kv_project'));
		
			
		if ($posted['PrimaryEventContact'] == null )
			$errors->add('empty_PrimaryEventContact', __('<strong>Notice</strong>: Please enter the Primary Contact.', 'kv_project'));
				
		if ($posted['PrimaryPhoneNo'] == null )
			$errors->add('empty_PrimaryPhoneNo', __('<strong>Notice</strong>: Please enter the Primary Phone Number.', 'kv_project'));
			
		if ($posted['PrimaryMobilePhoneNo'] == null )
			$errors->add('empty_PrimaryMobilePhoneNo', __('<strong>Notice</strong>: Please enter the Primary Mobile Number.', 'kv_project'));
			
		if ($posted['primaryEmail'] != null ){
			if(is_email($posted['primaryEmail'])){
				if(email_exists($posted['primaryEmail']) == false)
					$primaryEmail =  $posted['primaryEmail'];
				else 
					$errors->add('exist_usr_email', __('<strong>Notice</strong>: Email Already Registered in it.', 'kv_project'));
			}else 
				$errors->add('invalid_usr_email', __('<strong>Notice</strong>: Please enter proper email.', 'kv_project'));
		} else 
			$errors->add('empty_usr_email', __('<strong>Notice</strong>: Please enter Your Email.', 'kv_project'));	
	
		
		if ($posted['SecondaryEventContact'] == null )
			$errors->add('empty_SecondaryEventContact', __('<strong>Notice</strong>: Please enter the Secondary Event Contact.', 'kv_project'));
			
		if ($posted['SecondaryPhoneNo'] == null )
			$errors->add('empty_SecondaryPhoneNo', __('<strong>Notice</strong>: Please enter the Secondary Phone Number.', 'kv_project'));
			
		if ($posted['SecondaryMobilePhoneNo'] == null )
			$errors->add('empty_SecondaryMobilePhoneNo', __('<strong>Notice</strong>: Please enter the Secondary Mobile Number.', 'kv_project'));
		
		if ($posted['secondary_contact_email'] != null ){
			if(is_email($posted['secondary_contact_email'])){
				if(email_exists($posted['secondary_contact_email']) == false)
					$secondary_contact_email =  $posted['secondary_contact_email'];
				else 
					$errors->add('exist_usr_email', __('<strong>Notice</strong>: Secondary Email Already Registered in it.', 'kv_project'));
			}else 
				$errors->add('invalid_usr_email', __('<strong>Notice</strong>: Please enter Proper Secondary Email.', 'kv_project'));
		}else 
			$errors->add('empty_sec_usr_email', __('<strong>Notice</strong>: Please enter Secondary Email.', 'kv_project'));	
		
		
		if ($posted['event_description'] == null )
			$errors->add('empty_event_description', __('<strong>Notice</strong>: Please enter your Event Description.', 'kv_project'));
		
		if ($posted['date_of_event'] == null )
			$errors->add('empty_date_of_event', __('<strong>Notice</strong>: Please enter your Date Of Event.', 'kv_project'));
		
		if ($posted['event_time'] == null )
			$errors->add('empty_event_time', __('<strong>Notice</strong>: Please enter your Time Of Event.', 'kv_project'));
		
		if ($posted['eventDate'] == null )
			$errors->add('empty_event_date_other', __('<strong>Notice</strong>: Please enter your Other Date Of Event.', 'kv_project'));
		
		
		if ($posted['location_of_event'] == null )
			$errors->add('empty_location_of_event', __('<strong>Notice</strong>: Please enter the Location of Event.', 'kv_project'));
		
		if ($posted['TypeOfEvent'] == -1 )
			$errors->add('empty_TypeOfEvent', __('<strong>Notice</strong>: Please Select the  Type Of Event.', 'kv_project'));
		
		if ($posted['TypeOfPerformance'] == -1 )
			$errors->add('empty_TypeOfPerformance', __('<strong>Notice</strong>: Please Select The Type Of Performance.', 'kv_project'));
		
		if ($posted['title_of_performance'] == null )
			$errors->add('empty_title_of_speech', __('<strong>Notice</strong>: Please enter your Title of Performance.', 'kv_project'));
		
		if ($posted['why_artist_choosen'] == null )
			$errors->add('empty_why_speaker_choosen', __('<strong>Notice</strong>: Please enter Why Artist Choosen.', 'kv_project'));
		
		if ($posted['list_of_artist'] == null )
			$errors->add('empty_list_of_artist', __('<strong>Notice</strong>: Please enter List of Artists.', 'kv_project'));
		
		if ($posted['ExpectedAttendance'] == null )
			$errors->add('empty_ExpectedAttendance', __('<strong>Notice</strong>: Please enter your Expected Attendance.', 'kv_project'));
		
		if ($posted['description_of_your_expected_audience'] == null )
			$errors->add('empty_description_of_your_expected_audience', __('<strong>Notice</strong>: Please enter your Description for Expected Audience.', 'kv_project'));
		
		if ($posted['suggested_artist_honorarium'] == null )
			$errors->add('empty_suggested_artist_honorarium', __('<strong>Notice</strong>: Please enter suggested_artist_honorarium.', 'kv_project'));
		
		if ($posted['PaymentOption'] == null )
			$errors->add('empty_PaymentOption', __('<strong>Notice</strong>: Please enter honorarium payment options', 'kv_project'));
		
		if ($posted['performance_recording'] == null )
			$errors->add('empty_performance_recording', __('<strong>Notice</strong>: Please choose Performance Recorded or Not', 'kv_project'));

		if ($posted['speechRequest'] == null )
			$errors->add('empty_speechRequest', __('<strong>Notice</strong>: You have to check Speech Request', 'kv_project'));

		if ($posted['TravelArrangement'] == null )
			$errors->add('empty_TravelArrangement', __('<strong>Notice</strong>: You have to check Travel Arrangements', 'kv_project'));

		if ($posted['FinancialResponse'] == null )
			$errors->add('empty_FinancialResponse', __('<strong>Notice</strong>: You have to check Financial Responsibility', 'kv_project'));
		if ($posted['Cancellations'] == null )
			$errors->add('empty_Cancellations', __('<strong>Notice</strong>: You have to check Cancellations', 'kv_project'));
		if ($posted['AcceptHonorairum'] == null )
			$errors->add('empty_AcceptHonorairum', __('<strong>Notice</strong>: You have to check Honorarium', 'kv_project'));
		if ($posted['ContentOwnership'] == null )
			$errors->add('empty_ContentOwnership', __('<strong>Notice</strong>: You have to check Content Ownership', 'kv_project'));

		if ( !$errors->get_error_code() ) {
			if($_POST['category'] == 'Speaker')
				$cat_id =  611;
			elseif($_POST['category'] == 'Artist')
				$cat_id =  612;
			$new_post = array( 
				'post_title'	=>	$posted['title_of_performance'],
				'post_content'	=>	$posted['event_description'],
				'post_status'	=>	'pending' ,
				'post_type'		=>	'speak_art_request',				
				'tax_input' 	=> array('zone' => $posted['SponsorCountry'],
										'type_of_performance' =>  $posted['TypeOfPerformance'],
										'speaker_or_artist' => $cat_id,
										'type_of_event' => $posted['TypeOfEvent']),
				'post_author'	=>	1 ,
			);			
			$jid = wp_insert_post($new_post);
			$metafields = array( 'PrimarySponsor','PrimarySponsorURL','phoneNumber','DateofBirth','postalAddress','ZipCode','AdditionalSponsor','PrimaryEventContact','PrimaryPhoneNo','PrimaryMobilePhoneNo','primaryEmail','SecondaryEventContact','SecondaryPhoneNo','SecondaryMobilePhoneNo','secondary_contact_email','date_of_event', 'event_time','eventDate','location_of_event','ZipCode2','LocationCountry','TypeOfEvent','TypeOfPerformance','why_artist_choosen','list_of_artist','ExpectedAttendance','description_of_your_expected_audience','suggested_artist_honorarium','PaymentOption','honorium_payment_options',	'performance_recording', 'person_id', 'category');	
			foreach ($metafields as $value) {
				update_post_meta($jid, $value, $posted[$value]);
			} 
			kv_admin_mail_new_job_pending($jid);
			$success = "yes"; 
			$posted = array();				
		}
		$category = $_POST['category'];
		$person_id = $_POST['person_id'];		
	}

	if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['resetSubmit'])) {
		$category = $_POST['category'];
		$person_id = $_POST['person_id'];
		$posted = array();
	}
	if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['form_submit'])) {
		$category = $_POST['category'];
		$person_id = $_POST['person_id'];
	}
	get_header();  ?>	

	<style type="text/css">
		body { background:#f3f4f9}
		.hr { border-bottom:1px solid}
		.required-field { color:red; font-size:18px; }
		#speaker-list li { }
		#speaker-list p { margin:0}
		.date-dropdowns select {  width: auto !important; }
	</style>
<link href="<?php echo get_template_directory_uri();?>/css/datepicker.css" rel="stylesheet" />
<script src="<?php echo get_template_directory_uri();?>/js/jquery.date-dropdowns.min.js"></script>
<script src="<?php echo get_template_directory_uri();?>/js/wickedpicker.js"></script>
<link href="<?php echo get_template_directory_uri();?>/css/wickedpicker.css" rel="stylesheet" type="text/css">

<!-- ##############   speaker form  ######################  -->
<div class="container" style="min-height: 350px;"'">
<br>
<br>
<div style="clear:both;"></div>
<?php if(!isset($person_id)){
	echo   "<ul class='error text-center' > <li> There is no Artist or Speaker Selected to continue. Please <a href='".site_url('speakers')."' > Go Back </a> </li> </ul>";
	} else { 
	
	?>

<p>To submit a <?php echo (isset($category) ? $category : ''); ?> Request, please complete the form below.</p>
<p> Due to the large number of requests, we cannot guarantee an instant response to  your request. </p>
<p> Required fields are marked with an asterisk (*)</p><br>
  
<hr class="hr">
 <?php if (isset($errors) && sizeof($errors)>0 && $errors->get_error_code()) :
		          echo '<ul class="error text-center">';
		             foreach ($errors->errors as $error) {
			             echo '<li>'.$error[0].'</li>';
		                 }
		            echo '</ul>';
		elseif(isset($success) && $success == 'yes'):
		echo '<div class="success"> Your Request has been submitted successfully. And we will review and approve it. </div>'; 
	
	    endif; ?>
  <form class="form-horizontal" method="post" action="">
  		<?php if(isset($person_id)){
  			echo '<input type="hidden" name="person_id" value="'.$person_id.'" >';  		}
  		if(isset($category)){
  			echo '<input type="hidden" name="category" value="'.$category.'" >';
  		} ?>
        <div class="form-group">
		          <h2 align="center">Event Sponsor Information</h2>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3" for="PrimarySponsor">Primary Sponsor: <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="PrimarySponsor" name="PrimarySponsor" value="<?php if(isset($posted['PrimarySponsor'])){ echo $posted['PrimarySponsor']; }?>" placeholder="Primary Sponsor Name">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3" for="PrimarySponsorURL">Primary Sponsor URL: <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="PrimarySponsorURL" name="PrimarySponsorURL" placeholder="Primary Sponsor URL" value="<?php if(isset($posted['PrimarySponsorURL'])){ echo $posted['PrimarySponsorURL']; }?>">
            </div>
        </div>        
        <div class="form-group">
            <label class="control-label col-sm-3" for="phoneNumber">Primary Sponsor Phone Number: <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <input type="tel" class="form-control" id="phoneNumber"  name="phoneNumber" placeholder="Phone Number" value="<?php if(isset($posted['phoneNumber'])){ echo $posted['phoneNumber']; }?>">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-3" for="postalAddress">Primary Sponsor Address: <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <textarea rows="3" class="form-control" id="postalAddress" name="postalAddress" placeholder="Postal Address"><?php if(isset($posted['postalAddress'])){ echo $posted['postalAddress']; }?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3" for="ZipCode">Zip Code/Post Code:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="ZipCode" name="ZipCode" placeholder="Zip Code" value="<?php if(isset($posted['ZipCode'])){ echo $posted['ZipCode']; }?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3" for="country">Country:</label>
            <div class="col-sm-9">
                <?php if($selected_id != ''){
					$term_list = wp_get_post_terms($selected_id, 'zone', array("fields" => "ids"));
					$location=$term_list[0];
					} else{
						if(isset($_POST['SponsorCountry'])) {  $location=$_POST['SponsorCountry']; }
						else
							$location=0;
					}
					 $SponsorCountry = wp_dropdown_categories( array('show_option_none'=> 'Select Country' ,'echo' => 0, 'taxonomy' => 'zone','selected' => $location, 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0 , 'orderby'            => 'name', 'order'      => 'ASC') );
					$SponsorCountry = str_replace( "name='cat' id=", "name='SponsorCountry' id=", $SponsorCountry );
					echo $SponsorCountry; ?>
            </div>
        </div>  
        <div class="form-group">
            <label class="control-label col-sm-3" for="AdditionalSponsor">Additional Sponsors: <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="AdditionalSponsor" name="AdditionalSponsor" placeholder="Additional Sponsors" value="<?php if(isset($posted['AdditionalSponsor'])){ echo $posted['AdditionalSponsor']; }?>">
            </div>
        </div>   
  
       <hr class="hr">           
        
        <div class="form-group">
          		<h2 align="center">Event Contact Information</h2>
        </div> 
        <div class="form-group">
            <label class="control-label col-sm-3" for="PrimaryEventContact">Primary Event Contact <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="PrimaryEventContact" name="PrimaryEventContact" placeholder="Title First Last Suffix" value="<?php if(isset($posted['PrimaryEventContact'])){ echo $posted['PrimaryEventContact']; }?>">
            </div>
        </div>      
        <div class="form-group">
            <label class="control-label col-sm-3" for="PrimaryPhoneNo">Primary Phone No:<span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="PrimaryPhoneNo" name="PrimaryPhoneNo" placeholder="Primary Phone No" value="<?php if(isset($posted['PrimaryPhoneNo'])){ echo $posted['PrimaryPhoneNo']; }?>">
            </div>
        </div>      
        <div class="form-group">
            <label class="control-label col-sm-3" for="PrimaryMobilePhoneNo">Primary Mobile Phone No:<span class="required-field">*</span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="PrimaryMobilePhoneNo" value="<?php if(isset($posted['PrimaryMobilePhoneNo'])){ echo $posted['PrimaryMobilePhoneNo']; }?>" name="PrimaryMobilePhoneNo" placeholder="Primary Mobile Phone No">
            </div>
        </div>  
        <div class="form-group">
            <label class="control-label col-sm-3" for="primaryEmail">Primary Email:<span class="required-field">*</span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="primaryEmail" value="<?php if(isset($posted['primaryEmail'])){ echo $posted['primaryEmail']; }?>" name="primaryEmail" placeholder="Primary Email Adress">
            </div>
        </div>   
        <div class="form-group">
            <label class="control-label col-sm-3" for="SecondaryEventContact">Secondary Event Contact <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="SecondaryEventContact" value="<?php if(isset($posted['SecondaryEventContact'])){ echo $posted['SecondaryEventContact']; }?>" name="SecondaryEventContact" placeholder="Title First Last Suffix">
            </div>
        </div> 
        <div class="form-group">
            <label class="control-label col-sm-3" for="SecondaryPhoneNo">Secondary Phone No:<span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="SecondaryPhoneNo" value="<?php if(isset($posted['SecondaryPhoneNo'])){ echo $posted['SecondaryPhoneNo']; }?>" name="SecondaryPhoneNo" placeholder="Secondary Phone No">
            </div>
        </div>      
        <div class="form-group">
            <label class="control-label col-sm-3" for="SecondaryMobilePhoneNo">Secondary Mobile Phone No:<span class="required-field">*</span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="SecondaryMobilePhoneNo" value="<?php if(isset($posted['SecondaryMobilePhoneNo'])){ echo $posted['SecondaryMobilePhoneNo']; }?>" name="SecondaryMobilePhoneNo" placeholder="Secondary Mobile Phone No">
            </div>
        </div>                                                
          <div class="form-group">
            <label class="control-label col-sm-3" for="secondary-contact-email">Secondary Email:<span class="required-field">*</span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="secondary-contact-email" value="<?php if(isset($posted['secondary_contact_email'])){ echo $posted['secondary_contact_email']; }?>" name="secondary_contact_email" placeholder="Secondary Email Adress">
            </div>
        </div> 
  
 
  		<hr class="hr">
  
  
        <div class="form-group">
                   <h2 align="center">Event Information</h2>
        </div> 
  
      <div class="form-group">
            <label class="control-label col-sm-3" for="EventDescription">Event Description:<span class="required-field">*</span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="EventDescription" value="<?php if(isset($posted['event_description'])){ echo $posted['event_description']; }?>" name="event_description" placeholder="Event Description">
            </div>
        </div> 
  
  
        <div class="form-group">
            <label class="control-label col-sm-3" for="Date-of-Event">Date of Event:<span class="required-field">*</span></label>
            <div class="col-sm-9">
                <input type="hidden" class="form-control" id="Date-of-Event" value="<?php if(isset($posted['date_of_event'])){ echo $posted['date_of_event']; }?>" name="date_of_event" >
            </div>
        </div> 
  
          <div class="form-group">
            <label class="control-label col-sm-3" for="EventTime">Event Time:<span class="required-field">*</span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="EventTime" value="<?php if(isset($posted['event_time'])){ echo $posted['event_time']; }?>" name="event_time" placeholder="Event Time">
            </div>
        </div> 
  
            <div class="form-group">
            <label class="control-label col-sm-3" for="EventDate">Event Date(other options):<span class="required-field">*</span></label>
            <div class="col-sm-9">
                <input type="hidden" class="form-control" id="EventDate" value="<?php if(isset($posted['eventDate'])){ echo $posted['eventDate']; }?>" name="eventDate" >
            </div>
        </div> 
        
        
    <div class="form-group">
            <label class="control-label col-sm-3" for="location-of-event"> <?php if(isset($category) && $category== 'Speaker'){ echo 'Primary Sponsor Address' ; } elseif(isset($category) && $category== 'Artist') { echo 'Location of Event'; } ?>: <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <textarea rows="3" class="form-control" id="location-of-event" name="location_of_event" placeholder="Location of Event"><?php if(isset($posted['location_of_event'])){ echo $posted['location_of_event']; }?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3" for="ZipCode">Zip Code/Post Code:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="ZipCode" value="<?php if(isset($posted['ZipCode2'])){ echo $posted['ZipCode2']; }?>" name="ZipCode2" placeholder="Zip Code">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3" for="country">Country:</label>
            <div class="col-sm-9">
                 <?php if($selected_id != ''){
					$term_list = wp_get_post_terms($selected_id, 'zone', array("fields" => "ids"));
					$location=$term_list[0];
					} else{
						if(isset($_POST['LocationCountry'])) {  $location=$_POST['LocationCountry']; }
						else
							$location=0;
					}
					 $LocationCountry = wp_dropdown_categories( array('show_option_none'=> 'Select Country' ,'echo' => 0, 'taxonomy' => 'zone','selected' => $location, 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0 , 'orderby'            => 'name', 'order'      => 'ASC') );
					$LocationCountry = str_replace( "name='cat' id=", "name='LocationCountry' id=", $LocationCountry );
					echo $LocationCountry; ?>
            </div>
   </div>      
   
   
   <div class="form-group">
            <label class="control-label col-sm-3" for="country">Type of Event:<span class="required-field"> * </span></label>
            <div class="col-sm-9">
			 <?php if($selected_id != ''){
					$term_list = wp_get_post_terms($selected_id, 'type_of_event', array("fields" => "ids"));
					$location=$term_list[0];
					} else{
						if(isset($_POST['TypeOfEvent'])) {  $location=$_POST['TypeOfEvent']; }
						else
							$location=0;
					}
					 $TypeOfEvent = wp_dropdown_categories( array('show_option_none'=> 'Select Event Type' ,'echo' => 0, 'taxonomy' => 'type_of_event','selected' => $location, 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0 , 'orderby'            => 'name', 'order'      => 'ASC') );
					$TypeOfEvent = str_replace( "name='cat' id=", "name='TypeOfEvent' id=", $TypeOfEvent );
					echo $TypeOfEvent; ?>
		
            </div>
   </div>      

   <div class="form-group">
            <label class="control-label col-sm-3" for="TypeOfPerformance">Type of Performance:<span class="required-field"> * </span></label>
            <div class="col-sm-9">
			 <?php if($selected_id != ''){
					$term_list = wp_get_post_terms($selected_id, 'type_of_performance', array("fields" => "ids"));
					$location=$term_list[0];
					} else{
						if(isset($_POST['TypeOfPerformance'])) {  $location=$_POST['TypeOfPerformance']; }
						else
							$location=0;
					}
					$TypeOfPerformance = wp_dropdown_categories( array('show_option_none'=> 'Select Performance Type' ,'echo' => 0, 'taxonomy' => 'type_of_performance','selected' => $location, 'hierarchical' => true,'class'  => 'form-control',  'hide_empty' => 0 , 'orderby'            => 'name', 'order'      => 'ASC') );
					$TypeOfPerformance = str_replace( "name='cat' id=", "name='TypeOfPerformance' id=", $TypeOfPerformance );
					echo $TypeOfPerformance; ?>
               
            </div>
   </div>      
        
       <div class="form-group">
            <label class="control-label col-sm-3" for="title-of-performance"><?php if(isset($category) && $category== 'Speaker'){ echo 'Suggested Topic/' ; } ?>Title of Performance: <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="title-of-performance" value="<?php if(isset($posted['title_of_performance'])){ echo $posted['title_of_performance']; }?>"  name="title_of_performance" placeholder="Title of performance">
            </div>
        </div>
 
 
         <div class="form-group">
            <label class="control-label col-sm-3" for="why-artist-choosen">Why was this <?php echo (isset($category) ? $category : ''); ?> chosen for this event? <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <textarea rows="3" class="form-control" id="why-artist-choosen" name="why_artist_choosen" placeholder="Why was this <?php echo (isset($category) ? $category : ''); ?> chosen for this event?"><?php if(isset($posted['why_artist_choosen'])){ echo $posted['why_artist_choosen']; }?></textarea>
            </div>
        </div>

         <div class="form-group">
            <label class="control-label col-sm-3" for="list-of-artist">List of other invited <?php echo (isset($category) ? $category : ''); ?>s: <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <textarea rows="3" class="form-control" id="list-of-artist" name="list_of_artist" placeholder="List of other invited <?php echo (isset($category) ? $category : ''); ?>"><?php if(isset($posted['list_of_artist'])){ echo $posted['list_of_artist']; }?></textarea>
            </div>
        </div>    
        
        
       <div class="form-group">
            <label class="control-label col-sm-3" for="Expected-Attendance">Expected Attendance : <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <input type="datetime" class="form-control" id="Expected-Attendance" value="<?php if(isset($posted['ExpectedAttendance'])){ echo $posted['ExpectedAttendance']; }?>"  name="ExpectedAttendance" placeholder="Expected Attendance">
            </div>
        </div>
        
        
               <div class="form-group">
            <label class="control-label col-sm-3" for="description-of-your-expected-audience">Description of your expected audience : <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <textarea rows="3" class="form-control" id="description-of-your-expected-audience" name="description_of_your_expected_audience" placeholder="Description of your expected audience "><?php if(isset($posted['description_of_your_expected_audience'])){ echo $posted['description_of_your_expected_audience']; }?></textarea>
            </div>
        </div>  
        
        
            
     <div class="form-group">
            <label class="control-label col-sm-3" for="SuggestedArtistHonorarium">Suggested <?php echo (isset($category) ? $category : ''); ?> Honorarium : <span class="required-field"> * </span></label>
            <div class="col-sm-9">
                <input type="datetime" class="form-control" id="SuggestedArtistHonorarium" value="<?php if(isset($posted['suggested_artist_honorarium'])){ echo $posted['suggested_artist_honorarium']; }?>"  name="suggested_artist_honorarium" placeholder="SuggestedSpeakerHonorarium">
            </div>
        </div>
                
        
         <div class="form-group">
          <label class="control-label col-sm-3" for="honorium-payment-options">Please check one of the following honorarium payment options: <span class="required-field"> * </span></label>
          <div class="col-sm-9">
            <label class="radio-inline"><input type="radio" value="MailHonorarium" name="PaymentOption" <?php if(isset($posted['PaymentOption']) && $posted['PaymentOption'] == 'MailHonorarium'){ echo 'checked'; }?>>Mail honorarium in advance of performance</label>
            <label class="radio-inline"><input type="radio" value="ProvideHonorarium" name="PaymentOption" <?php if(isset($posted['PaymentOption']) && $posted['PaymentOption'] == 'ProvideHonorarium'){ echo 'checked'; }?>>Provide honorarium on the date of the event</label>
            <label class="radio-inline"><input type="radio" value="Other" name="PaymentOption"<?php if(isset($posted['PaymentOption']) && $posted['PaymentOption'] == 'Other'){ echo 'checked'; }?>>Other (please indicate below)</label>
             <textarea rows="3" class="form-control" id="honorium-payment-options" name="honorium_payment_options" placeholder="Suggested <?php echo (isset($category) ? $category : ''); ?> Honorarium Other Option" style="margin-top:20px;"></textarea>
           </div>
        </div>
        
        
       <div class="form-group">
            <label class="control-label col-sm-3">Will this Performance be recorded? (If yes, please see OWNERSHIP OF CONTENT disclosure below.):<span class="required-field"> * </span></label>
            <div class="col-xs-2">
                <label class="radio-inline">
                    <input type="radio" name="performance_recording" value="yes" <?php if(isset($posted['performance_recording']) && $posted['performance_recording'] == 'yes'){ echo 'checked'; }?> > Yes
                </label>
            </div>
            <div class="col-xs-2">
                <label class="radio-inline">
                    <input type="radio" name="performance_recording" value="no" <?php if(isset($posted['performance_recording']) && $posted['performance_recording'] == 'no'){ echo 'checked'; }?> > No
                </label>
            </div>
        </div>
            
        <hr class="hr">  
 
<h3 align="center">INFORMATION DISCLOSURE</h3>

<p align="center">Each box must be checked to complete the application. By checking each box, you are agreeing to eimams speech request terms.</p>

      <div class="form-group">
          <div class="col-xs-12">
            <label class="checkbox-inline"><input type="checkbox" value="yes" name="speechRequest"  <?php if(isset($posted['speechRequest']) && $posted['speechRequest']=='yes'){ echo 'checked'; }?> >Speech Request Acceptance <span class="required-field"> * </span></label>

			 <div class="alert alert-success">
			 <?php if(isset($category) && $category == 'Artist') { ?>
			NO WARRANTIES OR REPRESENTATIONS REGARDING ACCEPTANCE OF PERFORMANCE REQUEST. Eimams Online LTD. (“Eimams”) receives a large volume of performance requests and is therefore simply not able to grant all requests. You (“Prospective Host”) understand that Eimams will consider Prospective Host’s request but that Eimams makes no representation that it will be willing or able to accommodate the request. You (“Prospective Host”) agree to receive confirmation from Eimams staff before making any public announcement that the PERFORMER("ARTIST") will be performing at or otherwise attending any event planned by Prospective Host.
			<?php } elseif(isset($category) && $category == 'Speaker') {?>
			NO WARRANTIES OR REPRESENTATIONS REGARDING ACCEPTANCE OF SPEECH REQUEST. Eimams Online LTD. (“Eimams”) receives a large volume of speech requests and is therefore simply not able to grant all requests. You (“Prospective Host”) understand that Eimams will consider Prospective Host’s request but that Eimams makes no representation that it will be willing or able to accommodate the request. You (“Prospective Host”) agree to receive confirmation from Eimams staff before making any public announcement that the Scholar ("SPEAKER") will be speaking at or otherwise attending any event planned by Prospective Host. 
			<?php }?>
			</div>

            <label class="checkbox-inline"><input type="checkbox" value="yes" name="TravelArrangement" <?php if(isset($posted['TravelArrangement'])&& $posted['TravelArrangement']=='yes'){ echo 'checked'; }?> >Travel Arrangements <span class="required-field"> * </span></label>
             <div class="alert alert-success">
             <?php if(isset($category) && $category == 'Artist') { ?>
             Eimams NOT RESPONSIBLE FOR TRAVEL ARRANGEMENTS. In the event that Eimams Online LTD is willing to fulfill Prospective Host’s request, Prospective Host agrees to coordinate all programme and travel details for ARTIST and or ARTIST’s ASSISTANT with Eimams staff, including all transportation, hotel and meals.
             <?php } elseif(isset($category) && $category == 'Speaker') {?>
             Eimams NOT RESPONSIBLE FOR TRAVEL ARRANGEMENTS. In the event that Eimams Online LTD is willing to fulfill Prospective Host’s request, Prospective Host agrees to coordinate all programme and travel details for SPEAKER and or SPEAKER’s ASSISTANT with Eimams staff, including all transportation, hotel and meals. 
			<?php }?>
             </div>
            <label class="checkbox-inline"><input type="checkbox" value="yes" name="FinancialResponse" <?php if(isset($posted['FinancialResponse'])&& $posted['FinancialResponse']=='yes'){ echo 'checked'; }?> >Financial Responsibility <span class="required-field"> * </span></label>
             <div class="alert alert-success">
              <?php if(isset($category) && $category == 'Artist') { ?>
               Travel arrangements, including transportation, hotel and meals, for ARTIST and or ARTISTS’s ASSISTANT, shall be the financial responsibility of the Prospective Host and must be approved beforehand by Eimams staff for scheduling with ARTIST and ARTIST’s ASSISTANT. Prospective Host agrees not to purchase travel or hotel until Prospective Host receives such approval from Eimams.
               <?php } elseif(isset($category) && $category == 'Speaker') {?>
               Travel arrangements, including transportation, hotel and meals, for SPEAKER and or SPEAKER’s ASSISTANT, shall be the financial responsibility of the Prospective Host and must be approved beforehand by Eimams staff for scheduling with SPEAKER and SPEAKER’s ASSISTANT. Prospective Host agrees not to purchase travel or hotel until Prospective Host receives such approval from Eimams. 
			<?php }?>
             </div>
             <label class="checkbox-inline"><input type="checkbox" value="yes" name="Cancellations" <?php if(isset($posted['Cancellations'])&& $posted['Cancellations']=='yes'){ echo 'checked'; }?> >Cancellations <span class="required-field"> * </span></label>
             <div class="alert alert-success">
              <?php if(isset($category) && $category == 'Artist') { ?>
             Eimams NOT RESPONSIBLE FOR CANCELLATIONS. Prospective Host agrees and understands that ARTIST may have to change plans, even at the last minute. Prospective Host shall not hold Eimams or ARTIST liable for any cancellation for good cause.
             <?php } elseif(isset($category) && $category == 'Speaker') {?>
             Eimams NOT RESPONSIBLE FOR CANCELLATIONS. Prospective Host agrees and understands that SPEAKER may have to change plans, even at the last minute. Prospective Host shall not hold Eimams or SPEAKER liable for any cancellation for good cause. 
			<?php }?>
             </div>
            <label class="checkbox-inline"><input type="checkbox" value="yes" name="AcceptHonorairum" <?php if(isset($posted['AcceptHonorairum'])&& $posted['AcceptHonorairum']=='yes'){ echo 'checked'; }?> >Honorarium <span class="required-field"> * </span></label>
             <div class="alert alert-success">
              <?php if(isset($category) && $category == 'Artist') { ?>
             HONORARIUM BEFORE OR AT EVENT. The honorarium shall be mailed prior to the event or provided at the time of the event unless otherwise specified.
             <?php } elseif(isset($category) && $category == 'Speaker') {?>
             HONORARIUM BEFORE OR AT EVENT. The honorarium shall be mailed prior to the event or provided at the time of the event unless otherwise specified. 
			<?php }?>
             </div>
            <label class="checkbox-inline"><input type="checkbox" value="yes" name="ContentOwnership" <?php if(isset($posted['ContentOwnership'])&& $posted['ContentOwnership']=='yes'){ echo 'checked'; }?> >Content Ownership   <span class="required-field"> * </span></label>
                <div class="alert alert-success">
               <?php if(isset($category) && $category == 'Artist') { ?>
               Prospective Host agrees that content of ARTIST’s presentation shall be the property of ARTIST and that all copyrights to such content will belong to ARTIST. Prospective Host agrees not to record, post, distribute without ARTIST’s express written consent. The views expressed are those of the ARTIST and not necessarily those of Eimams Online LTD.
               <?php } elseif(isset($category) && $category == 'Speaker') {?>
               Prospective Host agrees that content of SPEAKER’s presentation shall be the property of SPEAKER and that all copyrights to such content will belong to SPEAKER. Prospective Host agrees not to record, post, distribute without SPEAKER’s express written consent. The views expressed are those of the SPEAKER and not necessarily those of Eimams Online LTD. 
			<?php }?>
             </div>  
           </div>
        </div>
<input type="hidden" id="example1" name="">
    <input type="submit" name="requestSubmit" class="btn btn-lg btn-primary" value="Submit" />
    <input type="submit" name="resetSubmit" class="btn btn-lg btn-primary" value="Reset" />
<br>
<br>

</form> <?php } ?>

</div>

<script>  
	$(function() {
		$("#DateofBirth").dateDropdowns({minAge: 18});
		$("#Date-of-Event").dateDropdowns();
		$("#EventDate").dateDropdowns();
	});			
jQuery(document).ready(function(){
	jQuery("#EventTime").wickedpicker();
	/*jQuery('.datepicker').datepicker({
            dateFormat: "dd-M-yy",
            onClose: function () {
                var dt1 = jQuery('#dt1').datepicker('getDate');
                //console.log(dt1);
                var dt2 = jQuery('#dt2').datepicker('getDate');
                if (dt2 <= dt1) {
                    var minDate = jQuery('#dt2').datepicker('option', 'minDate');
                    jQuery('#dt2').datepicker('setDate', minDate);
                }
            },  onSelect: function (date) {
                var date3 = jQuery('#dt2').datepicker('getDate');
                date3.setDate(date3.getDate() + 1);
                jQuery('#dt3').datepicker('setDate', date3);
                //sets minDate to dt1 date + 1
                jQuery('#dt3').datepicker('option', 'minDate', date3);
            }
			});
        */
});
</script>
<?php get_footer(); ?>			    				