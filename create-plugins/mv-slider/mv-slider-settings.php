<?php 

if (!class_exists('MV_Slider_Settings')) {
    class MV_Slider_Settings {
        public static $options;

        public function __construct() {
            self::$options = get_option('mv_slider_options');

            add_action('admin_init', [$this, 'admin_init']);
        }

        public function admin_init() {
            register_setting('mv_slider_group', 'mv_slider_options', [$this, 'mv_slider_validations']);

            // first setting section
            add_settings_section(
                'mv_slider_main_section',
                esc_html__('How does it works?', 'mv-slider'),
                null,
                'mv_slider_settings_page1'
            );

            add_settings_field(
                'mv_slider_shortcode',
                esc_html__('Shortcode', 'mv-slider'),
                [$this, 'mv_slider_shortcode'], 
                'mv_slider_settings_page1',
                'mv_slider_main_section'
            );

            // second setting section
            add_settings_section(
                'mv_slider_second_section',
                esc_html__('Other Plugin Options', 'mv-slider'),
                null,
                'mv_slider_settings_page2'
            );

            add_settings_field(
                'mv_slider_title',
                esc_html__('Slider Title', 'mv-slider'),
                [$this, 'mv_slider_title'], 
                'mv_slider_settings_page2',
                'mv_slider_second_section'
            );

            add_settings_field(
                'mv_slider_bullets',
                esc_html__('Display Bullets?', 'mv-slider'),
                [$this, 'mv_slider_bullets'], 
                'mv_slider_settings_page2',
                'mv_slider_second_section'
            );

            add_settings_field(
                'mv_slider_style',
                esc_html__('Slider Style', 'mv-slider'),
                [$this, 'mv_slider_style'], 
                'mv_slider_settings_page2',
                'mv_slider_second_section'
            );
        }

        public function mv_slider_shortcode() {
            ?>
            <span><?= esc_html_e('Use the shortcode [mv_slider] to display the slider on any page/post/widget', 'mv-slider') ?></span>
            <?php
        }

        public function mv_slider_title() {
            $title = '';
            if (isset(self::$options['mv_slider_title'])) {
                $title = esc_attr(self::$options['mv_slider_title']);
            }

            ?>
            <input 
                type="text"
                name="mv_slider_options[mv_slider_title]"
                id="mv_slider_title"
                value="<?= $title ?>"
            >
            <?php
        }

        public function mv_slider_bullets() {
            $bullets = "0";
            if (isset(self::$options['mv_slider_bullets'])) {
                $bullets = esc_attr(self::$options['mv_slider_bullets']);
            }

            ?>
            <input 
                type="checkbox"
                name="mv_slider_options[mv_slider_bullets]"
                id="mv_slider_bullets"
                value="1"
                <?php
                    checked("1", $bullets, true);
                ?>
            >
            <label for="mv_slider_bullets"><?= esc_html_e('Whether to display bullets?', 'mv-slider') ?></label>
            <?php
        }

        public function mv_slider_style() {
            $style = "";
            if (isset(self::$options['mv_slider_style'])) {
                $style = esc_attr(self::$options['mv_slider_style']);
            }

            ?>
            <select
                name="mv_slider_options[mv_slider_style]"
                id="mv_slider_style"
            >
                <option value="style-1" <?php selected("style-1", $style, true); ?>><?= esc_html_e('Style 1', 'mv-slider') ?></option>
                <option value="style-2" <?php selected("style-2", $style, true); ?>><?= esc_html_e('Style 2', 'mv-slider') ?></option>
            </select>
            <?php
        }

        public function mv_slider_validations($input) {
            $new_input = [];

            foreach($input as $key => $value) {
                switch($key) {
                    case 'mv_slider_title':
                        if (empty($value)) {
                            add_settings_error('mv_slider_options', 'mv_slider_message', 'The title cannot be empty', 'error');
                            $value = esc_html__('Please type some text', 'mv-slider');
                        }

                        $new_input[$key] = sanitize_text_field($value);
                        break;
                    case 'mv_slider_url':
                        $new_input[$key] = esc_url_raw($value);
                        break;
                    case 'mv_slider_int':
                        $new_input[$key] = absint($value);
                        break;
                    default:
                        $new_input[$key] = sanitize_text_field($value);
                        break;
                }
            }

            return $new_input;
        }
    }
}