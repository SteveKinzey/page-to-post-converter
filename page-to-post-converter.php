<?php
/**
 * Plugin Name: Page to Post Converter
 * Description: Converts WordPress Pages to Posts, with Elementor compatibility.
 * Version: 1.2
 * Author: Steve Kinzey
 * License: GPL2
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Admin settings
function p2p_admin_menu() {
    add_options_page('Page to Post Converter', 'Page to Post Converter', 'manage_options', 'p2p-converter', 'p2p_settings_page');
}
add_action('admin_menu', 'p2p_admin_menu');

function p2p_settings_page() {
    ?>
    <div class="wrap">
        <h1>Page to Post Converter Settings</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields('p2p_settings_group');
                do_settings_sections('p2p_settings_group');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Preserve Elementor Data?</th>
                        <td><input type="checkbox" name="p2p_preserve_elementor" value="1" <?php checked(1, get_option('p2p_preserve_elementor'), true); ?> /></td>
                    </tr>
                </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function p2p_register_settings() {
    register_setting('p2p_settings_group', 'p2p_preserve_elementor');
}
add_action('admin_init', 'p2p_register_settings');

// Conversion logic
function p2p_convert_page_to_post($page_id) {
    if (get_post_type($page_id) !== 'page') return false;

    $page = get_post($page_id, ARRAY_A);
    $page['post_type'] = 'post';
    $new_post_id = wp_insert_post($page);

    if (is_wp_error($new_post_id)) return false;

    if (get_option('p2p_preserve_elementor')) {
        $meta_keys = [
            '_elementor_data',
            '_elementor_edit_mode',
            '_elementor_template_type',
            '_elementor_page_settings'
        ];
        foreach ($meta_keys as $key) {
            $value = get_post_meta($page_id, $key, true);
            if (!empty($value)) {
                update_post_meta($new_post_id, $key, $value);
            }
        }
    }

    // Copy featured image
    $thumbnail_id = get_post_thumbnail_id($page_id);
    if ($thumbnail_id) {
        set_post_thumbnail($new_post_id, $thumbnail_id);
    }

    return $new_post_id;
}
