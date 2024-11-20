<?php
/* 
Plugin Name: Testing 
Plugin URI: https://piltos.ru/ 
Description: Declares a plugin that will create a custom post type displaying product. 
Version: 1.0 
Author: Denis Usmanov 
Author URI: http://piltos.ru/ 
License: GPLv2 
*/



define('TESTING__PLUGIN_DIR', plugin_dir_path(__FILE__));

function create_product_posttype()
{
    register_post_type(
        'product',
        array(
            'labels' => array(
                'name' => __('Товар'),
                'singular_name' => __('Товар')
            ),
            'taxonomies' => array(CustomTaksonomies::TAX_CODE_ATTRIBUTES),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'product'),
            'publicly_queryable' => true
        )
    );
}

require_once TESTING__PLUGIN_DIR . '/taksonomy.php';
require_once TESTING__PLUGIN_DIR . '/attributes.php';
// add_action('admin_init', 'my_admin');

// add_action('save_post', 'add_attributes', 10, 2);
add_action('init', 'create_product_posttype');
?>