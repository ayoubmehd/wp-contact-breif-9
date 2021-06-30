<?php

function wp_contact_breif_9()
{
    global $wpdb;
    $table = $wpdb->prefix . 'contact';
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $content = $_POST["content"];
    $headers = array('From: ' . $name . ' <' . $email . '>');

    // Contact
    $admin_email = get_option('admin_email', false);
    wp_mail($options ? $options["to_email"] : $admin_email, $subject, $content, $headers); // Customize to email
    $wpdb->insert($table, [
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'content' => $content
    ]);

    // Redirect after submit
    $options = get_option('wp_contact_breif_9_options', false);
    $wpdb->wp_redirect(home_url('/' . $options ? $options["thank_you_page"] : "thank-you"), 301); // Customize your thank you page
    exit();
}
