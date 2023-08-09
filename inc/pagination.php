<?php

/**
 * function pagination for handle.
 *
 * @package Velocity Toko
 */

if (!function_exists('velocityproduk_pagination')) {
    /**
     * Load pagination
     */
    function velocityproduk_pagination($args = null)
    {

        if (function_exists('justg_pagination')) {
            justg_pagination();
        } elseif (function_exists('vsstem_pagination')) {
            vsstem_pagination();
        } else {

            if (!isset($args['total']) && $GLOBALS['wp_query']->max_num_pages <= 1) {
                return;
            }

            $args = wp_parse_args(
                $args,
                array(
                    'mid_size'           => 2,
                    'prev_next'          => true,
                    'prev_text'          => __('&laquo;', 'justg'),
                    'next_text'          => __('&raquo;', 'justg'),
                    'type'               => 'array',
                    'current'            => max(1, get_query_var('paged')),
                    'screen_reader_text' => __('Posts navigation', 'justg'),
                )
            );

            $links = paginate_links($args);
            if (!$links) {
                return;
            }

?>

            <nav aria-labelledby="posts-nav-label">

                <h2 id="posts-nav-label" class="sr-only">
                    <?php echo esc_html($args['screen_reader_text']); ?>
                </h2>

                <ul class="pagination">

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
}
