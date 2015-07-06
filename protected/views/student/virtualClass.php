<!--直播begin-->
<link href="<?php echo CSS_URL; ?>braodcast_style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>getMediaElement.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_URL; ?>index.css" rel="stylesheet" type="text/css" />
<!--end-->
<!--点播 begin-->
<link href="<?php echo CSS_URL; ?>webrtc_style.css" rel="stylesheet" type="text/css"/>
<style>
.media-container{display: block;margin-bottom: 10px;margin-right: 10px;}//直播框
/*#aaa{border: 5px solid red;}
#bbb{border: 5px solid red;}*/
</style>
<!--点播end-->
<!--自定义css begin-->
<link href="<?php echo CSS_URL; ?>my_style.css" rel="stylesheet" type="text/css" />
<!--自定义css end-->

<script>
    var $$=jQuery;
    $$(function(){
    $i=1;
    $$('#parent').click(function(e){
        if(e.target==$('#bu_zhibo')[0]){
    $$('#controll2').css('display','none');        
    $$('#controll1').css('display','block');
    function teacher_auto(){
    if(!!$$('#setup-new-room')){
        $$('#setup-new-room').on('click',function(){
            $$('#zhibo').css('display','none');
       });
        $$('#setup-new-room').click();
    }
    }
    if($i==1){
      teacher_auto();
      $i++;
    }
    }else if(e.target==$('#bu_dianbo')[0]){
           $$('#controll1').css('display','none');
           $$('#controll2').css('display','block'); 
        }
    });
    //  控制显示直播数量       
    //    $$('#aaa').css('background','red');
    });
</script>

<body style="overflow-x:hidden;">
<!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN PAGE -->
            <div class="page-content">
                <!-- 右侧内容展示开始-->
                <div class="container-fluid">
                    <div class="row-fluid" style="margin:20px 0;" id="parent">
                        <div class="span12">
                            <a class="btn btn-info offset5" id="bu_zhibo" >直播</a>
                            <a class="btn btn-info" id="bu_dianbo">点播</a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row-fluid" style="margin-bottom: 20px;">
                        <div class="span6" style="height:750px;overflow-y:auto;overflow-x:hidden;" id="display_zhibo" >
                            <script src="http://<%php echo HOST_IP %>:8888/socketio.js"></script>
                            <script src="http://192.168.101.235:8888/js/RTCPeerConnection-v1.5.js" ></script>
                            <script src="http://192.168.101.235:8888/js/conference.js" ></script>
                            <script src="http://192.168.101.235:8888/js/getMediaElement.min.js" ></script>
                            <script src="http://192.168.101.235:8888/js/navigator.customGetUserMediaBar.js" ></script>
                        <div class="github-stargazers"></div>
                        <div id="controll1">
                        <section class="experiment">
                        <table style="width: 100%;" id="rooms-list"></table>
                        <div id="videos-container"></div>
                         </section>
                        </div>
                          <!--点播begin-->
                        <div id="controll2">
                            <video id="video1" width="600px" height="680px">
                                <source src="<?php echo WWW; ?>dianbo_video/1.mp4">
                            </video>
                            <div id="link">
                            <button  onclick="WebSocketTest()" class="btn btn-primary">连接</button> 
                            </div>
                        </div>
                     <!--点播end-->
                    </div>
                    <div class="span6" style="">
                        <?php
                            $this->beginwidget('application.extensions.MjmChat.MjmChat', array(
                                'title' => 'Chat room',
                                'rooms' => array('php' => '聊天室'),
                                'host' => 'http://192.168.101.235',
                                'port' => '3001',));
                        ?>
                        <?php
                            $this->endWidget();
                        ?>
                    </div>  
                        
                    </div>
                </div>
                
            </div>
            <!-- END PAGE -->
            
        </div>
        <!-- END CONTAINER -->
        <!--直播begin-->
        <script src="<?php  echo JS_URL;?>jquery-2.1.3.min.js"></script>
        <script src="<?php echo JS_URL; ?>zhibo.js" ></script>
        <!--直播end-->
        <!--点播begin-->
        <script src="<?php echo JS_URL; ?>dianbo.js" ></script>
        <!--点播end-->
</body>

      