function GalleryImages() {
    jQuery.each(jQuery('.img'), function () {
        var background = jQuery(this).data('background');
        jQuery(this).css('background-image', 'url('+background+')')
    })
}
var ImageZoom =  {
    init: function(jQueryblock, isChosen, isArchive) {
        var jQuerycnt = jQuery('#image-zoom');
        var jQuerycntInner = jQuery('.image-zoom-inner', jQuerycnt);
        var jQueryimg = jQuery('.main-img', jQuerycntInner);
        var imgUrl = jQueryblock.data('background');
        var jQuerystarButton = jQuery('.star-button', jQuerycntInner);

        function hideImageZoom() {
            jQuerycnt.removeClass('visible');
        }
        if (isArchive) {
            jQuerystarButton.hide();
        }
        isChosen
            ? jQuerystarButton.addClass('chosen')
            : jQuerystarButton.removeClass('chosen');

        jQuerycnt.addClass('visible');
        jQueryimg.attr('src', imgUrl);

        jQuery('.close').on('click', function () {
            hideImageZoom();
        });

        jQuery('.next-img').on('click', function () {
            if (jQueryblock.parent().is(':last-child')) {
                return false;
            }

            (jQueryblock.parent().next().children('svg').hasClass('chosen'))
                ? jQuery('svg', jQuerycntInner).addClass('chosen')
                : jQuery('svg', jQuerycntInner).removeClass('chosen');
            jQueryblock = jQueryblock.parent().next().children('.img');
            imgUrl = jQueryblock.data('background');
            jQueryimg.attr('src', imgUrl);
        });

        jQuery('.prev-img').on('click', function () {
            if (jQueryblock.parent().is(':first-child')) {
                return false;
            }

            (jQueryblock.parent().prev().children('svg').hasClass('chosen'))
                ? jQuery('svg', jQuerycntInner).addClass('chosen')
                : jQuery('svg', jQuerycntInner).removeClass('chosen');
            jQueryblock = jQueryblock.parent().prev().children('.img');
            imgUrl = jQueryblock.data('background');
            jQueryimg.attr('src', imgUrl);
        });

        jQuery('.star-button', jQuerycntInner).on('click', function () {
            var jQuerygalleryBlock = jQuery('#gallery-images');

            if ((jQuery('.chosen', jQuerygalleryBlock).length < 3) || jQuery(this).hasClass('chosen')) {
                jQuery(this).toggleClass('chosen');
                jQueryblock.siblings().toggleClass('chosen');
            }

            if (jQuery('.chosen', jQuerygalleryBlock).length < 3) {
                jQuery('#show-form').removeClass('active');
            } else {
                jQuery('#show-form').addClass('active');
            }
        });

        jQuery(document).mouseup(function (e) {
            if (jQuerycnt.has(e.target).length === 0) {
                hideImageZoom();
            }
        });
    }
};

jQuery(document).ready(function () {
    GalleryImages();
    var jQuerygalleryBlock = jQuery('#gallery-images');


    jQuery('.img').on('click', function () {
        var isChosen = false;
        if (jQuery(this).siblings().hasClass('chosen')) {
            isChosen = true;
        }
        if (jQuery(this).closest('#archive-gallery')) {
            ImageZoom.init(jQuery(this), isChosen, true)
        }
        ImageZoom.init(jQuery(this), isChosen);
    });

    jQuery('.star-button', jQuerygalleryBlock).on('click', function () {
        if ((jQuery('.chosen', jQuerygalleryBlock).length < 3) || jQuery(this).hasClass('chosen')) {
            jQuery(this).toggleClass('chosen');
        }

        if (jQuery('.chosen', jQuerygalleryBlock).length < 3) {
            jQuery('#show-form').removeClass('active');
        } else {
            jQuery('#show-form').addClass('active');
        }
    });

    jQuery('#show-full-rules').on('click', function (e) {
        e.preventDefault();

        jQuery(this).hide().parent().siblings('.hidden').slideDown();
    });

    jQuery('.show-form-button').on('click', function () {
        if (!jQuery(this).hasClass('active')) {
            return false;
        }
        jQuery(this).hide();
        jQuery('#main-form').slideDown(300);
    });

    jQuery('#show-archiv').on('click', function (e) {
        e.preventDefault();

        jQuery('#archive-gallery').slideDown(200);
        jQuery(this).hide();
    })
});