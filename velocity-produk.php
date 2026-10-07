<?php

/**
 * Plugin Name: Velocity Produk
 * Plugin URI: http://velocitydeveloper.com/
 * Description: Hanya Untuk klien VelocityDeveloper.
 * Version: 2.1.2
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
if (!defined('VELOCITY_PRODUK_VERSION'))        define('VELOCITY_PRODUK_VERSION', '2.1.2'); // Plugin version constant
if (!defined('VELOCITY_PRODUK_PLUGIN'))         define('VELOCITY_PRODUK_PLUGIN', trim(dirname(plugin_basename(__FILE__)), '/')); // Name of the plugin folder eg - 'velocity-toko'
if (!defined('VELOCITY_PRODUK_PLUGIN_DIR'))     define('VELOCITY_PRODUK_PLUGIN_DIR', plugin_dir_path(__FILE__)); // Plugin directory absolute path with the trailing slash. Useful for using with includes eg - /var/www/html/wp-content/plugins/velocity-produk/
if (!defined('VELOCITY_PRODUK_PLUGIN_URL'))     define('VELOCITY_PRODUK_PLUGIN_URL', plugin_dir_url(__FILE__)); // URL to the plugin folder with the trailing slash. Useful for referencing src eg - http://localhost/wp/wp-content/plugins/velocity-produk/

add_action('admin_enqueue_scripts', 'velocityproduk_admin_scripts');
function velocityproduk_admin_scripts($hook)
{
    if (($hook === 'post.php' || $hook === 'post-new.php') && get_post_type() === 'produk') {
        wp_enqueue_media();
        // Get the version.
        $the_version = VELOCITY_PRODUK_VERSION;
        wp_enqueue_style('velocityproduk-admin-style', VELOCITY_PRODUK_PLUGIN_URL . 'admin/css/admin-style.css', array(), $the_version, false);

        wp_enqueue_script('vdproduk-admin-script', VELOCITY_PRODUK_PLUGIN_URL . 'admin/js/admin-script.js', array('jquery'), $the_version, true);
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

        // Aset galeri: Slick & Magnific Popup (sebelumnya hanya numpang dari child theme / VD Gallery)
        wp_register_style('slick-style', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css', array(), '1.8.1');
        wp_register_style('slick-style-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css', array('slick-style'), '1.8.1');
        wp_register_script('slick-scripts', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array('jquery'), '1.8.1', true);
        wp_register_style('magnific-popup-styles', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css', array(), '1.1.0');
        wp_register_script('magnific-popup-script', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array('jquery'), '1.1.0', true);
        wp_register_script('velocityproduk-slider', VELOCITY_PRODUK_PLUGIN_URL . 'js/slider-produk.js', array('jquery'), $the_version, true);
    }
    add_action('wp_enqueue_scripts', 'velocityproduk_register_scripts');
}

/**
 * Cek apakah pustaka (slick/magnific) sudah dimuat tema atau plugin lain, berdasarkan handle/src.
 */
function velocityproduk_asset_loaded($type, $pattern)
{
    $deps = $type === 'style' ? wp_styles() : wp_scripts();
    foreach (array_merge($deps->queue, $deps->done) as $handle) {
        $src = isset($deps->registered[$handle]) ? (string) $deps->registered[$handle]->src : '';
        if (preg_match($pattern, $handle . ' ' . $src)) {
            return true;
        }
    }
    return false;
}

/**
 * Muat aset slider galeri produk tanpa menggandakan pustaka yang sudah ada.
 */
function velocityproduk_enqueue_gallery_assets()
{
    if (!wp_script_is('velocityproduk-slider', 'registered')) {
        return;
    }
    if (!velocityproduk_asset_loaded('script', '/slick(\.min)?\.js/i')) {
        wp_enqueue_script('slick-scripts');
    }
    if (!velocityproduk_asset_loaded('style', '/slick(\.min)?\.css/i')) {
        wp_enqueue_style('slick-style');
        wp_enqueue_style('slick-style-theme');
    }
    if (!velocityproduk_asset_loaded('script', '/magnific-popup(\.min)?\.js/i')) {
        wp_enqueue_script('magnific-popup-script');
    }
    if (!velocityproduk_asset_loaded('style', '/magnific-popup(\.min)?\.css/i')) {
        wp_enqueue_style('magnific-popup-styles');
    }
    wp_enqueue_script('velocityproduk-slider');
}

// Single produk: muat di <head> agar CSS slider tidak telat (prioritas 99 = sesudah tema & addons)
add_action('wp_enqueue_scripts', function () {
    if (is_singular('produk') && velocityproduk_get_gallery(get_queried_object_id())) {
        velocityproduk_enqueue_gallery_assets();
    }
}, 99);

/**
 * ID gambar galeri produk yang masih ada di Media.
 */
function velocityproduk_get_gallery($post_id)
{
    $gallery = get_post_meta($post_id, 'gallery_images', true);
    if (empty($gallery) || !is_array($gallery)) {
        return [];
    }
    return array_values(array_filter(array_map('absint', $gallery), 'wp_attachment_is_image'));
}

/**
 * Harga produk siap tampil. Harga kosong/0 -> "Hubungi Admin" (bukan "Rp -").
 *
 * @param int  $post_id ID produk.
 * @param bool $kecil   Harga coret dibungkus <small> (kartu & widget).
 */
function velocityproduk_harga_html($post_id, $kecil = true)
{
    $harga  = (float) get_post_meta($post_id, 'ak_harga', true);
    $diskon = (float) get_post_meta($post_id, 'ak_harga_dis', true);
    if ($harga <= 0 && $diskon <= 0) {
        return '<span class="vdproduk-hubungi-admin">' . esc_html__('Hubungi Admin', 'velocity-produk') . '</span>';
    }
    if ($harga <= 0) {
        return 'Rp ' . number_format($diskon, 2);
    }
    if ($diskon > 0) {
        $coret = '<s style="color: #c01a1a;">Rp ' . number_format($harga, 2) . '</s>';
        return ($kecil ? '<small>' . $coret . '</small>' : $coret) . ' Rp ' . number_format($diskon, 2);
    }
    return 'Rp ' . number_format($harga, 2);
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
