# 1. Complete WordPress Developer Course 2023 - Plugins & Themes

- [1. Complete WordPress Developer Course 2023 - Plugins \& Themes](#1-complete-wordpress-developer-course-2023---plugins--themes)
  - [1.1. Getting started](#11-getting-started)
    - [1.1.1. What to expect](#111-what-to-expect)
    - [1.1.2. What is an environment?](#112-what-is-an-environment)
    - [1.1.3. Installing WordPress](#113-installing-wordpress)
    - [1.1.4. `Local` quick tour](#114-local-quick-tour)
    - [1.1.5. Text editors](#115-text-editors)
  - [1.2. PHP Fundamentals](#12-php-fundamentals)
    - [1.2.1. Introduction to PHP](#121-introduction-to-php)
    - [1.2.2. Variables](#122-variables)
    - [1.2.3. Strings and Booleans](#123-strings-and-booleans)
    - [1.2.4. Functions](#124-functions)
    - [1.2.5. Arrays](#125-arrays)
    - [1.2.6. Loops](#126-loops)
    - [1.2.7. Constants](#127-constants)
    - [1.2.8. Understanding Errors](#128-understanding-errors)
    - [1.2.9. Comments](#129-comments)
  - [1.3. Kickstarting a Theme](#13-kickstarting-a-theme)
    - [1.3.1. Exploring the WordPress Configuration](#131-exploring-the-wordpress-configuration)
    - [Adjusting the configuration](#adjusting-the-configuration)

## 1.1. Getting started

### 1.1.1. What to expect

- Custom post types
- Extending the REST API
- Translations
- Block development
- Custom database tables
- Transients
- Data sanitization
- Code optimization
- ...

### 1.1.2. What is an environment?

- Learn why it's important to have an environment for WordPress
- A location where your code runs
- **Production**: Publicly accessible, managed by a company
- **Development**: Private, managed by developer, using `Local` tool to config WP dev environment

### 1.1.3. Installing WordPress

- Installing WordPress through `Local`
- WordPress needs 3 programs to run:
  - PHP
  - Web Server
  - Database

- PHP is a programming language. Generally, programming languages can give instructions to machines to perform various actions.
- A web server is responsible for exposing files via an HTTP URL
- A database is a program for storing info

### 1.1.4. `Local` quick tour

- Select site
- Change site domain
- SSL certificate
- Folders
  - `conf`: configuration
  - `log`: logs
  - `app`: website

### 1.1.5. Text editors

In this course, I'll be using a text editor called Visual Studio Code.

## 1.2. PHP Fundamentals

### 1.2.1. Introduction to PHP

PHP: it's a programming language that runs on servers to process web pages. A programming language
allows us to give instructions to a machine.

### 1.2.2. Variables

> Variables allow us to store values.

Syntax: the rules for how a programming language is written
    - The `$` symbol tells PHP to create a variable
    - Another way of saying it is declaring a variable

Variable naming rules
    - A variable must start with a letter or underscore character
    - A variable name cannot start with a number
    - A variable name can only contain alpha-numeric characters and underscores

```php
<?php 
    $age = 23;
    echo $age;
?>
```

### 1.2.3. Strings and Booleans

Data can be categorized. Aside from numbers, we can also use strings and booleans.

Data types:
    - Categories for data
    - Integer
    - Float
    - String
    - Boolean
    - Array
    - Object
    - Null

```php
<?php 
    $name = 'Hieu Nguyen Trong';
    echo $name;

    $isLoggedIn = true;
    echo $isLoggedIn;
?>
```

### 1.2.4. Functions

A function is a block of code that can perform a specific task.

- **Parameters** - The variable in the declaration of the function
- **Argument** - The value of the variable that gets passed to the function

```php
<?php 
    function greeting($message) {
        echo $message;
    }

    greeting('Hello World');
?>
```

### 1.2.5. Arrays

PHP array can store a collection of data.

```php
<?php 
    $foods = array('Pizza', 'Hamburger', 'Sushi');

    echo $foods[0];
    echo $foods[1];
    echo $foods[2];
?>
```

### 1.2.6. Loops

Using loops to iterate through the array

```php
<?php 
    $foods =['Pizza', 'Hamburger', 'Sushi'];

    $count = 0;
    while($count < count($foods)) {
        echo $foods[$count];
        $count += 1;
    }

?>
```

### 1.2.7. Constants

A constant is a variable that cannot have its value updated.
Constants can be reliable for storing data that never needs to change.

```php
<?php 
    define('NAME', 'Hieu Nguyen Trong');
    echo NAME;
?>
```

### 1.2.8. Understanding Errors

```php
<?php 
    define('NAME', 'Hieu Nguyen Trong');
    echo NAME;

    // errors
    define('NAME', 'Errors');
?>
```

### 1.2.9. Comments

```php
<?php 
    // Single-line comment
    define('NAME', 'Hieu Nguyen Trong');

    /*
     *  Multiline
     *  comment
     */
    echo NAME;
?>
```

## 1.3. Kickstarting a Theme

### 1.3.1. Exploring the WordPress Configuration

The configuration file is loaded on every request.

It can be found in the root directory of your WordPress installation, the file is called `wp-config.php`

WordPress configuration settings are defined with constants.

### Adjusting the configuration

Enable debug mode in WordPress

```php
<?php 
    define('WP_DEBUG', true);
    define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
?>
```
