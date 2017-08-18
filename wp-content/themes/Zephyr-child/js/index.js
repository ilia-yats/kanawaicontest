function GalleryImages() {
    $.each($('.img'), function () {
        var background = $(this).data('background');
        $(this).css('background-image', 'url('+background+')')
    });
}

function IsActiveForm() {
    var $galleryBlock = $('#gallery-images');
    var $button = $('#show-form');

    if ($('.chosen', $galleryBlock).length < 1) {
        $button.removeClass('active');
    } else {
        $button.addClass('active');
    }
}

function starButtonAction($toggleClassBlock, $block, isImageZoom) {
    var $galleryBlock = $('#gallery-images');

    isImageZoom = isImageZoom || false;

    if ($toggleClassBlock.hasClass('chosen')) {
        chosenPicture.remove($block.attr('id'));
        $toggleClassBlock.toggleClass('chosen');
    } else if ($('.chosen', $galleryBlock).length < 3) {
        chosenPicture.add($block.attr('id'), $block.children('.img').data('background'));
        $toggleClassBlock.toggleClass('chosen');
    }
    if (isImageZoom) {
        $block.toggleClass('chosen');
    }

    IsActiveForm();
}

var modalWindow = {
    $close: null,
    $backgroundScreen: $('#background-screen'),
    show: function ($modal) {
        var self = this;
        var $currentModal = $($modal);
        this.$close = $('.close', $modal);
        $currentModal.addClass('active');
        self.$backgroundScreen.fadeIn(200);

        this.$close.on('click', function () {
            self.hide($modal);
        });
        $(document).mouseup(function (e) {
            if ($currentModal.has(e.target).length === 0) {
                self.hide($modal);
            }
        })
    },
    hide: function ($modal) {
        var $currentModal = $($modal);
        $currentModal.removeClass('active');
        this.$backgroundScreen.fadeOut(200);
        $(document).unbind('mouseup');
    }
};

var chosenPicture = {
    $cnt: $('#chosen-pictures'),
    $cntInner: $('.chosen-pictures-block', this.$cnt),
    add: function (imgId, imgSrc) {
        this.$cntInner.append('<img src="'+ imgSrc +'" class="'+ imgId +'">');
    },
    remove: function (imgId) {
        this.$cntInner.children('.'+imgId).remove();
    }
};

var ImageZoom =  {
    $cnt: null,
    $block: null,
    $img: null,
    $starButton: null,
    $cntInner: null,
    $imgText: null,
    $imgLink: null,
    $backgroundScreen: null,
    imgUrl: null,
    init: function() {
        var self = this;
        this.$cnt = $('#image-zoom');
        this.$cntInner = $('.image-zoom-inner', this.$cnt);
        this.$starButton = $('.star-button', this.$cntInner);
        this.$imgLink = $('#image-link', this.$cntInner);
        this.$img = $('.main-img', this.$cntInner);
        this.$imgText = $('#image-zoom-text', this.$cnt);
        this.$backgroundScreen = $('.background-screen');

        $('#next').click(function () {
            if (self.$block.is(':last-child')) {
                return false;
            }

            self.prevOrNext(true);
        });

        $('#prev').click(function () {
            if (self.$block.is(':first-child')) {
                return false;
            }

            self.prevOrNext(false);
        });


        $('#close').click(function () {
            self.hide();
        });

        $('.star-button', self.$cntInner).click(function () {
            starButtonAction($(this), self.$block, true);
        });
    },
    show: function ($block, imgLink) {
        var self = this;
        self.$block = $block;

        self.$backgroundScreen.fadeIn(200);
        self.$block.hasClass('chosen')
            ? self.$starButton.addClass('chosen')
            : self.$starButton.removeClass('chosen');

        self.$imgLink.attr('href', imgLink).text(imgLink);
        self.imgUrl = self.$block.children('.img').data('background');
        self.$cnt.addClass('visible');
        self.$img.attr('src', self.imgUrl);

        $(document).mouseup(function (e) {
            if (self.$cnt.has(e.target).length === 0) {
                self.hide();
            }
        });
    },
    hide: function () {
        this.$cnt.removeClass('visible');
        this.$cntInner.children().removeClass('active');
        this.$img.attr('src', '');
        this.$imgLink.text('');
        this.$backgroundScreen.fadeOut(200);
        $(document).unbind('mouseup');
    },
    prevOrNext: function (isNext) {
        var self = this;

        isNext ? self.$block = self.$block.next() : self.$block = self.$block.prev();
        (self.$block.hasClass('chosen'))
            ? $('svg', self.$cntInner).addClass('chosen')
            : $('svg', self.$cntInner).removeClass('chosen');
        if (self.$block.children('span').length !== 0) {
            self.$imgText.text(self.$block.children('span').text());
        }
        var imgLinkAttr = self.$block.children('a').attr('href');
        self.$imgLink.attr('href', imgLinkAttr).text(imgLinkAttr);
        self.imgUrl = self.$block.children('.img').data('background');
        self.$img.attr('src', self.imgUrl);
    }
};

$(document).ready(function () {
    var $mainBanner = $('.main-banner');
    var $galleryBlock = $('#gallery-images');
    var $imgBlock = $('.img-container');
    var $galleryShowButton = $('#gallery-show');
    var bannerCoefficient = 1459/641;
    var galleryCoefficient = 633/357;

    GalleryImages();
    ImageZoom.init();

    $mainBanner.height($mainBanner.width() / bannerCoefficient);
    $imgBlock.height($imgBlock.width() / galleryCoefficient);
    if ($galleryShowButton.hasClass('visible')) {
        $galleryBlock.height(($imgBlock.height() + 5) * 3);
    }

    $(window).resize(function() {
        $mainBanner.height($mainBanner.width() / bannerCoefficient);
        $imgBlock.height($imgBlock.width() / galleryCoefficient);
        if ($galleryShowButton.hasClass('visible')) {
            $galleryBlock.height(($imgBlock.height() + 5) * 3);
        }
    });

    $galleryShowButton.on('click', function () {
        var value = '100%';
        $(this).removeClass('visible').hide();
        $galleryBlock.height(value);
    });

    $('#main-form').on('click', 'button', function (e) {
        e.preventDefault();

        $('#main-form input').val('');
        setTimeout(function () {
            modalWindow.show('.thanks-for-vote-modal');
        }, 500)
    });

    $('.img').on('click', function () {
        var imgContainer = $(this).parent();
        var $imgZoomStarButton = $('#image-zoom .star-button');
        var $imgZoomText = $('#image-zoom-text');
        var imgLink = $(this).siblings('a').attr('href');

        if (imgContainer.hasClass('archive')) {
            $imgZoomText.text(imgContainer.children('span').text()).addClass('active');
        } else {
            $imgZoomText.removeClass('active');
            $imgZoomStarButton.addClass('active');
        }
        ImageZoom.show(imgContainer, imgLink);
    });

    $('.star-button', $galleryBlock).on('click', function () {
        var parent = $(this).parent();

        starButtonAction(parent, parent, false);
    });

    $('#show-full-rules').on('click', function (e) {
        e.preventDefault();

        $(this).hide().parent().siblings('.not-visible-p').slideDown();
    });

    $('.show-form-button').on('click', function () {
        if (!$(this).hasClass('active')) {
            return false;
        }
        $(this).hide();
        $('#main-form').slideDown(200);
    });

    $('#show-archive').on('click', function (e) {
        e.preventDefault();

        $('#archive-gallery').slideDown(200);
        $(this).hide();
    });
});