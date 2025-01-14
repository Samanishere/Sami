<?php
/*
Plugin Name: Image Watermark
Description: Automatically adds a watermark to all uploaded images with customizable opacity, scaling modes, and position. Supports JPEG, PNG, GIF, WEBP, and AVIF formats.
Version: 1.8
Author: Saman Hesaraki (Revised by Gemini)
*/

// Add submenu under Tools
function image_watermark_add_menu() {
    add_submenu_page(
        'tools.php',
        'Image Watermark',
        'Image Watermark',
        'manage_options',
        'image-watermark',
        'image_watermark_settings_page'
    );
}
add_action('admin_menu', 'image_watermark_add_menu');

// Register settings
function image_watermark_register_settings() {
    register_setting('image_watermark_settings', 'watermark_opacity', ['default' => 100]);
    register_setting('image_watermark_settings', 'watermark_scale', ['default' => 'none']);
    register_setting('image_watermark_settings', 'watermark_rotation', ['default' => 0]);
    register_setting('image_watermark_settings', 'watermark_position', ['default' => 'center']);
}
add_action('admin_init', 'image_watermark_register_settings');

// Load the settings page template
function image_watermark_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    include plugin_dir_path(__FILE__) . 'templates/settings-page.php';
}

// Include the main plugin class
require_once plugin_dir_path(__FILE__) . 'includes/class-image-watermark.php';

// Add watermark with scaling, opacity, and position
add_filter('wp_handle_upload', ['Image_Watermark', 'add_custom_watermark']);
?>