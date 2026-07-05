$(document).ready(function () {
    var $slider = $('.specialties__slider');
    if ($slider.length && !$slider.hasClass('slick-initialized')) {
        $slider.slick({
            arrows: false,
            dots: true,
            appendDots: $('.specialties__dots'),
            infinite: true,
            speed: 600,
            slidesToShow: 1,
            slidesToScroll: 1,
            fade: true,
            cssEase: 'ease-in-out',
            autoplay: true,
            autoplaySpeed: 4000,
            pauseOnHover: true,
            pauseOnFocus: true
        });
    }
});
