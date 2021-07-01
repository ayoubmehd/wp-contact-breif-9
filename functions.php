<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

function prefix($string)
{
    return 'wp_contact_breif_9_' . $string;
}

function app_output_buffer()
{
    ob_start();
} // soi_output_buffer
add_action('init', 'app_output_buffer');
// add_action('init', 'wp_contact_breif_9_activate');

function wp_contact_breif_9_activate()
{
    global $wpdb;
    $table = $wpdb->prefix . 'contact';
    $sql = "CREATE TABLE IF NOT EXISTS `$table`(
        `id` INT NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(255), 
        `email` VARCHAR(255),
        `subject` VARCHAR(255),
        `content` TEXT,
        PRIMARY KEY(`id`)
    )";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function wp_contact_breif_9_uninstall()
{
    global $wpdb;
    $table = $wpdb->prefix . 'contact';
    $sql = "DROP TABLE IF EXISTS $table";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function wp_contact_breif_9_add_form_group()
{
?>
    <div class="<?php echo $attr['class'] ?>">
        <label for="email"><?php echo $attr['labeltext'] ?></label>
        <$attr['formcontrol']' class="' . $attr['formcontrolclass'] . '" type="email" id="email" name="email" placeholder="<?php echo $attr['labeltext'] ?>" />';
    </div>
<?php
}

function wp_contact_breif_9_add_form($attr)
{

    /**
     * Todo
     * [*] Customize classes for input
     */

    // Start form
    echo '<form class="' . $attr['formclass'] . '" method="POST">';

    // Name
    echo '<div class="' . $attr['formgroupclass'] . '">';
    echo '<label for="name">' . $attr["nametext"] . '</label>';
    echo '<input class="' . $attr['formcontrolclass'] . '" id="name" name="name" type="text" placeholder="' . $attr["nametext"] . '"  />';
    echo '</div>';

    // Email
    echo '<div class="' . $attr['formgroupclass'] . '">';
    echo '<label for="email">' . $attr["emailtext"] . '</label>';
    echo '<input class="' . $attr['formcontrolclass'] . '" type="email" id="email" name="email" placeholder="' . $attr["emailtext"] . '" />';
    echo '</div>';

    // Subject
    echo '<div class="' . $attr['formgroupclass'] . '">';
    echo '<label for="subject">' . $attr["subjecttext"] . '</label>';
    echo '<input class="' . $attr['formcontrolclass'] . '" type="text" id="subject" name="subject" placeholder="' . $attr["subjecttext"] . '" />';
    echo '</div>';

    // Content
    echo '<div class="' . $attr['formgroupclass'] . '">';
    echo '<label for="content">' . $attr["contenttext"] . '</label>';
    echo '<textarea id="content" name="content" placeholder="' . $attr["contenttext"] . '">';
    echo '</textarea>';
    echo '</div>';

    // submit button
    echo '<div>';
    echo '<button class="' . $attr["buttonclass"] . '" name="contact_form">' . $attr["submittext"] . '</button>';
    echo '</div>';

    // End form
    echo '</form>';
}

function wp_contact_breif_9_show_error()
{
    echo "fsdafsdafsd";
}

// Admin pannel
function wp_contact_breif_9_admin_link()
{
    require_once "includes/wp_contact_breif_9_admin_page.php";
    add_menu_page(
        'Contact Managment', // Title of the page
        'Contacts', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'wp_contact_breif_9_admin_page', // The 'slug' - file to display when clicking the link
        'wp_contact_breif_9_admin_page'
    );

    add_submenu_page(
        'wp_contact_breif_9_admin_page',
        'Show Contacts',
        'Show Contacts',
        'manage_options',
        'wp_contact_breif_9_admin_page_show_contacts',
        'wp_contact_breif_9_admin_page_show_contacts',
    );
}

function wp_contact_breif_9_options()
{
    register_setting('wp_contact_breif_9_options', 'wp_contact_breif_9_options', [
        'default' => 'thank-you',
    ]);

    add_settings_section(
        'global_settings',
        'Global Settings',
        'wp_contact_breif_9_global_options_text',
        'global_settings'
    );

    add_settings_field(
        prefix('options_to_email'),
        'To email',
        'wp_contact_breif_9_options_to_email',
        'global_settings',
        'global_settings'
    );

    add_settings_field(
        prefix('options_thank_you_page'),
        'Thank you page',
        'wp_contact_breif_9_thank_you_page',
        'global_settings',
        'global_settings'
    );
}

function wp_contact_breif_9_global_options_text()
{
    echo '<p>Here you can set all the options for using the contact form</p>';
}

// Contact managment
function wp_contact_breif_9_admin_page_show_contacts()
{
    global $wpdb;
    $table = $wpdb->prefix . 'contact';
    $limit = isset($_GET["limit"]) ? $_GET["limit"] : 10;
    $pageNumber = isset($_GET["page_number"]) ? $_GET["page_number"] : 1;
    $sql = "SELECT `id`, `name`, `email`, `subject`, `content` FROM `$table` LIMIT %d OFFSET %d";
    $contacts =
        $wpdb->get_results(
            $wpdb->prepare(
                $sql,
                [$limit, ($limit * $pageNumber) - $limit]
            )
        );

    if (isset($_GET["action"])) {
        $action = $_GET["action"];
        if ($action === "delete") {
            $contact_id = isset($_GET["id"]) ? $_GET["id"] : null;

            if ($contact_id) {
                $wpdb->delete($table, ["id" => $contact_id], ["%d"]);
            }
            $current_url = remove_query_arg(["action", "id"], home_url($_SERVER["REQUEST_URI"]));
            wp_redirect($current_url, 301);
            exit();
        }
    }
?>
    <div class="wrap">
        <h3>Show contacts</h3>
        <table class="wp-list-table widefat fixed striped table-view-list posts">
            <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column"></td>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">Name</th>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">User Email</th>
                    <th scope="col" id="author" class="manage-column column-author">Subject</th>
                    <th scope="col" id="categories" class="manage-column column-categories">Content</th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php foreach ($contacts as $key => $contact) : ?>

                    <tr id="post-<?php echo $key ?>" class="iedit author-self level-0 post-<?php echo $key ?> type-post status-publish format-standard hentry category-uncategorized entry">

                        <td class="column-primary" data-colname="Title">

                        </td>
                        <td class="column-primary" data-colname="Title">
                            <?php echo $contact->name ?>
                            <div class="row-actions">
                                <a href="<?php  ?>" aria-label="Replay To <?php echo $contact->subject ?>">
                                    replay
                                </a> |
                                <span class="trash">
                                    <a href="<?php echo home_url($_SERVER["REQUEST_URI"]) ?>&action=delete&id=<?php echo $contact->id ?>" class="submitdelete" aria-label="Delete <?php echo $contact->subject ?>">
                                        delete
                                    </a>
                                </span>
                            </div>
                        </td>
                        <td class="column-primary" data-colname="Title">
                            <?php echo $contact->email ?>
                        </td>
                        <td class="column-primary" data-colname="Title">
                            <?php echo $contact->subject ?>
                        </td>
                        <td class="column-primary" data-colname="Title">
                            <?php echo substr($contact->content, 0, 140) ?>...
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td class="manage-column column-cb check-column"></td>
                    <th scope="col" class="manage-column column-title column-primary sortable desc">Name</th>
                    <th scope="col" class="manage-column column-author">User Email</th>
                    <th scope="col" class="manage-column column-categories">Subject</th>
                    <th scope="col" class="manage-column column-tags">Content</th>
                </tr>
            </tfoot>

        </table>
    </div>
<?php
}
