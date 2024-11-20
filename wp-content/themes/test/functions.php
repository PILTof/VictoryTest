<?php
/**
 * Twenty Twenty-Two functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Testing
 * @subpackage TingingSub
 * @since Testing Testing-Two 1.0
 */

add_action('wp_enqueue_scripts', 'theme_add_scripts');
function theme_add_scripts()
{
    wp_enqueue_style('testing', get_stylesheet_uri());
    wp_enqueue_script('jquery3', 'https://code.jquery.com/jquery-3.7.1.min.js');
    wp_enqueue_script('testing', get_stylesheet_directory_uri() . '/script.js', array(), '3.0');

}

// Установка нужной страницы
function true_request($query)
{

    $url = urldecode($_SERVER['REQUEST_URI']);
    /**Установка страницы каталога */
    if (preg_match('/.*catalog.*/', $url)) {
        $query['pagename'] = 'catalog';
    }
    /**Установка страницы раздела каталога */
    if (preg_match('/\/catalog\/([A-z0-9-]+)/', $url)) {
        $arUrl = explode('/', $url);
        $arUrl = array_filter($arUrl, fn($q) => ($q !== 'catalog') && boolval($q));
        $query['pagename'] = 'catalog-sections';
        $_SERVER['sections_slug'] = implode(';', $arUrl);
    }

    return $query;
}

add_filter('request', 'true_request', 9999, 1);

// ajax post

add_action('wp_ajax_post_filter', 'post_filter_callback'); // For logged in users
add_action('wp_ajax_post_filter_category', 'post_filter_category'); // For logged in users
add_action('wp_ajax_nopriv_post_filter', 'post_filter_callback'); // For anonymous users
add_action('wp_ajax_nopriv_post_filter_category', 'post_filter_category'); // For anonymous users

function post_filter_callback()
{
    $_REQUEST['ajax'] = "Y";
    include __DIR__ . '/taxonomy-catalog.php';
    wp_die();
}
function post_filter_category()
{
    $_REQUEST['ajax'] = "Y";
    $_SERVER['sections_slug'] = $_REQUEST['sections_slug'];
    include __DIR__ . '/taxonomy-catalog-sections.php';
    wp_die();
}

?>