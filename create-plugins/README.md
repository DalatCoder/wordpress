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
