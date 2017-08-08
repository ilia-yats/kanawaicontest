<?php
/*
Template Name: Contest Page
*/
?>

<?php get_header(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contest</title>
<!--    <link rel="stylesheet" href="main.css">-->
    <style>
        @import url("https://fonts.googleapis.com/css?family=Libre+Franklin:400,700");
        body {
            position: relative;
            font-family: 'Libre Franklin', sans-serif;
            color: #212121;
            font-size: 15px; }
        body h2 {
            margin: 0 0 50px;
            padding: 0;
            text-transform: uppercase;
            font-size: 40px;
            text-align: left; }
        body .inner {
            width: 1140px;
            margin: 0 auto; }

        .main-banner {
            width: 100%;
            height: 800px;
            background: url("../images/baner_landing.jpg") right/cover no-repeat; }

        .what-win {
            width: 100%;
            padding: 85px 0; }
        .what-win .inner h2 {
            margin-bottom: 50px; }
        .what-win .inner ul {
            margin: 15px 0 35px;
            padding: 0; }
        .what-win .inner ul li {
            margin-bottom: 15px;
            list-style: none; }
        .what-win .inner ul li:last-child {
            margin-bottom: 0; }
        .what-win .inner ul li:before {
            content: "\25B6";
            display: inline-block;
            color: #d51130;
            padding-right: 4px; }
        .what-win .inner p span {
            display: block;
            margin-bottom: 35px; }
        .what-win .inner p span:last-child {
            margin-bottom: 0; }

        .what-need-to-do {
            width: 100%;
            padding: 85px 0;
            background-color: #d8d1ca; }
        .what-need-to-do .inner {
            display: flex;
            flex-flow: row nowrap;
            justify-content: space-between;
            align-items: flex-start; }
        .what-need-to-do .inner .steps {
            max-width: 180px;
            cursor: pointer; }
        .what-need-to-do .inner .steps:hover .img-box {
            box-shadow: 0 -1px 0 transparent inset, 0 2px 3px rgba(0, 0, 0, 0.1), 0 4px 8px rgba(0, 0, 0, 0.3) !important; }
        .what-need-to-do .inner .steps:hover div:not(.img-box) {
            color: #ed1836; }
        .what-need-to-do .inner .steps .img-box {
            display: inline-block;
            line-height: 0;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            transition: box-shadow 0.3s; }
        .what-need-to-do .inner .steps div:not(.img-box) {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
            transition: color 0.3s; }

        .gallery {
            padding: 85px 0;
            width: 100%; }
        .gallery .gallery-title {
            width: 1140px;
            margin: 0 auto; }
        .gallery .gallery-title h2 {
            margin: 0 0 20px; }
        .gallery .gallery-title span {
            color: #d51130; }
        .gallery .gallery-images, .gallery .archive-gallery {
            display: grid;
            grid-gap: 5px;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            margin: 50px 0 0; }
        .gallery .gallery-images .img-container, .gallery .archive-gallery .img-container {
            position: relative;
            width: 100%;
            height: 416px;
            background-color: rgba(0, 0, 0, 0.5); }
        .gallery .gallery-images .img-container:hover div.img div.layer, .gallery .archive-gallery .img-container:hover div.img div.layer {
            background-color: transparent; }
        .gallery .gallery-images .img-container .img, .gallery .archive-gallery .img-container .img {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-color: inherit;
            cursor: pointer; }
        .gallery .gallery-images .img-container .img .layer, .gallery .archive-gallery .img-container .img .layer {
            background-color: rgba(0, 0, 0, 0.7);
            width: 100%;
            height: 100%;
            transition: all .2s ease; }
        .gallery .gallery-images .img-container svg, .gallery .archive-gallery .img-container svg {
            position: absolute;
            width: 70px;
            height: 67px;
            bottom: 20px;
            left: 20px;
            fill: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            transition: all 0.2s ease; }
        .gallery .gallery-images .img-container svg.chosen, .gallery .gallery-images .img-container svg:hover, .gallery .archive-gallery .img-container svg.chosen, .gallery .archive-gallery .img-container svg:hover {
            fill: #d51130; }

        .rules {
            margin-bottom: 85px; }
        .rules .inner {
            text-align: center; }
        .rules .inner .rules-text {
            margin-bottom: 85px; }
        .rules .inner .rules-text p {
            margin: 0;
            text-align: left;
            line-height: 22px; }
        .rules .inner .rules-text .hidden {
            display: none; }
        .rules .inner .thanks-for-vote {
            display: none;
            margin-bottom: 85px; }
        .rules .inner .thanks-for-vote span {
            text-align: left;
            color: #a3a3a3; }
        .rules .inner .show-form-button {
            width: 310px;
            margin: 0 auto 50px;
            padding: 18px 20px;
            color: #fefefe;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            cursor: default;
            background-color: #c2c2c2; }
        .rules .inner .show-form-button.active {
            background-color: #d51130;
            cursor: pointer; }
        .rules .inner .already-vote {
            display: none;
            padding: 20px 0;
            margin-bottom: 50px;
            font-size: 18px;
            color: #fefefe;
            text-transform: uppercase;
            background-color: #ffa900; }
        .rules .inner .form {
            display: none;
            margin-bottom: 85px;
            text-align: left; }
        .rules .inner .form form label {
            display: block;
            padding-bottom: 5px; }
        .rules .inner .form form input {
            width: 700px;
            height: 46px;
            margin-bottom: 30px;
            line-height: 46px;
            border: none;
            border-bottom: 1px solid;
            border-color: #e0e0e0;
            box-sizing: border-box;
            outline-width: 0;
            -webkit-transition: all 0.3s ease;
            -moz-transition: all 0.3s ease;
            -ms-transition: all 0.3s ease;
            -o-transition: all 0.3s ease;
            transition: all 0.3s ease; }
        .rules .inner .form form input[type=number]::-webkit-inner-spin-button, .rules .inner .form form input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0; }
        .rules .inner .form form input:hover, .rules .inner .form form input:focus {
            box-shadow: 0 -1px 0 0 #d51130 inset;
            border-color: #d51130; }
        .rules .inner .form form input:last-of-type {
            margin-bottom: 50px; }
        .rules .inner .form form input::-webkit-input-placeholder {
            font-weight: bold;
            font-size: 24px;
            color: #f0f0f0; }
        .rules .inner .form form input::-moz-placeholder {
            color: #f0f0f0; }
        .rules .inner .form form input:-moz-placeholder {
            color: #f0f0f0; }
        .rules .inner .form form input:-ms-input-placeholder {
            color: #f0f0f0; }
        .rules .inner .form form span {
            display: block;
            margin-bottom: 55px;
            color: #a3a3a3; }
        .rules .inner .form form button {
            margin-top: 30px;
            padding: 14px;
            text-transform: uppercase;
            color: #fefefe;
            border: none;
            cursor: pointer;
            background-color: #d51130;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px; }
        .rules .inner .show-archiv-button {
            font-weight: bold;
            color: #c2c2c2;
            text-transform: uppercase; }

        .archive-gallery {
            display: none;
            margin-bottom: 85px; }
        .archive-gallery .archive-gallery-title {
            width: 1140px;
            margin: 0 auto; }
        .archive-gallery .archive-gallery-inner {
            display: grid;
            grid-gap: 5px;
            grid-template-columns: 1fr 1fr 1fr 1fr; }
        .archive-gallery .archive-gallery-inner .img-container {
            position: relative;
            width: 100%;
            height: 416px; }
        .archive-gallery .archive-gallery-inner .img-container:hover div.img div.layer {
            background-color: transparent; }
        .archive-gallery .archive-gallery-inner .img-container .img {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-color: inherit;
            cursor: pointer; }
        .archive-gallery .archive-gallery-inner .img-container .img .layer {
            background-color: rgba(0, 0, 0, 0.7);
            width: 100%;
            height: 100%;
            transition: all .2s ease; }
        .archive-gallery .archive-gallery-inner .img-container span {
            position: absolute;
            bottom: 20px;
            left: 20px;
            font-size: 16px;
            font-weight: bold;
            color: #FFFFFF; }

        .image-zoom {
            visibility: hidden;
            position: fixed;
            top: 50%;
            left: 50%;
            padding: 50px;
            -webkit-transform: translate(-50%, -50%) scale(0.7);
            -moz-transform: translate(-50%, -50%) scale(0.7);
            -ms-transform: translate(-50%, -50%) scale(0.7);
            -o-transform: translate(-50%, -50%) scale(0.7);
            transform: translate(-50%, -50%) scale(0.7);
            opacity: 0;
            -webkit-transition: all 0.2s ease;
            -moz-transition: all 0.2s ease;
            -ms-transition: all 0.2s ease;
            -o-transition: all 0.2s ease;
            transition: all 0.2s ease;
            background-color: #FFFFFF;
            -webkit-box-shadow: 0 3px 21px 0 rgba(23, 16, 27, 0.46);
            -moz-box-shadow: 0 3px 21px 0 rgba(23, 16, 27, 0.46);
            box-shadow: 0 3px 21px 0 rgba(23, 16, 27, 0.46); }
        .image-zoom.visible {
            visibility: visible;
            opacity: 1;
            -webkit-transform: translate(-50%, -50%) scale(1);
            -moz-transform: translate(-50%, -50%) scale(1);
            -ms-transform: translate(-50%, -50%) scale(1);
            -o-transform: translate(-50%, -50%) scale(1);
            transform: translate(-50%, -50%) scale(1); }
        .image-zoom .image-zoom-inner {
            position: relative; }
        .image-zoom .image-zoom-inner .prev-img, .image-zoom .image-zoom-inner .next-img, .image-zoom .image-zoom-inner .star-button, .image-zoom .image-zoom-inner .close {
            position: absolute;
            cursor: pointer; }
        .image-zoom .image-zoom-inner .prev-img, .image-zoom .image-zoom-inner .next-img {
            top: calc(50% - 37px); }
        .image-zoom .image-zoom-inner .prev-img, .image-zoom .image-zoom-inner .star-button {
            left: 20px; }
        .image-zoom .image-zoom-inner .next-img, .image-zoom .image-zoom-inner .close {
            right: 20px; }
        .image-zoom .image-zoom-inner .close {
            top: 20px; }
        .image-zoom .image-zoom-inner .star-button {
            width: 70px;
            height: 67px;
            bottom: 20px;
            fill: rgba(255, 255, 255, 0.7);
            transition: all 0.2s ease; }
        .image-zoom .image-zoom-inner .star-button.chosen, .image-zoom .image-zoom-inner .star-button:hover {
            fill: #d51130; }

        /*! normalize.css v5.0.0 | MIT License | github.com/necolas/normalize.css */
        /**
         * 1. Change the default font family in all browsers (opinionated).
         * 2. Correct the line height in all browsers.
         * 3. Prevent adjustments of font size after orientation changes in
         *    IE on Windows Phone and in iOS. */
        /* Document
         * ========================================================================== */
        html {
            font-family: sans-serif;
            /* 1 */
            line-height: 1.15;
            /* 2 */
            -ms-text-size-adjust: 100%;
            /* 3 */
            -webkit-text-size-adjust: 100%; }

        /* 3 */
        /* Sections
         * ========================================================================== */
        /**
         * Remove the margin in all browsers (opinionated). */
        body {
            margin: 0; }

        /**
         * Add the correct display in IE 9-. */
        article, aside, footer, header, nav, section {
            display: block; }

        /**
         * Correct the font size and margin on `h1` elements within `section` and
         * `article` contexts in Chrome, Firefox, and Safari. */
        h1 {
            font-size: 2em;
            margin: 0.67em 0; }

        /* Grouping content
         * ========================================================================== */
        /**
         * Add the correct display in IE 9-.
         * 1. Add the correct display in IE. */
        figcaption, figure, main {
            /* 1 */
            display: block; }

        /**
         * Add the correct margin in IE 8. */
        figure {
            margin: 1em 40px; }

        /**
         * 1. Add the correct box sizing in Firefox.
         * 2. Show the overflow in Edge and IE. */
        hr {
            box-sizing: content-box;
            /* 1 */
            height: 0;
            /* 1 */
            overflow: visible; }

        /* 2 */
        /**
         * 1. Correct the inheritance and scaling of font size in all browsers.
         * 2. Correct the odd `em` font sizing in all browsers. */
        pre {
            font-family: monospace, monospace;
            /* 1 */
            font-size: 1em; }

        /* 2 */
        /* Text-level semantics
         * ========================================================================== */
        /**
         * 1. Remove the gray background on active links in IE 10.
         * 2. Remove gaps in links underline in iOS 8+ and Safari 8+. */
        a {
            background-color: transparent;
            /* 1 */
            -webkit-text-decoration-skip: objects;
            /* 2 */ }
        a:active, a:hover {
            outline-width: 0; }

        /**
         * Remove the outline on focused links when they are also active or hovered
         * in all browsers (opinionated). */
        /**
         * 1. Remove the bottom border in Firefox 39-.
         * 2. Add the correct text decoration in Chrome, Edge, IE, Opera, and Safari. */
        abbr[title] {
            border-bottom: none;
            /* 1 */
            text-decoration: underline;
            /* 2 */
            text-decoration: underline dotted; }

        /* 2 */
        /**
         * Prevent the duplicate application of `bolder` by the next rule in Safari 6. */
        b, strong {
            font-weight: inherit; }

        /**
         * Add the correct font weight in Chrome, Edge, and Safari. */
        b, strong {
            font-weight: bolder; }

        /**
         * 1. Correct the inheritance and scaling of font size in all browsers.
         * 2. Correct the odd `em` font sizing in all browsers. */
        code, kbd, samp {
            font-family: monospace, monospace;
            /* 1 */
            font-size: 1em; }

        /* 2 */
        /**
         * Add the correct font style in Android 4.3-. */
        dfn {
            font-style: italic; }

        /**
         * Add the correct background and color in IE 9-. */
        mark {
            background-color: #ff0;
            color: #000; }

        /**
         * Add the correct font size in all browsers. */
        small {
            font-size: 80%; }

        /**
         * Prevent `sub` and `sup` elements from affecting the line height in
         * all browsers. */
        sub, sup {
            font-size: 75%;
            line-height: 0;
            position: relative;
            vertical-align: baseline; }

        sub {
            bottom: -0.25em; }

        sup {
            top: -0.5em; }

        /* Embedded content
         * ========================================================================== */
        /**
         * Add the correct display in IE 9-. */
        audio, video {
            display: inline-block; }

        /**
         * Add the correct display in iOS 4-7. */
        audio:not([controls]) {
            display: none;
            height: 0; }

        /**
         * Remove the border on images inside links in IE 10-. */
        img {
            border-style: none; }

        /**
         * Hide the overflow in IE. */
        svg:not(:root) {
            overflow: hidden; }

        /* Forms
         * ========================================================================== */
        /**
         * 1. Change the font styles in all browsers (opinionated).
         * 2. Remove the margin in Firefox and Safari. */
        button, input, optgroup, select, textarea {
            font-family: sans-serif;
            /* 1 */
            font-size: 100%;
            /* 1 */
            line-height: 1.15;
            /* 1 */
            margin: 0; }

        /* 2 */
        /**
         * Show the overflow in IE.
         * 1. Show the overflow in Edge. */
        button, input {
            /* 1 */
            overflow: visible; }

        /**
         * Remove the inheritance of text transform in Edge, Firefox, and IE.
         * 1. Remove the inheritance of text transform in Firefox. */
        button, select {
            /* 1 */
            text-transform: none; }

        /**
         * 1. Prevent a WebKit bug where (2) destroys native `audio` and `video`
         *    controls in Android 4.
         * 2. Correct the inability to style clickable types in iOS and Safari. */
        button, html [type="button"], [type="reset"], [type="submit"] {
            -webkit-appearance: button; }

        /* 2 */
        /**
         * Remove the inner border and padding in Firefox. */
        button::-moz-focus-inner, [type="button"]::-moz-focus-inner, [type="reset"]::-moz-focus-inner, [type="submit"]::-moz-focus-inner {
            border-style: none;
            padding: 0; }

        /**
         * Restore the focus styles unset by the previous rule. */
        button:-moz-focusring, [type="button"]:-moz-focusring, [type="reset"]:-moz-focusring, [type="submit"]:-moz-focusring {
            outline: 1px dotted ButtonText; }

        /**
         * Change the border, margin, and padding in all browsers (opinionated). */
        fieldset {
            border: 1px solid #c0c0c0;
            margin: 0 2px;
            padding: 0.35em 0.625em 0.75em; }

        /**
         * 1. Correct the text wrapping in Edge and IE.
         * 2. Correct the color inheritance from `fieldset` elements in IE.
         * 3. Remove the padding so developers are not caught out when they zero out
         *    `fieldset` elements in all browsers. */
        legend {
            box-sizing: border-box;
            /* 1 */
            color: inherit;
            /* 2 */
            display: table;
            /* 1 */
            max-width: 100%;
            /* 1 */
            padding: 0;
            /* 3 */
            white-space: normal; }

        /* 1 */
        /**
         * 1. Add the correct display in IE 9-.
         * 2. Add the correct vertical alignment in Chrome, Firefox, and Opera. */
        progress {
            display: inline-block;
            /* 1 */
            vertical-align: baseline; }

        /* 2 */
        /**
         * Remove the default vertical scrollbar in IE. */
        textarea {
            overflow: auto; }

        /**
         * 1. Add the correct box sizing in IE 10-.
         * 2. Remove the padding in IE 10-. */
        [type="checkbox"], [type="radio"] {
            box-sizing: border-box;
            /* 1 */
            padding: 0; }

        /* 2 */
        /**
         * Correct the cursor style of increment and decrement buttons in Chrome. */
        [type="number"]::-webkit-inner-spin-button, [type="number"]::-webkit-outer-spin-button {
            height: auto; }

        /**
         * 1. Correct the odd appearance in Chrome and Safari.
         * 2. Correct the outline style in Safari. */
        [type="search"] {
            -webkit-appearance: textfield;
            /* 1 */
            outline-offset: -2px;
            /* 2 */ }
        [type="search"]::-webkit-search-cancel-button, [type="search"]::-webkit-search-decoration {
            -webkit-appearance: none; }

        /**
         * Remove the inner padding and cancel buttons in Chrome and Safari on macOS. */
        /**
         * 1. Correct the inability to style clickable types in iOS and Safari.
         * 2. Change font properties to `inherit` in Safari. */
        ::-webkit-file-upload-button {
            -webkit-appearance: button;
            /* 1 */
            font: inherit; }

        /* 2 */
        /* Interactive
         * ========================================================================== */
        /*
         * Add the correct display in IE 9-.
         * 1. Add the correct display in Edge, IE, and Firefox. */
        details, menu {
            display: block; }

        /*
         * Add the correct display in all browsers. */
        summary {
            display: list-item; }

        /* Scripting
         * ========================================================================== */
        /**
         * Add the correct display in IE 9-. */
        canvas {
            display: inline-block; }

        /**
         * Add the correct display in IE. */
        template, [hidden] {
            display: none; }

        /* Hidden
         * ========================================================================== */
        /**
         * Add the correct display in IE 10-. */

    </style>
<!--    <script-->
<!--            src="http://code.jquery.com/jquery-3.2.1.min.js"-->
<!--            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="-->
<!--            crossorigin="anonymous"></script>-->
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/170217_cleandevil_final.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/CGAG_VW%20-%2001.06.%20-%2031.12.2017.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/Edelweiss_pizza%20-%2014.08.%20-%2019.08.2017.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/Hafners%20British%20Bikes%20-%20Letzte%2010%20Tage%20vom%20Monat.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/Jazz%20Club%20Lichtensteig%20-%2007.08.%20-%2010.08.2017.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/Möbel%20GAMMA%20-%2017.07.%20-%2021.07.2017_02.08.%20-%2004.08.2017_10.08.%20-%2011.08.2017.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/Möbel%20Schnetzer.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/Neue%20Blumenau%20-%2010.08.%20-%2012.08.2017.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/Royal%20King%20BWC%20-%2016zu9_6%20-%2028.08.%20-%2003.09.2017.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/Verein%20Highlander_31.07.-06.08.2017.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/170217_cleandevil_final.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/CGAG_VW%20-%2001.06.%20-%2031.12.2017.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/Edelweiss_pizza%20-%2014.08.%20-%2019.08.2017.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/Hafners%20British%20Bikes%20-%20Letzte%2010%20Tage%20vom%20Monat.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/Jazz%20Club%20Lichtensteig%20-%2007.08.%20-%2010.08.2017.jpg">
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
        <div class="img-container">
            <div class="img" data-background="images/image_736х416/Möbel%20GAMMA%20-%2017.07.%20-%2021.07.2017_02.08.%20-%2004.08.2017_10.08.%20-%2011.08.2017.jpg">
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
                <label for="name">Vorname/Name:</label>
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
        <img src="images/close.png" alt="" class="close">
        <img src="images/prev.png" alt="" class="prev-img">
        <img src="images/next.png" alt="" class="next-img">
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
<script>

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


</script>
</body>
</html>
<?php get_footer(); ?>