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

<script src="<?php echo JS_URL; ?>MyMultiConnection.js"></script>        <!-- required -->
<!--直播begin-->
<link href="<?php echo CSS_URL; ?>braodcast_style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>getMediaElement.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>index.css" rel="stylesheet" type="text/css" />
<!--点播 begin-->
<link href="<?php echo CSS_URL; ?>webrtc_style.css" rel="stylesheet" type="text/css"/>
<!--点播end-->

<?php
$username = Yii::app()->user->name;
echo "<script>var current_username=\"$username\";</script>";

$role = Yii::app()->session['role_now'];
echo "<script>var role='$role';</script>";
?>


<!--自定义css begin-->
<link href="<?php echo CSS_URL; ?>my_style.css" rel="stylesheet" type="text/css" />
<!--自定义css end-->

                <div class="left">
                    <span style="display:none">
                        <a href="/Pluginfree-Screen-Sharing/" target="_blank" title="Open this link for private screen sharing!">
                            <code style="display:none"><strong id="unique-token">#123456789</strong></code>
                        </a>
                    </span>
                        <!-- sunpy: video start -->
                        <button id="student-close-camera" style="display:none">关闭视频</button>
                        <button id="student-open-camera" style="display:none">打开视频</button>
                        <!-- sunpy: video end-->

                        <!-- list of all available broadcasting rooms -->
                        <table style="width: 100%;" id="rooms-list"></table>

                        <!-- local/remote videos container -->
                        <div style="display:block;padding-top:2px;font-size:15px;margin-left:1%;margin-right:1%;height:10px;width:98%;border:none;">
                            <table style="border: 1px solid #d9d9d9;" height="15px">
                                <tr height="15px">
                                    <td align="center" height="15px" id="sw-show-screen">屏幕共享</td>
                                    <td align="center" height="15px" id="sw-show-dianbo">点播文件</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div id="videos-container" style="height: 100%; width: 100%; margin-top:45px; display:none"></div>
                        
                        <div id="dianbo-videos-container" style="margin-top:45px;display:none">  </div>
                </div>

                <div class="right">
                    <div>
                        <div align="center" id="sw-teacher-camera"><h4>教 师 视 频</h4></div>
                        <div id="teacher-camera" style="border:1px solid #ccc; margin-left:auto;margin-right:auto;width:80%; height:202px; clear:both;"></div>
                        <div align="center" id="sw-bulletin"><h4>通 知 公 告</h4></div>
                        <div id="bulletin" class="bulletin" style="display:none">
                                <textarea disabled id="bulletin-textarea" style="margin-left:auto;margin-right:auto;width:100%; height:200px;margin:0; padding:0;clear:both"></textarea>
                        </div>
                        <div align="center" id="sw-chat"><h4>课 堂 问 答</h4></div>
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

document.getElementById('sw-show-screen').onclick = function() {
    //this.disabled = true;
    var conDes = connection.join("class");
};
</script>
