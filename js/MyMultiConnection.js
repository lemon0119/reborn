/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// ......................................................
// ..................RTCMultiConnection Code.............
// ......................................................

var connection = new RTCMultiConnection();

connection.session = {
    screen: true,
    oneway: true
};

connection.sdpConstraints.mandatory = {
    OfferToReceiveAudio: false,
    OfferToReceiveVideo: false
};
connection.transmitRoomOnce = false;
connection.interval = 3000; // in milliseconds

connection.onstream = function(event) {
    var container = document.getElementById("videos-container");
    $("#videos-container").show();
    container.insertBefore(event.mediaElement, container.firstChild);
};

connection.onopen = function(e) {
    console.log('uid'+e.userid);
};
