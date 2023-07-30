<?php
/**
 Template Name: Apply Online
 */

get_header(); 
global $status;

//if(isset($_POST['submit_apply_online'])){
	//$pack = $_POST['pack'];
	//$days = $_POST['days'];
//}


$posted['full_name']= '';
$posted['email'] ='';
$posted['phone'] = '';
$posted['course_name'] = '';
$posted['course_level'] = '';


// reset
if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['reset_value'])) {
	$posted['full_name']= '';
	$posted['email'] ='';
	$posted['phone'] = '';
	$posted['course_name'] = '';
	$posted['course_level'] = '';
}
	
$errors = new WP_Error();			
	
	if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['submit_apply_online'])) {
		
		
		$fields = array(
				'full_name',
				'dob',
				'email',
				'phone',
				'course_name',
				'course_level',
				'upload_files'
				
			);
		
	foreach ($fields as $field) {
		if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
	}
	
	
	if ($posted['full_name'] != null )
		$full_name =  $posted['full_name'];
	else 
		$errors->add('empty_full_name', __('<strong>Notice</strong>: Please enter your name.'));						
	
	if ($posted['dob'] != null )
		$dob =  $posted['dob'];
	else 
		$errors->add('empty_dob', __('<strong>Notice</strong>: Please enter your Date of Birth.'));	
		
	if ($posted['phone'] != null )
		$phone =  $posted['phone'];
	else 
		$errors->add('empty_phone', __('<strong>Notice</strong>: Please enter your phone number.'));				
		
	if ($posted['email'] != null )
		$email =  $posted['email'];
	else 
		$errors->add('empty_email', __('<strong>Notice</strong>: Please enter your Email.'));
		
	if ($posted['course_name'] != null )
		$course_name =  $posted['course_name'];
	else 
		$errors->add('empty_course_name', __('<strong>Notice</strong>: Please enter your course name.'));		
	
	if ($posted['course_level'] != null )
		$course_level =  $posted['course_level'];
	else 
		$errors->add('empty_course_level', __('<strong>Notice</strong>: Please enter your course level.'));	
		

	if ($_FILES['upload_files']['size'] > 0 ){}
	else 
		$errors->add('empty_upload_files', __('<strong>Notice</strong>: Please upload necessary files.'));	
		
	}
	
	
	if ( !$errors->get_error_code() ) { 	
		send_my_awesome_form($posted);
     }
	 
	 
	 
	 	
	
// ************    files upload start code ****************************	


// *****************************************************************************
	

	function send_my_awesome_form($posted){
		global $status;
				if (!isset($_POST['submit_apply_online'])) { return; }
			
				// get the info from the from the form
				//$attachments = array();
				$form = $attachment=  array();
				if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
				$form['full_name'] = $_POST['full_name'];
				$form['dob'] = $_POST['dob'];
				$form['email'] = $_POST['email'];
				$form['phone'] = $_POST['phone'];
				$form['course_name'] = $_POST['course_name'];
				$form['course_level'] = $_POST['course_level'];
				//$attachments = $_FILES['upload_files'];
				//$form['course_name'] = $_POST['course_name'];
				//$form['course_level'] = $_POST['course_level'];	
				$form['message'] = $_POST['message'];								
				//wp_mail( 'mizan@faabra.com', 'test mail', 'test email');
				// Build the message
				$message  = "Name :" . $posted['full_name']."<br>".PHP_EOL;
				$message .= "Date of Birth :" . $form['dob']."<br>".PHP_EOL;
				$message .= "Email :" . $form['email']."<br>".PHP_EOL;
				$message .= "Phone :" . $form['phone']."<br>".PHP_EOL;
				$message .= "Course Name :" . $form['course_name']  ."<br>"   .PHP_EOL;
				$message .= "Course Level:" . $form['course_level']."<br>"  .PHP_EOL;
				$message .= "Message :" . $form['message']  ."<br>"   .PHP_EOL;
		
			$files = $_FILES["upload_files"];  
		foreach ($files['name'] as $key => $value) { 			
				if ($files['name'][$key]) { 
					$file = array( 
						'name' => $files['name'][$key],
	 					'type' => $files['type'][$key], 
						'tmp_name' => $files['tmp_name'][$key], 
						'error' => $files['error'][$key],
 						'size' => $files['size'][$key]
					); 
					$_FILES = array ("upload_files" => $file); 
					foreach ($_FILES as $file) {				
						$movefile = wp_handle_upload(   $file, array( 'test_form' => false ));
           	 			$attachments[] = $movefile[ 'file' ];
					}
				} 
			} 
			
				//set the form headers
				$headers = 'From: Contact form <your@globalvision.com>';
			
				// The email subject
				$subject = 'you got mail from Global Vision Apply Online Form';
			
				// Who are we going to send this form too
				$send_to = 'mizan@faabra.com';
				add_filter(       'wp_mail_content_type',       'onestop_email_content_type'   );
				if (wp_mail( $send_to, $subject, $message, $headers, $attachments ) ) {
					$status =  '<div style=" background: #63b2f5;border: 1px solid #ccc;box-shadow: 0px 0px 3px 1px #ccc;border-radius: 8px;margin: 30px auto;z-index: 1000;width: 500px;    padding: 10px;text-align: center;color: #eee;">Your mail is sent. we will contact you as soon as possible.</div>';
					//var_dump($attachments);
					foreach($attachments as $one_at)
					unlink( $one_at );
				 }
				remove_filter(       'wp_mail_content_type',       'onestop_email_content_type'   );
		}


function onestop_email_content_type( $content_type ) {
        return 'text/html';
    }
	
	
	
		add_action('wp_head', 'send_my_awesome_form');

?>
<div style="height:900px;width:100%;float:left; margin-top:50px;">
	
<div id="about-us" class="col-xs-12 col-sm-12 col-md-12">

    <?php 
		if (isset($errors) && sizeof($errors)>0 && $errors->get_error_code()) :
			echo '<ul class="errors">';
			foreach ($errors->errors as $error) {
				echo '<li>'.$error[0].'</li>';
			}
			echo '</ul>';
		endif;
		if($status != null ) echo $status; 	?>
   

<form class="form-horizontal" method="post" enctype='multipart/form-data' action="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI']; ?>">						


        <div class="form-group">
            <label class="control-label col-xs-3" for="Name">Name: <span class="mandatory"> *</span></label>
            <div class="col-xs-8">
                <input type="text" class="form-control" id="Name" name="full_name" placeholder=" Name" value="<?php echo $posted['full_name']; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-xs-3" for="Name">Date of Birth: <span class="mandatory"> *</span></label>
            <div class="col-xs-8">
                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $posted['dob']; ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-3" for="inputEmail">Email: <span class="mandatory"> *</span></label>
            <div class="col-xs-8">
                <input type="email" class="form-control" id="email" name="email" placeholder=" Email" value="<?php echo $posted['email']; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-xs-3" for="phoneNumber">Phone: <span class="mandatory"> *</span></label>
            <div class="col-xs-8">
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number" value="<?php echo $posted['phone']; ?>">
            </div>
        </div>
        
        
       <div class="form-group">
            <label class="control-label col-xs-3" for="course_level">Course Name: <span class="mandatory"> *</span></label>
            <div class="col-xs-8">
                <input type="text" class="form-control" id="course_name" name="course_name" placeholder=" Course Name" value="<?php echo $posted['course_name']; ?>">
            </div>
        </div>


       <div class="form-group">
            <label class="control-label col-xs-3" for="course_level">Course Level: <span class="mandatory"> *</span></label>
            <div class="col-xs-8">
                <input type="text" class="form-control" id="course_level" name="course_level" placeholder=" Course Level" value="<?php echo $posted['course_level']; ?>">
            </div>
        </div>
               

        <div class="form-group">
            <label class="control-label col-xs-3" for="message">Upload Files: <span class="mandatory"> *</span></label>
            <div class="col-xs-8">
                <input type="file" class="form-control" id="upload_files" name="upload_files[]" accept='.doc,.docx, .rtf, .pdf, jpg, png' multiple="multiple">
                <p>(Passport-mandatory, Proof of address-mandatory, Accademic Qualification-optoinal, CV in MS-word-mandatory) </p>
            </div>
        </div>
                

        <div class="form-group">
            <label class="control-label col-xs-3" for="message">Messange:</label>
            <div class="col-xs-8">
                <textarea class="form-control" id="message" name="message" placeholder="Message"> </textarea>
            </div>
        </div>
        
        <br>
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-8">
                <input type="submit" class="btn btn-primary" name="submit_apply_online" value="Submit">
                <input type="submit" class="btn btn-default" name="reset_value" value="Reset">
            </div>
        </div>
        
    </form>



	
	</div>

</div>

<?php // get_footer(); ?>
