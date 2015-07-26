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

                    <!-- sunpy: if teachers -->
                    <?php if ($role == 'teacher') { ?>

                        <div style="display:inline;padding-right:60px;">
                            <button id="share-screen" style="font-size:20px;height:40px">屏幕</button>
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
                                                   
                        <!-- sunpy: if students -->    
                    <?php } else { ?>
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
                                    <td align="center" height="15px" id="sw-show-screen666">屏幕共享</td>
                                    <td align="center" height="15px" id="sw-show-dianbo">点播文件</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div id="videos-container" style="height: 100%; width: 100%; margin-top:45px; display:none"></div>
                        
                        <div id="dianbo-videos-container" style="margin-top:45px;display:none">  </div>                                                
                    <?php } ?>    
                </div>

                <div class="right">
                    <div>
                        <div align="center" id="sw-teacher-camera"><h4>教 师 视 频</h4></div>
                        <button id="ssssssssssssssssssss">dddddd</button>
                        <div id="teacher-camera" style="border:1px solid #ccc; margin-left:auto;margin-right:auto;width:80%; height:202px; clear:both;"></div>
                        <div align="center" id="sw-bulletin"><h4>通 知 公 告</h4></div>
                        <div id="bulletin" class="bulletin" style="display:none">
                            <?php if ($role == 'teacher') { ?>
                                <textarea id="bulletin-textarea" style="margin-left:auto;margin-right:auto;width:100%; height:200px;margin:0; padding:0;clear:both"></textarea>
                                <button id="postnotice" name="发布公告" class="btn btn-primary" style="margin-left: 100px; margin-top: 5px;">发布公告</button>
                            <?php } else {?>
                                <textarea disabled id="bulletin-textarea" style="margin-left:auto;margin-right:auto;width:100%; height:200px;margin:0; padding:0;clear:both"></textarea>
                            <?php }?>
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

document.getElementById('ssssssssssssssssssss').onclick = function() {
    this.disabled = true;
    connection.join("lichao");
};

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

connection.onstream = function(event) {
    document.body.appendChild(event.mediaElement);
};
</script>


                <!-- sunpy: chatroom -->
                <script>
                    $(function() {
                        var current_date = new Date();
                        var current_time = current_date.toLocaleTimeString();

                        $("#postnotice").click(function() {
                            var text = $("#bulletin-textarea").val();
                            $.ajax({
                                type: "POST",
                                url: "index.php?r=api/putBulletin",
                                data: {bulletin: '"' + text + '"', time: '"' + current_time + '"'},
                                success: function(){alert('公告发布成功！');},
                                error: function(){alert('出错了...');}
                            });
                        })
                    })

                    // ------------------------------------------------------ poll latest bulletin
                    /*第一次读取最新通知*/
                    setTimeout(function() {
                        pollBulletin();
                    }, 200);

                    function pollBulletin() {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: "index.php?r=api/GetLatestBulletin",
                            success: function(data) {
                                console.log(data[0].id);
                                if (role === 'student') {
                                    $("#bulletin-textarea").val(data[0].content);
                                } else {
                                    if ($("#bulletin-textarea").val() === "") {
                                        $("#bulletin-textarea").val(data[0].content);
                                    }
                                }
                            }
                        });
                    }

                    // ------------------------------------------------------ poll chat
                    setInterval(function() {
                        pollChatRoom();
                    }, 1000);

                    function pollChatRoom() {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: "index.php?r=api/getlatestchat",
                            success: function(data) {
                                $("#chatroom").empty();
                                var html = "";
                                var obj = eval(data);
                                $.each(obj, function(entryIndex, entry) {
                                    html += entry['username'] + "：" + entry['chat'] + "<br>";
                                });
                                $("#chatroom").append(html);
                                //$("#chatroom").scrollTop($("#chatroom").height);
                            }
                        });
                    }

                    // ------------------------------------------------------ send chat
                    $(function() {
                        $("#send-msg").click(function() {
                            var messageField = $('#messageInput');
                            var msg = messageField.val();
                            messageField.val('');

                            var current_date = new Date();
                            var current_time = current_date.toLocaleTimeString();

                            $.ajax({
                                type: "POST",
                                url: "index.php?r=api/putChat",
                                data: {
                                    username: '"' + current_username + '"',
                                    chat: '"' + msg + '"',
                                    time: '"' + current_time + '"'
                                }
                            })
                        })
                    })
                </script>

                <!-- sunpy: switch camera and bulletin -->
                <script>
                    $("#sw-teacher-camera").click(function() {
                        $("#teacher-camera").toggle('slow');
                    });
                    $("#sw-chat").click(function() {
                        $("#chat-box").toggle('slow');
                    });
                    $("#sw-bulletin").click(function() {
                        $("#bulletin").toggle('slow');
                    });
                </script>
                
                <!-- sunpy: switch dianbo and screen -->
                <script>
                    $("#sw-show-dianbo").click(function() {
                        $("#sw-show-dianbo").css("background-color", "#d9d9d9");
                        $("#sw-show-screen").css("background-color", "white");
                        $("#sw-show-screen").css("filter", "Alpha(opacity=50)");
                        $("#videos-container").hide();
                        $("#dianbo-videos-container").show();
                    });

                    $("#sw-show-screen").click(function() {
                        $("#sw-show-screen").css("background-color", "#d9d9d9");
                        $("#sw-show-dianbo").css("background-color", "white");
                        $("#sw-show-dianbo").css("filter", "Alpha(opacity=50)");
                        $("#videos-container").show();
                        $("#dianbo-videos-container").hide();
                    });
                </script>                
