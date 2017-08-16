<?php
/* Custom functions code goes here. */
function pb_colorbox()
{
    ?>
    <script>
    jQuery(document).ready(function(){
      jQuery('.trigger-download-ga a').on('click', function(e){
        ga('send', 'event', 'Datenaufbereitung', 'click');
      });

      jQuery('.trigger-download2-ga').on('click', function(e){
        ga('send', 'event', 'Broschuere', 'click');
      });

      jQuery('.wpb_wrapper .w-actionbox-controls .w-btn[href*="eepurl.com"]').colorbox({
        iframe: true,
        width: 680,
        height: 680,
        maxWidth: '90%',
        maxHeight: '90%'
      });

      syncheight = function() {
        imgURL = jQuery('.l-section-img:first-child').css('background-image');

        if( typeof imgURL != 'undefined' ) {
          console.log(imgURL);
          imgURL = imgURL.replace('url(','').replace(')','').replace(/\"/gi, "");
          console.log(imgURL);
          img = new Image();
          img.src = imgURL;
          imgHeight = img.height;
          imgWidth = img.width;
        } else {
          imgWidth = 2000;
          imgHeight = 880;
        }

        console.log(imgURL+': '+imgWidth+' x '+imgHeight);

        windowWidth = jQuery(window).width();

        newHeight = (imgHeight / imgWidth) * windowWidth;

        console.log(newHeight);

        if( (newHeight-240) < 10 ) {
          jQuery('.l-section-img').css('background-position', 'auto');
          jQuery('.l-section-img').css('background-size', 'cover');
          jQuery('div.l-canvas.sidebar_none.type_wide.titlebar_none section.with_img div.vc_empty_space').css('height', 120);
        } else {
          jQuery('.l-section-img').css('background-size', windowWidth+'px '+(newHeight)+'px');
          jQuery('.l-section-img').css('background-repeat', 'no-repeat');

          if( windowWidth < 600 ) {
            jQuery('.l-section-img').css('background-position', 'auto');
            newHeight = newHeight - 160;
          } else {
            jQuery('.l-section-img').css('background-position', '0 80px');
            newHeight = newHeight - 240;
          }

          if( newHeight > 800 ) {
            newHeight = 750;
          }

          jQuery('div.l-canvas.sidebar_none.type_wide.titlebar_none section.with_img div.vc_empty_space').css('height', newHeight);
        }
      };

      syncheight();

      jQuery(window).on('resize', function(){
        syncheight();
      });
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'pb_colorbox', 999 );

function pb_tag_manager()
{
  ?>
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

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
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WP4MDB2" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <?php
}

//add_action('us_before_canvas', 'pb_tag_manager2', 1);
