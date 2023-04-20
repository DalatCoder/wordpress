<?php 

if (!class_exists('MV_Slider_Post_Type')) {
    class MV_Slider_Post_Type {
        function __construct() {
            add_action('init', [$this, 'create_post_type']);
            add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
            add_action('save_post', [$this, 'save_post'], 10, 2);

            add_filter('manage_mv_slider_posts_columns', [$this, 'add_custom_columns']);
            add_action('manage_mv_slider_posts_custom_column', [$this, 'add_custom_columns_content'], 10, 2);
            add_filter('manage_edit-mv_slider_sortable_columns', [$this, 'add_sortable_columns']);
        }

        public function create_post_type() {
            register_post_type(
                'mv_slider', 
                [
                    'label' => esc_html__('Slider', 'mv-slider'),
                    'description' => esc_html__('Sliders', 'mv-slider'),
                    'labels' => [
                        'name' => esc_html__('Sliders', 'mv-slider'),
                        'singular_name' => esc_html__('Slider', 'mv-slider')
                    ],
                    'public' => true,
                    'supports' => [
                        'title',
                        'editor',
                        'thumbnail'
                    ],
                    'hierarchical' => false,
                    'show_ui' => true,
                    'show_in_menu' => false,
                    'menu_position' => 5,
                    'show_in_admin_bar' => true,
                    'show_in_nav_menus' => true,
                    'can_export' => true,
                    'has_archive' => false,
                    'exclude_from_search' => false,
                    'publicly_queryable' => true,
                    'show_in_rest' => true,
                    'menu_icon' => 'dashicons-images-alt2'
                ]
            );
        }

        public function add_meta_boxes() {
            add_meta_box(
                'mv_slider_meta_box',
                esc_html__('Link Options', 'mv-slider'),
                [$this, 'add_inner_meta_boxes'],
                'mv_slider',
                'normal',
                'high',
            );
        }

        public function add_inner_meta_boxes($post) {
            require_once(MV_SLIDER_PATH . 'views/mv-slider_metabox.php');
        }

        public function save_post($post_id) {
            // validation
            $nonce = '';
            if (isset($_POST['mv_slider_nonce'])) {
                $nonce = $_POST['mv_slider_nonce'];
            }
            if (!wp_verify_nonce($nonce, 'mv_slider_nonce')) {
                return;
            }

            // prevent auto save feature
            // only save when user submits the form
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            // check permission
            if (isset($_POST['post_type']) && $_POST['post_type'] != 'mv_slider') {
                return;
            }
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }

            if (isset($_POST['action']) && $_POST['action'] == 'editpost') {
                $old_text = get_post_meta($post_id, 'mv_slider_link_text', true);
                $old_url = get_post_meta($post_id, 'mv_slider_link_url', true);

                $new_text = $_POST['mv_slider_link_text'];
                $new_url = $_POST['mv_slider_link_url'];

                // sanitization
                $new_text = sanitize_text_field($new_text);
                $new_url = sanitize_text_field($new_url);

                // validation
                if (empty($new_text)) {
                    $new_text = esc_html__('Add some text', 'mv-slider');
                }
                if (empty($new_url)) {
                    $new_url = esc_html__('Add some URL', 'mv-slider');
                }

                update_post_meta($post_id, 'mv_slider_link_text', $new_text, $old_text);
                update_post_meta($post_id, 'mv_slider_link_url', $new_url, $old_url);
            }
        }

        public function add_custom_columns($columns) {
            $columns['mv_slider_link_text'] = esc_html__('Link text', 'mv-slider');
            $columns['mv_slider_link_url'] = esc_html__('Link url', 'mv-slider');

            return $columns;
        }

        public function add_custom_columns_content($column, $post_id) {
            switch($column) {
                case 'mv_slider_link_text':  
                    $text = get_post_meta($post_id, 'mv_slider_link_text', true);
                    echo esc_html($text);
                    break;

                case 'mv_slider_link_url':  
                    $url = get_post_meta($post_id, 'mv_slider_link_url', true);
                    echo esc_url($url);
                    break;
            }
        }

        public function add_sortable_columns($columns) {
            $columns['mv_slider_link_text'] = 'mv_slider_link_text';
            return $columns;
        }
    }
}
