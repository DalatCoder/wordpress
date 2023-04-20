<?php 

if (!class_exists('MV_Slider_Shortcode')) {
    class MV_Slider_Shortcode {
        public function __construct() {
            add_shortcode('mv_slider', [$this, 'add_shortcode']);
        }

        public function add_shortcode($attributes = [], $content = null, $tag = '') {
            $attributes = array_change_key_case((array)$attributes, CASE_LOWER);

            extract(
                shortcode_atts(
                    [
                        'id' => '',
                        'orderby' => 'date'
                    ],
                    $attributes,
                    $tag
                )
            );

            if (!empty($id)) {
                $id = array_map('absint', explode(',', $id));
            }

            $slider_title = MV_Slider_Settings::$options['mv_slider_title'];
            if (!empty($content)) {
                $slider_title = esc_html($content);
            }

            $slider_style = 'style-1';
            if (isset(MV_Slider_Settings::$options['mv_slider_style'])) {
                $slider_style = esc_html(MV_Slider_Settings::$options['mv_slider_style']);
            }

            $control_nav = false;
            if (isset(MV_Slider_Settings::$options['mv_slider_bullets'])) {
                if (MV_Slider_Settings::$options['mv_slider_bullets'] == "1") {
                    $control_nav = true;
                }
            } 

            $default_image_url = MV_SLIDER_URL . 'assets/images/default.jpeg';

            $query_args = [
                'post_type' => 'mv_slider',
                'post_status' => 'publish',
                'orderby' => $orderby
            ];

            if (!empty($id)) {
                $query_args['post__in'] = $id;
            }

            $query = new WP_Query($query_args);

            ob_start();
            require(MV_SLIDER_PATH . 'views/mv-slider_shortcode.php');

            wp_enqueue_script('mv-slider-main-jq');
            wp_enqueue_script('mv-slider-options-js');
            wp_enqueue_style('mv-slider-main-css');
            wp_enqueue_style('mv-slider-style-css');

            // localize data
            wp_enqueue_script(
                'mv-slider-options-js', 
                MV_SLIDER_URL . 'vendor/flexslider/flexslider.js',
                ['jquery'],
                MV_SLIDER_VERSION,
                true
            );
            wp_localize_script('mv-slider-options-js', 'SLIDER_OPTIONS', array(
                'controlNav' => $control_nav
            ));

            return ob_get_clean();
        }
    }
}