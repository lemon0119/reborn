<link href="<?php echo CSS_URL; ?>window.css" rel="stylesheet">
<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo JS_URL; ?>socketio.js"></script>
<script>

</script>

<!--直播begin-->
<link href="<?php echo CSS_URL; ?>my_style.css" rel="stylesheet" type="text/css" />
<?php
echo "<script>var current_username=\"$userName\";</script>";
$role = Yii::app()->session['role_now'];
echo "<script>var role='$role';</script>";
$role = Yii::app()->session['role_now'];
$userid = Yii::app()->session['userid_now'];
$videoFilePath = $role . "/" . $userid . "/" . $classID . "/" . $on . "/video/";
$vdir = "./resources/" . $videoFilePath;
$pptFilePath = $role . "/" . $userid . "/" . $classID . "/" . $on . "/ppt/";
$pdir = "./resources/" . $pptFilePath;
$picFilePath = $role . "/" . $userid . "/" . $classID . "/" . $on . "/picture/";
$picdir = "./resources/" . $picFilePath;
$txtFilePath = $role . "/" . $userid . "/" . $classID . "/" . $on . "/txt/";
$txtdir = "./resources/" . $txtFilePath;
$voiceFilePath = $role . "/" . $userid . "/" . $classID . "/" . $on . "/voice/";
$voicedir = "./resources/" . $voiceFilePath;


$courseID = TbClass::model()->findCourseIDByClassID($classID);
$adminPptFilePath = "admin/001/$courseID/$on/ppt/";
$adminPdir = "./resources/admin/001/$courseID/$on/ppt/";
$adminPublicPdir = "./resources/public/ppt/";
$adminPublicPicdir = "./resources/public/picture/";
$adminPublicTxtdir = "./resources/public/txt/";
$adminPublicVoicedir = "./resources/public/voice/";
$adminVideoFilePath = "admin/001/$courseID/$on/video/";
$adminPublicVdir = "./resources/public/video/";
$adminVdir = "./resources/admin/001/$courseID/$on/video/";
?>

<div class="left">
    <div id="title_movie" class="title_select" style="border-bottom-left-radius: 5px;border-top-left-radius: 5px;" >
        <div   align="center"  id="sw-movie"><h4 style="line-height: 40px;">视 频 </h4></div>

    </div>
    <div id="title_picture" class="title_select" >
        <div   align="center" id="sw-picture"><h4 style="line-height: 40px;">图 片 </h4></div>
    </div>
    <div id="title_text" class="title_select" >
        <div   align="center" id="sw-text"><h4 style="line-height: 40px;">文 本 </h4></div>

    </div>
    <div id="title_ppt" class="title_select" >
        <div   align="center" id="sw-ppt"><h4 style="line-height: 40px;">PPT </h4></div>
    </div>
    <div id="title_video" class="title_select" >
        <div   align="center" id="sw-video"><h4 style="line-height: 40px;">音 频 </h4></div>

    </div>
    <div id="title_classExercise" class="title_select" >
        <div  align="center" id="sw-classExercise"><h4 >课 堂<br/>练 习 </h4></div>

    </div>

    <div id="title_bull" class="title_select" style="width: 185px;border-bottom-right-radius: 5px;border-top-right-radius: 5px;" >
        <div style="text-align: center" id="sw-bull"><h4>&nbsp;本 班 学 生：&nbsp;&nbsp;<?php echo $totle ?>&nbsp;人<br/>&nbsp;在 线 学 生：&nbsp;&nbsp;<font style="color: greenyellow" id="countPeople"><?php echo $count ?></font> 人</h4></div>

    </div>
    <button id="share-Cam" class="btn btn-primary" >直播视频</button>
    <button id="close-Cam" class="btn" disabled="disabled">关闭视频</button>
     <button id="startsign"   class='btn btn-primary' >签到</button>
     <button id="closesign"   class='btn btn-primary' >关闭签到</button>
     <div id = "st"></div>
        <button id="Absence"   class='btn btn-primary' onclick="showAbsence()" >查看缺勤</button>
    

    <div id="showOnline"  class="online"  style="display: none;border: 0px;width:120px;">
        <div id="dd" disabled="disabled"  style="border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;overflow-y: visible; overflow-x:hidden; background-color:#5e5e5e;color:yellow;width:100%; height:570px; padding:0;">
            <br/>
            <text id="dd1"   style="cursor: default;overflow-y:hidden;overflow-x:hidden;border: 0px; background-color:#5e5e5e;color:greenyellow;width:100%;  padding:0;"></text>
            <text id="dd2"   style="cursor: default; overflow-y:hidden;overflow-x:hidden;border: 0px; background-color:#5e5e5e;color:white;width:100%;  padding:0;"></text>
        </div>
    </div>

<!--    <div id="show-movie"   style="position: relative;left: 380px;display: none;border: 0px;width:100px;">-->
<!--        <div  class="title_select"  style="border-radius: 5px;pointer-events: none;background-color: gray;position:relative;right: 300px;top: 80px"  align="center" ><h4 >公 共<br/>资 源 </h4></div>-->
<div id="show-movie" style="display: none;">
    
<!--            <div  class="title_select"  style=" border-radius: 5px;pointer-events: none;background-color: gray;position:relative;bottom: 80px;"  align="center" ><h4 >公 共<br/>资 源 </h4></div>
            <div  style="width:150px;position:relative;bottom: 160px;left: 100px ">
                <select id="teacher-choose-file-public" style="width:150px;margin-top: 10px;">
                    <?php
//                    $mydir = dir($adminPublicVdir);
//                    while ($file = $mydir->read()) {
//                        if ((!is_dir("$adminPublicVdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php// echo $adminPublicVdir . iconv("gb2312", "UTF-8", $file); ?>"><?php //echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?></option>   
                            <?php
//                        }
//                    }
//                    $mydir->close();
                    ?>
                </select>
                <button id="teacher-dianbo-public" style="width: 150px;" class="btn btn-primary">点播视频</button>
                </div>-->
                
<!--                <button id="teacher-addMovie" onclick="addMovie()" style="width: 150px;top:5px;position: relative;" class="btn btn-primary">添加视频</button>    -->
            
            <!--<div  class="title_select"  onclick="addMovie()" style=" border-radius: 5px;pointer-events: none;background-color: gray;position:relative;bottom: 70px;left:300px;top:-223px;"  align="center" ><h1 >+</h1></div>  -->
            
<!--        </div>-->
        

    
<!--    新加平铺显示5/18-->
        <div style="width:150px;position:relative;left: 20px ">
            <div><h3>本 课 资 源 </h3></div>
            <div id="teacher-choose-file" class="table-bordered summary" style="width:645px;padding:15px 0 15px 0px ;">
    
    <?php
                    $mydir = dir($vdir);
                    while ($file = $mydir->read()) {
                        if ((!is_dir("$vdir/$file")) AND ( $file != ".") AND ( $file != "..")) {?>
                
                            <button id="teacher-dianbo" onclick="filePath1('<?php echo $videoFilePath . iconv("gb2312", "UTF-8", $file); ?>')" style="width: 140px;height:40px;margin:10px 0 10px 15px;" class="btn btn-primary" 
                                    title="<?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file));?>" 
                                    value ="<?php echo $videoFilePath . iconv("gb2312", "UTF-8", $file); ?>">
                                <?php 
                            $myFile=substr(Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)),0,strrpos(Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)),"."));
                            if(Tool::clength($myFile)<=6){
                                echo $myFile;
                            }else{
                                echo Tool::csubstr($myFile, 0, 6) . "...";
                            }
                            ?> </button><?php
                        }
                    }
                    $mydir->close();
                    ?>
            </div>
        </div>
<div><h3> &nbsp;&nbsp;&nbsp; 公 共 资 源 </h3></div>        
<!--        <div style="display:inline;">-->
<!--            <div  style="width:150px;position:relative;right: 200px ">-->
             <div class="table-bordered summary" style="width: 700px;margin:0 0 0 20px;padding:0;">
                 <ul>
                     <li style="padding:5px 100px 5px 75px;">
                <select id="teacher-choose-file-public" style="width:250px;margin-top: 10px;">
                    <?php
                    $mydir = dir($adminVdir);
                    while ($file = $mydir->read()) {
                        if ((!is_dir("$$adminVdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php echo $adminVideoFilePath . iconv("gb2312", "UTF-8", $file); ?>" title="<?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file));?>">
                                
                                <?php $file1=Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); 
                            
                                if(Tool::clength($file1)<=15){
                                echo $file1;
                            }else{
                                echo Tool::csubstr($file1, 0, 15) . "...";
                            }
                            ?>
                            </option>   
                            <?php
                        }
                    }
                    $mydir->close();
                    ?>
                    <?php
                    $mydir = dir($adminPublicVdir);
                    while ($file = $mydir->read()) {
                        if ((!is_dir("$adminPublicVdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php echo $adminPublicVdir . iconv("gb2312", "UTF-8", $file); ?>" title="<?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file));?>">
                                <?php $publicFile= Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); 
                                if(Tool::clength($publicFile)<=15){
                                echo $publicFile;
                            }else{
                                echo Tool::csubstr($publicFile, 0, 15) . "...";
                            }
                                ?></option>   
                            <?php
                        }
                    }
                    $mydir->close();
                    ?>
                </select>
<!--                <select id="teacher-choose-file" style="width:150px;margin-top: 10px;">
                    <?php
//                    $mydir = dir($adminVdir);
//                    while ($file = $mydir->read()) {
//                        if ((!is_dir("$$adminVdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php// echo $adminVideoFilePath . iconv("gb2312", "UTF-8", $file); ?>"><?php// echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?></option>   
                            <?php
//                        }
//                    }
//                    $mydir->close();
                    ?>
                    <?php
//                    $mydir = dir($vdir);
//                    while ($file = $mydir->read()) {
//                        if ((!is_dir("$vdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php// echo $videoFilePath . iconv("gb2312", "UTF-8", $file); ?>"><?php// echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?></option>   
                            <?php
//                        }
//                    }
//                    $mydir->close();
                    ?>
                </select>-->
<!--                <button id="teacher-dianbo" style="width: 150px;" class="btn btn-primary">点播视频</button>-->

                                <button id="teacher-dianbo-public" style="width: 150px;margin-left:105px" class="btn btn-primary">点播视频</button>
                     </li>
            <!--</div>-->
                 </ul>
             </div>
    </div>

<!--5/23资源平铺-->
<div id="show-ppt" style="display: none;">

        <div style="width:150px;position:relative;left: 20px ">
            <div><h3>本 课 资 源 </h3></div>
            <div id="choose-ppt" class="table-bordered summary" style="width:645px;padding:15px 0 15px 0px;">
    
    <?php
                    $mydir = dir($pdir);
                            while ($file = $mydir->read()) {
                                if ((is_dir("$pdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                                    ?>
                
                                    
                
                            <button id="play-ppt"  onclick="filePptPath('<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                            $dir = "$pdir/$file";
                            $num = sizeof(scandir($dir));
                            $num = ($num > 2) ? ($num - 2) : 0;
                            echo $num;
                            ?>+-+tea')" 
                            title="<?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt")); ?>"
                            style="width: 140px;height:40px;margin:10px 0 10px 15px;" class="btn btn-primary" value ="<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                            $dir = "$pdir/$file";
                            $num = sizeof(scandir($dir));
                            $num = ($num > 2) ? ($num - 2) : 0;
                            echo $num;
                            ?>+-+tea"><?php
                            $myPptFile=substr(Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt")),0,strrpos(Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt")),"."));
                            if(Tool::clength($myPptFile)<=6){
                                echo $myPptFile;
                            }else{
                                echo Tool::csubstr($myPptFile, 0, 6) . "...";
                            }?></button>   
                                    <?php
                                }
                            }
                            $mydir->close();
                    ?>
            </div>
        </div>
    <div><h3> &nbsp;&nbsp;&nbsp; 公 共 资 源 </h3></div>        

             <div class="table-bordered summary" style="width: 700px;margin:0 0 0 20px;padding:0;">
                 <ul>
                     <li style="padding:5px 100px 5px 75px;">
                <select id="choose-ppt-public" style="width:250px;margin-top: 10px;">
                    <?php
                    $mydir = dir($adminPdir);
                    while ($file = $mydir->read()) {
                        if ((is_dir("$adminPdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option title="<?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt"));?>" value ="<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                            $dir = "$adminPdir/$file";
                            $num = sizeof(scandir($dir));
                            $num = ($num > 2) ? ($num - 2) : 0;
                            echo $num;
                            ?>+-+admin"><?php $filePptAdmin= Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt")); 
                            if(Tool::clength($filePptAdmin)<=15){
                                echo $filePptAdmin;
                            }else{
                                echo Tool::csubstr($filePptAdmin, 0, 15) . "...";
                            }  
                            ?></option>   
                                    <?php
                                }
                            }
                            $mydir->close();
                    ?>
                    <?php
                    $mydir = dir($adminPublicPdir);
                    while ($file = $mydir->read()) {
                        if ((is_dir("$adminPublicPdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option title="<?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt"));?>" value ="<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                            $dir = "$adminPublicPdir/$file";
                            $num = sizeof(scandir($dir));
                            $num = ($num > 2) ? ($num - 2) : 0;
                            echo $num;
                            ?>+-+admin1"><?php $filePptTea= Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt")); 
                            if(Tool::clength($filePptTea)<=15){
                                echo $filePptTea;
                            }else{
                                echo Tool::csubstr($filePptTea, 0, 15) . "...";
                            }  
                            ?></option>   
                                    <?php
                                }
                            }
                            $mydir->close();
                            ?>
                </select>

                                <button id="play-ppt-public" style="width: 150px;margin-left:105px" class="btn btn-primary">放映PPT</button>
                     </li>

                 </ul>
             </div>
    </div>
    
<!--    <div id="show-ppt"   style="position: relative;left: 380px;display: none;border: 0px;width:100px;">
        <div  class="title_select"  style="border-radius: 5px;pointer-events: none;background-color: gray;position:relative;right: 300px;top: 80px"  align="center" ><h4 >备 课<br/>资 源 </h4></div>
        <div style="display:inline;width:150px;">
            <div  style="width:150px;position:relative;right: 200px ">
                <select id="choose-ppt" style="width:150px;margin-top: 10px;">
                    <?php
//                    $mydir = dir($adminPdir);
//                    while ($file = $mydir->read()) {
//                        if ((is_dir("$adminPdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php// echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
//                            $dir = "$adminPdir/$file";
//                            $num = sizeof(scandir($dir));
//                            $num = ($num > 2) ? ($num - 2) : 0;
//                            echo $num;
                            ?>+-+admin"><?php //echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt")); ?></option>   
                                    <?php
//                                }
//                            }
//                            $mydir->close();
                            ?>
                            <?php
//                            $mydir = dir($pdir);
//                            while ($file = $mydir->read()) {
//                                if ((is_dir("$pdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                                    ?>
                            <option value ="<?php// echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
//                            $dir = "$pdir/$file";
//                            $num = sizeof(scandir($dir));
//                            $num = ($num > 2) ? ($num - 2) : 0;
//                            echo $num;
                            ?>+-+tea"><?php //echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt")); ?></option>   
                                    <?php
//                                }
//                            }
//                            $mydir->close();
                            ?>
                </select>
                <button id="play-ppt" style="width: 150px;" class="btn btn-primary">放映PPT</button>
                
            </div>
            <div  class="title_select"  style=" border-radius: 5px;pointer-events: none;background-color: gray;position:relative;bottom: 80px;"  align="center" ><h4 >公 共<br/>资 源 </h4></div>
            <div  style="width:150px;position:relative;bottom: 160px;left: 100px ">
                <select id="choose-ppt-public" style="width:160px;margin-top: 10px;">
                    <?php
//                    $mydir = dir($adminPublicPdir);
//                    while ($file = $mydir->read()) {
//                        if ((is_dir("$adminPublicPdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php //echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
//                            $dir = "$adminPublicPdir/$file";
//                            $num = sizeof(scandir($dir));
//                            $num = ($num > 2) ? ($num - 2) : 0;
//                            echo $num;
                            ?>+-+admin"><?php //echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file . ".ppt")); ?></option>   
                                    <?php
//                                }
//                            }
//                            $mydir->close();
                            ?>
                </select>
                <button id="play-ppt-public" style="width: 150px;" class="btn btn-primary">放映PPT</button>
                <button id="teacher-addMovie" onclick="addPpt()" style="width: 150px;top:5px;position: relative;" class="btn btn-primary">添加PPT</button>
            </div>
        </div>
    </div> -->
                            
<!--5/23资源平铺-->
<div id="show-picture" style="display: none;">
        <div style="width:150px;position:relative;left: 20px ">
            <div><h3>本 课 资 源 </h3></div>
            <div id="choose-pic" class="table-bordered summary" style="width:645px;padding:15px 0 15px 0px ;">
    
    <?php
    
                $mydir = dir($picdir);$i=0;
                    while ($file = $mydir->read()) {
                        $i++;
                        if ((!is_dir("$picdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                
                <button id="play-pic"  onclick="filePicPath('<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                            $dir = "$picdir";
                            $num = sizeof(scandir($dir));
                            $num = ($num > 2) ? ($num - 2) : 0;
                            echo $num;
                            ?>+-+tea+-+<?php echo $i;?>')" 
                            title="<?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?>"
                            style="width: 140px;height:40px;margin:10px 0 10px 15px ;" class="btn btn-primary" value ="<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                            $dir = "$picdir";
                            $num = sizeof(scandir($dir));
                            $num = ($num > 2) ? ($num - 2) : 0;
                            echo $num;
                            ?>+-+tea+-+<?php echo $i;?>"><?php
                            $myPicFile=substr(Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)),0,strrpos(Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)),"."));
                            if(Tool::clength($myPicFile)<=6){
                                echo $myPicFile;
                            }else{
                                echo Tool::csubstr($myPicFile, 0, 6) . "...";
                            }?></button>
                                    <?php
                                }
                            }
                            $mydir->close();

                    ?>
            </div>
        </div>
    <div><h3> &nbsp;&nbsp;&nbsp; 公 共 资 源 </h3></div>        

             <div class="table-bordered summary" style="width: 700px;margin:0 0 0 20px;padding:0;">
                 <ul>
                     <li style="padding:5px 100px 5px 75px;">
                <select id="choose-pic-public" style="width:250px;margin-top: 10px;">
                    <?php
                    $mydir = dir($adminPublicPicdir);
                    while ($file = $mydir->read()) {
                        if ((!is_dir("$adminPublicPicdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option title="<?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file));?>" value ="<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                            $dir = "$adminPublicPicdir";
                            $num = sizeof(scandir($dir));
                            $num = ($num > 2) ? ($num - 2) : 0;
                            echo $num;
                            ?>+-+admin"><?php $filePic= Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); 
                            if(Tool::clength($filePic)<=15){
                                echo $filePic;
                            }else{
                                echo Tool::csubstr($filePic, 0, 15) . "...";
                            }  
                            ?></option>   
                                    <?php
                                }
                            }
                            $mydir->close();
                    ?>

                </select>
                                <button id="play-pic-public" style="width: 150px;margin-left:105px" class="btn btn-primary">放映图片</button>
                     </li>

                 </ul>
             </div>
    </div>                            
                            
<!--    <div id="show-picture"  style="position: relative;left: 380px;display: none;border: 0px;width:100px;">
        <div  class="title_select"  style="border-radius: 5px;pointer-events: none;background-color: gray;position:relative;right: 300px;top: 80px"  align="center" ><h4 >备 课<br/>资 源 </h4></div>
        <div style="display:inline;">
            <div  style="width:150px;position:relative;right: 200px ">

                <select id="choose-pic" style="width:150px;margin-top: 10px;">
                    <?php
//                    $mydir = dir($picdir);$i=0;
//                    while ($file = $mydir->read()) {
//                        $i++;
//                        if ((!is_dir("$picdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php// echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
//                            $dir = "$picdir";
//                            $num = sizeof(scandir($dir));
//                            $num = ($num > 2) ? ($num - 2) : 0;
//                            echo $num;
                            ?>+-+tea+-+<?php// echo $i;?>"><?php// echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?></option>   
                                    <?php
//                                }
//                            }
//                            $mydir->close();
                            ?>
                </select>
                <button id="play-pic" style="width: 150px;" class="btn btn-primary">放映图片</button>
                
            </div>
            <div  class="title_select"  style=" border-radius: 5px;pointer-events: none;background-color: gray;position:relative;bottom: 80px;"  align="center" ><h4 >公 共<br/>资 源 </h4></div>
            <div  style="width:150px;position:relative;bottom: 160px;left: 100px ">
                <select id="choose-pic-public" style="width:160px;margin-top: 10px;">
                    <?php
//                    $mydir = dir($adminPublicPicdir);
//                    while ($file = $mydir->read()) {
//                        if ((!is_dir("$adminPublicPicdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php// echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
//                            $dir = "$adminPublicPicdir";
//                            $num = sizeof(scandir($dir));
//                            $num = ($num > 2) ? ($num - 2) : 0;
//                            echo $num;
                            ?>+-+admin"><?php// echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?></option>   
                                    <?php
//                                }
//                            }
//                            $mydir->close();
                            ?>
                </select>
                <button id="play-pic-public" style="width: 150px;" class="btn btn-primary">放映图片</button>
                <button id="teacher-addMovie" onclick="addPic()" style="width: 150px;top:5px;position: relative;" class="btn btn-primary">添加图片</button>
            </div>
        </div>
    </div>-->

                            <!--5/23 text资源平铺-->
<div id="show-text" style="display: none;">
    
        <div style="width:150px;position:relative;left: 20px ">
            <div><h3>本 课 资 源 </h3></div>
            <div id="choose-txt" class="table-bordered summary" style="width:645px;padding:15px 0 15px 0px ;">
    
    <?php
    
                 $mydir = dir($txtdir);
                    while ($file = $mydir->read()) {
                        if ((!is_dir("$txtdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                
                <button id="choose-txt"  onclick="fileTxtPath('<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                            $dir = "$txtdir";
                            $num = sizeof(scandir($dir));
                            $num = ($num > 2) ? ($num - 2) : 0;
                            echo $num;
                            ?>+-+tea')" 
                            title="<?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?>"
                            style="width: 140px;height:40px;margin:10px 0 10px 15px ;" class="btn btn-primary" value ="<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                            $dir = "$txtdir";
                            $num = sizeof(scandir($dir));
                            $num = ($num > 2) ? ($num - 2) : 0;
                            echo $num;
                            ?>+-+tea"><?php
                            $myTxtFile=substr(Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)),0,strrpos(Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)),"."));
                            if(Tool::clength($myTxtFile)<=6){
                                echo $myTxtFile;
                            }else{
                                echo Tool::csubstr($myTxtFile, 0, 6) . "...";
                            }?></button>
                                    <?php
                                }
                            }
                            $mydir->close();

                    ?>
            </div>
        </div>
    <div><h3> &nbsp;&nbsp;&nbsp; 公 共 资 源 </h3></div>        

             <div class="table-bordered summary" style="width: 700px;margin:0 0 0 20px;padding:0;">
                 <ul>
                     <li style="padding:5px 100px 5px 75px;">
                <select id="choose-txt-public" style="width:250px;margin-top: 10px;">
                    <?php
                    $mydir = dir($adminPublicTxtdir);
                    while ($file = $mydir->read()) {
                        if ((!is_dir("$adminPublicTxtdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                    
                            <option title="<?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file));?>" value ="<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                            $dir = "$txtdir";
                            $num = sizeof(scandir($dir));
                            $num = ($num > 2) ? ($num - 2) : 0;
                            echo $num;
                            ?>+-+tea"><?php $filtTxtPath= Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); 
                            if(Tool::clength($filtTxtPath)<=15){
                                echo $filtTxtPath;
                            }else{
                                echo Tool::csubstr($filtTxtPath, 0, 15) . "...";
                            }
                            ?></option>   
                                    <?php
                                }
                            }
                            $mydir->close();
                    ?>

                </select>
                                <button id="play-txt-public" style="width: 150px;margin-left:105px" class="btn btn-primary">阅览文本</button>
                     </li>

                 </ul>
             </div>
    </div>                            
                            
<!--    <div id="show-text"  style="position: relative;left: 380px;display: none;border: 0px;width:100px;">
        <div  class="title_select"  style="border-radius: 5px;pointer-events: none;background-color: gray;position:relative;right: 300px;top: 80px"  align="center" ><h4 >备 课<br/>资 源 </h4></div>
        <div style="display:inline;">
            <div  style="width:150px;position:relative;right: 200px ">
                <select id="choose-txt" style="width:150px;margin-top: 10px;">
                    <?php
                    $mydir = dir($txtdir);
                    while ($file = $mydir->read()) {
                        if ((!is_dir("$txtdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                            $dir = "$txtdir";
                            $num = sizeof(scandir($dir));
                            $num = ($num > 2) ? ($num - 2) : 0;
                            echo $num;
                            ?>+-+tea"><?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?></option>   
                                    <?php
                                }
                            }
                            $mydir->close();
                            ?>
                </select>
                <button id="choose-txt" style="width: 150px;" class="btn btn-primary">阅览文本</button>
            </div>
            <div  class="title_select"  style=" border-radius: 5px;pointer-events: none;background-color: gray;position:relative;bottom: 80px;"  align="center" ><h4 >公 共<br/>资 源 </h4></div>
            <div  style="width:150px;position:relative;bottom: 160px;left: 100px ">
                <select id="choose-txt-public" style="width:160px;margin-top: 10px;">
                    <?php
                    $mydir = dir($adminPublicTxtdir);
                    while ($file = $mydir->read()) {
                        if ((!is_dir("$adminPublicTxtdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php echo iconv("gb2312", "UTF-8", $file); ?>+-+<?php
                            $dir = "$adminPublicTxtdir";
                            $num = sizeof(scandir($dir));
                            $num = ($num > 2) ? ($num - 2) : 0;
                            echo $num;
                            ?>+-+admin"><?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?></option>   
                                    <?php
                                }
                            }
                            $mydir->close();
                            ?>
                </select>
                <button id="play-txt-public" style="width: 150px;" class="btn btn-primary">阅览文本</button>
                <button id="teacher-addMovie" onclick="addTxt()" style="width: 150px;top:5px;position: relative;" class="btn btn-primary">添加文本</button>
            </div>
        </div>
    </div>-->
                            
<!--5/23 voice资源平铺-->
<div id="show-voice" style="display: none;">
    
        <div style="width:150px;position:relative;left: 20px ">
            <div><h3>本 课 资 源 </h3></div>
            <div id="choose-voice" class="table-bordered summary" style="width:645px;padding:15px 0 15px 0px ;">
    
    <?php
    
                 $mydir = dir($voicedir);
                    while ($file = $mydir->read()) {
                        if ((!is_dir("$vdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                
                <button id="play-voice"  onclick="fileVoicePath('<?php echo $voiceFilePath . iconv("gb2312", "UTF-8", $file); ?>')" 
                            title="<?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?>"
                            style="width: 140px;height:40px;margin:10px 0px 10px 15px;" class="btn btn-primary" value ="<?php echo $voiceFilePath . iconv("gb2312", "UTF-8", $file); ?>"><?php
                            $myVoiceFile=substr(Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)),0,strrpos(Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)),"."));
                            if(Tool::clength($myVoiceFile)<=6){
                                echo $myVoiceFile;
                            }else{
                                echo Tool::csubstr($myVoiceFile, 0, 6) . "...";
                            }?></button>
                                    <?php
                                }
                            }
                            $mydir->close();

                    ?>
            </div>
        </div>
    <div><h3> &nbsp;&nbsp;&nbsp; 公 共 资 源 </h3></div>        

             <div class="table-bordered summary" style="width: 700px;margin:0 0 0 20px;padding:0;">
                 <ul>
                     <li style="padding:5px 100px 5px 75px;">
                <select id="choose-voice-public" style="width:250px;margin-top: 10px;">
                    <?php
                    $mydir = dir($adminPublicVoicedir);
                    while ($file = $mydir->read()) {
                        if ((!is_dir("$adminPublicVoicedir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                    
                            <option title="<?php echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file));?>" value ="<?php echo $adminPublicVoicedir . iconv("gb2312", "UTF-8", $file);?>"><?php $fileVoicePath= Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file));  
                            if(Tool::clength($fileVoicePath)<=15){
                                echo $fileVoicePath;
                            }else{
                                echo Tool::csubstr($fileVoicePath, 0, 15) . "...";
                            }
                            ?></option>   
                                    <?php
                                }
                            }
                            $mydir->close();
                    ?>

                </select>
                                <button id="play-voice-public" style="width: 150px;margin-left:105px" class="btn btn-primary">播放音频</button>
                     </li>

                 </ul>
             </div>
    </div>                            

<!--    <div id="show-voice"   style="position: relative;left: 380px;display: none;border: 0px;width:100px;">
        <div  class="title_select"  style="border-radius: 5px;pointer-events: none;background-color: gray;position:relative;right: 300px;top: 80px"  align="center" ><h4 >备 课<br/>资 源 </h4></div>
        <div style="display:inline;">
            <div  style="width:150px;position:relative;right: 200px ">
                <select id="choose-voice" style="width:150px;margin-top: 10px;">
                            <?php
//                    $mydir = dir($voicedir);
//                    while ($file = $mydir->read()) {
//                        if ((!is_dir("$vdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php// echo $voiceFilePath . iconv("gb2312", "UTF-8", $file); ?>"><?php// echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?></option>   
                            <?php
//                        }
//                    }
//                    $mydir->close();
                    ?>
                </select>
                <button id="play-voice" style="width: 150px;" class="btn btn-primary">播放音频</button>
            </div>
            <div  class="title_select"  style=" border-radius: 5px;pointer-events: none;background-color: gray;position:relative;bottom: 80px;"  align="center" ><h4 >公 共<br/>资 源 </h4></div>
            <div  style="width:150px;position:relative;bottom: 160px;left: 100px ">
                <select id="choose-voice-public" style="width:160px;margin-top: 10px;">
                             <?php
//                    $mydir = dir($adminPublicVoicedir);
//                    while ($file = $mydir->read()) {
//                        if ((!is_dir("$adminPublicVoicedir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                            ?>
                            <option value ="<?php// echo $adminPublicVoicedir . iconv("gb2312", "UTF-8", $file); ?>"><?php// echo Resourse::model()->getOriName(iconv("gb2312", "UTF-8", $file)); ?></option>   
                            <?php
//                        }
//                    }
//                    $mydir->close();
                    ?>
                </select>
                <button id="play-voice-public" style="width: 150px;" class="btn btn-primary">播放音频</button>
                <button id="teacher-addMovie" onclick="addVoice()" style="width: 150px;top:5px;position: relative;" class="btn btn-primary">添加音频</button>
            </div>
        </div>
    </div>-->
    <div id="show-classExercise"  style="position: relative;display: none;border: 0px;">
        <h3 style="margin-left: 20px;">课堂练习</h3>
        <div  style="margin-left: 30px;margin-right: 30px">
            <input style="margin-bottom: 5px;"  type="checkbox" name="all" onclick="document.getElementById('iframe_class').contentWindow.check_all(this)"  /><span class="normalfont">全选</span>
            &nbsp;<span class="normalfont">批量操作:</span>&nbsp;
            <a class="normalfont" style="cursor: pointer"  onclick="document.getElementById('iframe_class').contentWindow.checkBoxStartExercise()">开放</a>&nbsp;
            <a onclick="closeAllOpenNow()"  class="normalfont" style="cursor: pointer">全部关闭</a>&nbsp;
            <a onclick="addNewClassExercise()"  class="normalfont" style="cursor: pointer">添加</a>
            <span class="normalfont fr" style="font-weight: bolder;color:#7c8489">>></span><a  class="normalfont fr" style="cursor: pointer" onclick='document.getElementById("iframe_class").contentWindow.lookAnalysis()'>学生信息反馈</a>
        </div>
        <div style="position: relative;text-align: center;top: 10px;width: 100%;height: 550px;overflow: auto">
            <iframe id="iframe_class" style="border: 0px;height: 95%;width: 95%;"></iframe>
        </div>
    </div>
    <div id="scroll-video" style="display:inline;">
        <button id="close-dianbo" class="btn" disabled="disabled">关闭点播</button> 
    </div>
    <div id="scroll-page" style="display:inline;width: 900px">
        <button id="page-up" style="font-size: x-small" class="btn btn-primary">←</button>
        <input id="yeshu" style="width:30px;position: relative;top:4px" value="1">
        <input id="all-yeshu" style="width:30px;position: relative;top:4px" readOnly="true">
        <button id="page-go" class="btn btn-primary">跳转</button>
        <button id="page-down" style="font-size: x-small;" class="btn btn-primary">→</button>
        <button id="full-screen-button" class="btn btn-primary">全屏</button>
        <button id="close-ppt" class="btn" disabled="disabled">停止放映</button>
    </div>

    <div id="videos-container" style="height: 100%; width: 100%; margin-top:0px;display:none">
        <iframe src="" name="iframe_a" style="width: 100%; height: 100%; margin-top:0px; margin-left:0px;" frameborder="0" scrolling="no"></iframe>
    </div>
    <div id="dianbo-videos-container" style="display:none;margin-top: 0px;"> 
    </div>
    <div id="ppt-container" align="center" style="width: 100% ; height: 560px;  margin-top:0px;display:none;overflow-x: hidden">
        <img id="ppt-img" src="" style="height: 100%;"/>
    </div>
    <div id="txt-container" align="center" style="width: 100% ; height: 100%;  margin-top:0px;display:none">
        <textarea id="txt-textarea" style="background:transparent;border-style:none; width: 720px;height: 600px" disabled="disable">
        </textarea>
    </div>

</div>
<div class="right"style="background-color: #3b3b3b;border: 0px" >

    <div align="center" id="sw-teacher-camera"><a href="#"><h4 style="color: white">教 师 视 频</h4></a></div>  
    <div id="teacher-camera" style="border:0px solid #ccc; margin-left:auto;margin-right:auto;width:100%; height:280px; clear:both;">
        <iframe src="./index.php?r=webrtc/teaCam&&classID=<?php echo $classID; ?>" name="iframe_b" style="background-color:#5e5e5e; width: 100%; height: 100%; margin-top:0px; margin-left:0px;" frameborder="0" scrolling="no" allowfullscreen></iframe>
    </div>
    <!--            <div align="center" id="sw-bulletin"><a href="#"><h4 style="color: white">通 知 公 告</h4></a></div>
                <div id="bulletin" class="bulletin" style="display:none;border: 0px;width: 100%;margin-left: -1.1px">
    
                <textarea id="bulletin-textarea" style="background-color:#5e5e5e;color:yellow;margin-left:auto;margin-right:auto;width:100%; height:200px;margin:0; padding:0;clear:both"oninput="this.style.color='red'"></textarea>
                <a id="postnoticeTea"></a>
=======
            <textarea id="bulletin-textarea" style="background-color:#5e5e5e;color:yellow;margin-left:auto;margin-right:auto;width:100%; height:200px;margin:0; padding:0;clear:both"oninput="this.style.color='red'"></textarea>
            <a id="postnoticeTea"></a>
            
            </div>
            <div align="center" id="sw-chat"><a href="#"><h4 style="color: white">课 堂 问 答</h4></a> <button onclick="checkforbid()">查看禁言</button></div>            
            <div id="chat-box" style="border: 0px">   
                <div id="chatroom" class="chatroom" style="background-color:#5e5e5e;border: 0px;width: 100%">
                 </div>
>>>>>>> 
                
    
                </div>-->
    <div align="center" id="sw-chat" style="width:100%">
        <div style="width: 50%;float: left">
        <a href="#"><h4 style="color: white">课 堂 问 答</h4></a>
        </div>
        <div style="width: 50%;float: right">
            
            <a href="#"><h4 onclick="checkforbid()" style="color: white" >查 看 禁 言</h4></a>
        </div>
    </div>            
    <div id="chat-box" style="border: 0px">   
        <div id="chatroom" class="chatroom" style="height:277px;background-color:#5e5e5e;border: 0px;width: 100%">
        </div>

        <div class="sendfoot" style="width: 100%;height: 100%;border: 0px;margin-left: -1.5px">
            <input onfocus="setPress()"onblur="delPress()" type='text' id='messageInput' style="border: 0px;width:283px;height:26px; margin-top:0px;margin-bottom:0px;margin-right: 0px;color:gray" oninput="this.style.color='black'">
            <a  id="send-msg"></a>

        </div>
    </div>

</div>
<script>
//全屏
    $('#full-screen-button').on('click', function () {
        window.wxc.xcConfirm("按方向键左右进行跳转，按Esc退出！", window.wxc.xcConfirm.typeEnum.warning, {
            onOk: function () {
                var docelem = document.getElementById('ppt-container');
                if (docelem.requestFullscreen) {
                    docelem.requestFullscreen();
                } else if (docelem.webkitRequestFullscreen) {
                    docelem.webkitRequestFullscreen();
                } else if (docelem.mozRequestFullScreen) {
                    docelem.mozRequestFullScreen();
                } else if (docelem.msRequestFullscreen) {
                    docelem.msRequestFullscreen();
                }
            }
        });
    });

    function keyDown(e) {
        var keycode = e.which;

//     var realkey = String.fromCharCode(e.which);   　　 　　    
        if (cur_ppt != -1)
        {
            if (keycode == 37)
            {
                pageUp();
            } else if (keycode == 39)
            {
                pageDown();
            }
        }
    }

    document.onkeydown = keyDown;

    function delPress() {
        document.onkeydown = function (event) {
            e = event ? event : (window.event ? window.event : null);
            e.returnValue = false;
        }
    }
    function setPress() {
        //綁定enter
        document.onkeydown = function (event) {
            e = event ? event : (window.event ? window.event : null);
            if (e.keyCode == 13) {
                var messageField = $('#messageInput');
                var msg = messageField.val();
                messageField.val('');

                var current_date = new Date();
                var current_time = current_date.toLocaleTimeString();
                $.ajax({
                    type: "POST",
                    url: "index.php?r=api/putChat&&classID=<?php echo $classID; ?>",
                    data: {
                        username: '"' + current_username + '"',
                        chat: '"' + msg + '"',
                        time: '"' + current_time + '"'
                    }
                });
            }
        }
    }
</script>

<script>
    var isOnLive = "";
    var picOrppt = "";
    //chat and bulletin   
    $(document).ready(function () {
        checkLeave();
         $.ajax({
                    type: "POST",
                    //消除检查离开状态
                    //url: "index.php?r=teacher/closeAllOpenExerciseNow",
                    data: {},
                    success: function (data) {
                    },
                });
        /*
         $("div.container div.navbar div.navbar-inner div.container div.nav-collapse ul.nav li.dropdown ul.dropdown-menu li").find("a").click(function() {
         var url=$(this).attr("href");
         if(url.indexOf("index.php")>0){
         $.ajax({
         type: "POST",
         url: "index.php?r=api/updateVirClass&&classID=<?php echo $classID; ?>",
         data: {},
         success: function(){ 
         window.location.href = url;
         //window.wxc.xcConfirm('更新成功！', window.wxc.xcConfirm.typeEnum.success);
         },
         error: function(xhr, type, exception){
         window.wxc.xcConfirm('出错了呀...', window.wxc.xcConfirm.typeEnum.error);
         console.log(xhr.responseText, "Failed");
         }
         });
         return false;
         }
         });*/
        var current_date = new Date();
        var current_time = current_date.toLocaleTimeString();
        $("#postnoticeTea").click(function () {
            var text = $("#bulletin-textarea").val();
            $.ajax({
                type: "POST",
                url: "index.php?r=api/putBulletin&&classID=<?php echo $classID; ?>",
                data: {bulletin: '"' + text + '"', time: '"' + current_time + '"'},
                success: function () {
                    window.wxc.xcConfirm('公告发布成功！', window.wxc.xcConfirm.typeEnum.success);
                },
                error: function (xhr, type, exception) {
                    window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                    console.log(xhr.responseText, "Failed");
                }
            });
        });

        $("#send-msg").click(function () {
            var messageField = $('#messageInput');
            var msg = messageField.val();
            messageField.val('');
            var current_date = new Date();
            var current_time = current_date.toLocaleTimeString();
            $.ajax({
                type: "POST",
                url: "index.php?r=api/putChat&&classID=<?php echo $classID; ?>",
                data: {
                    username: '"' + current_username + '"',
                    chat: '"' + msg + '"',
                    time: '"' + current_time + '"', }
            });
        });


        //每5秒，发送一次时间
        setInterval(function () {    //setInterval才是轮询，setTimeout是一定秒数后，执行一次的！！
            checkLeave();
        }, 5000);

        // ------------------------------------------------------ on line
        setInterval(function () {
            getBackTime();
            //freshOnline();
        }, 4000);
        // ------------------------------------------------------ poll latest bulletin
        /*第一次读取最新通知*/
        setTimeout(function () {
            pollBulletin();
        }, 200);
        /*30轮询读取函数*/
        setInterval(function () {
            pollBulletin();
        }, 10000);
        // ------------------------------------------------------ poll chat
        setInterval(function () {
            pollChatRoom();
        }, 1000);

        // ------------------------------------------------------ send chat

    });

    function checkLeave() {
        $.ajax({
            type: "POST",
            url: "index.php?r=api/updateVirClass&&classID=<?php echo $classID; ?>&&number=<?php echo $_GET['on']; ?>",
            data: {},
            success: function () {
            },
            error: function (xhr, type, exception) {
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
        });
        return false;
    }
    function getBackTime() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "index.php?r=api/GetStuOnLine&&classID=<?php echo $classID; ?>",
            success: function (data) {
                var now =<?php echo time() ?>;    //这个时间是页面进入的时候，生成的。
                //虽然点击的时候，才会执行这个js代码，但是，php是加载的时候就已经生成了
                //也就是说，等到用户点击，这个时间now的值，是加载页面的时间。
                //var user = new Array(0,1,2,3,4);
                $("#ff").val(data[2]);
                var content = data[0].join("<br/>&nbsp;&nbsp;&nbsp;&nbsp;");
                var content2 = data[1].join("<br/>&nbsp;&nbsp;&nbsp;&nbsp;");
                $("#dd1").html("&nbsp;&nbsp;&nbsp;&nbsp;" + content);
                $("#countPeople").html(data[0].length);
                $("#dd2").html("<br/>&nbsp;&nbsp;&nbsp;&nbsp;" + content2);
            },
            error: function (xhr, type, exception) {
                console.log('get backtime error', type);
                console.log(xhr, "Failed");
            }
        });
    }
    function pollChatRoom() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "index.php?r=api/getlatestchat&&classID=<?php echo $classID; ?>",
            success: function (data) {
                $("#chatroom").empty();
                var html = "";
                var obj = eval(data);
                $.each(obj, function (entryIndex, entry) {
                    if (entry['identity'] == 'teacher')
                        html += "<font color=\"#00FF00\">" + entry['username'] + "</font>：" + "<font color=\"#fff\">" + entry['chat'] + "</font><br>";
                    else
                    {
                        html += "<a onclick=shitup('" + entry['userid'] + "') href=\"#\">" + entry['username'] + "</a>" + "：" + entry['chat'] + "<br>";
                    }
                });
                $("#chatroom").append(html);
                //$("#chatroom").scrollTop($("#chatroom").height);
            }
        });
    }

    function shitup(userid) {
        var txt = "确定要禁言吗";
        var option = {
            title: "禁言",
            btn: parseInt("0011", 2),
            onOk: function () {
                $.ajax({
                    type: "GET",
                    url: "index.php?r=teacher/shitup&&userid=" + userid,
                    success: function () {
                        var txt = "成功！";
                        window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.success);
                    },
                    error: function () {
                        var txt = "失败！";
                        window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.success);
                    }
                });
            }
        };
        window.wxc.xcConfirm(txt, "custom", option);
    }

    function pollBulletin() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "index.php?r=api/GetLatestBulletin&&classID=<?php echo $classID; ?>",
            success: function (data) {
                if (role === 'student') {
                    $("#bulletin-textarea").val(data[0].content);
                } else {
                    if ($("#bulletin-textarea").val() === "") {
                        $("#bulletin-textarea").val(data[0].content);
                    }
                }
            },
            error: function (xhr, type, exception) {
                console.log('get Bulletin erroe', type);
                console.log(xhr.responseText, "Failed");
            }
        });
    }
</script>

<script>
    function js_method() {
        alert("ty");
    }
    //sunpy: switch camera and bulletin
    $(document).ready(function () {
        var flag = "";
        $("#sw-teacher-camera").click(function () {
            $("#teacher-camera").toggle(200);
        });
        $("#sw-chat").click(function () {
            $("#chat-box").toggle(200);
        });
        $("#sw-movie").click(function () {
            if (flag === "movie") {
                flag = "";
                $("#title_movie").css({"color": "#fff"});
            } else {
                flag = "movie";
                $("#title_movie").css({"color": "#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#show-movie").toggle(200);
        });
        $("#sw-picture").click(function () {
            if (flag === "picture") {
                flag = "";
                $("#title_picture").css({"color": "#fff"});
            } else {
                flag = "picture";
                $("#title_picture").css({"color": "#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#show-picture").toggle(200);
        });
        $("#sw-text").click(function () {
            if (flag === "text") {
                flag = "";
                $("#title_text").css({"color": "#fff"});
            } else {
                flag = "text";
                $("#title_text").css({"color": "#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#show-text").toggle(200);
        });
        $("#sw-ppt").click(function () {
            if (flag === "ppt") {
                flag = "";
                $("#title_ppt").css({"color": "#fff"});
            } else {
                flag = "ppt";
                $("#title_ppt").css({"color": "#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#show-ppt").toggle(200);
        });
        $("#sw-video").click(function () {
            if (flag === "video") {
                flag = "";
                $("#title_video").css({"color": "#fff"});
            } else {
                flag = "video";
                $("#title_video").css({"color": "#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#show-voice").toggle(200);
        });
        $("#sw-classExercise").click(function () {
            document.getElementById('iframe_class').src = "index.php?r=teacher/tableClassExercise4virtual&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['on']; ?>&&on=<?php echo $_GET['on']; ?>";
            if (flag === "classExercise") {
                flag = "";
                $("#title_classExercise").css({"color": "#fff"});
            } else {
                flag = "classExercise";
                $("#title_classExercise").css({"color": "#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#show-classExercise").toggle(200);
        });
//    $("#sw-bulletin").click(function() {
//        $("#bulletin").toggle(200);
//    });
        $("#sw-bull").click(function () {
            if (flag === "showOnline") {
                flag = "";
                $("#title_bull").css({"color": "#fff"});
            } else {
                flag = "showOnline";
                $("#title_bull").css({"color": "#f46500"});
            }
            closeTitleWithoutFlag(flag);
            $("#showOnline").toggle(200);
        });
        getBackTime();
    });
</script>

<script>
    $("#scroll-page").hide();
    $("#scroll-video").hide();
    $(document).ready(function () {
        $.ajax({
            type: "POST",
            url: "index.php?r=teacher/openClassExercise",
            data: {exerciseID: 0},
            success: function (data) {
            },
        });
        //打开连接
        openConnect();

        $("#play-ppt-public").click(function () {
        exitNowOn();
            isOnLive = "play-ppt";
            $("#voice").attr("src", "");
            window.picOrppt = "ppt";
            closeAllTitle();
            if ($("#choose-ppt-public")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);
            document.getElementById("close-ppt").disabled = false;
            $("#close-ppt").attr("class", "btn btn-primary");
            $("#ppt-container").show();
            $("#txt-container").hide();
            $("#voice-container").hide();
            $("#scroll-page").show();
            $("#scroll-page").show();
            $("#page-up").show();
            $("#page-go").show();
            $("#yeshu").show();
            $("#all-yeshu").show();
            $("#page-down").show();
            $("#full-screen-button").show();

            cur_ppt = 1;
            var file_info = $("#choose-ppt-public option:selected").val().split("+-+");
            console.log(file_info);
            var source = file_info[2];
            var server_root_path;
            
            if (source === "admin")
            {
                server_root_path = "<?php echo SITE_URL . 'resources/' . $adminPptFilePath; ?>";
            } else {
                server_root_path = "<?php echo $adminPublicPdir;; ?>";
            }
            var dirname = file_info[0];
            ppt_dir = server_root_path + dirname;
            ppt_pages = file_info[1];
            $("#all-yeshu").val(ppt_pages);
            goCurPage();
            if (timer_ppt !== null)
                clearInterval(timer_ppt);
            timer_ppt = setInterval(function () {
                var syn_msg;
                syn_msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
                ws.send(syn_msg);
            }, 4000);
        });
        $("#page-up").click(function () {
            pageUp();
        });
        $("#page-go").click(function () {
            var input_page = $("#yeshu").val();
            input_page = input_page - 1 + 1;
            if ((input_page >= 1) && (input_page <= ppt_pages))
            {
                cur_ppt = input_page;
                goCurPage();
            } else {
                window.wxc.xcConfirm("请输入合适范围的页数！", window.wxc.xcConfirm.typeEnum.info);
            }
        });
        $("#page-down").click(function () {
            pageDown();
        });
        $("#close-ppt").click(function () {
            $("#voice").attr("src", "");
            cur_ppt = -1;
            ppt_pages = -1;
            if (timer_ppt !== null)
                clearInterval(timer_ppt);
            var msg = "<?php echo $classID; ?>closeppt";
            ws.send(msg);
            this.disabled = true;
            $("#close-ppt").attr("class", "btn");
            if(document.getElementById("play-ppt")!=null){
            document.getElementById("play-ppt").disabled = false;
        }
            $("#ppt-container").hide();
            $("#voice-container").hide();
            $("#scroll-page").hide();
            $("#txt-container").hide();
        });
        


        $("#close-Cam").click(function () {
            var msg = "<?php echo $classID; ?>closeCam";
            ws.send(msg);
            if (timer_cam != null)
                clearInterval(timer_cam);
            this.disabled = true;
            $("#close-Cam").attr("class", "btn");
            document.getElementById("share-Cam").disabled = false;
            $("#share-Cam").attr("class", "btn btn-primary");
            iframe_b.location.href = './index.php?r=webrtc/null';
            iframe_b.location.href = './index.php?r=webrtc/teaCam&&classID=<?php echo $classID; ?>';
        });



        $("#teacher-dianbo-public").click(function () {
            exitNowOn();
            isOnLive = "teacher-dianbo";
            $("#voice").attr("src", "");
            //disablebutton("teacher-dianbo");
            closeAllTitle();
            if ($("#teacher-choose-file-public")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);
            $("#scroll-video").show();
            document.getElementById("close-dianbo").disabled = false;
            $("#close-dianbo").attr("class", "btn btn-primary");
            var server_root_path = "<?php echo SITE_URL; ?>";
            var filepath = $("#teacher-choose-file-public option:selected").val();
            var file_info=$("#teacher-choose-file-public option:selected").val().split("/");
            console.log(file_info);
            var source = file_info[0];
            var server_root_path;
            if (source === "admin")
            {
                server_root_path = "<?php echo SITE_URL . 'resources/' ; ?>";
            } else {            
                server_root_path = "<?php echo SITE_URL ; ?>";
            }
            
            console.log(filepath);
            var absl_path = server_root_path + filepath;
            var video_element;
            var video_time_duration;

            var video = document.getElementById('video1');
            if (video === null) {
                var html = "";
                html += '<video id="video1" width="100%" controls>';
                html += '<source src="' + absl_path + '">';
                html += '</video>';
                $("#dianbo-videos-container").empty();
                $("#dianbo-videos-container").append(html);
            } else {
                video.setAttribute("src", absl_path);
            }
            $("#dianbo-videos-container").show();
            $("#videos-container").hide();
            video_element = document.getElementById("video1");
            video_element.onloadedmetadata = function () {
                video_time_duration = video_element.duration;
                console.log("sunpy: video duration " + video_time_duration);
            };
            WebSocketConnect(absl_path);
        });

        $("#close-dianbo").click(function () {
            $("#scroll-video").hide();
            $("#play-ppt").attr("class", "btn btn-primary");
            clearVideo();
            this.disabled = true;
            $("#close-dianbo").attr("class", "btn");
        });


        $("#play-pic-public").click(function () {
            exitNowOn();
            isOnLive = "play-pic";
            $("#voice").attr("src", "");
            window.picOrppt = "pic-public";
            closeAllTitle();
            if ($("#choose-pic-public")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);
            if(document.getElementById("close-ppt")!=null){
             document.getElementById("close-ppt").disabled = false;
         }
            $("#close-ppt").attr("class", "btn btn-primary");
            $("#ppt-container").show();
            $("#txt-container").hide();
            $("#voice-container").hide();
            $("#scroll-page").show();
            
            cur_ppt = 1;
            var file_info = $("#choose-pic-public option:selected").val().split("+-+");
            console.log(file_info);
            var source = file_info[2];
            var server_root_path = "<?php echo $adminPublicPicdir; ?>";
            var dirname = file_info[0];
            ppt_dir = server_root_path + dirname;
            ppt_pages = file_info[1];
            $("#all-yeshu").val(ppt_pages);
            goCurPage();
            if (timer_ppt !== null)
                clearInterval(timer_ppt);
            timer_ppt = setInterval(function () {
                var syn_msg;
                syn_msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
                ws.send(syn_msg);
            }, 4000);
        });


        $("#play-txt-public").click(function () {
            exitNowOn();
            isOnLive = "play-txt";
            $("#voice").attr("src", "");
            window.picOrppt = "txt-public";
            closeAllTitle();
            if ($("#choose-txt-public")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);
            document.getElementById("close-ppt").disabled = false;
            $("#close-ppt").attr("class", "btn btn-primary");
            $("#ppt-container").hide();
            $("#txt-container").show();
            $("#voice-container").hide();
            $("#scroll-page").show();
            $("#page-up").hide();
            $("#page-go").hide();
            $("#yeshu").hide();
            $("#all-yeshu").hide();
            $("#page-down").hide();
            $("#full-screen-button").hide();
            cur_ppt = 1;
            var file_info = $("#choose-txt-public option:selected").val().split("+-+");
            var source = file_info[2];
            var server_root_path = "<?php echo $adminPublicTxtdir; ?>";
            var dirname = file_info[0];
            ppt_dir = server_root_path + dirname;
            ppt_pages = file_info[1];
            $("#all-yeshu").val(ppt_pages);
            goCurPage();
        });



        $("#play-voice-public").click(function () {
           exitNowOn();
            isOnLive = "play-voice";
            $("#voice").attr("src", "");
            closeAllTitle();
            if ($("#choose-voice-public")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);

            $("#scroll-video").show();
            document.getElementById("close-dianbo").disabled = false;
            $("#close-dianbo").attr("class", "btn btn-primary");
            var server_root_path = "<?php echo SITE_URL; ?>";
            var filepath = $("#choose-voice-public option:selected").val();
            var absl_path = server_root_path+filepath;
            var video_element;
            var video_time_duration;


            var video = document.getElementById('video1');
            if (video === null) {
                var html = "";
                html += '<audio style="margin-bottom:200px;height:400px" id="video1" class="div_listen" width="100%" controls>';
                html += '<source src="' + absl_path + '">';
                html += '</audio>';
                $("#dianbo-videos-container").empty();
                $("#dianbo-videos-container").append(html);
            } else {
                video.setAttribute("src", absl_path);
            }
            $("#dianbo-videos-container").show();
            $("#videos-container").hide();
            video_element = document.getElementById("video1");
            video_element.onloadedmetadata = function () {
                video_time_duration = video_element.duration;
                console.log("sunpy: video duration " + video_time_duration);
            };
            WebSocketConnect(absl_path);
        });

          
    });

    var play_fun = null;
    var pause_fun = null;
    var timer = null;
    var timer_ppt = null;
    var timer_cam = null;
    var ws = null;
    var cur_ppt = -1;
    var ppt_pages = -1;
    var ppt_dir = null;
    function goPicCurPage() {
        $("#yeshu").val(cur_ppt);
        $("#ppt-img").attr("src", ppt_dir);
        var msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
        ws.send(msg);
    }
    
    function filePath1(path){
           exitNowOn();
            isOnLive = "teacher-dianbo";
            $("#voice").attr("src", "");
            closeAllTitle();
            if ($("#teacher-choose-file")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);

            $("#scroll-video").show();
            document.getElementById("close-dianbo").disabled = false;
            $("#close-dianbo").attr("class", "btn btn-primary");
            var server_root_path = "<?php  echo SITE_URL . 'resources/' ?>";
   
            var absl_path = server_root_path + path;
            var video_element;
            var video_time_duration;

            var video = document.getElementById('video1');
            if (video === null) {
                var html = "";
                html += '<video id="video1" width="100%" controls>';
                html += '<source src="' + absl_path + '">';
                html += '</video>';
                $("#dianbo-videos-container").empty();
                $("#dianbo-videos-container").append(html);
            } else {
                video.setAttribute("src", absl_path);
            }
            $("#dianbo-videos-container").show();
            $("#videos-container").hide();
            video_element = document.getElementById("video1");
            video_element.onloadedmetadata = function () {
                video_time_duration = video_element.duration;
                console.log("sunpy: video duration " + video_time_duration);
            };
            WebSocketConnect(absl_path);
        }
        
        function filePptPath(path){
        exitNowOn();
            isOnLive = "play-ppt";
            $("#voice").attr("src", "");
            window.picOrppt = "ppt";
            closeAllTitle();
            if ($("#choose-ppt")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);
            document.getElementById("close-ppt").disabled = false;
            $("#close-ppt").attr("class", "btn btn-primary");
            $("#ppt-container").show();
            $("#txt-container").hide();
            $("#voice-container").hide();
            $("#scroll-page").show();
            $("#page-up").show();
            $("#yeshu").show();
            $("#all-yeshu").show();
            $("#page-go").show();
            $("#page-down").show();
            $("#full-screen-button").show();
            cur_ppt = 1;
            var file_info = path.split("+-+");
//            console.log(file_info);
//            var source = file_info[2];
            var server_root_path;
            server_root_path = "<?php echo SITE_URL . 'resources/' . $pptFilePath; ?>";
            console.log(file_info);
//            if (source === "tea")
//            {
//                server_root_path = "<?php// echo SITE_URL . 'resources/' . $pptFilePath; ?>";
//            } else {
//                server_root_path = "<?php// echo SITE_URL . 'resources/' . $adminPptFilePath; ?>";
//            }
            var dirname = file_info[0];
            ppt_dir = server_root_path + dirname;
            ppt_pages = file_info[1];
            $("#all-yeshu").val(ppt_pages);
            goCurPage();
            if (timer_ppt !== null)
                clearInterval(timer_ppt);
            timer_ppt = setInterval(function () {
                var syn_msg;
                syn_msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
                ws.send(syn_msg);
            }, 4000);
        }
        
        function filePicPath(path){
         exitNowOn();
            isOnLive = "play-pic";
            $("#voice").attr("src", "");
            window.picOrppt = "pic";
            closeAllTitle();
            if ($("#choose-pic")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);
            $("#ppt-container").show();
            $("#txt-container").hide();
            $("#voice-container").hide();
            $("#scroll-page").show();
            
            cur_ppt = 1;
             document.getElementById("close-ppt").disabled = false;
            $("#close-ppt").attr("class", "btn btn-primary");
            var file_info = path.split("+-+");

            var server_root_path = "<?php echo SITE_URL . 'resources/' . $picFilePath; ?>";
            var dirname = file_info[0];
            ppt_dir = server_root_path + dirname;
            ppt_pages = file_info[1];
            $("#all-yeshu").val(ppt_pages);
            cur_ppt=file_info[3]-2;
            console.log(cur_ppt);
            goCurPage();
            if (timer_ppt !== null)
                clearInterval(timer_ppt);
            timer_ppt = setInterval(function () {
                var syn_msg;
                syn_msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
                ws.send(syn_msg);
            }, 4000);
    }
    
    function fileTxtPath(path){
    exitNowOn();
            isOnLive = "play-txt";
            $("#voice").attr("src", "");
            window.picOrppt = "txt";
            closeAllTitle();
            if ($("#choose-txt")[0].selectedIndex == -1)
            {
                return;
            }
            $("#ppt-container").hide();
            window.scrollTo(0, 130);
            document.getElementById("close-ppt").disabled = false;
            $("#close-ppt").attr("class", "btn btn-primary");
            $("#txt-container").show();
            $("#voice-container").hide();
            $("#scroll-page").show();
            $("#page-up").hide();
            $("#yeshu").hide();
            $("#all-yeshu").hide();
            $("#page-go").hide();
            $("#page-down").hide();
            $("#full-screen-button").hide();
            
            cur_ppt = 1;
            var file_info = path.split("+-+");
            var source = file_info[2];
            var server_root_path = "<?php echo SITE_URL . 'resources/' . $txtFilePath; ?>";
            var dirname = file_info[0];
            ppt_dir = server_root_path + dirname;
            ppt_pages = file_info[1];
            $("#all-yeshu").val(ppt_pages);
            goCurPage();
    }
    
    function fileVoicePath(path){
        exitNowOn();
            isOnLive = "play-voice";
            $("#voice").attr("src", "");
            closeAllTitle();
            if ($("#choose-voice")[0].selectedIndex == -1)
            {
                return;
            }
            window.scrollTo(0, 130);

            $("#scroll-video").show();
            document.getElementById("close-dianbo").disabled = false;
            $("#close-dianbo").attr("class", "btn btn-primary");
            var server_root_path = "<?php echo SITE_URL . 'resources/' ?>";
            var filepath = path;
            var absl_path = server_root_path + filepath;
            console.log("per"+absl_path);
            var video_element;
            var video_time_duration;


            var video = document.getElementById('video1');
            if (video === null) {
                var html = "";
                html += '<audio style="margin-bottom:200px;height:400px" id="video1" class="div_listen" width="100%" controls>';
                html += '<source src="' + absl_path + '">';
                html += '</audio>';
                $("#dianbo-videos-container").empty();
                $("#dianbo-videos-container").append(html);
            } else {
                video.setAttribute("src", absl_path);
            }
            $("#dianbo-videos-container").show();
            $("#videos-container").hide();
            video_element = document.getElementById("video1");
            video_element.onloadedmetadata = function () {
                video_time_duration = video_element.duration;
                console.log("sunpy: video duration " + video_time_duration);
            };
            WebSocketConnect(absl_path);
    }
    
    function goCurPage() {
        $("#yeshu").val(cur_ppt);
        if (window.picOrppt === "pic") {
            var server_root_path = ppt_dir.split("picture")[0] + "picture/";
            var array_fileName = new Array();
            <?php
            $dir = dir($picdir);
            $count = 0;
            while ($file = $dir->read()) {
                if ((!is_dir("$picdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                    $fileName4Path = iconv("gb2312", "UTF-8", $file);
                    ?>
                                array_fileName[<?php echo $count; ?>] = "<?php echo $fileName4Path; ?>";
                    <?php
                    $count++;
                }
            }
            $dir->close();
            ?>
            ppt_dir = server_root_path + array_fileName[cur_ppt - 1];
            $("#ppt-img").attr("src", ppt_dir);
            var msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
            ws.send(msg);
        } else if (window.picOrppt === "pic-public") {
            var server_root_path = "./resources/public/picture/";
            var array_fileName = new Array();
            <?php
            $dir = dir($adminPublicPicdir);
            $count = 0;
            while ($file = $dir->read()) {
                if ((!is_dir("$adminPublicPicdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                    $fileName4Path = iconv("gb2312", "UTF-8", $file);
                    ?>
                                array_fileName[<?php echo $count; ?>] = "<?php echo $fileName4Path; ?>";
                    <?php
                    $count++;
                }
            }
            $dir->close();
            ?>
            ppt_dir = server_root_path + array_fileName[cur_ppt - 1];
            $("#ppt-img").attr("src", ppt_dir);
            var msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
            ws.send(msg);
        } else if (window.picOrppt === "txt") {
            var server_root_path = ppt_dir.split("txt")[0] + "txt/";
            ppt_dir = ppt_dir.split("reborn/")[1];
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "index.php?r=api/getTxtValue",
                data: {url: ppt_dir},
                success: function (data) {
                    $("#txt-textarea").val(data);
                    ws.send("<?php echo $classID; ?>show_text"+data);
                },
                error: function (xhr, type, exception) {
                    console.log('getTxtValue error', type);
                    console.log(xhr, "Failed");
                    console.log(exception, "exception");
                }
            });
            if (timer_ppt !== null)
                clearInterval(timer_ppt);
            timer_ppt = setInterval(function () {
                $.ajax({
                type: "POST",
                dataType: "json",
                url: "index.php?r=api/getTxtValue",
                data: {url: ppt_dir},
                success: function (data) {
                    $("#txt-textarea").val(data);
                    ws.send("<?php echo $classID;?>show_text"+data);
                },
                error: function (xhr, type, exception) {
                    console.log('getTxtValue error', type);
                    console.log(xhr, "Failed");
                    console.log(exception, "exception");
                }
            });
            }, 4000);

        } else if (window.picOrppt === "txt-public") {
            var server_root_path = "./resources/public/txt/";
            ppt_dir = ppt_dir.split("./")[1];
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "index.php?r=api/getTxtValue",
                data: {url: ppt_dir},
                success: function (data) {
                    $("#txt-textarea").val(data);
                    ws.send("<?php echo $classID;?>show_text"+data);
                },
                error: function (xhr, type, exception) {
                    console.log('getTxtValue error', type);
                    console.log(xhr, "Failed");
                    console.log(exception, "exception");
                }
            });
             if (timer_ppt !== null)
                clearInterval(timer_ppt);
            timer_ppt = setInterval(function () {
                $.ajax({
                type: "POST",
                dataType: "json",
                url: "index.php?r=api/getTxtValue",
                data: {url: ppt_dir},
                success: function (data) {
                    $("#txt-textarea").val(data);
                    ws.send("<?php echo $classID;?>show_text"+data);
                },
                error: function (xhr, type, exception) {
                    console.log('getTxtValue error', type);
                    console.log(xhr, "Failed");
                    console.log(exception, "exception");
                }
            });
            }, 4000);
        } else if (window.picOrppt === "voice") {
            var server_root_path = ppt_dir.split("voice")[0] + "voice/";
            var array_fileName = new Array();
<?php
$dir = dir($voicedir);
$count = 0;
while ($file = $dir->read()) {
    if ((!is_dir("$voicedir/$file")) AND ( $file != ".") AND ( $file != "..")) {
        $fileName4Path = iconv("gb2312", "UTF-8", $file);
        ?>
                    array_fileName[<?php echo $count; ?>] = "<?php echo $fileName4Path; ?>";
        <?php
        $count++;
    }
}
$dir->close();
?>
            ppt_dir = server_root_path + array_fileName[cur_ppt - 1];
            $("#voice").attr("src", ppt_dir);
            var msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
            ws.send(msg);

        } else if (window.picOrppt === "voice-public") {
            var server_root_path = "./resources/public/voice/";
            var array_fileName = new Array();
<?php
$dir = dir($adminPublicVoicedir);
$count = 0;
while ($file = $dir->read()) {
    if ((!is_dir("$adminPublicVoicedir/$file")) AND ( $file != ".") AND ( $file != "..")) {
        $fileName4Path = iconv("gb2312", "UTF-8", $file);
        ?>
                    array_fileName[<?php echo $count; ?>] = "<?php echo $fileName4Path; ?>";
        <?php
        $count++;
    }
}
$dir->close();
?>
            ppt_dir = server_root_path + array_fileName[cur_ppt - 1];
            $("#voice").attr("src", ppt_dir);
            var msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
            ws.send(msg);

        } else if (window.picOrppt === "ppt") {
            $("#ppt-img").attr("src", ppt_dir + "/幻灯片" + cur_ppt + ".JPG");
            var msg = "<?php echo $classID; ?>playppt" + $("#ppt-img")[0].src;
            ws.send(msg);
        }

    }
    function pageUp() {
        if (cur_ppt <= 1) {
            cur_ppt = 1;
            window.wxc.xcConfirm("已到第一页！", window.wxc.xcConfirm.typeEnum.info);
        } else {
            cur_ppt = cur_ppt - 1;
            goCurPage();
        }
    }

    function pageDown() {
        if (cur_ppt >= ppt_pages) {
            cur_ppt = ppt_pages;
            window.wxc.xcConfirm("已到最后页！", window.wxc.xcConfirm.typeEnum.info);
        } else {
            cur_ppt = cur_ppt + 1;
            goCurPage();
        }

    }


    function openConnect() {
        if (ws !== null)
            return;
        ws = new WebSocket("wss://<?php echo HOST_IP; ?>:8443", 'echo-protocol');// initializing the connection through the websocket api
        ws.onclose = function (event) {
            console.log("与点播服务器的连接断开...");
        };
    }

    function closeConnect() {
        if (ws === null)
            return;
        var message_sent = "<?php echo $classID; ?>close";
        ws.send(message_sent);
        ws.close();
        ws = null;
    }

    function clearVideo() {
        var myVideo = document.getElementById("video1");
        if (play_fun != null && myVideo != null)
        {
            myVideo.removeEventListener("play", play_fun);
            myVideo.removeEventListener("pause", pause_fun);
        }
        //   teacher side broadcasts sync msg
        //   progress + path
        if (timer != null)
            clearInterval(timer);
        var message_sent = "<?php echo $classID; ?>closeVideo";
        ws.send(message_sent);
        $("#dianbo-videos-container").empty();
        $("#dianbo-videos-container").hide();
    }
    ;

    function checkforbid() {
        window.open("./index.php?r=teacher/checkforbid&&classID=<?php echo $classID; ?>", 'newwindow', 'height=480,width=600,top=0,left=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,left=500,top=200,');
    }

    function WebSocketConnect(absl_path) {
        console.log("sunpy [WebSocketConnect]");
        var myVideo = document.getElementById("video1");
        if (play_fun != null)
        {
            myVideo.removeEventListener("play", play_fun);
            myVideo.removeEventListener("pause", pause_fun);
        }
        //   teacher side broadcasts sync msg
        //   progress + path
        if (timer != null)
            clearInterval(timer);
        timer = setInterval(function () {
            var syn_msg;
            var video_current_time = myVideo.currentTime;

            if (myVideo.paused) {
                syn_msg = "<?php echo $classID; ?>pauseSync " + absl_path + "-----" + video_current_time;
            } else {
                syn_msg = "<?php echo $classID; ?>playSync " + absl_path + "-----" + video_current_time;
            }
            ws.send(syn_msg);
        }, 4000);

        play_fun = function () {
            console.log("sunpy: btn play");
            var message_sent = "<?php echo $classID; ?>Play";
            ws.send(message_sent);
            message_sent = "<?php echo $classID; ?>Path " + absl_path;
            ws.send(message_sent);
        };
        pause_fun = function () {
            console.log("sunpy: btn pause");
            var message_sent = "<?php echo $classID; ?>Pause";
            ws.send(message_sent);
        }
        myVideo.addEventListener("play", play_fun);
        myVideo.addEventListener("pause", pause_fun);

    }

    function exitNowOn() {
        switch (isOnLive) {
            case "teacher-dianbo":
                isOnLive = "";
                $("#scroll-video").hide();
                $("#play-ppt").attr("class", "btn btn-primary");
                clearVideo();
                this.disabled = true;
                $("#close-dianbo").attr("class", "btn");
                break;
            case "play-ppt":
                isOnLive = "";
                $("#voice").attr("src", "");
                cur_ppt = -1;
                ppt_pages = -1;
                if (timer_ppt !== null)
                    clearInterval(timer_ppt);
                var msg = "<?php echo $classID; ?>closeppt";
                ws.send(msg);
                this.disabled = true;
                $("#close-ppt").attr("class", "btn");
                if(document.getElementById("play-ppt")!=null){
                document.getElementById("play-ppt").disabled = false;
            }
                $("#ppt-container").hide();
                $("#voice-container").hide();
                $("#scroll-page").hide();
                $("#txt-container").hide();
                isOnLive = "";
                break;
            case "play-pic":
                isOnLive = "";
                 $("#voice").attr("src", "");
                cur_ppt = -1;
                ppt_pages = -1;
                if (timer_ppt !== null)
                    clearInterval(timer_ppt);
                var msg = "<?php echo $classID; ?>closeppt";
                ws.send(msg);
                this.disabled = true;
                $("#close-ppt").attr("class", "btn");
                if(document.getElementById("play-ppt")!=null){
                document.getElementById("play-ppt").disabled = false;
            }
                $("#ppt-container").hide();
                $("#voice-container").hide();
                $("#scroll-page").hide();
                $("#txt-container").hide();
                isOnLive = "";
                break;
            case "play-txt":
                isOnLive = "";
                 $("#voice").attr("src", "");
                cur_ppt = -1;
                ppt_pages = -1;
                var msg = "<?php echo $classID; ?>closeppt";
                ws.send(msg);
                this.disabled = true;
                $("#close-ppt").attr("class", "btn");
                if(document.getElementById("play-ppt")!=null){
                document.getElementById("play-ppt").disabled = false;
            }
                $("#ppt-container").hide();
                $("#voice-container").hide();
                $("#scroll-page").hide();
                $("#txt-container").hide();
                if (timer_ppt !== null)
                    clearInterval(timer_ppt);
                var msg = "<?php echo $classID; ?>closeppt";
                ws.send(msg);
                isOnLive = "";
                break;
            case "play-voice":
                isOnLive = "";
                 $("#scroll-video").hide();
                 $("#voice-container").hide();
                $("#play-ppt").attr("class", "btn btn-primary");
                clearVideo();
                this.disabled = true;
                $("#close-dianbo").attr("class", "btn");
                break;
//            case "classExercis":
//                closeAllOpenNow();
//                isOnLive = "";
//                break;
        }

    }

    function closeAllTitle() {
        $("#show-movie").hide();
        $("#title_movie").css({"color": "#fff"});
        $("#show-picture").hide();
        $("#title_picture").css({"color": "#fff"});
        $("#show-text").hide();
        $("#title_text").css({"color": "#fff"});
        $("#show-ppt").hide();
        $("#title_ppt").css({"color": "#fff"});
        $("#show-voice").hide();
        $("#title_video").css({"color": "#fff"});
        $("#show-classExercise").hide();
        $("#title_classExercise").css({"color": "#fff"});
        $("#showOnline").hide();
        $("#title_bull").css({"color": "#fff"});
    }
    function closeTitleWithoutFlag(flag) {
        $.ajax({
            type: "POST",
            url: "index.php?r=teacher/openClassExercise",
            data: {exerciseID: 0},
            success: function (data) {
            },
        });
        switch (flag) {
            case "movie":
                $("#show-picture").hide();
                $("#title_picture").css({"color": "#fff"});
                $("#show-text").hide();
                $("#title_text").css({"color": "#fff"});
                $("#show-ppt").hide();
                $("#title_ppt").css({"color": "#fff"});
                $("#show-voice").hide();
                $("#title_video").css({"color": "#fff"});
                $("#show-classExercise").hide();
                $("#title_classExercise").css({"color": "#fff"});
                $("#showOnline").hide();
                $("#title_bull").css({"color": "#fff"});
                break;
            case "picture":
                $("#show-movie").hide();
                $("#title_movie").css({"color": "#fff"});
                $("#show-text").hide();
                $("#title_text").css({"color": "#fff"});
                $("#show-ppt").hide();
                $("#title_ppt").css({"color": "#fff"});
                $("#show-voice").hide();
                $("#title_video").css({"color": "#fff"});
                $("#show-classExercise").hide();
                $("#title_classExercise").css({"color": "#fff"});
                $("#showOnline").hide();
                $("#title_bull").css({"color": "#fff"});
                break;
            case "text":
                $("#show-movie").hide();
                $("#title_movie").css({"color": "#fff"});
                $("#show-picture").hide();
                $("#title_picture").css({"color": "#fff"});
                $("#show-ppt").hide();
                $("#title_ppt").css({"color": "#fff"});
                $("#show-voice").hide();
                $("#title_video").css({"color": "#fff"});
                $("#show-classExercise").hide();
                $("#title_classExercise").css({"color": "#fff"});
                $("#showOnline").hide();
                $("#title_bull").css({"color": "#fff"});
                break;
            case "ppt":
                $("#show-movie").hide();
                $("#title_movie").css({"color": "#fff"});
                $("#show-picture").hide();
                $("#title_picture").css({"color": "#fff"});
                $("#show-text").hide();
                $("#title_text").css({"color": "#fff"});
                $("#show-voice").hide();
                $("#title_video").css({"color": "#fff"});
                $("#show-classExercise").hide();
                $("#title_classExercise").css({"color": "#fff"});
                $("#showOnline").hide();
                $("#title_bull").css({"color": "#fff"});
                break;
            case "video":
                $("#show-movie").hide();
                $("#title_movie").css({"color": "#fff"});
                $("#show-picture").hide();
                $("#title_picture").css({"color": "#fff"});
                $("#show-text").hide();
                $("#title_text").css({"color": "#fff"});
                $("#show-ppt").hide();
                $("#title_ppt").css({"color": "#fff"});
                $("#show-classExercise").hide();
                $("#title_classExercise").css({"color": "#fff"});
                $("#showOnline").hide();
                $("#title_bull").css({"color": "#fff"});
                break;
            case "classExercise":
                $("#show-movie").hide();
                $("#title_movie").css({"color": "#fff"});
                $("#show-picture").hide();
                $("#title_picture").css({"color": "#fff"});
                $("#show-text").hide();
                $("#title_text").css({"color": "#fff"});
                $("#show-ppt").hide();
                $("#title_ppt").css({"color": "#fff"});
                $("#show-voice").hide();
                $("#title_video").css({"color": "#fff"});
                $("#showOnline").hide();
                $("#title_bull").css({"color": "#fff"});
                break;
            case "showOnline":
                $("#show-movie").hide();
                $("#title_movie").css({"color": "#fff"});
                $("#show-picture").hide();
                $("#title_picture").css({"color": "#fff"});
                $("#show-text").hide();
                $("#title_text").css({"color": "#fff"});
                $("#show-ppt").hide();
                $("#title_ppt").css({"color": "#fff"});
                $("#show-voice").hide();
                $("#title_video").css({"color": "#fff"});
                $("#show-classExercise").hide();
                $("#title_classExercise").css({"color": "#fff"});
                break;
        }
    }
    function addMovie(){
        window.open("./index.php?r=teacher/addMovie&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['on']; ?>&&on=<?php echo $_GET['on']; ?>", 'newwindow', 'width=' + (window.screen.availWidth - 10) + ',height=' + (window.screen.availHeight - 30) + 'alwaysRaised=yes,top=0,left=0,toolbar=yes,z-look=yes,menubar=yes,scrollbars=yes,resizable=yes,location=no,status=no,');
    }
    function addPic(){
      window.open("./index.php?r=teacher/addPic&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['on']; ?>&&on=<?php echo $_GET['on']; ?>", 'newwindow', 'width=' + (window.screen.availWidth - 10) + ',height=' + (window.screen.availHeight - 30) + 'alwaysRaised=yes,top=0,left=0,toolbar=yes,z-look=yes,menubar=yes,scrollbars=yes,resizable=yes,location=no,status=no,');
    }
    function addTxt(){
      window.open("./index.php?r=teacher/addTx&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['on']; ?>&&on=<?php echo $_GET['on']; ?>", 'newwindow', 'width=' + (window.screen.availWidth - 10) + ',height=' + (window.screen.availHeight - 30) + 'alwaysRaised=yes,top=0,left=0,toolbar=yes,z-look=yes,menubar=yes,scrollbars=yes,resizable=yes,location=no,status=no,');
    }
    function addPpt(){
      window.open("./index.php?r=teacher/addPp&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['on']; ?>&&on=<?php echo $_GET['on']; ?>", 'newwindow', 'width=' + (window.screen.availWidth - 10) + ',height=' + (window.screen.availHeight - 30) + 'alwaysRaised=yes,top=0,left=0,toolbar=yes,z-look=yes,menubar=yes,scrollbars=yes,resizable=yes,location=no,status=no,');
    }
    function addVoice(){
      window.open("./index.php?r=teacher/addVo&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['on']; ?>&&on=<?php echo $_GET['on']; ?>", 'newwindow', 'width=' + (window.screen.availWidth - 10) + ',height=' + (window.screen.availHeight - 30) + 'alwaysRaised=yes,top=0,left=0,toolbar=yes,z-look=yes,menubar=yes,scrollbars=yes,resizable=yes,location=no,status=no,');
    }
    function addNewClassExercise() {
        window.open("./index.php?r=teacher/classExercise4Type&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['on']; ?>&&on=<?php echo $_GET['on']; ?>", 'newwindow', 'width=' + (window.screen.availWidth - 10) + ',height=' + (window.screen.availHeight - 30) + 'alwaysRaised=yes,top=0,left=0,toolbar=yes,z-look=yes,menubar=yes,scrollbars=yes,resizable=yes,location=no,status=no,');
    }

    function iframReload() {
        document.getElementById('iframe_class').contentWindow.location.reload(true);
    }

    function startClassExercise(exerciseID) {
        var classID = <?php echo $_GET['classID']; ?>;
        document.getElementById('iframe_class').src = "index.php?r=teacher/tableClassExercise4Analysis&&exerciseID=" + exerciseID + "&&classID=" + classID;
    }

    function startNow4Lot(checkboxs) {
        var check = "";
        var exerciseID = "";
        window.wxc.xcConfirm("开放？这将使学生跳转到此练习", window.wxc.xcConfirm.typeEnum.info, {
            onOk: function () {
                for (var i = 0; i < checkboxs.length; i++) {
                    if (checkboxs[i].checked) {
                        if (exerciseID === "") {
                            exerciseID = checkboxs[i].value;
                        }
                        check += checkboxs[i].value + "&";
                    }
                }
                $.ajax({
                    type: "POST",
                    url: "index.php?r=teacher/openClassExercise4lot",
                    data: {check: check},
                    success: function (data) {
                        if (data == "开放成功！") {
                            window.wxc.xcConfirm(data, window.wxc.xcConfirm.typeEnum.success);
                           // window.parent.startClassExercise(exerciseID);
                           window.parent.backToTableClassExercise4virtual();
                        } else {
                            window.wxc.xcConfirm(data, window.wxc.xcConfirm.typeEnum.error);
                        }
                    },
                    error: function (xhr, type, exception) {
                        console.log('GetAverageSpeed error', type);
                        console.log(xhr, "Failed");
                        console.log(exception, "exception");

                    }
                });
            }
        });


    }

    function startNow(exerciseID) {
        window.wxc.xcConfirm("开放？这将使学生跳转到此练习", window.wxc.xcConfirm.typeEnum.info, {
            onOk: function () {
                $.ajax({
                    type: "POST",
                    url: "index.php?r=teacher/openClassExercise",
                    data: {exerciseID: exerciseID},
                    success: function (data) {
                        if (data == 1) {
                             window.wxc.xcConfirm("开放成功！", window.wxc.xcConfirm.typeEnum.success);
                             // window.parent.startClassExercise(exerciseID);
                           window.parent.backToTableClassExercise4virtual();
                        } else {
                            alert("开放失败");
                        }
                    },
                    error: function (xhr, type, exception) {
                        console.log('GetAverageSpeed error', type);
                        console.log(xhr, "Failed");
                        console.log(exception, "exception");

                    }
                });
            }
        });
    }

    function backToTableClassExercise4virtual() {
        document.getElementById('iframe_class').src = "index.php?r=teacher/tableClassExercise4virtual&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['on']; ?>&&on=<?php echo $_GET['on']; ?>";
    }

    function closeAllOpenNow() {
        window.wxc.xcConfirm("确定要关闭当前所有正在进行中的练习？", window.wxc.xcConfirm.typeEnum.info, {
            onOk: function () {

                $.ajax({
                    type: "POST",
                    url: "index.php?r=teacher/closeAllOpenExerciseNow",
                    data: {},
                    success: function (data) {
                        backToTableClassExercise4virtual();
                        window.wxc.xcConfirm(data, window.wxc.xcConfirm.typeEnum.success);
                    },
                    error: function (xhr, type, exception) {
                        console.log('GetAverageSpeed error', type);
                        console.log(xhr, "Failed");
                        console.log(exception, "exception");
                    }
                });
            }
        });
    }
    function alertError(text){
        window.wxc.xcConfirm(text, window.wxc.xcConfirm.typeEnum.error);
    }
    //签到
function startSign(){
       var option = {
            title: "签到",
            btn: parseInt("0011", 2),
            onOk: function () {
            window.location.href = "./index.php?r=teacher/startSign&&classID=<?php echo $classID; ?>&&lessonID=<?php 
            $less = Lesson::model()->find('classID=? and number=?', array($classID, $on));
            echo $less['lessonID'];?>";
            //window.location.reload();
            sss();
            }
        }
        window.wxc.xcConfirm("签到？？？？？？", "custom", option);
    }
     $("#startsign").click(function() {
        $.ajax({
            type: "POST",
            url: "./index.php?r=teacher/startSign&&classID=<?php echo $classID; ?>&&lessonID=<?php 
            $less = Lesson::model()->find('classID=? and number=?', array($classID, $on));
            echo $less['lessonID'];?>",
            success: function(){    
            window.wxc.xcConfirm('学生将收到签到！', window.wxc.xcConfirm.typeEnum.success,{
                onOk:function(){
                //window.location.reload();
                }
            });
            },
            error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr.responseText, "Failed");
            }
        });
    });
    
         $("#closesign").click(function() {
        $.ajax({
            type: "POST",
            url: "./index.php?r=teacher/closeSign&&classID=<?php echo $classID; ?>&&lessonID=<?php 
            $less = Lesson::model()->find('classID=? and number=?', array($classID, $on));
            echo $less['lessonID'];?>",
            success: function(){    
            window.wxc.xcConfirm('关闭成功签到！', window.wxc.xcConfirm.typeEnum.success,{
                onOk:function(){
                //window.location.reload();
                }
            });
            },
            error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr.responseText, "Failed");
            }
        });
    });
    
    //窗口
    var dialogInstace , onMoveStartId , mousePos = {x:0,y:0};	//	用于记录当前可拖拽的对象
	
	// var zIndex = 9000;

	//	获取元素对象	
	function g(id){return document.getElementById(id);}

	//	自动居中元素（el = Element）
	function autoCenter( el ){
		var bodyW = document.documentElement.clientWidth;
		var bodyH = document.documentElement.clientHeight;

		var elW = el.offsetWidth;
		var elH = el.offsetHeight;

		el.style.left = (bodyW-elW)/2 + 'px';
		el.style.top = (bodyH-elH)/2 + 'px';
		
	}

	//	自动扩展元素到全部显示区域
	function fillToBody( el ){
		el.style.width  = document.documentElement.clientWidth  +'px';
		el.style.height = document.documentElement.clientHeight + 'px';
	}

	//	Dialog实例化的方法
	function Dialog( dragId , moveId ){

		var instace = {} ;

		instace.dragElement  = g(dragId);	//	允许执行 拖拽操作 的元素
		instace.moveElement  = g(moveId);	//	拖拽操作时，移动的元素

		instace.mouseOffsetLeft = 0;			//	拖拽操作时，移动元素的起始 X 点
		instace.mouseOffsetTop = 0;			//	拖拽操作时，移动元素的起始 Y 点

		instace.dragElement.addEventListener('mousedown',function(e){

			var e = e || window.event;

			dialogInstace = instace;
			instace.mouseOffsetLeft = e.pageX - instace.moveElement.offsetLeft ;
			instace.mouseOffsetTop  = e.pageY - instace.moveElement.offsetTop ;
			
			onMoveStartId = setInterval(onMoveStart,10);
			return false;
			// instace.moveElement.style.zIndex = zIndex ++;
		})

		return instace;
	}

	//	在页面中坚挺鼠标弹起事件
	document.onmouseup = function(e){
		dialogInstace = false;
		clearInterval(onMoveStartId);
	}
	document.onmousemove = function( e ){
		var e = window.event || e;
		mousePos.x = e.clientX;
		mousePos.y = e.clientY;
		

		e.stopPropagation && e.stopPropagation();
		e.cancelBubble = true;
		e = this.originalEvent;
        e && ( e.preventDefault ? e.preventDefault() : e.returnValue = false );

        document.body.style.MozUserSelect = 'none';
	}	

	function onMoveStart(){


		var instace = dialogInstace;
	    if (instace) {
	    	
	    	var maxX = document.documentElement.clientWidth -  instace.moveElement.offsetWidth;
	    	var maxY = document.documentElement.clientHeight - instace.moveElement.offsetHeight ;

			instace.moveElement.style.left = Math.min( Math.max( ( mousePos.x - instace.mouseOffsetLeft) , 0 ) , maxX) + "px";
			instace.moveElement.style.top  = Math.min( Math.max( ( mousePos.y - instace.mouseOffsetTop ) , 0 ) , maxY) + "px";

	    }

	}

	//	重新调整对话框的位置和遮罩，并且展现
	function showDialog(){
		g('dialogMove').style.display = 'block';
		g('mask').style.display = 'block';
		autoCenter( g('dialogMove') );
		fillToBody( g('mask') );
                showAbsence();
    }
function showAbsence(){
//          $.ajax({
//            type: "GET",
//            dataType: 'json',
//            url: "",
//            success: function (data) {
//                
//      document.getElementById("queqin"). innerHTML = data['studentAbsence'].length;         
//            }
//        });  
window.open("./index.php?r=teacher/showAbsence&&classID=<?php echo $classID; ?>&&lessonID=<?php 
            $less = Lesson::model()->find('classID=? and number=?', array($classID, $on));
            echo $less['lessonID'];?>", 'newwindow', 'height=500,width=600,top=0,left=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,left=500,top=200,')   
   
}
	//	关闭对话框
	function hideDialog(){
		g('dialogMove').style.display = 'none';
		g('mask').style.display = 'none';
                flag = 1;
	}
	//	查看浏览器窗口大小变化
	window.onresize = showDialog;
	Dialog('dialogDrag','dialogMove');
	showDialog();
   
</script>
<div class="ui-mask" id="mask" onselectstart="return false"></div>
<div class="ui-dialog" id="dialogMove" onselectstart='return false;'>
	<div class="ui-dialog-title" id="dialogDrag"  onselectstart="return false;" >
		<a class="ui-dialog-closebutton" href="javascript:hideDialog();"></a>
缺勤表
	</div>
	<div class="ui-dialog-content">
            <div style="color: red" id="queqin">

                           
                            
                    
		</div>

		<div class="ui-dialog-l40">
		</div>
		<div>
			<a class="ui-dialog-submit" href="javascript:hideDialog();" >确定</a>
		</div>
		<div class="ui-dialog-l40">
		</div>
	</div>
</div>