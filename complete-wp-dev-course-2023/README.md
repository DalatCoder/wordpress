# Complete WordPress Developer Course 2023 - Plugins & Themes

- [Complete WordPress Developer Course 2023 - Plugins \& Themes](#complete-wordpress-developer-course-2023---plugins--themes)
  - [1. Getting started](#1-getting-started)
    - [1.1. What to expect](#11-what-to-expect)
    - [1.2. What is an environment?](#12-what-is-an-environment)
    - [1.3. Installing WordPress](#13-installing-wordpress)
    - [1.4. `Local` quick tour](#14-local-quick-tour)
    - [1.5. Text editors](#15-text-editors)
  - [2. PHP Fundamentals](#2-php-fundamentals)
    - [2.1. Introduction to PHP](#21-introduction-to-php)
    - [2.2. Variables](#22-variables)
    - [2.3. Strings and Booleans](#23-strings-and-booleans)
    - [2.4. Functions](#24-functions)
    - [2.5. Arrays](#25-arrays)
    - [2.6. Loops](#26-loops)
    - [2.7. Constants](#27-constants)
    - [2.8. Understanding Errors](#28-understanding-errors)
    - [2.9. Comments](#29-comments)

## 1. Getting started

### 1.1. What to expect

- Custom post types
- Extending the REST API
- Translations
- Block development
- Custom database tables
- Transients
- Data sanitization
- Code optimization
- ...

### 1.2. What is an environment?

- Learn why it's important to have an environment for WordPress
- A location where your code runs
- **Production**: Publicly accessible, managed by a company
- **Development**: Private, managed by developer, using `Local` tool to config WP dev environment

### 1.3. Installing WordPress

- Installing WordPress through `Local`
- WordPress needs 3 programs to run:
  - PHP
  - Web Server
  - Database

- PHP is a programming language. Generally, programming languages can give instructions to machines to perform various actions.
- A web server is responsible for exposing files via an HTTP URL
- A database is a program for storing info

### 1.4. `Local` quick tour

- Select site
- Change site domain
- SSL certificate
- Folders
  - `conf`: configuration
  - `log`: logs
  - `app`: website

### 1.5. Text editors

In this course, I'll be using a text editor called Visual Studio Code.

## 2. PHP Fundamentals

### 2.1. Introduction to PHP

PHP: it's a programming language that runs on servers to process web pages. A programming language
allows us to give instructions to a machine.

### 2.2. Variables

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

### 2.3. Strings and Booleans

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

### 2.4. Functions

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

### 2.5. Arrays

PHP array can store a collection of data.

```php
<?php 
    $foods = array('Pizza', 'Hamburger', 'Sushi');

    echo $foods[0];
    echo $foods[1];
    echo $foods[2];
?>
```

### 2.6. Loops

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

### 2.7. Constants

A constant is a variable that cannot have its value updated.
Constants can be reliable for storing data that never needs to change.

```php
<?php 
    define('NAME', 'Hieu Nguyen Trong');
    echo NAME;
?>
```

### 2.8. Understanding Errors

```php
<?php 
    define('NAME', 'Hieu Nguyen Trong');
    echo NAME;

    // errors
    define('NAME', 'Errors');
?>
```

### 2.9. Comments

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
