<?php
add_shortcode('slider-produk', function($atts){
    ob_start();
    
    global $post;
    $atribut = shortcode_atts(array(
        'width'         => '600',
        'height'        => '600',
        'crop'          => 'true',
        'upscale'       => 'true',
        'nav-vertical'  => 'true',
        'post_id'  		=> $post->ID,
    ), $atts);
    $nodeid     = uniqid();
    $post_id    = $atribut['post_id'];
    $width      = $atribut['width'];
    $height     = $atribut['height'];
    $crop       = $atribut['crop'];
    $crop       = $crop == 'true' ? true : false;
    $upscale    = $atribut['upscale'];
    $upscale    = $upscale == 'true' ? true : false;
    $navertical = $atribut['nav-vertical'];
    $gallery    = get_post_meta($post_id, 'gallery_images',true);
    $ratio      = ($height / $width) * 100;

    echo    '<div class="velocityproduk-slider-produk overflow-hidden velocityproduk-'.$nodeid.'" nodeid="'.$nodeid .'">';
    $html   = '';

    $dataimg    = [];

    if (!empty($gallery)) {
        foreach ($gallery as $image_id) {
            $imgfull    = wp_get_attachment_image_src($image_id, 'full');
            $urlfull    = $imgfull[0];
            $imgthumb   = wp_get_attachment_image_url($image_id, 'thumbnail');
            $imgset     = wp_get_attachment_image_url($image_id, [$width,$height]);


            $dataimg[]  = [
                'id'    => $image_id,
                'full'  => $urlfull,
                'thumb' => $imgthumb,
                'img'   => $imgset,
            ];
        }
    }

    if ($dataimg) {
        $html .= '<div class="row">';
        if ($dataimg) {

            $lp = $navertical=='true'?'col-md-8 col-xl-9':'col-12';
            $html .= '<div class="col-parent '.$lp.'">';
                $html .= '<div class="slick-inarrow position-relative slider-produk">';
                    $html .= '<div class="id-' . $post_id . ' bigslide">';
                    foreach ($dataimg as $image) {
                        $html .= '<a class="d-inline-block" href="' . $image['full'] . '">';
                            $html .= '<div class="ratio ratio-1x1" style="--bs-aspect-ratio: '.$ratio.'%;">';
                                $html .= '<img class="lazy img-float img-fluid w-100" alt="" src="' . $image['img'] . '" width="'.$width.'" height="'.$height.'">';
                            $html .= '</div>';
                        $html .= '</a>';
                    }
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</div>';

            if (!empty($gallery)) {
                $ln = $navertical=='true'?'col-md-3 col-xl-2':'col-12';
                $html .= '<div class="col-navigasi mt-1 '.$ln.'">';
                    $html .= '<div class="navigasi navid-' . $post_id . '">';
                    foreach ($dataimg as $image) {
                        $html .= '<div class="px-1 pb-1">';
                        $html .= '<div class="ratio ratio-1x1">';
                        $html .= '<img class="lazy img-float img-fluid w-100"  alt="" src="' . $image['thumb'] . '" width="100" height="100">';
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                $html .= '</div>';
            }
        }
        $html .= '</div>';
    } else {
        $html = '<div class="slider-produk">';
        $url = '<div class="p-1"><svg style="background-color: #ececec;width: 100%;height: auto;" width="' . $width . '" height="' . $height . '"></svg></div>';
            $html .= '<div class="id-' . $post_id . '">';
            $html .= $url;
            $html .= '</div>';
        $html .= '</div>';
    }
    echo $html;
?>
    <script>
        jQuery(function($) {
            $(document).ready(function(){
                var w<?php echo $nodeid; ?> = $('.velocityproduk-<?php echo $nodeid; ?>').width();
                if(w<?php echo $nodeid; ?>){
                    $('.velocityproduk-<?php echo $nodeid; ?>').css('max-width',w<?php echo $nodeid; ?>);
                    $('.velocityproduk-<?php echo $nodeid; ?> .bigslide').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        asNavFor: '.velocityproduk-<?php echo $nodeid; ?> .navigasi',
                        autoplay: false,
                    });
                    $('.velocityproduk-<?php echo $nodeid; ?> .navigasi').slick({
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        asNavFor: '.velocityproduk-<?php echo $nodeid; ?> .bigslide',
                        focusOnSelect: true,
                        arrows: true,
                        autoplay: false,
                        vertical: <?php echo $navertical; ?>,
                        responsive : [
                            { 
                                breakpoint: 768, 
                                settings: {                            
                                    vertical: false,
                                }
                            }
                        ],
                    });
                    $('.velocityproduk-<?php echo $nodeid; ?> .bigslide').magnificPopup({
                        delegate: 'a', // child items selector, by clicking on it popup will open
                        type: 'image',
                        gallery: {
                            enabled: true
                        }
                    });                    
                }
            });
        });
    </script>
    <?php
    echo '</div>';

    return ob_get_clean();
});