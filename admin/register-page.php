<?php

/**
 * Velocity Toko register page default.
 */
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

//register product template
add_filter('template_include', function ($template) {
    $page_template = get_post_meta(get_the_ID(), '_wp_page_template', true);
    if (is_singular('produk')) {
        $template = VELOCITY_PRODUK_PLUGIN_DIR . 'inc/single-produk.php';
    }
    if (is_post_type_archive('produk') || is_tax('kategori')) {
        $template = VELOCITY_PRODUK_PLUGIN_DIR . 'inc/archive-produk.php';
    }
    if (is_singular()) {
        if ('velocityproduk-templates_katalog' === $page_template) {
            $template = VELOCITY_PRODUK_PLUGIN_DIR . 'inc/page-katalog.php';
        }
    }
    return $template;
});

function velocityproduk_templates_page($post_templates)
{

    $post_templates['velocityproduk-templates_katalog']   = __('Velocity Produk Katalog', 'velocity-produk');

    return $post_templates;
}
add_filter("theme_page_templates", 'velocityproduk_templates_page');

// Register Katalog Page
add_filter('after_setup_theme', 'velocity_create_katalog');
function velocity_create_katalog()
{
    $post_id        = -1;
    $slug           = 'katalog';
    $title          = 'Katalog';
    if (null == get_page_by_path($slug)) {
        $post_id = wp_insert_post(
            array(
                'comment_status'    =>    'closed',
                'ping_status'        =>    'closed',
                'post_author'        =>    '1',
                'post_name'            =>    $slug,
                'post_title'        =>    $title,
                'post_status'        =>    'publish',
                'post_type'            =>    'page',
                'page_template'        =>  'velocityproduk-templates_katalog'
            )
        );
    } else {
        $post_id = -2;
    }
}
