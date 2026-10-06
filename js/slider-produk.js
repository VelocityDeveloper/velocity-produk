/**
 * Slider galeri produk [slider-produk].
 * Butuh Slick (slider) & Magnific Popup (popup) - dimuat oleh plugin bila tema/addons belum memuat.
 */
jQuery(function ($) {
    $('.velocityproduk-slider-produk').each(function () {
        var $wrap = $(this);
        var $big = $wrap.find('.bigslide');
        var $nav = $wrap.find('.navigasi');
        var vertical = $wrap.data('vertical') === true || $wrap.data('vertical') === 'true';

        if (!$big.length || $big.hasClass('slick-initialized')) {
            return;
        }

        var width = $wrap.width();
        if (width) {
            $wrap.css('max-width', width);
        }

        if ($.fn.slick) {
            $big.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                asNavFor: $nav.length ? $nav : null,
                autoplay: false,
            });
            if ($nav.length) {
                $nav.slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    asNavFor: $big,
                    focusOnSelect: true,
                    arrows: true,
                    autoplay: false,
                    vertical: vertical,
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                vertical: false,
                            }
                        }
                    ],
                });
            }
        }

        if ($.fn.magnificPopup) {
            $big.magnificPopup({
                delegate: 'a:not(.slick-cloned)',
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        }
    });
});
