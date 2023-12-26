<?php

/**
 * Template Name: Katalog
 *
 * @package velocity
 */

get_header();
$container        = get_theme_mod('justg_container_type', 'container');
$search_query     = new WP_Query(array(
    'post_type'         => 'produk',
    'post_status'       => 'publish',
    'order'             => 'asc',
    'orderby'           => 'title',
    'posts_per_page'    => -1
));
?>

<div class="wrapper" id="page-wrapper">

    <div class="<?php echo esc_attr($container); ?>" id="content">

        <div class="row">

            <div class="content-area col order-2" id="primary">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <?php //the_title('<h1 class="entry-title fs-4 m-0">', '</h1>'); 
                    ?>
                    <span>
                        <a onclick="printContent('main')" class="btn btn-secondary text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                            </svg> Print
                        </a>
                    </span>
                </div>

                <main class="site-main mt-3" id="main">
                    <div class="print-area" id="content">
                        <?php if ($search_query->have_posts()) : ?>
                            <div class="row">
                                <?php while ($search_query->have_posts()) : $search_query->the_post(); ?>
                                    <?php
                                    require(VELOCITY_PRODUK_PLUGIN_DIR . 'inc/produk-content.php');
                                    ?>
                                <?php endwhile; ?>
                            </div>
                        <?php else : ?>
                            <div class="container text-center">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
                                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z" />
                                    </svg>
                                </span>
                                <h3 class="mt-2 mb-3"> Produk tidak ditemukan ! :(</h3>
                            </div>
                        <?php endif; ?>

                    </div>
                </main><!-- #main -->

            </div><!-- #primary -->

        </div>

    </div><!-- #content -->

</div><!-- #page-wrapper -->
<script>
    function printContent(element) {
        var printContents = document.getElementById(element).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
<?php
get_footer();
