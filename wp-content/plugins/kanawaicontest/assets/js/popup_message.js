/**
 * This file provides popup window for clients
 *  and is included on the page when Bookingamanager_Util::popup_message method is called
 */
if(typeof msg !== 'undefined') {
    var fullMessage = '';
    try{
        var messages = JSON.parse(msg.messages);
        for(var text in messages) {
            fullMessage += "\n" + text;
        }

        alert(fullMessage);

    } catch(e){
        //console.log(e);
    }
}