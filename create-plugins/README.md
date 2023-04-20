# Creating Wordpress Plugins The Right Way

## Before begins

WordPress workflow: [Link](https://gist.github.com/johnbillion/4fa3c4228a8bb53cc71d)

Setup tools

- XAMPP
- PHP 7.4
- VSCode + Extensions:
  - PHP debug
  - PHP DocBlocker for code comment
  - PHP Exntesion Pack
  - PHP Intellisense
  - PHPfmt

For debugging:

- `define('WP_DEBUG', true)`
- `var_dump()`
- `print_r()`

Some plugins for debug in wordpress:

- Debug bar
- Simply show hooks
- Show current template
- Query monitor

- Get examples of wordpress functions: hotexamples.com
- Download some plugins and use `find in folder`
- Wordpress reference: [Link](https://developer.wordpress.org/reference/)

What is `action hooks`?

Hooks are nothing more than specific
locations within the WordPress code, where
to let you execute an additional piece of
code. A function, let's say or modify existing
content.

They are the basis of plugin creation,
since that's what plugins are made for. They
are designed to modify the way Wordpress
behaves and perform a task it was not initialy
created for.

There are 2 types of hooks:

- `action hooks`
- `filter hooks`

```php
function test() {
    
}

add_action('hook', 'test');
add_filter('filter', 'test');
```

The `test` callback function are triggered
or fired within an action hook or a filter.

What is the difference between an `action` hook and a `filter` hook?

- `action` hook is used to add something
  at a certain point in the WP code execution.
  For example, there is an action hook called
  `wp_enquece_scripts` which is widely used
  both in theme and plugin creation. It is
  used to add both `css` and `js` files to
  the `header` of the web page of a WP site.
  Or `save_post`, which we use a lot in this
  course, this hook is a specific point
  of WP that allows you to write a function
  that you'll then save some information in
  the database.

- `filter` is used to modify some information
  that already exist in WP. They hijacked
  some kind of information from WP and then
  give us back the same formation, but modified.
  In some cases, they can also do this hijacking
  and simply not give us anything back.

The main difference between `action` hooks
and `filter` hooks is that `action hook` only
execute some additional code. They don't
return anything to the function that
called it. You could say that they interrupt the flow of the code at a certain point,
perform some action, then exit, but without
modifying anything without changing any existing
information. `filter hook` on the other hand
is required to return some feedback to the
function that called it, generally we pass
a variable as parameter to a `filter` and
the goal is to return this variable back but
modified in some way. They simply take this
information and apply a filter, they modified
this information and returned it.

```php
function test_filter($param) {
    $param = 'New Content';
    return $param;
}

add_filter('hook', 'test_filter');
```

For better visualization, we use the plugin
called `Simply Show Hooks`

### Action hooks

Action hooks only work because of 2 functions
that complete each other.

- `do_action()`
- `add_action()`

`do_action` sets an action hook anywhere in the
wordpress code. Example in `wp-settings.php`, search for `do_action`.

`add_action` help you hook your callback
functions.

You can say that `do_action` is the point
in the code where the hook is available and
the `add_action` is where you will in fact
hook your callback functions.

Wordpress Load Workflow:

Examples, place this code in `functions.php`
of a theme.

```php
function add_div_tag_before() {
    ?>
        <div>The loop has started
    <?php
}
add_action('loop_start', 'add_div_tag_before', 10);

function add_section_tag_before() {
    ?>
        <div>The section has started
    <?php
}
add_action('loop_start', 'add_section_tag_before', 11);

function add_section_tag_end() {
    ?>
        The section has ended</div>
    <?php
}
add_action('loop_end', 'add_section_tag_end', 9);

function add_div_tag_end() {
    ?>
        The loop has ended</div>
    <?php
}
add_action('loop_end', 'add_div_tag_end', 10);
```

If the callback have the same priority number,
then the order is specified by the order
in code.

### Filter hooks

The `filter` hook received `param` and
return that `param`, but in a modified format.

`filter` works through a pair of functions
that work together

- `apply_filters()`
- `add_filter()`

`apply_filter` is the one that defines
the filter within WordPress code.

Open `post-template.php` file and search for
the `the_content` function.

```php
function the_content() {
    // some works 

    $content = apply_filters('the_content', $content)

    echo $content;
}
```

`add_filter` is for you to modify the value
of the `filter`.

```php
function modify_content($content) {
    return $content . ' Copyright 2021, All Rights reserved';
}
add_filter('the_content', 'modify_content');
```

Or

```php
function modify_body_classes($classes, $class) {
    if (is_single()) {
        $class[] = 'test-single';
        $classes = array_merge($classes, $class);
    }
    return $classes;
}

add_filter('body_class', 'modify_body_classes', 10, 2);
```

WP divides `filter` into 2 groups:

- `filters` that apply to information that is
  read from the database first and then filtered out. (`the_content`)
- `filters` that apply to some information and
  later sent to the database. (`content_save_pre`)

Filter references: [link](https://codex.wordpress.org/Plugin_API/Filter_Reference)

## MV Slider Plugin Project

### Overview

Slideshow plugin, based on FlexSlider2 at [link](http://flexslider.woothemes.com) or OwlCarousel.

Learn:

- Custom Post Type & Custom Fields & Metabox
- Menu & Submenu
- Manage slide
- Settings page & Options API
- Shortcode & How to use the plugin on the Frontend Page
- Add `css` and `js` both on FE and BE
- Translation
- How to uninstall plugin
- Security notions
- Validate fields & sanitization

### Structuring the plugin

- Directory: `wp-content` -> `plugins`
- Simple: one php file: `hello.php`
- Complex: `wp-content` -> `plugins` -> `mv-slider`

`mv-slider`:

- `assets`
  - `css`
  - `images`
  - `js`

- `functions`
- `languages`
- `post-types`
- `shortcodes`
- `vendor`
- `views`
- `mv-slider.php`: main file

In the main file `mv-slider.php`

Add some plugin information throught comment

```php
<?php 
/**
 * Plugin Name: MV Slider
 * Plugin URI: https://wordpress.org/mv-slider
 * Description: My plugin's description
 * Version: 1.0.0
 * Requires at least: 5.6
 * Author: Hieu Nguyen Trong
 * Author URI: https://dalatcoder.github.io
 */
```

Add `text-domain` for translation and the folder contains all
the translated strings.

```php
<?php 
/**
 * Text Domain: mv-slider
 * Domain Path: /languages
 */
```

Create `index.php` in the root of the `mv-slider` folder to prevent
other user to list all the files inside the plugin folder (using the URL).
We only want our plugin can be run and accessed by WP and no one else.

```php
<?php 
# Silence is golden
```

Another check to prevent someone execute the plugin `.php` file directly.
We only want WP access and run those plugin files. In the `mv-slider.php`
file, we check for the constant `ABSPATH`

```php
if (!defined('ABSPATH')) {
    die('Im just a plugin');
}
```

We can try to access the file via browser: `host/wp-content/plugins/mv-slider/mv-slider.php`

### Plugin with `class` and plugin without `class`

In the course, the author will try to be as close as possible to
the MVC pattern.

Most of our `.php` files are going to be class based.

Using `class` to seperate the responsibility of each file or using
single `.php` file for the plugin.

Set up plugin with `OOP` in PHP

```php
<?php 

 if (!defined('ABSPATH')) {
    die('Im just a plugin');
 }

 if (class_exists('MV_Slider')) {
    die('The class with the same name MV_Slider already exists');
 }

 class MV_Slider {
    function __construct() {

    }
 }

 $mv_slider = new MV_Slider();

 # $mv_slider->start();
```

### Define some plugin constants

- Plugin directory path (file system path): `define('MV_SLIDER_PATH', plugin_dir_path(__FILE__));`, we will get the path like this: `/home/www/your_side/wp-content/plugins/your_plugins/`

- Plugin URL path: `define('MV_SLIDER_URL', plugin_dir_url(__FILE__));`,
  used for browser to load `js` and `css` files. We also can use `plugins_url()` or `plugin_basename()` to get the URL path.

- `version`: for reset cache of `js` and `css` file.

```php
<?php

 class MV_Slider {
    function __construct() {
        $this->define_constants();
    }

    public function define_constants() {
        define('MV_SLIDER_PATH', plugin_dir_path(__FILE__));
        define('MV_SLIDER_URL', plugin_dir_url(__FILE__));
        define('MV_SLIDER_VERSION', '1.0.0');
    }
 }
```

### Active, deactive and uninstall methods

We want to execute some code when the plugin is installing, deactivating
or uninstalling.

When our plugin have the archived page, WP can not immediately recognize
the link structure, so the `404` error will occur. To handle this,
we can manually recreate the site's permanent links in the WP setting page.
Or we can use 2 functions with code

- `update_option('rewrite_rules', '')`: more performance
- `flush_rewrite_rules()`

```php
<?php

 class MV_Slider {
    function __construct() {
        $this->define_constants();
    }

    public function define_constants() {
    }

    public static function activate() {
        update_option('rewrite_rules', '');
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }

    public static function uninstall() {

    }
 }

 register_activation_hook(__FILE__, ['MV_Slider', 'activate']);
 register_deactivation_hook(__FILE__, ['MV_Slider', 'deactivate']);
 register_uninstall_hook(__FILE__, ['MV_Slider', 'uninstall']);

 $mv_slider = new MV_Slider();
```

### Creating custom post type

Some wordpress default post types: `select post_type from wp_posts group by post_type`:

- attachment: images,...
- page
- post
- revision

Create new custom post type:

- create file `mv-slider-cpt.php` inside `post-types` directory

```php
<?php 

if (class_exists('MV_Slider_Post_Type')) {
    die('Class with name MV_Slider_Post_Type already exist.');
}

class MV_Slider_Post_Type {
    function __construct() {
        add_action('init', [$this, 'create_post_type']);
    }

    public function create_post_type() {
        register_post_type(
            'mv_slider', 
            [
                'label' => 'Slider',
                'description' => 'Sliders',
                'labels' => [
                    'name' => 'Sliders',
                    'singular_name' => 'Slider'
                ],
                'public' => true
            ]
        );
    }
}
```

Some attributes:

- `public`: our CPT will be available for accessing by BE and FE,
  the default value is `false`. WP `revision` is not `public`, so
  no one can access it.

- `supports`: the resources that are avaiable in the edit area of our CPT.
  By default, it supports the title and the editor

- `hierarchical`: create parent and child post relationship, the default
  value is `false`. It only works with page, we must enable page attribute
  in the `supports` field like this: `['page-attributes']`

- `show_ui`: related to the `public` attribute, if `true`, we will see
  the post table, post edit area,... Post `revision` is not public and
  does not have the user interface for any to edit.

- `show_in_menu`: visible in admin menu or not
- `menu_position`: position, 5
- `archive page` in the frontend:
  - `has_archive`
  - `exclude_from_search`
  - `publicly_queryable`

- Support REST API and Block editor: `show_in_rest = true`

```php
<?php
register_post_type(
    'mv_slider', 
    [
        'label' => 'Slider',
        'description' => 'Sliders',
        'labels' => [
            'name' => 'Sliders',
            'singular_name' => 'Slider'
        ],
        'public' => true,
        'supports' => [
            'title',
            'editor',
            'thumbnail'
        ],
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_menu' => true,
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
```

Instantiate our CPT

```php
 class MV_Slider {
    function __construct() {
        $this->define_constants();

        require_once(MV_SLIDER_PATH . 'post-types/mv-slider-cpt.php');
        new MV_Slider();
    }

    public function define_constants() {
        define('MV_SLIDER_PATH', plugin_dir_path(__FILE__));
        define('MV_SLIDER_URL', plugin_dir_url(__FILE__));
        define('MV_SLIDER_VERSION', '1.0.0');
    }

    public static function deactivate() {
        flush_rewrite_rules();
        unregister_post_type('mv-slider');
    }
 }
```

### Metabox API

Terms:

- Metabox: UI
- Metadata: some related information link to CPT

### Add Metabox

- Using action hook `add_metabox`
- Or add attribute `register_meta_box_cb` in the `register_post_type` function

```php
<?php 

if (!class_exists('MV_Slider_Post_Type')) {
    class MV_Slider_Post_Type {
        function __construct() {
            add_action('init', [$this, 'create_post_type']);
            add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        }

        public function create_post_type() {
            register_post_type(
                'mv_slider', 
                [
                    'label' => 'Slider',
                    'description' => 'Sliders',
                    'labels' => [
                        'name' => 'Sliders',
                        'singular_name' => 'Slider'
                    ],
                    'public' => true,
                    'supports' => [
                        'title',
                        'editor',
                        'thumbnail'
                    ],
                    'hierarchical' => false,
                    'show_ui' => true,
                    'show_in_menu' => true,
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
                'Link Options',
                [$this, 'add_inner_meta_boxes'],
                'mv_slider',
                'normal',
                'high',
            );
        }

        public function add_inner_meta_boxes($post) {

        }
    }
}
```

### Create form inside metabox

Create new view file at `views/mv-slider_metabox.php` and then require
it inside the function

```php
<?php 

if (!class_exists('MV_Slider_Post_Type')) {
    class MV_Slider_Post_Type {
        function __construct() {
            add_action('init', [$this, 'create_post_type']);
            add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        }

        public function create_post_type() {
        }

        public function add_meta_boxes() {
        }

        public function add_inner_meta_boxes($post) {
            require_once(MV_SLIDER_PATH . 'views/mv-slider_metabox.php');
        }
    }
}
```

For the view, we use the table to construct the UI

```php
<table class="form-table mv-slider-metabox">
    <tr>
        <th>
            <label for="mv_slider_link_text">Link Text</label>
        </th>
        <td>
            <input type="text" name="mv_slider_link_text" id="mv_slider_link_text" class="regular-text link-text"
                value="" required>
        </td>
    </tr>

    <tr>
        <th>
            <label for="mv_slider_link_url">Link URL</label>
        </th>
        <td>
            <input type="url" name="mv_slider_link_url" id="mv_slider_link_url" class="regular-text link-url"
                value="" required>
        </td>
    </tr>
</table>
```

### Saving metabox data

Using action hook called `save_post` to intercept the process of
wordpress.

WP automatically create a form to wrap our metabox data. We can get those data
from `$_POST`. To ensure data is from the post, we can compare the `$_POST['action']`
with the value of `editpost`

```php
<?php 

if (!class_exists('MV_Slider_Post_Type')) {
    class MV_Slider_Post_Type {
        function __construct() {
            add_action('save_post', [$this, 'save_post'], 10, 2);
        }

        public function save_post($post_id) {
            if (isset($_POST['action']) && $_POST['action'] == 'editpost') {

            }
        }
    }
}
```

To dealt with post meta, we can use the functions:

- `add_post_meta`: C
- `get_post_meta`: R
- `update_post_meta`: U
- `delete_post_meta`: D

Those functions are wrapper for 4 basic WP functions with metadata:

- `add_metadata`
- `get_metadata`
- `update_metadata`
- `delete_metadata`

```php
<?php

function save_post($post_id) {
    if (isset($_POST['action']) && $_POST['action'] == 'editpost') {
        $old_text = get_post_meta($post_id, 'mv_slider_link_text', true);
        $old_url = get_post_meta($post_id, 'mv_slider_link_url', true);

        $new_text = $_POST['mv_slider_link_text'];
        $new_url = $_POST['mv_slider_link_url'];

        update_post_meta($post_id, 'mv_slider_link_text', $new_text, $old_text);
        update_post_meta($post_id, 'mv_slider_link_url', $new_url, $old_url);
    }
}
```

### Validating and sanitizing data before saving

> Never trust user supplied information

All information you get from the user should be filtered on input and
escaped on output.

A very common type of attack is `xss`. Basically, if your application allows
JS code to be stored in the DB. It can be executed on the FE, which leaves the door
open for several type of attacks.

```php
<?php 

function save_post($post_id) {
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
            $new_text = 'Add some text';
        }
        if (empty($new_url)) {
            $new_url = 'Add some URL';
        }

        update_post_meta($post_id, 'mv_slider_link_text', $new_text, $old_text);
        update_post_meta($post_id, 'mv_slider_link_url', $new_url, $old_url);
    }
}
```

Now, if the `$new_text` = `"<script>alert(0)</script>"`, it will be sanitized to
`""`, add then, the validation process occur, assign the default text `"Add some text"`
to the `$new_text` variable.

### Escaping data

Escaping function in WP:

- `esc_html`
- `esc_url`
- `esc_js`
- `esc_attr`
- `esc_textarea`

```php
<?php 
    $text = get_post_meta($post->ID, 'mv_slider_link_text', true);
    $url = get_post_meta($post->ID, 'mv_slider_link_url', true);

    if (!isset($text)) {
        $text = "";
    }

    if (!isset($url)) {
        $url = "";
    }

    $text = esc_html($text);
    $url = esc_url($url);
?>

<table class="form-table mv-slider-metabox">
    <tr>
        <th>
            <label for="mv_slider_link_text">Link Text</label>
        </th>
        <td>
            <input type="text" name="mv_slider_link_text" id="mv_slider_link_text" class="regular-text link-text"
                value="<?= $text ?>" required>
        </td>
    </tr>

    <tr>
        <th>
            <label for="mv_slider_link_url">Link URL</label>
        </th>
        <td>
            <input type="url" name="mv_slider_link_url" id="mv_slider_link_url" class="regular-text link-url"
                value="<?= $url ?>" required>
        </td>
    </tr>
</table>
```

### Nonces and other validations

> Nonce - Number used once

We use `nonce` to ensure the data is submitted from the form, not from other sources
such as `bot`, or some client like `PostMan`,...

First, we create an hidden input contains our nonce value

```php
<input type="hidden" name="mv_slider_nonce" value="<?= wp_create_nonce("mv_slider_nonce") ?>">
```

And then, we verify nonce in the code and other important validations like this

```php
function save_post($post_id) {
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
            $new_text = 'Add some text';
        }
        if (empty($new_url)) {
            $new_url = 'Add some URL';
        }

        update_post_meta($post_id, 'mv_slider_link_text', $new_text, $old_text);
        update_post_meta($post_id, 'mv_slider_link_url', $new_url, $old_url);
    }
}
```

### Showing values on the post type table

We use a filter to hijack the content displaying on the table: `manage_<cpt>_posts_columns`.

To add new columns:

```php
<?php 

if (!class_exists('MV_Slider_Post_Type')) {
    class MV_Slider_Post_Type {
        function __construct() {
            add_filter('manage_mv_slider_posts_columns', [$this, 'add_custom_columns']);
        }

        public function add_custom_columns($columns) {
            $columns['mv_slider_link_text'] = 'Link text';
            $columns['mv_slider_link_url'] = 'Link url';

            return $columns;
        }
    }
}
```

To edit the content of our custom columns, we use the action hook called `manage_<cpt>_posts_custom_column`

```php
<?php 

if (!class_exists('MV_Slider_Post_Type')) {
    class MV_Slider_Post_Type {
        function __construct() {
            add_action('manage_mv_slider_posts_custom_column', [$this, 'add_custom_columns_content'], 10, 2);
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
    }
}
```

To add sort feature to a column, we use the filter called `manage_edit-<cpt>_sortable_columns`

```php
<?php 

if (!class_exists('MV_Slider_Post_Type')) {
    class MV_Slider_Post_Type {
        function __construct() {
            add_filter('manage_edit-mv_slider_sortable_columns', [$this, 'add_sortable_columns']);
        }

        public function add_sortable_columns($columns) {
            $columns['mv_slider_link_text'] = 'mv_slider_link_text';
            return $columns;
        }
    }
}
```

### Adding menu to admin page

WP has 5 different type of menus:

- Higher level menu: (already exist when installing WP)
- Custom menu
- Submenu that will be placed under `Plugins` menu
- Submenu that will be placed under `Appearance` menu
- Submenu that will be placed under `Settings` menu

To add new menu, we use an action hook called `admin_menu`.

WP roles & capabilities:

- Super Admin (Multi-site)
- Administrator
- Editor
- Author
- Contributor
- Subscriber

`manage_options` is the capability that only `super admin` and `admin` can access,
so we use this capability to restrict the access to our menu.

```php
<?php

 if (!class_exists('MV_Slider')) {
    class MV_Slider {
        function __construct() {
            $this->define_constants();

            add_action('admin_menu', [$this, 'add_menu']);

            require_once(MV_SLIDER_PATH . 'post-types/mv-slider-cpt.php');
            new MV_Slider_Post_Type();
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

        }

        public function add_menu() {
            add_menu_page(
                'MV Slider Options',
                'MV Slider',
                'manage_options',
                'mv_slider_admin',
                [$this, 'mv_slider_settings_page'],
                'dashicons-images-alt2'
            );
        }

        public function mv_slider_settings_page() {
            echo 'Hello';
        }
    }
 }

 if (class_exists('MV_Slider')) {
    register_activation_hook(__FILE__, ['MV_Slider', 'activate']);
    register_deactivation_hook(__FILE__, ['MV_Slider', 'deactivate']);
    register_uninstall_hook(__FILE__, ['MV_Slider', 'uninstall']);

    $mv_slider = new MV_Slider();
 }
```

### Adding submenu page

Hide our CPT menu by setting the attribute `show_in_menu` to `false`.

Then, add 2 submenu to manage our CPT.

```php
<?php

 if (!class_exists('MV_Slider')) {
    class MV_Slider {
        function __construct() {
            $this->define_constants();

            add_action('admin_menu', [$this, 'add_menu']);

            require_once(MV_SLIDER_PATH . 'post-types/mv-slider-cpt.php');
            new MV_Slider_Post_Type();
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

        }

        public function add_menu() {
            add_menu_page(
                'MV Slider Options',
                'MV Slider',
                'manage_options',
                'mv_slider_admin',
                [$this, 'mv_slider_settings_page'],
                'dashicons-images-alt2'
            );

            add_submenu_page(
                'mv_slider_admin',
                'Manage Slides',
                'Manage Slides',
                'manage_options',
                'edit.php?post_type=mv_slider'
            );

            add_submenu_page(
                'mv_slider_admin',
                'Add New Slide',
                'Add New Slide',
                'manage_options',
                'post-new.php?post_type=mv_slider'
            );
        }

        public function mv_slider_settings_page() {
            echo 'Hello';
        }
    }
 }

 if (class_exists('MV_Slider')) {
    register_activation_hook(__FILE__, ['MV_Slider', 'activate']);
    register_deactivation_hook(__FILE__, ['MV_Slider', 'deactivate']);
    register_uninstall_hook(__FILE__, ['MV_Slider', 'uninstall']);

    $mv_slider = new MV_Slider();
 }
```

### Setting page & option API

#### Introduction

Store all plugin setting key-value pairs inside the `wp_options` table using
`Options API`

Some advantages of using setting page & options api:

- Don't have to build UI from scratch
- Don't have to worry about security issues such as nonce,...

#### Building form

Setting up our view file

```php
<?php

 if (!class_exists('MV_Slider')) {
    class MV_Slider {
        function __construct() {
            add_action('admin_menu', [$this, 'add_menu']);
        }

        public function add_menu() {
            add_menu_page(
                'MV Slider Options',
                'MV Slider',
                'manage_options',
                'mv_slider_admin',
                [$this, 'mv_slider_settings_page'],
                'dashicons-images-alt2'
            );

            add_submenu_page(
                'mv_slider_admin',
                'Manage Slides',
                'Manage Slides',
                'manage_options',
                'edit.php?post_type=mv_slider'
            );

            add_submenu_page(
                'mv_slider_admin',
                'Add New Slide',
                'Add New Slide',
                'manage_options',
                'post-new.php?post_type=mv_slider'
            );
        }

        public function mv_slider_settings_page() {
            require_once(MV_SLIDER_PATH . 'views/setting-page.php');
        }
    }
 }
```

Add basic form

```php
<div class="wrap">
    <h1><?= esc_html(get_admin_page_title()) ?></h1>
    <form action="options.php" method="post">

    </form>
</div>
```

All form data will be post to `options.php` to be handled. We can also
view all options by access to `https://<site>/wp-admin/options.php`

To add field and section to our form, we must use 2 WP functions

- `settings_fields()`
- `do_secttings_sections()`

#### Adding sections and fields

Register setting key

```php
<?php

if (!class_exists('MV_Slider_Settings')) {
    class MV_Slider_Settings {
        public function __construct() {
            add_action('admin_init', [$this, 'admin_init']);
        }

        public function admin_init() {
            register_setting('mv_slider_group', 'mv_slider_options');
        }
    }
}
```

All setting values will be store in 1 array, to get those option
easier, we use the static attribute `$options`

```php
<?php

if (!class_exists('MV_Slider_Settings')) {
    class MV_Slider_Settings {
        public static $options;

        public function __construct() {
            self::$options = get_option('mv_slider_options');

            add_action('admin_init', [$this, 'admin_init']);
        }

        public function admin_init() {
            register_setting('mv_slider_group', 'mv_slider_options');
        }
    }
}
```

Now we can access an option value by using `MV_Slider_Settings::options['key']`

To add a section to our setting page, do the following:

- First, register our section with: `add_settings_section` and `add_settings_field`

```php
<?php 

if (!class_exists('MV_Slider_Settings')) {
    class MV_Slider_Settings {
        public static $options;

        public function __construct() {
            self::$options = get_option('mv_slider_options');

            add_action('admin_init', [$this, 'admin_init']);
        }

        public function admin_init() {
            register_setting('mv_slider_group', 'mv_slider_options');

            add_settings_section(
                'mv_slider_main_section',
                'How does it works?',
                null,
                'mv_slider_settings_page1'
            );

            add_settings_field(
                'mv_slider_shortcode',
                'Shortcode',
                [$this, 'mv_slider_shortcode'], 
                'mv_slider_settings_page1',
                'mv_slider_main_section'
            );
        }

        public function mv_slider_shortcode() {
            ?>
            <span>Use the shortcode [mv_slider] to display the slider on any page/post/widget</span>
            <?php
        }
    }
}
```

- Second, `echo` out the UI via `settings_fields`, `do_settings_sections` and `submit_button`

```php
<div class="wrap">
    <h1><?= esc_html(get_admin_page_title()) ?></h1>
    <form action="options.php" method="post">
        <?php
            settings_fields('mv_slider_group');
            do_settings_sections('mv_slider_settings_page1');
            submit_button('Save settings');
        ?>
    </form>
</div>
```

#### Validating setting values

To validate input value from settings page, we could follow this

- Add callback to validate value when register setting page: `register_setting('mv_slider_group', 'mv_slider_options', [$this, 'mv_slider_validations']);`
- Write validation logic inside the callback function.

```php
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
        }

        public function mv_slider_validations($input) {
            $new_input = [];

            foreach($input as $key => $value) {
                switch($key) {
                    case 'mv_slider_title':
                        if (empty($value)) {
                            $value = 'Please type some text';
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
```

#### Manage permissions

Prevent user from accessing the setting page directly by URL

```php
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

            add_action('admin_menu', [$this, 'add_menu']);

            require_once(MV_SLIDER_PATH . 'post-types/mv-slider-cpt.php');
            new MV_Slider_Post_Type();

            require_once(MV_SLIDER_PATH . 'mv-slider-settings.php');
            new MV_Slider_Settings();
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

        }

        public function add_menu() {
            add_menu_page(
                'MV Slider Options',
                'MV Slider',
                'manage_options',
                'mv_slider_admin',
                [$this, 'mv_slider_settings_page'],
                'dashicons-images-alt2'
            );

            add_submenu_page(
                'mv_slider_admin',
                'Manage Slides',
                'Manage Slides',
                'manage_options',
                'edit.php?post_type=mv_slider'
            );

            add_submenu_page(
                'mv_slider_admin',
                'Add New Slide',
                'Add New Slide',
                'manage_options',
                'post-new.php?post_type=mv_slider'
            );
        }

        public function mv_slider_settings_page() {
            if (!current_user_can('manage_options')) {
                return;
            }

            require_once(MV_SLIDER_PATH . 'views/setting-page.php');
        }
    }
 }

 if (class_exists('MV_Slider')) {
    register_activation_hook(__FILE__, ['MV_Slider', 'activate']);
    register_deactivation_hook(__FILE__, ['MV_Slider', 'deactivate']);
    register_uninstall_hook(__FILE__, ['MV_Slider', 'uninstall']);

    $mv_slider = new MV_Slider();
 }
```

#### Handling notifications

```php
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

            add_action('admin_menu', [$this, 'add_menu']);

            require_once(MV_SLIDER_PATH . 'post-types/mv-slider-cpt.php');
            new MV_Slider_Post_Type();

            require_once(MV_SLIDER_PATH . 'mv-slider-settings.php');
            new MV_Slider_Settings();
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

        }

        public function add_menu() {
            add_menu_page(
                'MV Slider Options',
                'MV Slider',
                'manage_options',
                'mv_slider_admin',
                [$this, 'mv_slider_settings_page'],
                'dashicons-images-alt2'
            );

            add_submenu_page(
                'mv_slider_admin',
                'Manage Slides',
                'Manage Slides',
                'manage_options',
                'edit.php?post_type=mv_slider'
            );

            add_submenu_page(
                'mv_slider_admin',
                'Add New Slide',
                'Add New Slide',
                'manage_options',
                'post-new.php?post_type=mv_slider'
            );
        }

        public function mv_slider_settings_page() {
            if (!current_user_can('manage_options')) {
                return;
            }

            if (isset($_GET['settings-updated'])) {
                add_settings_error('mv_slider_options', 'mv_slider_message', 'Settings Saved', 'success');
            }
            settings_errors('mv_slider_options');

            require_once(MV_SLIDER_PATH . 'views/setting-page.php');
        }
    }
 }

 if (class_exists('MV_Slider')) {
    register_activation_hook(__FILE__, ['MV_Slider', 'activate']);
    register_deactivation_hook(__FILE__, ['MV_Slider', 'deactivate']);
    register_uninstall_hook(__FILE__, ['MV_Slider', 'uninstall']);

    $mv_slider = new MV_Slider();
 }
```

#### Splitting tabs

```php
<?php 
    $active_tab = 'main_options';

    if (isset($_GET['tab'])) {
        $active_tab = $_GET['tab'];
    }
?>

<div class="wrap">
    <h1><?= esc_html(get_admin_page_title()) ?></h1>
    <h2 class="nav-tab-wrapper">
        <a href="?page=mv_slider_admin&tab=main_options" class="nav-tab <?= $active_tab == 'main_options' ? 'nav-tab-active' : '' ?>">Main Options</a>
        <a href="?page=mv_slider_admin&tab=additional_options" class="nav-tab <?= $active_tab == 'additional_options' ? 'nav-tab-active' : '' ?>">Additional Options</a>
    </h2>
    <form action="options.php" method="post">
        <?php
            settings_fields('mv_slider_group');

            if ($active_tab == 'main_options') {
                do_settings_sections('mv_slider_settings_page1');
            } else if ($active_tab == 'additional_options') {
                do_settings_sections('mv_slider_settings_page2');
            }

            submit_button('Save settings');
        ?>
    </form>
</div>
```

### Shortcode API

They are shortcut, they are short tags that any WP user can add to post,
page or anywhere else.

The goal is to make thing easier for the plugins and the users
instead of having to write code.

- Shortcode tag: `[mv_slider]`
- Shortcode attributes: `[mv_slider id='1' orderby='random']`
- Or using completed tag with content like this: `[mv_slider]Hello world[/mv_slider]`

#### Create shortcode class

```php
<?php 

if (!class_exists('MV_Slider_Shortcode')) {
    class MV_Slider_Shortcode {
        public function __construct() {
            add_shortcode('mv_slider', [$this, 'add_shortcode']);
        }

        public function add_shortcode($attributes = [], $content = null, $tag = '') {
            $attributes = array_change_key_case($attributes, CASE_LOWER);

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
        }
    }
}
```

#### Download flexslider2 files

[Flexslider](http://flexslider.woothemes.com/)

Setup `vendor` folder

- Create folder `vendor/flexsider`
- Put `css`, `js` and `fonts` from flexsider
- Create main js file with the following

```js
jQuery(window).load(function() {
  jQuery('.flexslider').flexslider({
    animation: "slide",
    touch: true,
    directionNav: false,
    smoothHeight: true,
    controlNav: true
  });
});
```

#### Create shortcode view

Create simple view with raw HTML

```php
<h3>Title</h3>

<div class="mv-slider flexsider">
    <ul class="slides">
        <li>
            <div class="mvs-container">
                <div class="slider-details-container">
                    <div class="wrapper">
                        <div class="slider-title">
                            <h2>Slider title</h2>
                        </div>
                        <div class="slider-description">
                            <div class="subtitle">Subtitle</div>
                            <a class="link" href="#">Button text</a>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</div>
```

Read HTML and return it inside shortcode.

```php
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

            ob_start();
            require(MV_SLIDER_PATH . 'views/mv-slider_shortcode.php');

            return ob_get_clean();
        }
    }
}
```

Using `require` to handle if the user include the `mv_slider` shortcode twice
inside the same page.

Using `ob_start()` and `ob_get_clean()` to get HTML from buffer and return it.

Get slider title from DB and display on shortcode view

```php
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

            ob_start();
            require(MV_SLIDER_PATH . 'views/mv-slider_shortcode.php');

            return ob_get_clean();
        }
    }
}
```

#### Using custom loop from WP_Query to get CPT data

Build custom query from `WP_Query`

```php
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

            return ob_get_clean();
        }
    }
}
```

Using loop to get all CPT

```php
<h3><?= $slider_title ?></h3>

<div class="mv-slider flexsider">
    <ul class="slides">
        <?php 
            if ($query->have_posts()):
                while($query->have_posts()):
                    $query->the_post();

                    $button_text = get_post_meta(get_the_ID(), 'mv_slider_link_text', true);
                    $button_url = get_post_meta(get_the_ID(), 'mv_slider_link_url', true);
        ?>
            <li>
                <?php the_post_thumbnail('full', ['class' => 'img-fluid']) ?>
                <div class="mvs-container">
                    <div class="slider-details-container">
                        <div class="wrapper">
                            <div class="slider-title">
                                <h2><?php the_title(); ?></h2>
                            </div>
                            <div class="slider-description">
                                <div class="subtitle"><?php the_content(); ?></div>
                                <a class="link" href="<?= esc_attr($button_url) ?>"><?= esc_html($button_text) ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </ul>
</div>
```

#### Register and enqueue scripts

WP puts each `js` and `css` inside a queue and enqueue it in order.

- `wp_enqueue_script` for FE client
- `admin_enqueue_script` for FE admin

First, we need to register the need scripts and styles

```php
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

            add_action('admin_menu', [$this, 'add_menu']);

            require_once(MV_SLIDER_PATH . 'post-types/mv-slider-cpt.php');
            new MV_Slider_Post_Type();

            require_once(MV_SLIDER_PATH . 'mv-slider-settings.php');
            new MV_Slider_Settings();

            require_once(MV_SLIDER_PATH . 'shortcodes/mv-slider-shortcode.php');
            new MV_Slider_Shortcode();

            add_action('wp_enqueue_scripts', [$this, 'register_scripts'], 999);
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

        }

        public function add_menu() {
            add_menu_page(
                'MV Slider Options',
                'MV Slider',
                'manage_options',
                'mv_slider_admin',
                [$this, 'mv_slider_settings_page'],
                'dashicons-images-alt2'
            );

            add_submenu_page(
                'mv_slider_admin',
                'Manage Slides',
                'Manage Slides',
                'manage_options',
                'edit.php?post_type=mv_slider'
            );

            add_submenu_page(
                'mv_slider_admin',
                'Add New Slide',
                'Add New Slide',
                'manage_options',
                'post-new.php?post_type=mv_slider'
            );
        }

        public function mv_slider_settings_page() {
            if (!current_user_can('manage_options')) {
                return;
            }

            if (isset($_GET['settings-updated'])) {
                add_settings_error('mv_slider_options', 'mv_slider_message', 'Settings Saved', 'success');
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
        }
    }
 }

 if (class_exists('MV_Slider')) {
    register_activation_hook(__FILE__, ['MV_Slider', 'activate']);
    register_deactivation_hook(__FILE__, ['MV_Slider', 'deactivate']);
    register_uninstall_hook(__FILE__, ['MV_Slider', 'uninstall']);

    $mv_slider = new MV_Slider();
 }
```

Then, we can enqueue it when we need

```php
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

            return ob_get_clean();
        }
    }
}
```

#### Enqueue admin scripts

Using an action hook called `admin_enqueue_scripts`, then
we can use `$typenow` to check whether to enqueue our scripts or styles.

```php
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

        }

        public function add_menu() {
            add_menu_page(
                'MV Slider Options',
                'MV Slider',
                'manage_options',
                'mv_slider_admin',
                [$this, 'mv_slider_settings_page'],
                'dashicons-images-alt2'
            );

            add_submenu_page(
                'mv_slider_admin',
                'Manage Slides',
                'Manage Slides',
                'manage_options',
                'edit.php?post_type=mv_slider'
            );

            add_submenu_page(
                'mv_slider_admin',
                'Add New Slide',
                'Add New Slide',
                'manage_options',
                'post-new.php?post_type=mv_slider'
            );
        }

        public function mv_slider_settings_page() {
            if (!current_user_can('manage_options')) {
                return;
            }

            if (isset($_GET['settings-updated'])) {
                add_settings_error('mv_slider_options', 'mv_slider_message', 'Settings Saved', 'success');
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
```
