# Creating Wordpress Plugins The Right Way

## Before begins

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
