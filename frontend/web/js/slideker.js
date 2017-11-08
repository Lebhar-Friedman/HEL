/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function ($) {
    $(document).ready(function () {

    });

    $.fn.slideker = function (options) {

        // This is the easiest way to have default options.
        var settings = $.extend({
            // These are the defaults.
            slideAffect: 'slide-left',
            animationTime: 1000,
            animationDelay: 2000,
        }, options);

        console.log($(this).length);
        $(this).each(function () {
            console.log($(this).children().length);
            var slider = $(this);
            var sliderWidth = $(this).css('width');
            var noOfSlides = $(this).children().length;
            var slideWidth = 100 / (noOfSlides + 1) + '%';
            var firstSlide = $(this).children(':first-child');
            slider.html('<div class="slider-container" style="width:' + (100 * (noOfSlides + 1)) + '%;height:100%;position: relative;">' + slider.html() + '</div>');

            slider.children('.slider-container').append(firstSlide);
            slider.children('.slider-container').children().css('float', 'left');
            slider.children('.slider-container').children().css('width', slideWidth);
            setInterval(function () {
                slideLeft(slider);
            }, settings.animationDelay);
        });

        function slideLeft(slider) {
            var noOfSlides = slider.children('.slider-container').children().length;
            var maxwidth = parseInt(slider.children('.slider-container').css('width'));
            var slideWidth = (maxwidth / noOfSlides);
            slider.children('.slider-container').animate({
                left: '-=' + slideWidth + 'px',
            }, settings.animationTime,
                    function () {
                        if (parseInt($(this).css('left')) <= parseInt('-' + (maxwidth - slideWidth))) {
                            $(this).css('left', '0px');
                        }
                    });
        }
    };

}(jQuery));