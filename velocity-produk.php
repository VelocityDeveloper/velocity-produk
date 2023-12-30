<?php

/**
 * Plugin Name: Velocity Produk
 * Plugin URI: http://velocitydeveloper.com/
 * Description: Hanya Untuk klien VelocityDeveloper.
 * Version: 2.0.2
 * Author: Velocity Developer
 * Author URI: http://velocitydeveloper.com/
 * License: Dilarang menggunakan plugin ini tanpa izin dari velocitydeveloper.com, plugin ini hanya digunakan untuk produk dari Velocity Developer
 * Copyright (C) 2015 Vel Dev
 * Plugin ini hanya akan berjalan dengan baik jika menggunakan template
 * dari velocity developer. Jika ada masalah dengan pengelolaan website
 * mohon kontak team support kami dengan mengirim email ke 
 * revisiweb@gmail.com 
 * Terimakasih sudah mempercayakan kami untuk mengerjakan website anda.
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Define constants
 *
 * @since 2.0.0
 */
if (!defined('VELOCITY_PRODUK_VERSION'))        define('VELOCITY_PRODUK_VERSION', '2.0.2'); // Plugin version constant
if (!defined('VELOCITY_PRODUK_PLUGIN'))         define('VELOCITY_PRODUK_PLUGIN', trim(dirname(plugin_basename(__FILE__)), '/')); // Name of the plugin folder eg - 'velocity-toko'
if (!defined('VELOCITY_PRODUK_PLUGIN_DIR'))     define('VELOCITY_PRODUK_PLUGIN_DIR', plugin_dir_path(__FILE__)); // Plugin directory absolute path with the trailing slash. Useful for using with includes eg - /var/www/html/wp-content/plugins/velocity-produk/
if (!defined('VELOCITY_PRODUK_PLUGIN_URL'))     define('VELOCITY_PRODUK_PLUGIN_URL', plugin_dir_url(__FILE__)); // URL to the plugin folder with the trailing slash. Useful for referencing src eg - http://localhost/wp/wp-content/plugins/velocity-produk/

add_action('admin_enqueue_scripts', 'velocityproduk_admin_scripts');
function velocityproduk_admin_scripts($hook)
{
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        wp_enqueue_media();
        // Get the version.
        $the_version = VELOCITY_PRODUK_VERSION;
        wp_enqueue_style('velocityproduk-admin-style', VELOCITY_PRODUK_PLUGIN_URL . 'admin/css/admin-style.css', array(), $the_version, false);

        wp_enqueue_script('vdproduk-admin-script', VELOCITY_PRODUK_PLUGIN_URL . 'admin/js/admin-script.js');
    }
}

/**
 * function register asset css and js to frontend public.
 *
 * @package Velocity Produk
 */
if (!function_exists('velocityproduk_register_scripts')) {
    /**
     * Load theme's JavaScript and Style sources.
     */
    function velocityproduk_register_scripts()
    {
        // Get the version.
        $the_version = VELOCITY_PRODUK_VERSION;
        wp_enqueue_style('velocityproduk-style', VELOCITY_PRODUK_PLUGIN_URL . 'assets/style.min.css', array(), $the_version, false);

        // JS
        wp_enqueue_script('jquery');
        if (is_page('katalog')) :
            wp_enqueue_script('velocityproduk-printArea-script', VELOCITY_PRODUK_PLUGIN_URL . 'js/printArea.js', array('jquery', 'justg-scripts'), $the_version, true);
            wp_enqueue_script('velocityproduk-custom-script', VELOCITY_PRODUK_PLUGIN_URL . 'js/custom.js', array('jquery', 'justg-scripts'), $the_version, true);
        endif;
    }
    add_action('wp_enqueue_scripts', 'velocityproduk_register_scripts');
}

// Load everything
$includes = [
    'inc/produk.php',
    'inc/produk-metabox.php',
    'inc/produk-setting.php',
    'inc/pagination.php',
    'inc/shortcodes.php',
    'inc/beli.php',
    'inc/widget.php',
    'admin/register-page.php',
];
foreach ($includes as $include) {
    require_once(VELOCITY_PRODUK_PLUGIN_DIR . $include);
}


//single viewer
if (!function_exists('pietergoosen_get_post_views')) :

    function pietergoosen_get_post_views($postID)
    {
        if (is_singular('produk')) {
            $count_key = 'hit';
            $count = get_post_meta($postID, $count_key, true);
            if ($count == '') {
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, '0');
                return 0;
            }
        }
        return $count;
    }

endif;

// function to count views.
if (!function_exists('pietergoosen_update_post_views')) :

    function pietergoosen_update_post_views($postID)
    {
        if (!current_user_can('administrator')) {
            $user_ip = $_SERVER['REMOTE_ADDR']; //retrieve the current IP address of the visitor
            $key = $user_ip . 'x' . $postID; //combine post ID & IP to form unique key
            $value = array($user_ip, $postID); // store post ID & IP as separate values (see note)
            $visited = get_transient($key); //get transient and store in variable

            //check to see if the Post ID/IP ($key) address is currently stored as a transient
            if (false === ($visited)) {

                //store the unique key, Post ID & IP address for 12 hours if it does not exist
                set_transient($key, $value, 60 * 60 * 12);
                if (is_singular('produk')) {
                    // now run post views function
                    $count_key = 'hit';
                    $count = get_post_meta($postID, $count_key, true);
                    if ($count == '') {
                        $count = 0;
                        delete_post_meta($postID, $count_key);
                        add_post_meta($postID, $count_key, '0');
                    } else {
                        $count++;
                        update_post_meta($postID, $count_key, $count);
                    }
                }
            }
        }
    }

endif;

//replace Archive title
add_filter('get_the_archive_title', function ($title) {
    if (is_post_type_archive('produk')) {
        $title = post_type_archive_title('', false);
    }
    return $title;
});
