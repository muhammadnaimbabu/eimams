<?php

/** Contact us form submit**/
add_action('wp_ajax_wpse_sendmail', 'wpse_sendmail');
add_action('wp_ajax_nopriv_wpse_sendmail', 'wpse_sendmail');

function wpse_sendmail()
{

    // Get all data from form
    $arr = "";
    wp_parse_str($_POST["wpse_sendmail"], $arr);
    // $errors = new WP_Error();
    $contact_name = sanitize_user($arr['name']);
    $email = sanitize_user($arr['email']);
    $phone = sanitize_user($arr['phone']);
    $contact_comment = sanitize_user($arr['message']);

    // Prepare for mail
    $to = 'admin@eimams.com';
    $header = 'from: admin@eimams.com';
    $subject = 'Message from eimams contact form';

    $content = "Name:" . $contact_name . "\r \n";
    $content .= "Email:" . $email . "\r \n";
    $content .= "Phone:" . $phone . "\r \n";
    $content .= "Comments:" . $contact_comment . "\r \n";

    // Shoot the mail
    $contact_mail = wp_mail($to, $subject, $content, $header);

    if ($contact_mail) {
        $done = "Thank you for contacting us. We will reply you soon!";
    } else {
        $done =  "You massage wasn't send, Please contact with adminstrator";
    }

    wp_send_json($done);
    die();
}
