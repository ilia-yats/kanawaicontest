<style>
    .contest-link-block {
        position: fixed;
        z-index: 200;
        visibility: hidden;
        top: calc(86% - 195px);
        right: -400px;
        width: 400px;
        height: 195px;
        text-align: center;
        user-select: none;
        background: linear-gradient(69deg, #d51130 23%, #490000);
        -webkit-transition: 0.3s ease;
        -moz-transition: 0.3s ease;
        -ms-transition: 0.3s ease;
        -o-transition: 0.3s ease;
        transition: 0.3s ease;
        box-sizing: border-box; }
    .contest-link-block.active {
        visibility: visible;
        right: 0; }
    .contest-link-block .close {
        position: absolute;
        top: 15px;
        left: 20px;
        width: 20px;
        height: 20px;
        fill: #FFFFFF;
        cursor: pointer; }
    .contest-link-block h3 {
        margin: 50px 0 20px;
        padding: 0;
        text-align: center;
        text-transform: uppercase;
        font-weight: bold;
        font-size: 50px;
        line-height: 38px;
        color: #FFFFFF; }
    .contest-link-block .contest-link {
        display: inline-block;
        height: 68px;
        padding: 20px 20px 0;
        color: #000000;
        text-align: center;
        text-transform: uppercase;
        text-decoration: none;
        font-size: 35px;
        background-color: #FFFFFF;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        -webkit-box-shadow: 0 3px 3px 0 rgba(23, 16, 27, 0.5);
        -moz-box-shadow: 0 3px 3px 0 rgba(23, 16, 27, 0.5);
        box-shadow: 0 3px 3px 0 rgba(23, 16, 27, 0.5);
        box-sizing: border-box; }
</style>
<div class="contest-link-block active" id="contest-link-block">
    <svg class="close" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 156.1552734 156.1587219" enable-background="new 0 0 156.1552734 156.1587219" xml:space="preserve">
            <g>
                <rect x="-25.29459" y="71.0318832" transform="matrix(0.7070985 0.7071151 -0.7071151 0.7070985 78.0801392 -32.3402939)" width="206.7444" height="14.0949373"></rect>
                <rect x="71.0301437" y="-25.2928486" transform="matrix(0.7071068 0.7071068 -0.7071068 0.7071068 78.0788422 -32.3402939)" width="14.0949373" height="206.7444"></rect>
            </g>
        </svg>
    <h3>GEWINNSPIEL</h3>
    <a href="/contest" class="contest-link">JETZT MITMACHEN</a>
</div>