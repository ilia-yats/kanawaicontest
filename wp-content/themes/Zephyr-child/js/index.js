function GalleryImages() {
    $.each($('.img'), function () {
        var background = $(this).data('background');
        $(this).css('background-image', 'url('+background+')')
    })
}
var ImageZoom =  {
    $cnt: null,
    $block: null,
    $img: null,
    $starButton: null,
    $cntInner: null,
    imgUrl: null,
    init: function() {
        var self = this;
        this.$cnt = $('#image-zoom');
        this.$cntInner = $('.image-zoom-inner', this.$cnt);
        this.$starButton = $('.star-button', this.$cntInner);
        this.$img = $('.main-img', this.$cntInner);

        $('#next').click(function () {
            if (self.$block.parent().is(':last-child')) {
                return false;
            }

            self.nextImg();
        });

        $('#prev').click(function () {
            if (self.$block.parent().is(':first-child')) {
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
                self.$block.siblings().toggleClass('chosen');
            }

            if ($('.chosen', $galleryBlock).length < 3) {
                $('#show-form').removeClass('active');
            } else {
                $('#show-form').addClass('active');
            }
        });
    },
    show: function ($block, isChosen) {
        var self = this;
        self.$block = $block;

        isChosen
            ? this.$starButton.addClass('chosen')
            : this.$starButton.removeClass('chosen');

        this.imgUrl = self.$block.data('background');
        this.$cnt.addClass('visible');
        this.$img.attr('src', self.imgUrl);

        $(document).mouseup(function (e) {
            if (self.$cnt.has(e.target).length === 0) {
                self.hide();
            }
        });
    },
    hide: function () {
        this.$cnt.removeClass('visible');
        this.$img.attr('src', '');
        $(document).unbind('mouseup');
    },
    nextImg: function () {
        var self = this;

        (self.$block.parent().next().children('svg').hasClass('chosen'))
            ? $('svg', self.$cntInner).addClass('chosen')
            : $('svg', self.$cntInner).removeClass('chosen');
        self.$block = self.$block.parent().next().children('.img');
        self.imgUrl = self.$block.data('background');
        self.$img.attr('src', self.imgUrl);
    },
    prevImg: function () {
        var self = this;

        (self.$block.parent().prev().children('svg').hasClass('chosen'))
            ? $('svg', self.$cntInner).addClass('chosen')
            : $('svg', self.$cntInner).removeClass('chosen');
        self.$block = self.$block.parent().prev().children('.img');
        self.imgUrl = self.$block.data('background');
        self.$img.attr('src', self.imgUrl);
    }
};

$(document).ready(function () {
    GalleryImages();
    ImageZoom.init();
    var $galleryBlock = $('#gallery-images');


    $('.img').on('click', function () {
        var isChosen = false;
        if ($(this).siblings().hasClass('chosen')) {
            isChosen = true;
        }

        if ($(this).siblings('.star-button').length == 0) {
            $('#image-zoom .star-button').hide();
        } else {
            $('#image-zoom .star-button').show();
        }
        ImageZoom.show($(this), isChosen);
    });

    $('.star-button', $galleryBlock).on('click', function () {
        if (($('.chosen', $galleryBlock).length < 3) || $(this).hasClass('chosen')) {
            $(this).toggleClass('chosen');
        }

        if ($('.chosen', $galleryBlock).length < 3) {
            $('#show-form').removeClass('active');
        } else {
            $('#show-form').addClass('active');
        }
    });

    $('#show-full-rules').on('click', function (e) {
        e.preventDefault();

        $(this).hide().parent().siblings('.hidden').slideDown();
    });

    $('.show-form-button').on('click', function () {
        if (!$(this).hasClass('active')) {
            return false;
        }
        $(this).hide();
        $('#main-form').slideDown(300);
    });

    $('#show-archiv').on('click', function (e) {
        e.preventDefault();

        $('#archive-gallery').slideDown(200);
        $(this).hide();
    })
});