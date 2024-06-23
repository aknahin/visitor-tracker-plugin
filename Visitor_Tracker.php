<?php
/**
 * Plugin Name: Visitor Tracker
 * Description: Tracks visitors and sends email after session ends.
 * Version: 1.0
 * Author: AK Nahin
 * Author URI: https://aknahin.com
 */

// Ensure no direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add settings page
function vt_admin_menu() {
    add_options_page(
        'Visitor Tracker Settings',
        'Visitor Tracker',
        'manage_options',
        'visitor-tracker',
        'vt_settings_page'
    );
}
add_action('admin_menu', 'vt_admin_menu');

function vt_settings_page() {
    ?>
    <div class="wrap">
        <h1>Visitor Tracker Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('vt_settings_group');
            do_settings_sections('vt_settings_group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Email Addresses</th>
                    <td><textarea name="vt_email_addresses" rows="10" cols="50"><?php echo esc_attr(get_option('vt_email_addresses')); ?></textarea></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Email Subject</th>
                    <td><input type="text" name="vt_email_subject" value="<?php echo esc_attr(get_option('vt_email_subject', 'New visitor on your website')); ?>" placeholder="New visitor on your website" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <h2>Email Sending Log</h2>
        <table class="widefat fixed">
            <thead>
                <tr>
                    <th>Date and Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $email_log = get_option('vt_email_log', []);
                if (!empty($email_log)) {
                    foreach ($email_log as $log) {
                        echo '<tr><td>' . esc_html($log) . '</td></tr>';
                    }
                } else {
                    echo '<tr><td>No emails have been sent yet.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}

function vt_register_settings() {
    register_setting('vt_settings_group', 'vt_email_addresses');
    register_setting('vt_settings_group', 'vt_email_subject');
}
add_action('admin_init', 'vt_register_settings');

// Start session
function vt_start_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'vt_start_session', 1);

// Track visitor information
function vt_track_visitor() {
    if (!isset($_SESSION['vt_visitor'])) {
        $_SESSION['vt_visitor'] = [
            'ip' => $_SERVER['REMOTE_ADDR'],
            'start_time' => current_time('mysql'),
            'pages' => []
        ];
    }

    $_SESSION['vt_visitor']['pages'][] = [
        'url' => $_SERVER['REQUEST_URI'],
        'time' => current_time('mysql')
    ];
}
add_action('wp', 'vt_track_visitor');

// End session and send email
function vt_end_session() {
    if (isset($_SESSION['vt_visitor'])) {
        $visitor = $_SESSION['vt_visitor'];
        $visitor['end_time'] = current_time('mysql');
        vt_send_email($visitor);
        unset($_SESSION['vt_visitor']);
    }
}
add_action('wp_logout', 'vt_end_session');
add_action('shutdown', 'vt_end_session');

function vt_send_email($visitor) {
    $emails = explode("\n", get_option('vt_email_addresses'));
    $emails = array_map('trim', $emails);
    $emails = array_filter($emails);

    $subject = get_option('vt_email_subject', 'New visitor on your website');
    $body = "Visitor IP: {$visitor['ip']}\n";
    $body .= "Session Start: {$visitor['start_time']}\n";
    $body .= "Session End: {$visitor['end_time']}\n\n";
    $body .= "Pages Visited:\n";

    foreach ($visitor['pages'] as $page) {
        $body .= "- {$page['url']} at {$page['time']}\n";
    }

    foreach ($emails as $email) {
        wp_mail($email, $subject, $body);
    }

    // Update the email log
    $email_log = get_option('vt_email_log', []);
    $email_log[] = current_time('mysql');
    update_option('vt_email_log', $email_log);
}
?>
