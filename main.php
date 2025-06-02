<?php
/**
 * Plugin Name: AspireDev Custom Widgets Elementor
 * Description: Custom Elementor widgets for Duty Academy, including a video box and enhanced courses list.
 * Version: 2.1.9
 * Author: AspireDev
 * Text Domain: aspiredev-elementor
 * Requires Plugins: elementor, sfwd-lms
 * Elementor tested up to: 3.25.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load translations on init
add_action('init', function() {
    load_plugin_textdomain('aspiredev-elementor', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

// Check if Elementor and LearnDash are active
function aspiredev_check_requirements() {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (!is_plugin_active('elementor/elementor.php') || !is_plugin_active('sfwd-lms/sfwd_lms.php')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>' . esc_html__('AspireDev Custom Widgets Elementor requires Elementor and LearnDash to be active.', 'aspiredev-elementor') . '</p></div>';
        });
        return false;
    }
    return true;
}

// Register widgets
function aspiredev_register_widgets($widgets_manager) {
    require_once(__DIR__ . '/widgets/video-box-widget.php');
    $widgets_manager->register(new \Elementor_Video_Box_Widget());
    require_once(__DIR__ . '/widgets/courses-list-widget.php');
    $widgets_manager->register(new \AspireDev_Courses_List_Widget());
}

// Register custom category
function aspiredev_add_widget_categories($elements_manager) {
    $elements_manager->add_category(
        'aspire-custom-category',
        [
            'title' => esc_html__('Aspire Custom Widgets', 'aspiredev-elementor'),
            'icon' => 'fa fa-plug',
        ]
    );
}

// Initialize plugin
function aspiredev_init() {
    if (aspiredev_check_requirements()) {
        add_action('elementor/widgets/register', 'aspiredev_register_widgets');
        add_action('elementor/elements/categories_registered', 'aspiredev_add_widget_categories');
    }
}
add_action('plugins_loaded', 'aspiredev_init');
?>