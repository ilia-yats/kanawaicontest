/**
 * Simple function to handle multilingual support
 * @returns {plugin_translation_i18n.tr}
 */
function plugin_translation_i18n (){
    var tr;
    switch(jQuery('html').attr('lang')) {
        case 'en-US': tr = {
            yes:'Yes',
            no:'No',
        };
        break;
        case 'de-DE':
        default: tr = {
            yes:'Ja',
            no:'Nein',
        };
        break;
    }
    return tr;
}
