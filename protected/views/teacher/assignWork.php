<div class="span3">
    <div class="well-bottomnoradius" style="padding: 8px 0;">
        <ul class="nav nav-list">                     
            <li class="nav-header">班级列表</li>

            <?php foreach ($array_class as $class): ?>
                <li <?php if (Yii::app()->session['currentClass'] == $class['classID']) echo "class='active'"; ?> ><a href="./index.php?r=teacher/assignWork&&classID=<?php echo $class['classID']; ?>"><i class="icon-list"></i><?php echo $class['className']; ?></a></li>
            <?php endforeach; ?>

           </ul></div>
            <div class="well-topnoradius" style="padding: 8px 0;border-bottom-left-radius:0px;border-bottom-right-radius:0px;">
        <ul class="nav nav-list">    
            <li class="divider"></li>
            <?php if(Yii::app()->session['currentClass']&&Yii::app()->session['currentLesson']){?>
            
                <li class="nav-header" >作业题目</li>
                <input name= "title" type="text" class="search-query span2" placeholder="作业题目" id="title" value="" />
                <li style="margin-top:10px">
                    <a href="#"onclick="chkIt()" id="bth_create"></a>
                </li>
           <?php }?>
             <li class="divider"></li>
            <li class="nav-header">课时列表</li>
        </ul>
    </div>
<div class="well-topnoradius" style="padding: 8px 0;height:325px;overflow:auto; top:-40px;">
     <ul class="nav nav-list">
            <?php foreach ($array_lesson as $lesson): ?>
                <li <?php if (Yii::app()->session['currentLesson'] == $lesson['lessonID']) echo "class='active'"; ?> ><a href="./index.php?r=teacher/assignWork&&classID=<?php echo Yii::app()->session['currentClass']; ?>&&lessonID=<?php echo $lesson['lessonID']; ?>"><i class="icon-list"></i><?php echo $lesson['lessonName']; ?></a></li>
            <?php endforeach; ?> 
</div>
    

</div>

<div class="span9">
    <h2>现有作业</h2>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">标题</th>
                <th class="font-center">状态</th>
                <th class="font-center">操作</th>               
            </tr>
        </thead>
        <tbody>        
            <?php
            foreach ($array_allsuite as $suite):
                $isOpen = false;
            if($array_suite){
                foreach ($array_suite as $exitsuite)
                    if ($suite['suiteID'] == $exitsuite['suiteID']) {
                        $isOpen = true;
                        break;
                    }
            }
                ?>                    
                <tr>
                    <td class="font-center" ><?php echo $suite['suiteName']; ?></td>
                    <td class="font-center" style="width: 100px">
                        <?php if ($isOpen == false) { ?>
                            <a href="./index.php?r=teacher/ChangeSuiteClass&&suiteID=<?php echo $suite['suiteID']; ?>&&isOpen=0&&page=<?php echo $pages->currentPage + 1; ?>" style="color:green">开放</a>
                            <font style="color:grey">关闭</font>
                        <?php } else { ?>
                            <font style="color:grey">开放</font>
                            <a href="./index.php?r=teacher/ChangeSuiteClass&&suiteID=<?php echo $suite['suiteID']; ?>&&isOpen=1&&page=<?php echo $pages->currentPage + 1;  ?>" style="color:red">关闭</a>
                        <?php } ?>
                    </td>             
                    <td class="font-center" style="width: 100px">
                        <a href="./index.php?r=teacher/seeWork&&suiteID=<?php echo $suite['suiteID']; ?>"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"></a>

                        <a href="./index.php?r=teacher/modifyWork&&suiteID=<?php echo $suite['suiteID']; ?>&&type=choice"><img title="修改作业内容" src="<?php echo IMG_URL; ?>edit.png"></a>
                        <a href="#" onclick="dele(<?php echo $suite['suiteID']; ?>,<?php echo $pages->currentPage + 1; ?>)"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>

                    </td>
                </tr>            
            <?php endforeach; ?> 
        </tbody>
    </table>
    <div align=center>
    <?php
    $this->widget('CLinkPager', array('pages' => $pages));
    ?>
</div>
</div>


<script>
    $(document).ready(function(){
        if(<?php echo $res;?>==1){
            var txt=  "此作业已经被创建！";
	    window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.confirm);
            document.getElementById("title").value="";
        }
    });
    function dele(suiteID, currentPage)
    {
      
        var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							 window.location.href = "./index.php?r=teacher/deleteSuite&&suiteID=" + suiteID + "&&page=" + currentPage;
						}
					}
					window.wxc.xcConfirm("您确定删除吗？", "custom", option);
    }

    
    function chkIt(){
    var usernameVal = document.getElementById("title").value;  
    if(usernameVal==""){
        window.wxc.xcConfirm("题目不能为空", window.wxc.xcConfirm.typeEnum.warning);
            return false;
    }
    if(usernameVal.length > 30){ //一个汉字算一个字符  
        window.wxc.xcConfirm("大于30个字符", window.wxc.xcConfirm.typeEnum.warning);
        document.getElementById("title").value="";
    }
    window.location.href="./index.php?r=teacher/AddSuite&&title="+usernameVal;
}
</script>


