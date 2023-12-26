<?php

/**
 * Velocity Produk
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;


//register post
add_action('init', 'vd_produk_post_type');
function vd_produk_post_type()
{
    register_post_type('produk', array(
        'labels' => array(
            'name' => 'Produk',
            'singular_name' => 'produk',
            'add_new' => 'Tambah Produk Baru',
            'add_new_item' => 'Tambah Produk Baru',
            'edit_item' => 'Edit Produk',
            'view_item' => 'Lihat Produk',
            'search_items' => 'Cari Produk',
            'not_found' => 'Tidak ditemukan produk',
            'not_found_in_trash' => 'Tidak ada produk di kotak sampah'
        ),
        'public' => true,
        'menu_icon'           => 'dashicons-cart',
        'has_archive' => true,
        'taxonomies' => array('kategori'),
        'supports' => array(
            'title',
            'editor',
            'comments',
            'thumbnail'
        ),
    ));
}


//register taxonomy
add_action('init', 'vd_kategori_produk');
function vd_kategori_produk()
{
    register_taxonomy(
        'kategori',
        array('produk'),
        array(
            'label' => __('Kategori'),
            'rewrite' => array('slug' => 'kategori'),
            'hierarchical' => true,
        )
    );
}
