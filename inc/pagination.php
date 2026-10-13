<?php

/**
 * function pagination for handle.
 *
 * @package Velocity Toko
 */

if (!function_exists('velocityproduk_pagination')) {
    /**
     * Load pagination
     *
     * Markup sendiri (Bootstrap 5), tidak memakai justg_pagination tema:
     * judul "Posts navigation" berkelas sr-only di tema tampil sebagai teks
     * karena kelas itu tidak dikenal Bootstrap 5.
     */
    function velocityproduk_pagination($args = null)
    {

        if (!isset($args['total']) && $GLOBALS['wp_query']->max_num_pages <= 1) {
            return;
        }

        $args = wp_parse_args(
            $args,
            array(
                'mid_size'  => 2,
                'prev_next' => true,
                'prev_text' => __('&laquo;', 'justg'),
                'next_text' => __('&raquo;', 'justg'),
                'type'      => 'array',
                'current'   => max(1, get_query_var('paged')),
            )
        );

        $links = paginate_links($args);
        if (!$links) {
            return;
        }

?>

        <nav class="velocityproduk-pagination my-4" aria-label="<?php esc_attr_e('Halaman produk', 'justg'); ?>">

            <ul class="pagination justify-content-center flex-wrap">

                <?php
                foreach ($links as $key => $link) {
                ?>
                    <li class="page-item <?php echo strpos($link, 'current') ? 'active' : ''; ?>">
                        <?php echo str_replace('page-numbers', 'page-link', $link); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                        ?>
                    </li>
                <?php
                }
                ?>

            </ul>

        </nav>

<?php
    }
}
