<?php

/**
 * Velocity Produk
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class VelocityProduk_Produk_Settings
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_custom_submenu'));
        add_action('admin_init', array($this, 'add_option'));
    }

    public function add_custom_submenu()
    {
        add_submenu_page(
            'edit.php?post_type=produk',
            'Velocity Produk Setting',
            'Pengaturan',
            'manage_options',
            'vdproduk-setting',
            array($this, 'render_custom_submenu')
        );
    }

    public function add_option()
    {
        register_setting('ak-produk-group', 'vdproduk_info_pemesanan');
        register_setting('ak-produk-group', 'vdproduk_tombol_beli');
        register_setting('ak-produk-group', 'vdproduk_label_diskon');
        register_setting('ak-produk-group', 'vdproduk_nowa');
        register_setting('ak-produk-group', 'vdproduk_pesan_wa');
    }

    public function render_custom_submenu()
    {
?>

        <div class="wrap">
            <h2>Setting Produk</h2>

            <form method="post" action="options.php">
                <?php settings_fields('ak-produk-group'); ?>
                <table class="form-table">

                    <tr valign="top">
                        <th scope="row">Tampilkan Tombol beli</th>
                        <td>
                            <?php $tombeli = get_option('vdproduk_tombol_beli', 'info'); ?>
                            <select name="vdproduk_tombol_beli">
                                <option value="tidak" <?php selected($tombeli == 'tidak'); ?>>Tidak</option>
                                <option value="info" <?php selected($tombeli == 'info'); ?>>Info Beli</option>
                                <option value="wa" <?php selected($tombeli == 'wa'); ?>>Link Whatsapp</option>
                            </select>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Informasi Pemesanan</th>
                        <td>
                            <?php
                            $content   = get_option('vdproduk_info_pemesanan', '<p>Untuk pemesanan silahkan hubungi kontak dibawah ini : </p> <p>Telepon : <br> Whatsapp :</p>');
                            $editor_id = 'vdproduk_info_pemesanan';
                            wp_editor($content, $editor_id);
                            ?>
                            <br />Pesan informasi jika tombol beli di klik
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Nomor Whatsapp</th>
                        <td>
                            <input type="text" name="vdproduk_nowa" value="<?php echo get_option('vdproduk_nowa'); ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Pesan Whatsapp</th>
                        <td>
                            <textarea class="large-text code" type="text" rows="8" cols="50" name="vdproduk_pesan_wa"><?php echo get_option('vdproduk_pesan_wa', 'Hallo saya mau tanya [nama-produk] di [link-produk]'); ?></textarea>
                            <br />Isi pesan di link whatsapp, gunakan kode [nama-produk] [link-produk]
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Tampilkan Label Diskon</th>
                        <td>
                            <?php $lab_disk = get_option('vdproduk_label_diskon', 'ya'); ?>
                            <select name="vdproduk_label_diskon">
                                <option value="ya" <?php selected($lab_disk == 'ya'); ?>>Ya</option>
                                <option value="tidak" <?php selected($lab_disk == 'tidak'); ?>>Tidak</option>
                            </select>
                        </td>
                    </tr>

                </table>

                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>

            </form>
            <h3>Info</h3>

            <p>
                *Email untuk mengirim invoice kepada klien.
            </p>

            <p>
                *Jika menemukan masalah pada plugin ini silahkan hubungi <a href="https://velocitydeveloper.com/" target="_blank">VelocityDeveloper.com</a> untuk mendapatkan bantuan.
            </p>
        </div>
<?php
    }
}

new VelocityProduk_Produk_Settings();
