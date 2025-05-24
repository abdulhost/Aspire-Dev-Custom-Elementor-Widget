<?php
/*
Plugin Name: AspireDev Custom Widgets Elementor
Plugin URI: #
Description: A WordPress plugin to add custom Elementor widgets, including a reusable video box widget. Developed by AspireDev.
Version: 2.1.8
Author: AspireDev
Author URI: #
License: GPL2
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check if Elementor is active
if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

if (!is_plugin_active('elementor/elementor.php')) {
    add_action('admin_notices', function() {
        ?>
        <div class="notice notice-error">
            <p><?php _e('AspireDev Custom Widgets Elementor requires Elementor to be installed and activated.', 'text-domain'); ?></p>
        </div>
        <?php
    });
    return;
}

// Register widget
function register_video_box_widget($widgets_manager) {
    require_once(__DIR__ . '/widgets/video-box-widget.php');
    $widgets_manager->register(new \Elementor_Video_Box_Widget());
}
add_action('elementor/widgets/register', 'register_video_box_widget');

// Register custom category
function add_elementor_widget_categories($elements_manager) {
    $elements_manager->add_category(
        'aspire-custom-category',
        [
            'title' => __('Aspire Custom Widgets', 'text-domain'),
            'icon' => 'fa fa-plug',
        ]
    );
}
add_action('elementor/elements/categories_registered', 'add_elementor_widget_categories');