Post Type Description
=====================

Adds the ability to add a description to your post type in your theme templates.

![Overview](http://d7c3hoiply1bq.cloudfront.net/wp-content/uploads/2014/01/Post-Type-Description.png)

## Usage

* Activate Plugin
* Select 'Description' from your Post Type menu (your post type must be public).
* Enter your description, like you would in a normal page.
* Add the following function to your archive-post-type.php

```php
ptd_description();
```

### Filters

You can choose the post types that you want this to be activated on. It's activated on all non built-in public posts types by default but you can use the ptd_enabled_post_types filter, for example if you wanted to exclude the 'products' post type:

```php
function remove_my_post_type($post_types) {

	unset($post_types['products']);
    return $post_types;
    
}
add_filter('ptd_enabled_post_types', 'remove_my_post_type');
```

## About

Adds the ability to manage content at the top of your post type archive. For example, if you have a books post type and therefore a services archive (archive-books.php) then above the list of books you may want to add some information about books in general.

## Contribute

If you find any bugs or have a suggestion for improving the plugin, please raise these in the [issues](https://github.com/stompweb/Post-Type-Description/issues). If you can contribute to the code to make it more secure or efficient then please open a pull request.
