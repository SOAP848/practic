$(document).ready(function () {


    function loadStaticContent(section, callback) {
        $.ajax({
            url: 'api/static/' + section,
            method: 'GET',
            success: function (response) {
                if (response.success && response.data) {
                    callback(response.data);
                }
            },
            error: function () {
                console.warn('Failed to load static content for: ' + section);
            }
        });
    }

    loadStaticContent('about', function (data) {
        var $about = $('#about');
        $about.find('.section__title').text(data.title || 'About Us');
        if (data.subtitle) {
            $about.find('.about__lead').text(data.subtitle);
        }
        if (data.text) {
            $about.find('.about__desc').first().text(data.text);
        }
        if (data.image) {
            $about.find('.image-frame__img').attr('src', 'images/' + data.image);
        }
    });


    loadStaticContent('team', function (data) {
        var $team = $('#team');
        $team.find('.section__title').text(data.title || 'Master Chef');
        if (data.subtitle) {
            $team.find('.team__lead').text(data.subtitle);
        }
        if (data.text) {
            $team.find('.team__desc').first().text(data.text);
        }
        if (data.image) {
            $team.find('.image-frame__img').attr('src', 'images/' + data.image);
        }
    });

 
    loadStaticContent('events', function (data) {
        var $events = $('#events');
        $events.find('.events__label').text(data.title || 'Private Events');
        if (data.text) {
            $events.find('.events__info').text(data.text);
        }
    });

    function loadSpecialities() {
        $.ajax({
            url: 'api/specialities',
            method: 'GET',
            success: function (response) {
                if (response.success && response.data && response.data.length > 0) {
                    renderSpecialities(response.data);
                }
            },
            error: function () {
                console.warn('Failed to load specialities');
            }
        });
    }


    function renderSpecialities(items) {
        var $slider = $('.specialties__slider');
        $slider.empty();

        $.each(items, function (i, item) {
            var slide = '' +
                '<div class="specialties__slide">' +
                    '<div class="row align-items-center">' +
                        '<div class="col-lg-6">' +
                            '<div class="image-frame">' +
                                '<img class="image-frame__img" src="images/' + (item.image || 'image7.jpg') + '" alt="' + (item.title || 'Specialty') + '">' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-lg-6 specialties__content">' +
                            '<h3 class="specialties__title">' + (item.title || '') + '</h3>' +
                            '<div class="section__divider"></div>' +
                            '<p class="specialties__lead">' + (item.subtitle || '') + '</p>' +
                            '<p class="specialties__desc">' + (item.text || '') + '</p>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            $slider.append(slide);
        });

        if ($slider.hasClass('slick-initialized')) {
            $slider.slick('unslick');
        }
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
    function loadMenu(category) {
        var url = category && category !== 'all' ? 'api/menu/' + category : 'api/menu';

        $.ajax({
            url: url,
            method: 'GET',
            success: function (response) {
                if (response.success && response.data) {
                    renderMenu(response.data);
                }
            },
            error: function () {
                console.warn('Failed to load menu');
            }
        });
    }


    function renderMenu(items) {
        var $grid = $('.menu__grid');
        $grid.empty();

        if (items.length === 0) {
            $grid.html('<div class="col-12 text-center"><p>No items found</p></div>');
            return;
        }

        $.each(items, function (i, item) {
            var menuItem = '' +
                '<div class="col-md-4 menu__item" data-category="' + (item.category || '') + '">' +
                    '<div class="menu__dish">' +
                        '<span class="menu__dish-name">' + (item.title || '') + '</span>' +
                        '<span class="menu__dish-dots"></span>' +
                        '<span class="menu__dish-price">' + parseFloat(item.price).toFixed(2) + ' USD</span>' +
                    '</div>' +
                    '<p class="menu__dish-desc">' + (item.subtitle || '') + '</p>' +
                '</div>';
            $grid.append(menuItem);
        });
    }


    loadSpecialities();
    loadMenu('all');


    var $slider = $('.specialties__slider');
    if ($slider.length && !$slider.hasClass('slick-initialized') && $slider.children().length > 0) {
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

    var $header = $('.header');
    var scrollThreshold = 50;

    function toggleHeaderScroll() {
        if ($(window).scrollTop() > scrollThreshold) {
            $header.addClass('header--scrolled');
        } else {
            $header.removeClass('header--scrolled');
        }
    }

    toggleHeaderScroll();

    $(window).on('scroll', function () {
        toggleHeaderScroll();
    });


    $('.auth-modal__tab').on('click', function () {
        var tab = $(this).data('tab');

        $('.auth-modal__tab').removeClass('auth-modal__tab--active');
        $(this).addClass('auth-modal__tab--active');

        $('.auth-modal__panel').removeClass('auth-modal__panel--active');
        $('.auth-modal__panel[data-panel="' + tab + '"]').addClass('auth-modal__panel--active');
    });

    $('#signinForm').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var $btn = $form.find('.auth-modal__btn');
        $btn.prop('disabled', true).text('Signing in...');

        $.ajax({
            url: 'api/auth/signin',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                email: $form.find('[name="email"]').val(),
                password: $form.find('[name="password"]').val()
            }),
            success: function (response) {
                if (response.success) {
                    alert('Welcome back, ' + response.data.name + '!');
                    $('#authModal').modal('hide');
                    updateAuthUI(response.data);
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                var resp = xhr.responseJSON;
                alert(resp && resp.message ? resp.message : 'Sign in failed');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Sign In');
            }
        });
    });


    $('#signupForm').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var $btn = $form.find('.auth-modal__btn');
        $btn.prop('disabled', true).text('Signing up...');

        $.ajax({
            url: 'api/auth/signup',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                fullname: $form.find('[name="fullname"]').val(),
                email: $form.find('[name="email"]').val(),
                password: $form.find('[name="password"]').val(),
                confirm_password: $form.find('[name="confirm_password"]').val(),
                phone: $form.find('[name="phone"]').val()
            }),
            success: function (response) {
                if (response.success) {
                    alert('Registration successful! Welcome, ' + response.data.name + '!');
                    $('#authModal').modal('hide');
                    updateAuthUI(response.data);
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                var resp = xhr.responseJSON;
                alert(resp && resp.message ? resp.message : 'Registration failed');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Sign up');
            }
        });
    });


    $('#bookingForm').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var $btn = $form.find('.booking__submit');
        $btn.prop('disabled', true).text('Booking...');

        $.ajax({
            url: 'api/booking',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                name: $form.find('[name="name"]').val(),
                email: $form.find('[name="email"]').val(),
                phone: $form.find('[name="phone"]').val(),
                guests: $form.find('[name="guests"]').val(),
                date: $form.find('[name="date"]').val(),
                time: $form.find('[name="time"]').val()
            }),
            success: function (response) {
                if (response.success) {
                    alert('Table booked successfully! Check your email for confirmation.');
                    $form[0].reset();
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                var resp = xhr.responseJSON;
                alert(resp && resp.message ? resp.message : 'Booking failed');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Book Now');
            }
        });
    });


    $('#contactForm').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var $btn = $form.find('.contact__submit');
        $btn.prop('disabled', true).text('Sending...');

        $.ajax({
            url: 'api/contact',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                name: $form.find('[name="name"]').val(),
                email: $form.find('[name="email"]').val(),
                phone: $form.find('[name="phone"]').val(),
                message: $form.find('[name="message"]').val()
            }),
            success: function (response) {
                if (response.success) {
                    alert('Message sent successfully! We will get back to you soon.');
                    $form[0].reset();
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                var resp = xhr.responseJSON;
                alert(resp && resp.message ? resp.message : 'Failed to send message');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Send Message');
            }
        });
    });


    $('.menu__filters').on('click', '.menu__filter', function (e) {
        e.preventDefault();

        var category = $(this).data('category');

        $('.menu__filter').removeClass('menu__filter--active');
        $(this).addClass('menu__filter--active');

        loadMenu(category);
    });

    function checkAuth() {
        $.ajax({
            url: 'api/auth/check',
            method: 'GET',
            success: function (response) {
                if (response.success) {
                    updateAuthUI(response.data);
                }
            }
        });
    }

    function updateAuthUI(user) {
        var $loginLinks = $('.header__link[data-toggle="modal"]');
        $loginLinks.each(function () {
            $(this).text(user.name);
            $(this).removeAttr('data-toggle data-target');
            $(this).attr('href', '#');
            $(this).on('click', function (e) {
                e.preventDefault();
                if (confirm('Do you want to sign out?')) {
                    logout();
                }
            });
        });
    }

    function logout() {
        $.ajax({
            url: 'api/auth/logout',
            method: 'GET',
            success: function () {
                location.reload();
            }
        });
    }

    checkAuth();


    var $scrollTop = $('.scroll-top');

    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 300) {
            $scrollTop.addClass('scroll-top--visible');
        } else {
            $scrollTop.removeClass('scroll-top--visible');
        }
    });

    $scrollTop.on('click', function () {
        $('html, body').animate({ scrollTop: 0 }, 500);
    });

});
