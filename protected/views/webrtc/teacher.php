<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo JS_URL; ?>RTCPeerConnection.js"></script>        <!-- required -->
<script src="<?php echo JS_URL; ?>RTCMultiConnection.js"></script>       <!-- required -->
<script src="<?php echo JS_URL; ?>globals.js"></script>                  <!-- required -->
<script src="<?php echo JS_URL; ?>MultiPeersHandler.js"></script>        <!-- required -->
<script src="<?php echo JS_URL; ?>OnIceCandidateHandler.js"></script>    <!-- required -->
<script src="<?php echo JS_URL; ?>IceServersHandler.js"></script>        <!-- required -->


<script src="<?php echo JS_URL; ?>Plugin.EveryWhere.js"></script>        <!-- optional -->
<script src="<?php echo JS_URL; ?>getUserMedia.js"></script>             <!-- optional -->
<script src="<?php echo JS_URL; ?>BandwidthHandler.js"></script>         <!-- optional -->
<script src="<?php echo JS_URL; ?>DetectRTC.js"></script>                <!-- optional -->
<script src="<?php echo JS_URL; ?>FileBufferReader.js"></script>         <!-- optional -->
<script src="<?php echo JS_URL; ?>TextSenderReceiver.js"></script>       <!-- optional -->
<script src="<?php echo JS_URL; ?>MediaStreamRecorder.js"></script>      <!-- optional -->
<script src="<?php echo JS_URL; ?>StreamsHandler.js"></script>           <!-- optional -->
<script src="<?php echo JS_URL; ?>RecordingHandler.js"></script>         <!-- optional -->
<script src="<?php echo JS_URL; ?>Screen-Capturing.js"></script>         <!-- optional -->

<script src="<?php echo JS_URL; ?>socket.io.js"></script>
<!--直播begin-->
<link href="<?php echo CSS_URL; ?>braodcast_style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>getMediaElement.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>index.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>my_style.css" rel="stylesheet" type="text/css" />

<!--点播 begin-->
<link href="<?php echo CSS_URL; ?>webrtc_style.css" rel="stylesheet" type="text/css"/>
<!--点播end-->



<?php
$username = Yii::app()->user->name;
echo "<script>var current_username=\"$username\";</script>";

$role = Yii::app()->session['role_now'];
echo "<script>var role='$role';</script>";
?>
                <div class="left">
                    <span style="display:none">
                        <a href="/Pluginfree-Screen-Sharing/" target="_blank" title="Open this link for private screen sharing!">
                            <code style="display:none"><strong id="unique-token">#123456789</strong></code>
                        </a>
                    </span>
                    
                        <div style="display:inline;padding-right:60px;">
                            <button id="share-screen" class=" btn-large btn-primary">屏幕共享</button>
                        </div>

                        <div style="display:inline;padding-right:60px;">
                            <!-- sunpy: video start -->
                            <button id="setup-new-broadcast" style="font-size:20px;height:40px">视频</button>                        
                            <button id="teacher-close-camera" style="font-size:20px;height:40px" disabled="true">关闭视频</button>
                        </div>

                        <div style="display:inline;">
                            <!-- sunpy: broadcast local video -->    
                            <button id="teacher-dianbo" style="font-size:20px;height:40px">点播</button>
                            <select id="teacher-choose-file">
                                <option value ="1.mp4">速录演示视频</option>
                                <option value="CB6601435D33002ECE7BAD33F79126D6.flv">MV-不见长安</option>
                                <option value ="h0134j6z7lp.mp4">MV-时间都去哪了</option>
                                <option value="l0015jn2cz8.mp4">MV-泡沫</option>
                            </select>
                            <!--<input type="file" placeholder="选择点播文件" id="teacher-choose-file" style="font-size:20px;width:30%"></input>-->
                            <!-- <button id="teacher-stop-dianbo">停止点播</button> -->
                        </div>
                        
                        <div id="videos-container" style="height: 100%; width: 100%; margin-top:0px; display:none">
                        </div>
                        <div id="dianbo-videos-container" style="margin-top:18px;display:none">  
                        </div>
                </div>

                <div class="right">
                    <div>
                        <div align="center" id="sw-teacher-camera"><a href="#"><h4>教 师 视 频</h4></a></div>
                        <div id="teacher-camera" style="border:1px solid #ccc; margin-left:auto;margin-right:auto;width:80%; height:202px; clear:both;"></div>
                        <div align="center" id="sw-bulletin"><a href="#"><h4>通 知 公 告</h4></a></div>
                        <div id="bulletin" class="bulletin" style="display:none">
                            
                        <textarea id="bulletin-textarea" style="margin-left:auto;margin-right:auto;width:100%; height:200px;margin:0; padding:0;clear:both"></textarea>
                        <button id="postnotice" name="发布公告" class="btn btn-primary" style="margin-left: 100px; margin-top: 5px;">发布公告</button>

                        </div>
                        <div align="center" id="sw-chat"><a href="#"><h4>课 堂 问 答</h4></a></div>
                        <div id="chat-box">
                        <div id="chatroom" class="chatroom"></div>
                        <div class="sendfoot">
                            <input type='text' id='messageInput' style="width:55%;margin-top:0px;margin-bottom:0px">
                            <button id="send-msg" style="padding-top:4px;padding-bottom:4px;height:30px;width:25%;font-size:10px">发送</button>
                        </div>
                        </div>
                    </div>
                </div>

<script>
    
// ......................................................
// ......................screen-sharing..................
// .......................UI Code........................
// ......................................................
$(document).ready(function(){
    var connection ;
    var streamid;
    $('#share-screen').click(function(){
        var cls = $(this).attr('class');
        if(cls.indexOf('btn-primary') > 0){//按钮是共享
            connection = new RTCMultiConnection('screen-sharing-id-1');
            connection.session = {
                screen: true,
                oneway: true
            };
            connection.sdpConstraints.mandatory = {
                OfferToReceiveAudio: false,
                OfferToReceiveVideo: false
            };
            connection.onstream = function(event) {
                streamid = event.streamid;
                var container = document.getElementById("videos-container");
                $("#videos-container").show();
                container.insertBefore(event.mediaElement, container.firstChild);
            };
            
            connection.open("class");
            $(this).attr('class','btn btn-large');
            $(this).html('关闭共享');
        } else {
            //按钮是关闭
            connection.leave();
            connection.removeStream(streamid);
            connection = null;
            var container = document.getElementById("videos-container");
            $("#videos-container").hide();
            $("#videos-container").empty();
            $(this).attr('class','btn-large btn-primary');
            $(this).html('共享屏幕');
        }
    });
});
</script>