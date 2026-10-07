<?php

/**
 * The template for displaying all single posts
 *
 * @package justg
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();

$container  = get_theme_mod('justg_container_type', 'container');
$container  = get_theme_mod('vsstem_container_type', $container);
$tambeli    = get_option('vdproduk_tombol_beli');

pietergoosen_update_post_views(get_the_ID());
$viewers    = pietergoosen_get_post_views(get_the_ID());
?>

<div class="wrapper wrapper-single-produk" id="single-wrapper">

    <div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">

        <div class="row">

            <!-- Do the left sidebar check -->
            <?php get_template_part('global-templates/left-sidebar-check'); ?>

            <main class="site-main" id="main">

                <?php while (have_posts()) :
                    the_post();
                    $viewers    = get_post_meta(get_the_ID(), 'hit', true);
                ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?php if (velocityproduk_get_gallery(get_the_ID())) : ?>
                                <?php echo do_shortcode('[slider-produk post_id="' . get_the_ID() . '"]'); ?>
                            <?php elseif (has_post_thumbnail()) : ?>
                                <?php echo get_the_post_thumbnail(get_the_ID(), 'full', array('class' => 'aligncenter w-100')); ?>
                            <?php else : ?>
                                <svg viewBox="0 0 300 250" style="background-color: #ececec;" xmlns="http://www.w3.org/2000/svg">
                                    <text x="125" y="120">No Image</text>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">

                            <h2> <?php echo get_the_title(); ?></h2>
                            <table class="table table-vdproduk mt-3 mt-md-4">
                                <tbody>
                                    <?php
                                    // Stok & kode produk hanya tampil bila diisi.
                                    $stok = trim((string) get_post_meta($post->ID, 'ak_stok', true));
                                    $kode = trim((string) get_post_meta($post->ID, 'ak_kode', true));
                                    $velharga    = (float) get_post_meta($post->ID, 'ak_harga', true);
                                    $velhargadis = (float) get_post_meta($post->ID, 'ak_harga_dis', true);
                                    ?>
                                    <?php if ($stok !== '') : ?>
                                        <tr>
                                            <td>Stock Produk</td>
                                            <td><?php echo esc_html($stok); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($kode !== '') : ?>
                                        <tr>
                                            <td>Kode Produk</td>
                                            <td><?php echo esc_html($kode); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td>Harga</td>
                                        <td><?php echo velocityproduk_harga_html($post->ID, false); ?></td>
                                    </tr>
                                    <?php if ($velharga > 0 && $velhargadis > 0) : ?>
                                        <tr>
                                            <td>Anda Hemat</td>
                                            <td>
                                                <?php
                                                $diskonper  = ($velhargadis / $velharga) * 100;
                                                $diskonjadi = 100 - $diskonper;
                                                echo number_format($diskonjadi, 1, ',', '') . '%';
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td>Kategori</td>
                                        <td>
                                            <?php
                                            $cats = get_the_terms(get_the_ID(), 'kategori');
                                            $cats = is_array($cats) ? $cats : [];
                                            foreach ($cats as $k => $cat) {
                                                echo '<a class="me-1 mr-1 mb-1" href="' . esc_url(get_category_link($cat->term_id)) . '">' . esc_html($cat->name) . '</a>, ';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Dilihat</td>
                                        <td>
                                            <?php
                                            echo $viewers . ' kali';
                                            ?>
                                        </td>
                                    </tr>
                                    <?php if ($tambeli != 'tidak') : ?>
                                        <tr>
                                            <td colspan="2">
                                                <?php echo vdproduk_beli(); ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                        </div>

                        <div class="col-md-12 pt-3">
                            <h5 class="judul-single">Detail Produk</h5>
                            <?php echo the_content(); ?>
                        </div>
                    </div>



                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) {

                        do_action('justg_before_comments');
                        comments_template();
                        do_action('justg_after_comments');
                    }
                    ?>

                <?php endwhile; ?>

            </main><!-- #main -->

        </div>

        <!-- Do the right sidebar check -->
        <?php get_template_part('global-templates/right-sidebar-check'); ?>

    </div><!-- .row -->

</div><!-- #content -->

</div><!-- #single-wrapper -->

<?php
get_footer();
