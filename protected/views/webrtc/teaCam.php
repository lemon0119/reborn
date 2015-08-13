<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
    <script src="<?php echo JS_URL; ?>RTCMultiConnection.js"></script>
    <!-- socket.io for signaling -->
    <script src="<?php echo JS_URL; ?>socketio.js"></script>

    <script>
   
    window.parent.document.getElementById('share-Cam').onclick = function() {
        connection.open("class1Cam");
    };
        
    // ......................................................
    // ..................RTCMultiConnection Code.............
    // ......................................................

    var connection = new RTCMultiConnection();

    connection.session = {
        video:true,
        audio:true,
        oneway: true
    };

    connection.sdpConstraints.mandatory = {
        OfferToReceiveAudio: false,
        OfferToReceiveVideo: false
    };

    connection.onstream = function(event) {
        document.body.appendChild(event.mediaElement);
        scaleVideos();
    };
    connection.socketURL = "https://192.168.101.104:9001";
    
    function scaleVideos() {
        var videos = document.querySelectorAll('video'),
                length = videos.length,
                video;

        var minus = 130;
        var windowHeight = 400;
        var windowWidth = 400;
        var windowAspectRatio = windowWidth / windowHeight;
        var videoAspectRatio = 4 / 3;
        var blockAspectRatio;
        var tempVideoWidth = 0;
        var maxVideoWidth = 0;

        for (var i = length; i > 0; i--) {
            blockAspectRatio = i * videoAspectRatio / Math.ceil(length / i);
            if (blockAspectRatio <= windowAspectRatio) {
                tempVideoWidth = videoAspectRatio * windowHeight / Math.ceil(length / i);
            } else {
                tempVideoWidth = windowWidth / i;
            }
            if (tempVideoWidth > maxVideoWidth)
                maxVideoWidth = tempVideoWidth;
        }
        for (var i = 0; i < length; i++) {
            video = videos[i];
            if (video)
                video.width = maxVideoWidth - minus;
        }
    }

    window.onresize = scaleVideos;
    </script>
    </body>
</html>
