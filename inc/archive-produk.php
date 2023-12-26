<?php

/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package velocity produk
 */

get_header();
global $wp;
$container  = get_theme_mod('justg_container_type', 'container');
$container  = get_theme_mod('vsstem_container_type', $container);
?>

<div class="wrapper" id="archive-wrapper">

    <div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">


        <main class="site-main" id="main">

            <?php if (have_posts()) : ?>

                <header class="page-header">
                    <?php the_archive_description('<div class="taxonomy-description">', '</div>'); ?>
                </header><!-- .page-header -->

                <div class="velocityproduk-filter bg-light p-2 mb-3 text-right text-end">
                    <span>
                        <select name="select" onchange="if (this.value) window.location.href=this.value" class="border-dark px-2 py-1">
                            <option value="#" selected>Urutkan Berdasarkan</option>
                            <option value="<?php echo home_url($wp->request); ?>?orderby=title&order=asc">Nama, A &raquo; Z</option>
                            <option value="<?php echo home_url($wp->request); ?>?orderby=title&order=desc">Nama, A &raquo; Z</option>
                            <option value="<?php echo home_url($wp->request); ?>?orderby=date&order=asc">Tanggal, Lama &raquo; Terbaru</option>
                            <option value="<?php echo home_url($wp->request); ?>?orderby=date&order=desc">Tanggal, Terbaru &raquo; Lama</option>
                            <option value="<?php echo home_url($wp->request); ?>?orderby=meta_value_num&meta_key=ak_harga_fix&order=asc">Harga, Rendah &raquo; Tinggi</option>
                            <option value="<?php echo home_url($wp->request); ?>?orderby=meta_value_num&meta_key=ak_harga_fix&order=desc">Harga, Tinggi &raquo; Rendah</option>
                        </select>
                    </span>
                </div>

                <div class="row row-archive-produk align-self-stretch">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php
                        require(VELOCITY_PRODUK_PLUGIN_DIR . 'inc/produk-content.php');
                        ?>
                    <?php endwhile; ?>
                </div>

                <!-- The pagination component -->
                <?php velocityproduk_pagination(); ?>

            <?php else : ?>

                <div class="alert alert-secondary">
                    Tidak ada produk disini..
                </div>

            <?php endif; ?>

        </main><!-- #main -->


    </div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>

<script>
    jQuery(function($) {
        if ($('body.archive > .bg-abu-abu.py-5').length) {
            $('body.archive > .bg-abu-abu.py-5 > .container').html('<?php the_archive_title('<h3>', '</h3>'); ?>');
        }
    });
</script>