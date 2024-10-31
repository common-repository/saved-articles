<?php
/*
Plugin Name: saved_articles
Description: Plugin adds the user-selected articles to the list of favorites. The user can later open the saved articles and view them.
Version: 0.1
Author: Karpiuk Yurii
*/
require  __DIR__.'/function.php';

add_filter('the_content','ky_saved_articles_content');
add_action('wp_enqueue_scripts', 'ky_saved_articles_scripts');
add_action('admin_enqueue_scripts', 'ky_saved_articles_dashboard_scripts');
add_action('wp_ajax_ky_saved_articles_record', 'wp_ajax_ky_saved_articles_record');
add_action('wp_ajax_ky_saved_articles_dashboard_delete_all', 'wp_ajax_ky_saved_articles_dashboard_delete_all');
add_action('wp_dashboard_setup', 'ky_saved_articles_dashboard_widget');
register_uninstall_hook( __FILE__, 'ky_saved_articles_uninstall' );
