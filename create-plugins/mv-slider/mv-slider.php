<?php

/**
 * Plugin Name: MV Slider
 * Plugin URI: https://wordpress.org/mv-slider
 * Description: My plugin's description
 * Version: 1.0.0
 * Requires at least: 5.6
 * Author: Hieu Nguyen Trong
 * Author URI: https://dalatcoder.github.io
 * Text Domain: mv-slider
 * Domain Path: /languages
 */

 if (!defined('ABSPATH')) {
    die('Im just a plugin');
 }

 if (!class_exists('MV_Slider')) {
    class MV_Slider {
        function __construct() {
            $this->define_constants();
            $this->load_text_domain();

            add_action('admin_menu', [$this, 'add_menu']);

            require_once(MV_SLIDER_PATH . 'post-types/mv-slider-cpt.php');
            new MV_Slider_Post_Type();

            require_once(MV_SLIDER_PATH . 'mv-slider-settings.php');
            new MV_Slider_Settings();

            require_once(MV_SLIDER_PATH . 'shortcodes/mv-slider-shortcode.php');
            new MV_Slider_Shortcode();

            add_action('wp_enqueue_scripts', [$this, 'register_scripts'], 999);
            add_action('admin_enqueue_scripts', [$this, 'register_admin_scripts'], 999);
        }

        public function define_constants() {
            define('MV_SLIDER_PATH', plugin_dir_path(__FILE__));
            define('MV_SLIDER_URL', plugin_dir_url(__FILE__));
            define('MV_SLIDER_VERSION', '1.0.0');
        }

        public static function activate() {
            update_option('rewrite_rules', '');
        }

        public static function deactivate() {
            flush_rewrite_rules();
            unregister_post_type('mv-slider');
        }

        public static function uninstall() {
            delete_option('mv_slider_options');

            $posts = get_posts(
                [
                    'post_type' => 'mv_slider',
                    'number_posts' => -1,
                    'post_status' => 'any'
                ]
            );

            foreach ($posts as $post) {
                wp_delete_post($post->ID, true);
            }
        }

        public function load_text_domain() {
            load_plugin_textdomain(
                'mv-slider',
                false, 
                dirname(plugin_basename(__FILE__)) . '/languages/'
            );
        }

        public function add_menu() {
            add_menu_page(
                esc_html__('MV Slider Options', 'mv-slider'),
                esc_html__('MV Slider', 'mv-slider'),
                'manage_options',
                'mv_slider_admin',
                [$this, 'mv_slider_settings_page'],
                'dashicons-images-alt2'
            );

            add_submenu_page(
                'mv_slider_admin',
                esc_html__('Manage Slides', 'mv-slider'),
                esc_html__('Manage Slides', 'mv-slider'),
                'manage_options',
                'edit.php?post_type=mv_slider'
            );

            add_submenu_page(
                'mv_slider_admin',
                esc_html__('Add New Slide', 'mv-slider'),
                esc_html__('Add New Slide', 'mv-slider'),
                'manage_options',
                'post-new.php?post_type=mv_slider'
            );
        }

        public function mv_slider_settings_page() {
            if (!current_user_can('manage_options')) {
                return;
            }

            if (isset($_GET['settings-updated'])) {
                add_settings_error('mv_slider_options', 'mv_slider_message', esc_html__('Settings Saved', 'mv-slider'), 'success');
            }
            settings_errors('mv_slider_options');

            require_once(MV_SLIDER_PATH . 'views/setting-page.php');
        }

        public function register_scripts() {
            wp_register_script(
                'mv-slider-main-jq', 
                MV_SLIDER_URL . 'vendor/flexslider/jquery.flexslider-min.js',
                ['jquery'],
                MV_SLIDER_VERSION,
                true
            );

            wp_register_script(
                'mv-slider-options-js', 
                MV_SLIDER_URL . 'vendor/flexslider/flexslider.js',
                ['jquery'],
                MV_SLIDER_VERSION,
                true
            );

            wp_register_style(
                'mv-slider-main-css',
                MV_SLIDER_URL . 'vendor/flexslider/flexslider.css',
                [],
                MV_SLIDER_VERSION,
                'all'
            );

            wp_register_style(
                'mv-slider-style-css',
                MV_SLIDER_URL . 'assets/css/frontend.css',
                [],
                MV_SLIDER_VERSION,
                'all'
            );
        }

        public function register_admin_scripts() {
            global $typenow;
            if ($typenow == 'mv_slider') {
                wp_enqueue_style(
                    'mv-slider-admin',
                    MV_SLIDER_URL . 'assets/css/admin.css'
                );
            }
        }
    }
 }

 if (class_exists('MV_Slider')) {
    register_activation_hook(__FILE__, ['MV_Slider', 'activate']);
    register_deactivation_hook(__FILE__, ['MV_Slider', 'deactivate']);
    register_uninstall_hook(__FILE__, ['MV_Slider', 'uninstall']);

    $mv_slider = new MV_Slider();
 }
