<?php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
/**
 * Plugin Name: Page to Post Converter
 * Description: Convert Pages to Posts with Elementor compatibility, bulk and single conversion options.
 * Version: 1.3
 * Author: Steve Kinzey
 * License: GPL2
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Admin Settings Menu
add_action('admin_menu', function() {
    add_options_page('Page to Post Converter Settings', 'Page to Post Converter', 'manage_options', 'p2p-converter-settings', 'p2p_settings_page');
    add_management_page('Convert Pages to Posts', 'Page to Post Converter', 'manage_options', 'p2p-converter-tool', 'p2p_converter_tool');
});

// Register Setting
add_action('admin_init', function() {
    if (is_plugin_active("elementor/elementor.php")) { register_setting('p2p_settings_group', 'p2p_preserve_elementor'); }
});

// Settings Page
function p2p_settings_page() {
    ?>
    <div class="wrap"><h1>Page to Post Converter Settings</h1>
    <form method="post" action="options.php">
        <?php settings_fields('p2p_settings_group'); do_settings_sections('p2p_settings_group'); ?>
        <table class="form-table">
            <tr valign="top">
                <?php if (is_plugin_active("elementor/elementor.php")): ?><th scope="row">Preserve Elementor Data?</th>
                <td><input type="checkbox" name="p2p_preserve_elementor" value="1" <?php checked(1, get_option('p2p_preserve_elementor'), true); ?> /></td>
            <?php endif; ?></tr>
        </table>
        <?php submit_button(); ?>
    </form></div>
    <?php
}

// Converter UI Page
function p2p_converter_tool() {
    $status = $_GET['post_status'] ?? '';
    $author = $_GET['author'] ?? '';
    $search = $_GET['s'] ?? '';
    $args = ['post_type' => 'page', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => sanitize_text_field($_GET['sort_order'] ?? 'desc')];

    if ($status) $args['post_status'] = $status;
    if ($author) $args['author'] = $author;
    if ($search) $args['s'] = $search;

    $pages = get_posts($args);
    $authors = get_users(['who' => 'authors']);
    ?>
    <div class="wrap"><h1>Convert Pages to Posts</h1>
    <form method="get">
        <input type="hidden" name="page" value="p2p-converter-tool" />
        <select name="post_status">
            <option value="">All Statuses</option>
            <option value="publish" <?php selected($status, 'publish'); ?>>Published</option>
            <option value="draft" <?php selected($status, 'draft'); ?>>Draft</option>
        </select>
        <select name="author">
            <option value="">All Authors</option>
            <?php foreach ($authors as $a): ?>
            <option value="<?php echo esc_attr($a->ID); ?>" <?php selected($author, $a->ID); ?>><?php echo esc_html($a->display_name); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search title..." />     <select name="sort_order">         <option value="desc" <?php selected($_GET["sort_order"] ?? "", "desc"); ?>>Newest First</option>         <option value="asc" <?php selected($_GET["sort_order"] ?? "", "asc"); ?>>Oldest First</option>     </select>
        <button class="button">Filter</button>
    </form>
    <form method="post">
        <?php wp_nonce_field('p2p_bulk_convert'); ?>
        <table class="wp-list-table widefat fixed striped">
            <thead><tr><th><input type="checkbox" id="select_all"></th><th>Title</th><th>Author</th><th>Status</th><th>Action</th><?php endif; ?></tr></thead>
            <tbody>
            <?php foreach ($pages as $page): ?>
                <tr>
                    <td><input type="checkbox" name="p2p_convert_ids[]" value="<?php echo $page->ID; ?>"></td>
                    <td><?php echo esc_html($page->post_title); ?></td>
                    <td><?php echo esc_html(get_the_author_meta('display_name', $page->post_author)); ?></td>
                    <td><?php echo esc_html($page->post_status); ?></td>
                    <td><a href="<?php echo wp_nonce_url(admin_url('tools.php?page=p2p-converter-tool&convert_one=' . $page->ID), 'p2p_convert_one'); ?>" class="button">Convert Now</a></td>
                <?php endif; ?></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button class="button-primary" type="submit" name="p2p_bulk_convert">Convert Selected</button>
    </form></div>
    <script>
    document.getElementById("select_all").onclick = function() {
        const boxes = document.querySelectorAll('input[name="p2p_convert_ids[]"]');
        boxes.forEach(cb => cb.checked = this.checked);
    };
    </script>
    <?php
}

// Hook conversion handling
add_action('admin_init', function() {
    if (isset($_GET['convert_one']) && check_admin_referer('p2p_convert_one')) {
        p2p_convert_page_to_post((int) $_GET['convert_one']);
        wp_redirect(remove_query_arg('convert_one'));
        exit;
    }

    if (isset($_POST['p2p_bulk_convert']) && check_admin_referer('p2p_bulk_convert')) {
        foreach ($_POST['p2p_convert_ids'] as $id) {
            p2p_convert_page_to_post((int) $id);
        }
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>Selected pages converted successfully.</p></div>';
        });
    }
});

// Convert logic
function p2p_convert_page_to_post($page_id) {
    if (get_post_type($page_id) !== 'page') return false;

    $page = get_post($page_id, ARRAY_A);
    $page['post_type'] = 'post';
    $new_id = wp_insert_post($page);
    if (is_wp_error($new_id)) return false;

    if (is_plugin_active("elementor/elementor.php") && get_option('p2p_preserve_elementor')) {
        $keys = ['_elementor_data','_elementor_edit_mode','_elementor_template_type','_elementor_page_settings'];
        foreach ($keys as $k) {
            $v = get_post_meta($page_id, $k, true);
            if (!empty($v)) update_post_meta($new_id, $k, $v);
        }
    }

    $thumb_id = get_post_thumbnail_id($page_id);
    if ($thumb_id) set_post_thumbnail($new_id, $thumb_id);

    return $new_id;
}
