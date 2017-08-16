<?php
/*
Template Name: Contest Page
*/
wp_deregister_script( 'jquery' );
wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', false, '1.12.4' );
wp_enqueue_script( 'jquery' );
$templateDir = get_stylesheet_directory_uri();
?>
<script src='https://www.google.com/recaptcha/api.js?hl=de-CH'></script>
<?php get_header();?>
<div class="contest-page">
    <div class="main-banner"></div>
    <div class="what-win">
        <div class="inner">
            <h2>GEWINNSPIEL</h2>
            <span>Wählen Sie jetzt Ihre maximal 3 Favoriten der aktuellen Monats-Werbebilder aus und gewinnen Sie einen der attraktiven Monatspreise:</span>
            <ul>
                <li>VIP-Tickets mit Nachtessen für 4 Personen an einem Heimspiel des FCSG</li>
                <li>6 Skipässe und 6 Übernachtungen in Ischgl</li>
                <li>Kinogutscheine im Cinéwil, Wil</li>
            </ul>
            <p>
                <span>Abstimmen können alle Personen gemäss Teilnahmebedingungen solange unser Gewinnspiel des jeweiligen Monats online ist.</span>
                <span>Alle Werbebilder, die bis zum 25sten jedes Monats auf unseren MEGA SCREENS leuchten, nehmen automatisch an diesem Gewinnspiel teil.</span>
                <span>Das Werbebild, welches am meisten Teilnehmerstimmen erhält, wird das Gewinnerbild des Monats.</span>
            </p>
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
    <div class="gallery">
        <div class="gallery-title">
            <h2>gallery</h2>
            <span>Bitte maximal 3 Favoriten auswählen </span>
        </div>
        <div class="gallery-images" id="gallery-images">
            <?php foreach (KC_Public_Kanawaicontest::get_images() as $image) : ?>
                <div class="img-container">
                    <div class="img" data-background="<?php echo $image['image_url']; ?>">
                        <div class="layer"></div>
                    </div>
                    <svg class="star-button" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 70.0354767 67.0449219"  xml:space="preserve">
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
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="rules">
        <div class="inner">
            <h2>Teilnahmebedingungen</h2>
            <div class="rules-text">
                <p>Alle Personen können mitstimmen. Preisberechtigt sind beim Gewinnspiel der kanawai ag ‘Impression of the month’ jedoch nur erwachsene Personen über 18 Jahren mit einem festen Wohnsitz in der Schweiz. Stimm-, aber nicht preisberechtigt sind die Mitarbeitenden der kanawai ag und die Mitarbeitenden der an der Umsetzung beteiligten Agenturen.
                    Pro monatliches Gewinnspiel können die Anzahl Gewinner und die Anzahl und Art der Preise variieren. Es obliegt der kanawai ag, die Preise festzulegen und zu publizieren. <a href="#" id="show-full-rules" class="show-full-rules">Mehr</a>
                </p>
                <p class="not-visible-p">Jede Einsendung muss von einer gültigen E-Mail-Adresse und Telefonnummer erfolgen, an welche auch Antwortsendungen verschickt werden können. Jede Einsendung muss während der Zeit eingereicht werden, in welcher das Gewinnspiel online ist. Automatisch generierte Einträge und Versendungen sowie technische Manipulationen werden von der Teilnahme ausgeschlossen.
                    Das Werbebild, das am meisten Stimmen erhalten hat, wird das ‘Impression of the month’. Die Verlosung des Preises oder der Preise findet unter allen Abstimmenden statt, die das Gewinnerbild ausgewählt haben. Der oder die Gewinner werden durch eine systemische Zufallsgenerator-Software ausgewählt. Die Verlosungs-Datenbank wird jeden Monat aufs Neue mit den Abstimmenden gefüllt.
                    Die Preise können nicht bar ausbezahlt werden Über den Wettbewerb wird keine Korrespondenz geführt. Die Gewinner werden telefonisch benachrichtigt. Der Rechtsweg ist ausgeschlossen.
                    Die kanawai ag kann nicht haftbar gemacht werden für fehlgeleitete, verloren gegangene, zu späte oder fehlerhafte Einträge. Die kanawai ag lehnt jede Haftung ab bezüglich technischer sowie Hard- und Softwarefehler jeglicher Art, unterbrochener Netzwerkverbindungen oder unvollständiger, verspäteter oder verloren gegangener Übermittlungen oder Übermittlungen, welche Systembeschädigungen beim Benutzer hervorrufen könnten.
                    Adressen werden nicht an Dritte weitergegeben, können aber bei Bedarf und sofern nicht ausdrücklich abgelehnt für weitere werbliche Massnahmen von der kanawai ag verwendet werden.
                    Mit der Teilnahme am Gewinnspiel anerkennt der Teilnehmer diese Teilnahmebestimmungen. Die kanawai ag behält sich das Recht vor, diese Wettbewerbsbestimmungen zu jeder Zeit abzuändern.
                </p>
            </div>
            <div class="thanks-for-vote">
                <h2>Vielen Dank für Ihre Stimme – Sie nehmen an der aktuellen Verlosung teil</h2>
                <span>Die Gewinner werden vor Ende jedes Monats persönlich kontaktiert.</span>
            </div>
            <div id="show-form" class="show-form-button">Mitmachen und gewinnen<span>* Weiter und Registration</span></div>
            <div class="already-vote">
                <span>Sie haben Ihre Stimme fuer das aktuelle Gewinnspiel bereits abgegeben.<br>Gerne können Sie nächsten Monat wieder mitmachen!</span>
            </div>
            <div class="form" id="main-form">
                <form action="post">
                    <label for="name">Vorname:</label>
                    <input type="text" id="vorname">
                    <label for="name">Name:</label>
                    <input type="text" id="name">
                    <label for="email">E-Mail-Adresse:</label>
                    <input type="email" id="email">
                    <label for="phone">Telefonnummer:</label>
                    <input type="number" id="phone" placeholder="+4  1  11  111  11  11">
                    <span>* Die persönlichen Daten werden ausschliesslich für das kanawai-Gewinnspiel verwendet und dienen zur Kontaktaufnahme der monatlichen GewinnerInnen.</span>
                    <div class="g-recaptcha" data-sitekey="6Lft7CsUAAAAAP6U9ZXVc9pO85xaqAaFNRH9QXbB"></div>
                    <button type="submit">Absenden</button>
                </form>
            </div>
            <a href="#" id="show-archiv" class="show-archiv-button">archiv</a>
        </div>
    </div>
    <div class="archive-gallery" id="archive-gallery">
        <div class="archive-gallery-title">
            <h2>Archive</h2>
        </div>
        <div class="archive-gallery-inner">
            <div class="img-container">
                <div class="img" data-background="images/image_736х416/170217_cleandevil_final.jpg">
                    <div class="layer"></div>
                </div>
                <span>Gewinnerbild August 2017</span>
            </div>
            <div class="img-container">
                <div class="img" data-background="images/image_736х416/CGAG_VW%20-%2001.06.%20-%2031.12.2017.jpg">
                    <div class="layer"></div>
                </div>
                <span>Gewinnerbild August 2017</span>
            </div>
            <div class="img-container">
                <div class="img" data-background="images/image_736х416/Edelweiss_pizza%20-%2014.08.%20-%2019.08.2017.jpg">
                    <div class="layer"></div>
                </div>
                <span>Gewinnerbild August 2017</span>
            </div>
            <div class="img-container">
                <div class="img" data-background="images/image_736х416/Hafners%20British%20Bikes%20-%20Letzte%2010%20Tage%20vom%20Monat.jpg">
                    <div class="layer"></div>
                </div>
                <span>Gewinnerbild August 2017</span>
            </div>
            <div class="img-container">
                <div class="img" data-background="images/image_736х416/Jazz%20Club%20Lichtensteig%20-%2007.08.%20-%2010.08.2017.jpg">
                    <div class="layer"></div>
                </div>
                <span>Gewinnerbild August 2017</span>
            </div>
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
        </div>
        <span id="image-zoom-text" class="img-text"></span>
    </div>
    <div class="thanks-for-vote-modal" id="thanks-for-vote-modal">
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
</div>
<?php get_footer();?>
