<?php
/* Custom functions code goes here. */

function pb_colorbox()
{
    ?>
    <script>
        jQuery(document).ready(function () {
            jQuery('.trigger-download-ga a').on('click', function (e) {
                ga('send', 'event', 'Datenaufbereitung', 'click');
            });

            jQuery('.trigger-download2-ga').on('click', function (e) {
                ga('send', 'event', 'Broschuere', 'click');
            });

            jQuery('.wpb_wrapper .w-actionbox-controls .w-btn[href*="eepurl.com"]').colorbox({
                iframe: true,
                width: 680,
                height: 680,
                maxWidth: '90%',
                maxHeight: '90%'
            });

            syncheight = function () {
                imgURL = jQuery('.l-section-img:first-child').css('background-image');

                if (typeof imgURL != 'undefined') {
                    console.log(imgURL);
                    imgURL = imgURL.replace('url(', '').replace(')', '').replace(/\"/gi, "");
                    console.log(imgURL);
                    img = new Image();
                    img.src = imgURL;
                    imgHeight = img.height;
                    imgWidth = img.width;
                } else {
                    imgWidth = 2000;
                    imgHeight = 880;
                }

                console.log(imgURL + ': ' + imgWidth + ' x ' + imgHeight);

                windowWidth = jQuery(window).width();

                newHeight = (imgHeight / imgWidth) * windowWidth;

                console.log(newHeight);

                if ((newHeight - 240) < 10) {
                    jQuery('.l-section-img').css('background-position', 'auto');
                    jQuery('.l-section-img').css('background-size', 'cover');
                    jQuery('div.l-canvas.sidebar_none.type_wide.titlebar_none section.with_img div.vc_empty_space').css('height', 120);
                } else {
                    jQuery('.l-section-img').css('background-size', windowWidth + 'px ' + (newHeight) + 'px');
                    jQuery('.l-section-img').css('background-repeat', 'no-repeat');

                    if (windowWidth < 600) {
                        jQuery('.l-section-img').css('background-position', 'auto');
                        newHeight = newHeight - 160;
                    } else {
                        jQuery('.l-section-img').css('background-position', '0 80px');
                        newHeight = newHeight - 240;
                    }

                    if (newHeight > 800) {
                        newHeight = 750;
                    }

                    jQuery('div.l-canvas.sidebar_none.type_wide.titlebar_none section.with_img div.vc_empty_space').css('height', newHeight);
                }
            };

            syncheight();

            jQuery(window).on('resize', function () {
                syncheight();
            });
        });
    </script>
    <?php
}

add_action('wp_footer', 'pb_colorbox', 999);

function pb_tag_manager()
{
    ?>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-88976972-1', 'auto');
        ga('send', 'pageview');

    </script>
    <?php
    /*
    ?>
    <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-WP4MDB2');</script>
  <!-- End Google Tag Manager -->
    <?php*/
}

add_action('wp_head', 'pb_tag_manager', 1);

function pb_tag_manager2()
{
    ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WP4MDB2" height="0" width="0"
                style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
}


// Include Kanawai contest plugin and scripts
add_action('init', 'kanawaicontest_public_init');
// Kanawai contest plugin additional html and assets
add_action('wp_enqueue_scripts', 'kanawaicontest_assets', 12);
add_action('wp_footer', 'kanawaicontest_button');
function kanawaicontest_public_init()
{
    require_once ABSPATH . '/wp-content/plugins/kanawaicontest/public/Kanawaicontest.php';
}

function kanawaicontest_assets()
{
    $templateDir = get_stylesheet_directory_uri();
    wp_register_style('kc-main-style', $templateDir . '/css/main.css');
    wp_enqueue_style('kc-main-style');
    wp_register_script('kc-main-script', $templateDir . '/js/index.js', ['jquery']);
    wp_enqueue_script('kc-main-script');
}

function kanawaicontest_button()
{
    if (get_query_var('pagename') !== 'contest'):
        ?>
        <style>
            .contest-link-block {
                position: fixed;
                z-index: 200;
                visibility: hidden;
                top: calc(50% - 195px);
                right: -290px;
                width: 289px;
                height: 195px;
                text-align: center;
                user-select: none;
                background: linear-gradient(69deg, #d51130 23%, #490000);
                -webkit-transition: 0.3s ease;
                -moz-transition: 0.3s ease;
                -ms-transition: 0.3s ease;
                -o-transition: 0.3s ease;
                transition: 0.3s ease;
                box-sizing: border-box;
            }

            .contest-link-block.active {
                visibility: visible;
                right: 0;
            }

            .contest-link-block .close {
                position: absolute;
                top: 20px;
                left: 20px;
                width: 20px;
                height: 20px;
                fill: #FFFFFF;
                cursor: pointer;
            }

            .contest-link-block h3 {
                margin: 50px 0 30px;
                padding: 0;
                text-align: center;
                text-transform: uppercase;
                font-weight: bold;
                font-size: 28px;
                color: #FFFFFF;
            }

            .contest-link-block .contest-link {
                display: inline-block;
                height: 50px;
                padding: 14px 20px 0;
                color: #000000;
                text-align: center;
                text-transform: uppercase;
                text-decoration: none;
                font-size: 18px;
                background-color: #FFFFFF;
                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                border-radius: 3px;
                -webkit-box-shadow: 0 3px 3px 0 rgba(23, 16, 27, 0.5);
                -moz-box-shadow: 0 3px 3px 0 rgba(23, 16, 27, 0.5);
                box-shadow: 0 3px 3px 0 rgba(23, 16, 27, 0.5);
                box-sizing: border-box;
            }
        </style>
        <div class="contest-link-block active" id="contest-link-block">
            <svg class="close" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 viewBox="0 0 156.1552734 156.1587219" enable-background="new 0 0 156.1552734 156.1587219"
                 xml:space="preserve">
            <g>
                <rect x="-25.29459" y="71.0318832"
                      transform="matrix(0.7070985 0.7071151 -0.7071151 0.7070985 78.0801392 -32.3402939)"
                      width="206.7444" height="14.0949373"/>
                <rect x="71.0301437" y="-25.2928486"
                      transform="matrix(0.7071068 0.7071068 -0.7071068 0.7071068 78.0788422 -32.3402939)"
                      width="14.0949373" height="206.7444"/>
            </g>
        </svg>
            <h3>GEWINNSPIEL</h3>
            <a href="/contest" class="contest-link">JETZT MITMACHEN</a>
        </div>
        <?php
    endif;
}


