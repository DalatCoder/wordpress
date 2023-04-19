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
