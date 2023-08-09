<?php

/**
 * Velocity Produk
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class VelocityProduct_Produk_Metabox
{
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_custom_metabox'));
        add_action('save_post', array($this, 'save_custom_metabox'));
    }

    public function add_custom_metabox()
    {
        add_meta_box(
            'velocityproduk_metabox',
            'Detail Produk',
            array($this, 'render_custom_metabox'),
            'produk',
            'normal',
            'default'
        );
    }

    public function render_custom_metabox($post)
    {
        // Ambil nilai yang sudah tersimpan jika ada
        $harga  = get_post_meta($post->ID, 'ak_harga', true);
        $diskon = get_post_meta($post->ID, 'ak_harga_dis', true);
        $kode   = get_post_meta($post->ID, 'ak_kode', true);
        $stok   = get_post_meta($post->ID, 'ak_stok', true);

        // Tampilkan input field di dalam metabox
        echo '<table>';
        echo '<tbody>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="ak_stok">';
        _e('Stok produk : ', 'vdprodukdetail');
        echo '</label> ';
        echo '</td>';
        echo '<td>';
        echo '<input type="text" id="ak_stok" name="ak_stok" value="' . esc_attr($stok) . '" size="25" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="ak_kode">';
        _e('Kode produk :', 'vdprodukdetail');
        echo '</label> ';
        echo '</td>';
        echo '<td>';
        echo '<input type="text" id="ak_kode" name="ak_kode" value="' . esc_attr($kode) . '" size="25" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="ak_harga">';
        _e('Harga produk :', 'vdprodukdetail');
        echo '</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="number" id="ak_harga" name="ak_harga" value="' . esc_attr($harga) . '" size="25" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="ak_harga_dis">';
        _e('Harga produk Diskon :', 'vdprodukdetail');
        echo '</label>';
        echo '<td>';
        echo '<input type="number" id="ak_harga_dis" name="ak_harga_dis" value="' . esc_attr($diskon) . '" size="25" />';
        echo '</td>';
        echo '</tr>';

        echo '</tbody>';
        echo '</table>';

        wp_nonce_field('velocityproduk_metabox_nonce', 'velocityproduk_metabox_nonce');
    }

    public function save_custom_metabox($post_id)
    {
        // Periksa apakah aksi penyimpanan diaktifkan
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        // Periksa apakah nonce sudah divalidasi
        if (!isset($_POST['velocityproduk_metabox_nonce']) || !wp_verify_nonce($_POST['velocityproduk_metabox_nonce'], 'velocityproduk_metabox_nonce')) return;

        // Periksa apakah pengguna memiliki izin untuk menyimpan
        if (!current_user_can('edit_post', $post_id)) return;

        // Simpan nilai input ke dalam database
        if (isset($_POST['ak_stok'])) {
            update_post_meta($post_id, 'ak_stok', sanitize_text_field($_POST['ak_stok']));
        }
        if (isset($_POST['ak_kode'])) {
            update_post_meta($post_id, 'ak_kode', sanitize_text_field($_POST['ak_kode']));
        }
        if (isset($_POST['ak_harga'])) {
            update_post_meta($post_id, 'ak_harga', sanitize_text_field($_POST['ak_harga']));
            update_post_meta($post_id, 'ak_harga_fix', sanitize_text_field($_POST['ak_harga']));
        }
        if (isset($_POST['ak_harga_dis'])) {
            update_post_meta($post_id, 'ak_harga_dis', sanitize_text_field($_POST['ak_harga_dis']));
            update_post_meta($post_id, 'ak_harga_fix', sanitize_text_field($_POST['ak_harga_dis']));
        }
    }
}

new VelocityProduct_Produk_Metabox();
