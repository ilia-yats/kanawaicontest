<?php
/*
Template Name: Contest Page
*/
?>

<?php get_header();?>
<body>
<script src='https://www.google.com/recaptcha/api.js'></script>
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
                <img src="images/icons/1.png" alt="">
            </div>
            <div>Aktuelle Werbebilder ansehen</div>
        </div>
        <div class="steps">
            <div class="img-box">
                <img src="images/icons/2.png" alt="">
            </div>
            <div>3 Bild-Favoriten auswählen</div>
        </div>
        <div class="steps">
            <div class="img-box">
                <img src="images/icons/3.png" alt="">
            </div>
            <div>Kontakt-daten ausfüllen</div>
        </div>
        <div class="steps">
            <div class="img-box">
                <img src="images/icons/4.png" alt="">
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
                Pro monatliches Gewinnspiel können die Anzahl Gewinner und die Anzahl und Art der Preise variieren. Es obliegt der kanawai ag, die Preise festzulegen und zu publizieren...<a href="#" id="show-full-rules" class="show-full-rules">mehr</a>
            </p>
            <p class="hidden">Jede Einsendung muss von einer gültigen E-Mail-Adresse und Telefonnummer erfolgen, an welche auch Antwortsendungen verschickt werden können. Jede Einsendung muss während der Zeit eingereicht werden, in welcher das Gewinnspiel online ist. Automatisch generierte Einträge und Versendungen sowie technische Manipulationen werden von der Teilnahme ausgeschlossen.
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
        <div id="show-form" class="show-form-button">Mitmachen und gewinnen</div>
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
        <img src="" alt="" class="main-img">
        <svg id="close" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 0 22.1560116 22.1560116" enable-background="new 0 0 22.1560116 22.1560116" xml:space="preserve">
            <line stroke-width="2" stroke-miterlimit="10" x1="0.7071068" y1="0.7071068" x2="21.4489059" y2="21.4489059"/>
            <line stroke-width="2" stroke-miterlimit="10" x1="21.4489059" y1="0.7071068" x2="0.7071068" y2="21.4489059"/>
        </svg>
        <svg id="prev" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 0 22.1560116 22.1560116" enable-background="new 0 0 22.1560116 22.1560116" xml:space="preserve">
        </svg>
        <svg id="next" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 0 22.1560116 22.1560116" enable-background="new 0 0 22.1560116 22.1560116" xml:space="preserve">
        </svg>
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
</div>
</body>
<?php get_footer();?>
