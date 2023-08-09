<article <?php post_class('col-md-4 col-xl-3 col-6 pb-2 mb-3'); ?> id="post-<?php the_ID(); ?>">
    <div class="card h-100 card-produk">

        <div class="position-relative">
            <div class="card-image ratio-thumb-image">
                <?php if (has_post_thumbnail()) : ?>
                    <?php echo get_the_post_thumbnail(get_the_ID(), 'medium', array('class' => 'card-img-top', 'loading' => 'lazy')); ?>
                <?php else : ?>
                    <svg viewBox="0 0 250 250" class="card-img-top" style="background-color: #ececec;" xmlns="http://www.w3.org/2000/svg">
                    </svg>
                <?php endif; ?>
            </div>

            <?php
            $lab_disk       = get_option('vdproduk_label_diskon', 'ya');
            $velharga       = get_post_meta($post->ID, 'ak_harga', true);
            $velhargadis    = get_post_meta($post->ID, 'ak_harga_dis', true);
            if ($lab_disk == 'ya' && $velharga && $velhargadis) {
                $diskonper = ($velhargadis / $velharga) * 100;
                $diskonjadi = 100 - $diskonper;
                echo '<div class="label-diskon"><span>' . number_format($diskonjadi, 1, ',', '') . '%</span></div>';
            }
            ?>
        </div>

        <div class="card-body text-center">
            <div class="mb-2">
                <strong>
                    <a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a>
                </strong>
            </div>
            <div class="row">
                <div class="col-12 my-2">
                    <?php
                    echo $velhargadis ? '<small><s color: #c01a1a;> Rp ' : 'Rp ';
                    echo $velharga ? number_format($velharga, 2) : '-';
                    echo $velhargadis ? '</s></small> Rp ' . number_format($velhargadis, 2) : '';
                    ?>
                </div>
                <div class="col-12">
                    <a href="<?php echo get_the_permalink(); ?>" class="btn btn-sm btn-dark px-3 text-white">
                        Beli
                    </a>
                </div>
            </div>
        </div>
    </div>
</article><!-- #post-## -->