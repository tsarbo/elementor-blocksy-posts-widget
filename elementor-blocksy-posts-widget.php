<?php
/**
 * Plugin Name: Elementor Blocksy Posts Widget
 * Description: Adds a custom posts widget to Elementor.
 * Version: 1.0.0
 * Author: Giorgos Tsarmpopoulos
 * Author URI: https://beyondweb.gr
 * Text Domain: elementor-blocksy-posts-widget
 */

use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Check if Elementor is installed and active
function ecw_elementor_active_check()
{
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', 'ecw_elementor_inactive_notice');
        return false;
    }
    return true;
}

function ecw_elementor_inactive_notice()
{
    echo '<div class="notice notice-warning is-dismissible"><p>' . __('Elementor must be installed and active to use the Elementor Custom Widget plugin.', 'elementor-custom-widget') . '</p></div>';
}

// Initialize the custom widget
function ecw_register_custom_widget()
{
    // Load custom widget file
    require_once(__DIR__ . '/includes/blocksy-posts-widget.php');

    // Register the widget with Elementor
    Plugin::instance()->widgets_manager->register_widget_type(new Elementor_Blocksy_Posts_Widget());
}

// Action hooks
add_action('plugins_loaded', 'ecw_elementor_active_check');
add_action('elementor/widgets/widgets_registered', 'ecw_register_custom_widget');


//function ecw_enqueue_elementor_widget_scripts()
//{
//    wp_enqueue_script(
//        'elementor-widget-controls',
//        plugins_url('assets/js/blocksy_posts.js', __FILE__),
//        ['jquery'],
//        '1.0.0',
//        true
//    );
//}
//
//add_action('elementor/frontend/after_enqueue_scripts', 'ecw_enqueue_elementor_widget_scripts');

function ecw_enqueue_elementor_widget_scripts()
{
    wp_enqueue_script('elementor-widget-controls', plugins_url('assets/js/blocksy_posts.js', __FILE__), ['jquery'], '1.0.0', true);
    wp_localize_script('elementor-widget-controls', 'elementor_widget_controls', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('elementor_widget_controls_nonce'),
    ]);
}

//add_action('elementor/frontend/after_enqueue_scripts', 'ecw_enqueue_elementor_widget_scripts');


// Enqueue your script
//add_action('elementor/frontend/after_enqueue_scripts', function () {
//    wp_enqueue_script(
//        'elementor-dynamic-taxonomy-terms',
//        plugin_dir_url(__FILE__) . 'assets/js/dynamic-taxonomy.js', // Path to your JS file
//        ['jquery'],
//        false,
//        true
//    );
//
//    // Localize script for AJAX URL
//    wp_localize_script('elementor-dynamic-taxonomy-terms', 'ajax_object', [
//        'ajax_url' => admin_url('admin-ajax.php'),
//    ]);
//});