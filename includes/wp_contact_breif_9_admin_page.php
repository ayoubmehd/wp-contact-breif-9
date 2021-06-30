<?php

function wp_contact_breif_9_admin_page()
{
?>
    <div class="wrap">
        <h2>Your Plugin Page Title</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('wp_contact_breif_9_options');
            do_settings_sections('global_settings');
            ?>
            <button class="button button-primary">Save</button>
        </form>
    </div>
<?php
}


function wp_contact_breif_9_thank_you_page()
{
    $options = get_option('wp_contact_breif_9_options', false);
    $pages = get_pages();
?>
    <select name="wp_contact_breif_9_options[thank_you_page]">
        <option value="-1" selected disabled>Chose a page</option>
        <?php foreach ($pages as $page) : ?>
            <option value="<?php echo $page->post_name ?>" <?php echo isset($options["thank_you_page"]) && $options["thank_you_page"] === $page->post_name ? "selected" : "" ?>><?php echo $page->post_title  ?></option>
        <?php endforeach; ?>
    </select>
    <!-- <input id="wp_contact_breif_9_options_thank_you_page" name="wp_contact_breif_9_options[thank_you_page]" type='text' /> -->
<?php
}

function wp_contact_breif_9_options_to_email()
{
    $options = get_option('wp_contact_breif_9_options', false);
?>
    <input id="wp_contact_breif_9_options_to_email" name="wp_contact_breif_9_options[to_email]" type='text' value="<?php echo $options ? $options['to_email'] : '' ?>" />
<?php
}
