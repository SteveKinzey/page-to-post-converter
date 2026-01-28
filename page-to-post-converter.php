<?php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
/**
 * Plugin Name: SK America Page to Post Converter
 * Description: Convert Pages to Posts with full compatibility for Classic Editor, FSE, Elementor, Bricks, Breakdance, Divi, Beaver Builder, and WPBakery.
 * Version: 2.1.1
 * Author: Steve Kinzey
 * Organization: SK America LLC
 * Author URI: https://sk-america.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Admin Settings Menu
add_action('admin_menu', function() {
    add_options_page('Page to Post Converter Settings', 'Page to Post Converter', 'manage_options', 'p2p-converter-settings', 'p2p_settings_page');
    add_management_page('Convert Pages to Posts', 'Page to Post Converter', 'manage_options', 'p2p-converter-tool', 'p2p_converter_tool');
});

// Register Settings
add_action('admin_init', function() {
    register_setting('p2p_settings_group', 'p2p_preserve_builder_data');
    register_setting('p2p_settings_group', 'p2p_convert_categories_to_tags');
    register_setting('p2p_settings_group', 'p2p_convert_tags_to_categories');
    register_setting('p2p_settings_group', 'p2p_delete_original_page');
});

// Detect active page builders
function p2p_detect_page_builders() {
    $builders = [];
    if (is_plugin_active('elementor/elementor.php')) $builders['elementor'] = 'Elementor';
    if (is_plugin_active('bricks/bricks.php')) $builders['bricks'] = 'Bricks';
    if (is_plugin_active('breakdance/plugin.php')) $builders['breakdance'] = 'Breakdance';
    if (is_plugin_active('divi-builder/divi-builder.php') || is_plugin_active('Divi/divi.php')) $builders['divi'] = 'Divi';
    if (is_plugin_active('beaver-builder-lite-version/fl-builder.php') || is_plugin_active('bb-plugin/fl-builder.php')) $builders['beaver'] = 'Beaver Builder';
    if (is_plugin_active('js_composer/js_composer.php')) $builders['wpbakery'] = 'WPBakery';
    return $builders;
}

// Settings Page
function p2p_settings_page() {
    $builders = p2p_detect_page_builders();
    ?>
    <div class="wrap">
        <h1>Page to Post Converter Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('p2p_settings_group'); do_settings_sections('p2p_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Preserve Page Builder Data</th>
                    <td>
                        <input type="checkbox" name="p2p_preserve_builder_data" value="1" <?php checked(1, get_option('p2p_preserve_builder_data'), true); ?> />
                        <p class="description">
                            Detected builders: <?php echo empty($builders) ? 'None' : implode(', ', $builders); ?><br>
                            This will preserve data for Classic Editor, Full Site Editor, and all detected page builders.
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Delete Original Page</th>
                    <td>
                        <input type="checkbox" name="p2p_delete_original_page" value="1" <?php checked(1, get_option('p2p_delete_original_page'), true); ?> />
                        <p class="description">
                            Delete the original page after converting to post. If unchecked, both the page and post will exist.
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Category/Tag Conversion</th>
                    <td>
                        <label>
                            <input type="checkbox" name="p2p_convert_categories_to_tags" value="1" <?php checked(1, get_option('p2p_convert_categories_to_tags'), true); ?> />
                            Convert categories to tags during page to post conversion
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="p2p_convert_tags_to_categories" value="1" <?php checked(1, get_option('p2p_convert_tags_to_categories'), true); ?> />
                            Convert tags to categories during page to post conversion
                        </label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Converter UI Page
function p2p_converter_tool() {
    $status = isset($_GET['post_status']) ? sanitize_text_field($_GET['post_status']) : '';
    $author = isset($_GET['author']) ? absint($_GET['author']) : '';
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $sort_order = isset($_GET['sort_order']) ? sanitize_text_field($_GET['sort_order']) : 'desc';
    
    $args = [
        'post_type' => 'page',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => $sort_order
    ];

    if ($status) $args['post_status'] = $status;
    if ($author) $args['author'] = $author;
    if ($search) $args['s'] = $search;

    $pages = get_posts($args);
    $authors = get_users(['who' => 'authors']);
    ?>
    <div class="wrap">
        <h1>Convert Pages to Posts</h1>
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
            <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search title..." />
            <select name="sort_order">
                <option value="desc" <?php selected($sort_order, 'desc'); ?>>Newest First</option>
                <option value="asc" <?php selected($sort_order, 'asc'); ?>>Oldest First</option>
            </select>
            <button class="button">Filter</button>
        </form>
        <form method="post">
            <?php wp_nonce_field('p2p_bulk_convert'); ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all"></th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><input type="checkbox" name="p2p_convert_ids[]" value="<?php echo esc_attr($page->ID); ?>"></td>
                        <td><?php echo esc_html($page->post_title); ?></td>
                        <td><?php echo esc_html(get_the_author_meta('display_name', $page->post_author)); ?></td>
                        <td><?php echo esc_html($page->post_status); ?></td>
                        <td><a href="<?php echo esc_url(wp_nonce_url(admin_url('tools.php?page=p2p-converter-tool&convert_one=' . $page->ID), 'p2p_convert_one')); ?>" class="button">Convert Now</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p><button class="button-primary" type="submit" name="p2p_bulk_convert">Convert Selected</button></p>
        </form>
    </div>
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
        $result = p2p_convert_page_to_post((int) $_GET['convert_one']);
        if ($result) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>Page converted successfully.</p></div>';
            });
        }
        wp_redirect(admin_url('tools.php?page=p2p-converter-tool&converted=1'));
        exit;
    }

    if (isset($_POST['p2p_bulk_convert']) && check_admin_referer('p2p_bulk_convert')) {
        if (!empty($_POST['p2p_convert_ids']) && is_array($_POST['p2p_convert_ids'])) {
            $count = 0;
            foreach ($_POST['p2p_convert_ids'] as $id) {
                if (p2p_convert_page_to_post((int) $id)) {
                    $count++;
                }
            }
            add_action('admin_notices', function() use ($count) {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($count) . ' page(s) converted successfully.</p></div>';
            });
        }
    }
});

// Convert logic
function p2p_convert_page_to_post($page_id) {
    if (get_post_type($page_id) !== 'page') return false;

    $page = get_post($page_id);
    if (!$page) return false;

    // Get all post meta before conversion
    $all_meta = get_post_meta($page_id);

    // Update the post type directly (true conversion, not duplication)
    $result = wp_update_post([
        'ID' => $page_id,
        'post_type' => 'post'
    ]);

    if (is_wp_error($result)) return false;

    $new_id = $page_id; // Same ID since we're updating, not creating

    // Preserve page builder data if enabled
    if (get_option('p2p_preserve_builder_data')) {
        // All meta is already preserved since we're updating the same post
        // But we need to ensure builder-specific data is intact

        // Elementor - re-save to ensure compatibility
        if (is_plugin_active('elementor/elementor.php')) {
            $elementor_data = get_post_meta($new_id, '_elementor_data', true);
            if (!empty($elementor_data)) {
                // Trigger Elementor to recognize this as an Elementor post
                update_post_meta($new_id, '_elementor_edit_mode', 'builder');
            }
        }

        // Bricks
        if (is_plugin_active('bricks/bricks.php')) {
            $bricks_data = get_post_meta($new_id, '_bricks_data', true);
            if (!empty($bricks_data)) {
                update_post_meta($new_id, '_bricks_editor_mode', 'bricks');
            }
        }
    }

    // Handle taxonomy conversion
    $categories = wp_get_post_categories($new_id);
    $tags = wp_get_post_tags($new_id);

    if (get_option('p2p_convert_categories_to_tags') && !empty($categories)) {
        $tag_names = [];
        foreach ($categories as $cat_id) {
            $category = get_category($cat_id);
            if ($category) {
                $tag_names[] = $category->name;
            }
        }
        wp_set_post_tags($new_id, $tag_names, true);
    }

    if (get_option('p2p_convert_tags_to_categories') && !empty($tags)) {
        foreach ($tags as $tag) {
            $cat = get_category_by_slug($tag->slug);
            if (!$cat) {
                $cat_id = wp_create_category($tag->name);
                wp_set_post_categories($new_id, [$cat_id], true);
            } else {
                wp_set_post_categories($new_id, [$cat->term_id], true);
            }
        }
    }

    return $new_id;
}
