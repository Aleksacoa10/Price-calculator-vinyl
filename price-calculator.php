<?php
/*
Plugin Name: Price Calculator
Description: A calculator for determining the number of vinyl packages based on square meters.
Version: 2.0
Author: Aleksandar Ninić
Text Domain: price-calculator
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load text domain for translation
function price_calculator_load_textdomain() {
    load_plugin_textdomain('price-calculator', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'price_calculator_load_textdomain');

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add admin menu
function price_calculator_add_admin_menu() {
    add_menu_page(
        __('Price Calculator', 'price-calculator'),
        __('Price Calculator', 'price-calculator'),
        'manage_options',
        'price-calculator',
        'price_calculator_admin_page',
        'dashicons-calculator',
        6
    );
}
add_action('admin_menu', 'price_calculator_add_admin_menu');

// Admin page content
function price_calculator_admin_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Price Calculator', 'price-calculator'); ?></h1>
        <p><?php _e('Use the following shortcode to display the calculator on your page or post:', 'price-calculator'); ?></p>
        <code id="shortcode-text">[price_calculator]</code>
        <p><button id="copy-shortcode" class="button button-primary"><?php _e('Copy Shortcode', 'price-calculator'); ?></button></p>
    </div>
    <script>
        document.getElementById('copy-shortcode').addEventListener('click', function() {
            var shortcode = document.getElementById('shortcode-text').innerText;
            var tempInput = document.createElement('input');
            tempInput.style.position = 'absolute';
            tempInput.style.left = '-1000px';
            tempInput.style.top = '-1000px';
            tempInput.value = shortcode;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            alert('<?php _e('Shortcode copied!', 'price-calculator'); ?>');
        });
    </script>
    <?php
}

// Enqueue CSS and JS files
function price_calculator_enqueue_assets() {
    wp_enqueue_style('price-calculator-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('price-calculator-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'price_calculator_enqueue_assets');

// Enqueue CSS i JS za admin panel
function price_calculator_enqueue_admin_assets() {
    wp_enqueue_style('price-calculator-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css');
    wp_enqueue_script('price-calculator-admin-script', plugin_dir_url(__FILE__) . 'assets/js/admin-script.js', array(), null, true);
}
add_action('admin_enqueue_scripts', 'price_calculator_enqueue_admin_assets');

// Remove standard "View details" link
function price_calculator_remove_view_details($plugin_meta, $plugin_file, $plugin_data, $status) {
    if ($plugin_file == plugin_basename(__FILE__)) {
        foreach ($plugin_meta as $key => $link) {
            if (strpos($link, 'plugin-install.php?tab=plugin-information') !== false) {
                unset($plugin_meta[$key]);
            }
        }
    }
    return $plugin_meta;
}
add_filter('plugin_row_meta', 'price_calculator_remove_view_details', 10, 4);

// Add custom "View details" link
function price_calculator_custom_plugin_links($links, $file) {
    if ($file == plugin_basename(__FILE__)) {
        $custom_link = '<a href="#" id="price-calculator-view-details">' . __('View details', 'price-calculator') . '</a>';
        array_unshift($links, $custom_link);
    }
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'price_calculator_custom_plugin_links', 10, 2);

// Add modal HTML
function price_calculator_add_admin_modal() {
    ?>
    <div id="price-calculator-modal" class="price-calculator-modal">
        <div class="price-calculator-modal-content">
            <span class="price-calculator-modal-close">&times;</span>
            <h2><?php _e('Price Calculator Details', 'price-calculator'); ?></h2>
            <p><?php _e('This plugin helps you calculate the number of vinyl packages needed based on the square meters of your home or office. Additionally, it offers an option to add 10% more square meters for cutting.', 'price-calculator'); ?></p>
        </div>
    </div>
    <?php
}
add_action('admin_footer', 'price_calculator_add_admin_modal');

// Shortcode function
function display_price_calculator_form() {
    ?>
    <form id="price-calculator-form">
        <label for="square-meters"><?php _e('Enter square meters:', 'price-calculator'); ?></label>
        <input type="number" id="square-meters" name="square-meters" required>
        <br>
        <label for="add-10-percent"><?php _e('Add 10% for cutting', 'price-calculator'); ?></label>
        <input type="checkbox" id="add-10-percent" name="add-10-percent">
        <br>
        <button type="submit"><?php _e('Calculate', 'price-calculator'); ?></button>
        <div id="price-calculator-result"></div>
    </form>
    <?php
}
add_shortcode('price_calculator', 'display_price_calculator_form');
?>
