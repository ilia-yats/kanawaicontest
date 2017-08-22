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

var chosenPicturesIds = Object.create(null);
var chosenPicture = {
    add: function (imgId, imgSrc) {
		var $cntInner = $('.chosen-pictures-block');
        $cntInner.append('<img src="'+ imgSrc +'" class="'+ imgId +'">');
        chosenPicturesIds[imgId] = imgId;
    },
    remove: function (imgId) {
		var $cntInner = $('.chosen-pictures-block');
        $cntInner.children('.'+imgId).remove();
        delete chosenPicturesIds[imgId];
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

    $('#contest-link-block').on('click', 'svg', function () {
        $(this).parent().removeClass('active');
    });

    $galleryShowButton.on('click', function () {
        $(this).removeClass('visible').hide();
        $galleryBlock.removeAttr('style');
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
        $('.agreement').hide();
        $('#main-form').slideDown(200);
    });

    $('#show-archive').on('click', function (e) {
        e.preventDefault();

        $('#archive-gallery').slideDown(200);
        $(this).hide();
    });


    var $form = $('#main-form');
    $form.on('click', 'button', function (e) {
        e.preventDefault();
        var captchaResponse = grecaptcha.getResponse();
        if(captchaResponse.length == 0) {

            return false;
        }

        var $emailInput = $('#email');

        $.each($('input', $form), function () {
            if ($(this).val() === '') {
                $(this).addClass('error').siblings('.help-block').text('Cannot be blank').addClass('active');
            } else {
                $(this).removeClass('error').siblings('.help-block').removeClass('active');
            }
        });
        if (isValidEmailAddress($emailInput.val()) && $form.find('input').val() !== '') {

            var formData = $(this).closest('form').serialize();
            $.ajax({
                method: 'post',
                dataType: 'json',
                url: kanawaicontest.ajaxHandler,
                data: {
                    nonce: kanawaicontest.nonce,
                    action: kanawaicontest.action,
                    form:formData,
                    chosen_ids:chosenPicturesIds
                },
                success:function (response) {
                    console.log(response);
                    if (response.status == 'success') {
                        $('#main-form').slideUp(200);
                        modalWindow.show('.thanks-for-vote-modal');
                    } else {
                        modalWindow.show('.already-vote-modal');
                    }
                }
            });

        } else if (!isValidEmailAddress($emailInput.val()) && $emailInput.val() !== '') {
            $emailInput.addClass('error').siblings('.help-block').text('Not valid email').addClass('active');
        }
    });

    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    }
});