<?php
/*
Template Name: Contest Page
*/
wp_deregister_script( 'jquery' );
wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', false, '1.12.4' );
wp_enqueue_script( 'jquery' );
$templateDir = get_stylesheet_directory_uri();
$is_active_contest = !empty(KC_Tours_List::get_current_tour_id());
?>
<script src='https://www.google.com/recaptcha/api.js?hl=de-CH'></script>
<?php get_header();?>
<div class="contest-page">
    <div class="main-banner" style="background: url('<?php echo wp_get_attachment_image_src(get_option('kanawaicontest_main_banner_attachment_id'), 'full')[0]; ?>') right/cover no-repeat;"></div>
    <?php if (!$is_active_contest): ?>
            <?php echo get_option('kanawaicontest_contest_finished_message'); ?>
    <?php endif; ?>
    <div class="what-win">
        <div class="inner">
            <?php echo get_option('kanawaicontest_rules'); ?>
        </div>
    </div>
    <div class="what-need-to-do">
        <div class="inner">
            <div class="steps">
                <div class="img-box">
                    <img src="<?= $templateDir ?>/images/1.png" alt="">
                </div>
                <div>Aktuelle Werbebilder ansehen</div>
            </div>
            <div class="steps">
                <div class="img-box">
                    <img src="<?= $templateDir ?>/images/2.png" alt="">
                </div>
                <div>3 Bild-Favoriten auswählen</div>
            </div>
            <div class="steps">
                <div class="img-box">
                    <img src="<?= $templateDir ?>/images/3.png" alt="">
                </div>
                <div>Kontakt-daten ausfüllen</div>
            </div>
            <div class="steps">
                <div class="img-box">
                    <img src="<?= $templateDir ?>/images/4.png" alt="">
                </div>
                <div>Mit etwas Glück gewinnen und geniessen</div>
            </div>
        </div>
    </div>

    <?php if ($is_active_contest): ?>
        <div class="gallery">
            <div class="gallery-title">
                <h2>gallery – <span id="contest-month"><?php echo sanitize_text_field(KC_Tours_List::get_current_tour_title()) ?></span></h2>
                <span>Bitte maximal 3 Favoriten auswählen </span>
            </div>
            <div class="gallery-images" id="gallery-images">

                <?php KC_Public_Kanawaicontest::render_posters(); ?>

            </div>
            <div class="gallery-show visible" id="gallery-show">Mehr</div>
        </div>
        <div class="chosen-pictures" id="chosen-pictures">
            <h5 class="text-hidden">ist ausgewählt</h5>
            <div class="chosen-pictures-block"></div>
        </div>
    <?php endif; ?>
    <div class="rules">
        <div class="inner">
            <?php echo get_option('kanawaicontest_terms_and_conditions'); ?>
            <?php if ($is_active_contest): ?>
            <div id="show-form" class="show-form-button">Weiter und Registration</div>
            <div class="form" id="main-form">
                <form action="" method="post">
					<div class="name field">
                    <label for="first_name">Vorname:</label>
                    <input name="first_name" type="text" id="first_name">
						<div class="help-block"></div>
					</div>
					<div class="vorname field">
                    <label for="last_name">Name:</label>
                    <input name="last_name" type="text" id="last_name">
						<div class="help-block"></div>
					</div>
					<div class="email field">
                    <label for="email">E-Mail-Adresse:</label>
                    <input name="email" type="email" id="email">
						<div class="help-block"></div>
					</div>
					<div class="phone field">
                    <label for="phone">Telefonnummer:</label>
                    <input name="phone" type="number" id="phone" placeholder="+4  1  11  111  11  11">
						<div class="help-block"></div>
					</div>
                    <span>* Die persönlichen Daten werden ausschliesslich für das kanawai-Gewinnspiel verwendet und dienen zur Kontaktaufnahme der monatlichen GewinnerInnen.</span>
                    <div class="g-recaptcha" data-sitekey="6Lft7CsUAAAAAP6U9ZXVc9pO85xaqAaFNRH9QXbB"></div>
                    <div class="form-agreement">
                        <input type="checkbox" name="agree-with-rules" id="agree-with-rules">
                        <label for="agree-with-rules" class="main-label"></label>
                        <label for="agree-with-rules">Ich akzeptiere die Teilnahmebedingungen</label>
						<div class="help-block"></div>
                    </div>
                    <button type="submit">Absenden</button>
                </form>
            </div>
            <?php endif; ?>
            <?php if (KC_Public_Kanawaicontest::has_archive()): ?>
                <a href="#" id="show-archive" class="show-archive-button">archiv</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="archive-gallery" id="archive-gallery">
        <div class="archive-gallery-title">
            <h2><?php _e('Archive') ?></h2>
        </div>
        <div class="archive-gallery-inner">
            <?php KC_Public_Kanawaicontest::render_archive(); ?>
        </div>
    </div>
    <div class="image-zoom" id="image-zoom">
        <div class="image-zoom-inner">
            <img src="" alt="" id="zoom-img" class="main-img">
            <svg id="close" class="close" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 viewBox="0 0 156.1552734 156.1587219" enable-background="new 0 0 156.1552734 156.1587219" xml:space="preserve">
            <g>
                <rect x="-25.29459" y="71.0318832" transform="matrix(0.7070985 0.7071151 -0.7071151 0.7070985 78.0801392 -32.3402939)" width="206.7444" height="14.0949373"/>
                <rect x="71.0301437" y="-25.2928486" transform="matrix(0.7071068 0.7071068 -0.7071068 0.7071068 78.0788422 -32.3402939)" width="14.0949373" height="206.7444"/>
            </g>
        </svg>
            <div id="prev" class="prev">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 88.0531006 156.1586914" enable-background="new 0 0 88.0531006 156.1586914" xml:space="preserve">
                <g>
                    <rect x="36.9809608" y="56.9169044" transform="matrix(0.7073687 -0.7068447 0.7068447 0.7073687 -66.376564 63.9349174)" width="14.094964" height="110.4323044"/>
                    <polygon points="9.9840088,88.0375977 88.0531006,9.9666138 78.0865479,0 0.0305176,78.0578003 	"/>
                </g>
            </svg>
            </div>
            <div id="next" class="next">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 88.0531006 156.1586914" enable-background="new 0 0 88.0531006 156.1586914" xml:space="preserve">
                <g>
                    <rect x="36.9771767" y="56.9169044" transform="matrix(-0.7073687 -0.7068447 0.7068447 -0.7073687 -4.0943375 222.5710754)" width="14.094964" height="110.4323044"/>
                    <polygon points="78.0690918,88.0375977 0,9.9666138 9.9665527,0 88.022583,78.0578003 	"/>
                </g>
            </svg>
            </div>
            <?php if ($is_active_contest): ?>
                <svg class="star-button" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 70.0354767 67.0449219"  xml:space="preserve">
                    <path d="M53.9242935,67.0449219c-0.7185936,0-1.4357224-0.1764832-2.0729027-0.51091
                        l-16.4696312-8.6591988c-0.2037811-0.1092033-0.4226723-0.1033516-0.6069527-0.0058479l-16.486208,8.6582184
                        c-1.4323101,0.7663727-3.3243465,0.6522903-4.6854811-0.3266296c-1.3855104-1.0218277-2.0607147-2.6891212-1.7803955-4.3632393
                        l3.1420183-18.3489914c0.0380259-0.2164574-0.0341263-0.4368134-0.186718-0.5859909L1.4533342,29.9159775
                        c-1.2041557-1.1758804-1.6473044-2.9679756-1.1300538-4.5670166c0.5182257-1.6029396,1.9324991-2.7953949,3.6032043-3.0362263
                        l18.4113922-2.6744938c0.2125568-0.0321751,0.407074-0.1696529,0.4997005-0.3588085l8.2428589-16.6885242
                        c0.7512569-1.530789,2.2820473-2.4853382,3.9927292-2.4853382c1.715065,0,3.2482948,0.9545492,4.0014992,2.4902134
                        l8.2253113,16.6807232c0.0955505,0.1901302,0.287632,0.3295593,0.5089607,0.362709l18.4109039,2.6744938
                        c1.6658325,0.2408314,3.0776672,1.432312,3.5958939,3.0352516c0.5255356,1.6243916,0.0926285,3.3745613-1.1295624,4.5670166
                        L55.3678169,42.9033089c-0.1599045,0.1540527-0.2286415,0.3724594-0.1920776,0.5918388l3.1395798,18.3353424
                        c0.2905579,1.6702156-0.3836746,3.3414078-1.7608948,4.3564072C55.7768402,66.751442,54.8715286,67.0449219,53.9242935,67.0449219z"
                    />
                </svg>
            <?php endif; ?>
            <a href="" id="image-link"></a>
        </div>
        <span id="image-zoom-text" class="img-text"></span>
    </div>
    <div class="thanks-for-vote-modal modal-window" id="thanks-for-vote-modal">
        <svg class="close" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 0 156.1552734 156.1587219" enable-background="new 0 0 156.1552734 156.1587219" xml:space="preserve">
            <g>
                <rect x="-25.29459" y="71.0318832" transform="matrix(0.7070985 0.7071151 -0.7071151 0.7070985 78.0801392 -32.3402939)" width="206.7444" height="14.0949373"/>
                <rect x="71.0301437" y="-25.2928486" transform="matrix(0.7071068 0.7071068 -0.7071068 0.7071068 78.0788422 -32.3402939)" width="14.0949373" height="206.7444"/>
            </g>
        </svg>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 0 111.375 111.375" enable-background="new 0 0 111.375 111.375" xml:space="preserve">
            <circle fill="#FFFFFF" cx="55.8808594" cy="55.7296715" r="55.3263168"/>
            <circle fill="#ED1836" cx="55.8808594" cy="55.7296715" r="48.3533516"/>
            <g>
                <defs>
                    <circle cx="55.8808594" cy="55.7296715" r="48.3533516"/>
                </defs>
                <clipPath id="SVGID_2_">
                    <use xlink:href="#SVGID_1_"  overflow="visible"/>
                </clipPath>
                <path clip-path="url(#SVGID_2_)" fill="#B71326" d="M107.6336823,50.8943214L55.8563461,28.1791039
                    c0,0-0.9276657,1.4157181-1.1781998,2.1673241c-0.2505341,0.7516079-3.5074959,36.0771103-3.5074959,36.8287201
                    c0,0.7516022-18.5305939,15.4536667-18.5305939,15.4536667l0.5010719,1.0021439l47.1006699,18.7066498l15.3661804-14.8651047
                    c0,0,8.5182037-14.0299911,8.5182037-14.5310593S107.6336823,50.8943214,107.6336823,50.8943214z"/>
            </g>
            <g>
                <path fill="#FFFFFF" d="M66.7523727,84.8156204c0,0,0,0-0.0009766,0H34.6643448
                    c-1.8984413,0-3.4434147-1.6119766-3.4434147-3.5929642V52.3415909c0-1.2531662,0.6166286-2.3951416,1.6284847-3.0481873
                    c1.8634834-1.6906319,9.4659882-8.5997925,12.1393433-11.2066307c1.4041634-1.3692055,2.3956261-3.2749329,2.7927933-5.3651581
                    c0.0437012-0.2301445,0.0689468-0.4651413,0.096138-0.6996574c0.0922508-0.7919064,0.2058678-1.7775421,0.7778244-2.7466698
                    c0.7117958-1.2041264,1.894558-2.0892563,3.2462883-2.4291306c1.8236694-0.4622288,4.1979332-0.113615,6.0012093,0.8487148
                    c4.86409,2.5990677,6.2099876,9.5941677,4.4144821,14.4970989l-1.1021614,3.0064316
                    c2.3393059-0.0067978,5.6768723-0.0145645,8.300705-0.0145645c4.119278,0,4.2027893,0.01408,4.4261322,0.0514641
                    c3.2356033,0.5476837,5.7603836,3.026825,6.4304199,6.3158379c0.5467148,2.6816101-0.2621841,5.3496246-2.0897369,7.1898003
                    c0.8215256,2.764637,0.0495224,5.8545799-1.9809799,7.9137306c0.8234634,2.7631836,0.0524368,5.8531265-1.9741898,7.9127655
                    c0.6302261,2.0669174,0.3680344,4.3207703-0.7778244,6.2857285C72.1019897,83.3342514,69.5606995,84.8156204,66.7523727,84.8156204
                    z"/>
                <path fill="#B71326" d="M35.1984329,80.8381195h31.5539398l0,0c1.3779449,0,2.6354828-0.7433548,3.3618393-1.9892426
                    c0.7244186-1.2419968,0.7525787-2.6976318,0.0757446-3.8949585l-0.9263992-1.6386795l1.5857544-1.0152512
                    c1.7391891-1.1133347,2.3315353-3.4754562,1.3206558-5.2646561l-0.9254303-1.6381912l1.5838165-1.0147705
                    c1.7401581-1.1143036,2.3325043-3.4754601,1.3206558-5.2636833l-0.9263992-1.6386795l1.5857544-1.015255
                    c1.6343079-1.0472984,1.9324265-2.8146439,1.6673279-4.119278c-0.2660751-1.3012314-1.2167511-2.8034782-3.0918884-3.1690826
                    c-0.2515106-0.0058289-1.0895386-0.0150528-3.8677673-0.0150528c-4.5135345,0-11.1439972,0.0237923-11.1439972,0.0237923
                    l-2.8578606,0.0101929l3.0685806-8.3715897c1.1934471-3.2589111,0.3544426-8.0661888-2.5539131-9.620388
                    c-0.9448509-0.5049553-2.2858963-0.7185917-3.1559753-0.5001011c-0.324337,0.0815716-0.6205139,0.3034611-0.7923927,0.5942955
                    c-0.1427498,0.2427673-0.1932449,0.6778069-0.2515068,1.1817913c-0.0378723,0.3291931-0.0776863,0.6583862-0.1398354,0.9827232
                    c-0.5476837,2.8855324-1.9411697,5.5389824-3.9231224,7.4723816c-2.7995911,2.7296753-10.8118896,10.0063858-12.3898773,11.4372597
                    l-0.1777039,0.1611938V80.8381195z"/>
            </g>
        </svg>
        <h2>Vielen Dank für Ihre Stimme – Sie nehmen an der aktuellen Verlosung teil</h2>
        <span>Die Gewinner werden vor Ende jedes Monats persönlich kontaktiert.</span>
    </div>
    <div class="already-vote-modal modal-window" id="already-vote-modal">
        <svg class="close" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 0 156.1552734 156.1587219" enable-background="new 0 0 156.1552734 156.1587219" xml:space="preserve">
            <g>
                <rect x="-25.29459" y="71.0318832" transform="matrix(0.7070985 0.7071151 -0.7071151 0.7070985 78.0801392 -32.3402939)" width="206.7444" height="14.0949373"/>
                <rect x="71.0301437" y="-25.2928486" transform="matrix(0.7071068 0.7071068 -0.7071068 0.7071068 78.0788422 -32.3402939)" width="14.0949373" height="206.7444"/>
            </g>
        </svg>
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 0 110 110" enable-background="new 0 0 110 110" xml:space="preserve">
            <g>
                <g>
                    <circle fill="#FFFFFF" cx="54.8540497" cy="54.8540726" r="53.5206795"/>
                    <circle fill="#ED1836" cx="54.8540497" cy="54.8540726" r="46.9522324"/>
                    <g>
                        <defs>
                            <circle id="SVGID_13_" cx="54.6918793" cy="54.8804092" r="46.9522324"/>
                        </defs>
                        <clipPath id="SVGID_2_">
                            <use xlink:href="#SVGID_13_"  overflow="visible"/>
                        </clipPath>
                        <path clip-path="url(#SVGID_2_)" fill="#B71326" d="M104.4900131,47.0331459L60.7591896,25.6899586
                            c0,0-0.2710648,0.1729431-0.7005615,0.4518948c-1.5842018,1.0289116-20.2028961,3.1674938-20.4457703,3.7218456
                            c-0.3087196,0.7046452-25.7785378,17.4304676-6.1455307,43.1661072c0.4426613,0.5802536,9.4356155,6.4306946,9.4356155,6.4306946
                            l1.8938179,0.6617432l33.543457,20.4624329l6.0386887-7.2651443l10.7095795-10.2581329l5.5569611-12.6835938
                            c0,0,2.3618088-9.7999649,2.4725723-11.0112877C103.228775,58.1551933,104.4900131,47.0331459,104.4900131,47.0331459z"/>
                    </g>
                    <path fill="#FFFFFF" d="M54.8363533,83.283165c0,0-0.0008888,0-0.0017776,0c-7.82658,0-15.1765327-3.0399246-20.6963234-8.5597153
                        c-11.4141331-11.4136887-11.4141331-29.9860611,0-41.3997498c5.5197906-5.5202332,12.8706322-8.5601559,20.6972122-8.5601559
                        c7.8274689,0,15.1791992,3.0408115,20.7007599,8.5628185c5.5197906,5.5202332,8.5597153,12.8706322,8.5597153,20.6972122
                        c-0.000885,7.8270226-3.0417023,15.1774216-8.5623779,20.6976547
                        C70.0137787,80.2423553,62.6629333,83.283165,54.8363533,83.283165z M54.8354645,28.399025
                        c-6.8555794,0-13.2931137,2.6618195-18.126812,7.4950752c-9.9958,9.996685-9.9958,26.2622643,0,36.2589531
                        c4.8328094,4.8328094,11.2703438,7.4946289,18.1259232,7.4950714c6.8564682,0,13.2948952-2.6627045,18.1285896-7.4977341
                        c4.8345871-4.8337021,7.4972916-11.2712364,7.4972916-18.1268158s-2.6618195-13.2931137-7.4946289-18.126812
                        C68.1312408,31.0617313,61.6919327,28.399025,54.8354645,28.399025z"/>
                    <path fill="none" stroke="#000000" stroke-width="4" stroke-miterlimit="10" d="M66.7307663,59.7356186"/>
                </g>
                <path fill="#FFFFFF" d="M52.4624825,65.8827362c-0.4979553,0-0.9959106-0.1900558-1.37603-0.5701752l-9.3262062-9.32621
                    c-0.7602386-0.7597618-0.7602386-1.9922943,0-2.7520561c0.7602348-0.7602386,1.9918213-0.7602386,2.7520561,0l7.9501801,7.9497032
                    L67.467659,46.1797714c0.7602386-0.7602386,1.9918213-0.7602386,2.7520523,0c0.7602386,0.7597618,0.7602386,1.9922943,0,2.7520561
                    L53.8385086,65.312561C53.4583931,65.6926804,52.9604378,65.8827362,52.4624825,65.8827362z"/>
            </g>
        </svg>
        <h2>Sie haben Ihre Stimme fuer das aktuelle Gewinnspiel bereits abgegeben.</h2>
        <span>Gerne können Sie nächsten Monat wieder mitmachen!</span>
    </div>
    <div class="background-screen" id="background-screen"></div>
</div>
<?php get_footer();?>
