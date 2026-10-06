<?php
add_shortcode('slider-produk', function($atts){
    global $post;

    $atribut = shortcode_atts(array(
        'width'         => '600',
        'height'        => '600',
        'crop'          => 'true',
        'upscale'       => 'true',
        'nav-vertical'  => 'true',
        'post_id'       => isset($post->ID) ? $post->ID : 0,
    ), $atts);
    $nodeid     = uniqid();
    $post_id    = absint($atribut['post_id']);
    $width      = absint($atribut['width']) ?: 600;
    $height     = absint($atribut['height']) ?: 600;
    $navertical = $atribut['nav-vertical'] === 'false' ? 'false' : 'true';
    $gallery    = velocityproduk_get_gallery($post_id);
    $ratio      = ($height / $width) * 100;

    velocityproduk_enqueue_gallery_assets();

    $dataimg    = [];
    foreach ($gallery as $image_id) {
        $dataimg[]  = [
            'id'    => $image_id,
            'full'  => wp_get_attachment_image_url($image_id, 'full'),
            'thumb' => wp_get_attachment_image_url($image_id, 'thumbnail'),
            'img'   => wp_get_attachment_image_url($image_id, [$width, $height]),
        ];
    }

    $html   = '<div class="velocityproduk-slider-produk overflow-hidden velocityproduk-' . $nodeid . '" nodeid="' . $nodeid . '" data-vertical="' . $navertical . '">';

    if ($dataimg) {
        $html .= '<div class="row">';

            $lp = $navertical == 'true' ? 'col-md-8 col-xl-9' : 'col-12';
            $html .= '<div class="col-parent ' . $lp . '">';
                $html .= '<div class="slick-inarrow position-relative slider-produk">';
                    $html .= '<div class="id-' . $post_id . ' bigslide">';
                    foreach ($dataimg as $image) {
                        $html .= '<a class="d-inline-block" href="' . esc_url($image['full']) . '">';
                            $html .= '<div class="ratio ratio-1x1" style="--bs-aspect-ratio: ' . $ratio . '%;">';
                                $html .= '<img class="lazy img-float img-fluid w-100" alt="" src="' . esc_url($image['img']) . '" width="' . $width . '" height="' . $height . '">';
                            $html .= '</div>';
                        $html .= '</a>';
                    }
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</div>';

            $ln = $navertical == 'true' ? 'col-md-3 col-xl-2' : 'col-12';
            $html .= '<div class="col-navigasi mt-1 ' . $ln . '">';
                $html .= '<div class="navigasi navid-' . $post_id . '">';
                foreach ($dataimg as $image) {
                    $html .= '<div class="px-1 pb-1">';
                    $html .= '<div class="ratio ratio-1x1">';
                    $html .= '<img class="lazy img-float img-fluid w-100" alt="" src="' . esc_url($image['thumb'] ?: $image['full']) . '" width="100" height="100">';
                    $html .= '</div>';
                    $html .= '</div>';
                }
                $html .= '</div>';
            $html .= '</div>';

        $html .= '</div>';
    } else {
        $html .= '<div class="slider-produk">';
            $html .= '<div class="id-' . $post_id . '">';
            $html .= '<div class="p-1"><svg style="background-color: #ececec;width: 100%;height: auto;" width="' . $width . '" height="' . $height . '"></svg></div>';
            $html .= '</div>';
        $html .= '</div>';
    }

    $html .= '</div>';

    return $html;
});
