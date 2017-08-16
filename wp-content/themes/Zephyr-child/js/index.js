function GalleryImages() {
    $.each($('.img'), function () {
        var background = $(this).data('background');
        $(this).css('background-image', 'url('+background+')')
    })
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

var thanksModal = {
    $cnt: null,
    $close: null,
    init: function () {
        var self = this;

        this.$cnt = $('#thanks-for-vote-modal');
        this.$close = $('.close', this.$cnt);

        this.$close.on('click', function () {
            self.hide();
        });
    },
    show: function () {
        var self = this;

        this.$cnt.addClass('active');
        $(document).mouseup(function (e) {
            if (self.$cnt.has(e.target).length === 0) {
                self.hide();
            }
        })
    },
    hide: function () {
        this.$cnt.removeClass('active');
        $(document).unbind('mouseup');
    }
};

var ImageZoom =  {
    $cnt: null,
    $block: null,
    $img: null,
    $starButton: null,
    $cntInner: null,
    $imgText: null,
    imgUrl: null,
    init: function() {
        var self = this;
        this.$cnt = $('#image-zoom');
        this.$cntInner = $('.image-zoom-inner', this.$cnt);
        this.$starButton = $('.star-button', this.$cntInner);
        this.$img = $('.main-img', this.$cntInner);
        this.$imgText = $('#image-zoom-text', this.$cnt);

        $('#next').click(function () {
            if (self.$block.is(':last-child')) {
                return false;
            }

            self.nextImg();
        });

        $('#prev').click(function () {
            if (self.$block.is(':first-child')) {
                return false;
            }

            self.prevImg();
        });


        $('#close').click(function () {
            self.hide();
        });

        $('.star-button', self.$cntInner).click(function () {
            var $galleryBlock = $('#gallery-images');

            if (($('.chosen', $galleryBlock).length < 3) || $(this).hasClass('chosen')) {
                $(this).toggleClass('chosen');
                self.$block.toggleClass('chosen');
            }

            IsActiveForm();
        });
    },
    show: function ($block) {
        var self = this;
        self.$block = $block;

        self.$block.hasClass('chosen')
            ? self.$starButton.addClass('chosen')
            : self.$starButton.removeClass('chosen');

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
        $(document).unbind('mouseup');
    },
    nextImg: function () {
        var self = this;

        self.$block = self.$block.next();
        (self.$block.hasClass('chosen'))
            ? $('svg', self.$cntInner).addClass('chosen')
            : $('svg', self.$cntInner).removeClass('chosen');
        if (self.$block.children('span').length !== 0) {
            self.$imgText.text(self.$block.children('span').text());
        }
        self.imgUrl = self.$block.children('.img').data('background');
        self.$img.attr('src', self.imgUrl);
    },
    prevImg: function () {
        var self = this;

        self.$block = self.$block.prev();
        (self.$block.hasClass('chosen'))
            ? $('svg', self.$cntInner).addClass('chosen')
            : $('svg', self.$cntInner).removeClass('chosen');
        if (self.$block.children('span').length !== 0) {
            self.$imgText.text(self.$block.children('span').text());
        }
        self.imgUrl = self.$block.children('.img').data('background');
        self.$img.attr('src', self.imgUrl);
    }
};

$(document).ready(function () {
    GalleryImages();
    thanksModal.init();
    ImageZoom.init();
    var $galleryBlock = $('#gallery-images');


    $('.img').on('click', function () {
        var imgContainer = $(this).parent();
        var $imgZoomStarButton = $('#image-zoom .star-button');
        var $imgZoomText = $('#image-zoom-text');

        if (imgContainer.hasClass('archive')) {
            var text = imgContainer.children('span').text();
            $imgZoomText.text(text).addClass('active');
        } else {
            $imgZoomText.removeClass('active');
            $imgZoomStarButton.addClass('active');
        }
        ImageZoom.show(imgContainer);
    });
    $(window).resize(function(){
        $('.img-container').height($('.img-container').width());
    });
    $('.star-button', $galleryBlock).on('click', function () {
        if (($('.chosen', $galleryBlock).length < 3) || $(this).parent().hasClass('chosen')) {
            $(this).parent().toggleClass('chosen');
        }

        IsActiveForm();
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
    })
    $('#contest-link-block').on('click', 'svg', function () {
        $(this).parent().removeClass('active');
    });
});

