<div class="span3">
    <div class="well-bottomnoradius" style="padding: 8px 0;">
        <ul class="nav nav-list">                     
            <li class="nav-header"><i class="icon-knowlage"></i>班级列表</li>

            <?php foreach ($array_class as $class): ?>
                <li <?php if (Yii::app()->session['currentClass'] == $class['classID']) echo "class='active'"; ?> ><a href="./index.php?r=teacher/assignWork&&classID=<?php echo $class['classID']; ?>"><i class="icon-list"></i><?php echo $class['className']; ?></a></li>
            <?php endforeach; ?>

           </ul></div>
            <div class="well-topnoradius" style="padding: 8px 0;border-bottom-left-radius:0px;border-bottom-right-radius:0px;">
        <ul class="nav nav-list">    
            <li class="divider"></li>
            <?php if(Yii::app()->session['currentClass']&&Yii::app()->session['currentLesson']){?>
            <li class="nav-header" ><i class="icon-knowlage"></i>新建练习</li>
            <?php if(isset($_GET['progress'])){ ?>  
            <form action="./index.php?r=teacher/addFreePractice&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>" id="add" method="post">
             <?php }else if(isset ($_GET['all'])){ ?>
                <form action="./index.php?r=teacher/addFreePractice&&classID=<?php echo $_GET['classID']; ?>&&all=all" id="add" method="post">
             <?php }else{ ?>
                 <form action="./index.php?r=teacher/addFreePractice&&classID=<?php echo $_GET['classID']; ?>&&lessonID=<?php echo $_GET['lessonID']; ?>" id="add" method="post">
             <?php  } ?>
                 <div class="selectoption">
                                <select  name="type" >
                                    <option  value="key" selected="selected">键打练习</option>
                                        <option  value="look" >看打练习</option>
                                        <option  value="listen" >听打练习</option>
                                </select>
                            </div>
                <input name= "title" type="text" class="search-query span2" placeholder="题目" id="title" value="" />
                </form>
                <li style="margin-top:10px">
                    <button href="#" onclick="chkIt()" id="bth_create"></button>
                </li>
                <li style="margin-top:10px">
                    <a href="./index.php?r=teacher/startCourse&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo  Yii::app()->session['progress'];?>&&on=<?php echo  Yii::app()->session['progress'];?>" id="bth_bigregray"></a>
                </li>
           <?php }?>
             <li class="divider"></li>
             <li class="nav-header"><i class="icon-knowlage"></i>课时列表<a  <?php if(isset($_GET['all'])){echo "style='background-color: #f46500;color: white'";}?> href="./index.php?r=teacher/assignFreePractice&&classID=<?php echo Yii::app()->session['currentClass']; ?>&&all=all" class="fr">查看全部</a></li>
        </ul>
    </div>
<div class="well-topnoradius" style="padding: 8px 0;height:280px;overflow:auto; top:-40px;">
     <ul class="nav nav-list">
            <?php foreach ($array_lesson as $lesson): ?>
         <li <?php if (Yii::app()->session['currentLesson'] == $lesson['lessonID']&&!isset($_GET['all'])) echo "class='active'"; ?> ><a href="./index.php?r=teacher/assignFreePractice&&classID=<?php echo Yii::app()->session['currentClass']; ?>&&lessonID=<?php echo $lesson['lessonID']; ?>"><i class="icon-list"></i><?php echo $lesson['lessonName']; ?></a></li>
            <?php endforeach; ?> 
</div>
    

</div>

<div class="span9">
    <h2>现有练习</h2>
    <input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
    <a href="#" onclick="deleCheck()"><img title="批量删除" src="<?php echo IMG_URL; ?>delete.png"></a>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center" style="width: 50px">选择</th>
                <th class="font-center">标题</th>
                <th class="font-center"  style="width: 80px">类型</th>
                <th class="font-center"  style="width: 80px">创建人</th>
                <th class="font-center"  style="width: 80px">状态</th>
                <th class="font-center" style="width: 100px">操作</th>               
            </tr>
        </thead>
        <tbody>     
            <form id="deleForm" method="post" action="./index.php?r=teacher/deleteSuite" > 
            <?php
            foreach ($array_allpractice as $suite):
                ?>                    
                <tr>
                    <td class="font-center" style="width: 50px"> <input type="checkbox" name="checkbox[]" value="<?php echo $suite['exerciseID']; ?>" /> </td>
                    <td class="font-center" ><?php echo $suite['title']; ?></td>
                    <td class="font-center" style="width: 80px" ><?php switch ($suite['type']){case 'key': echo "键打练习"; break; case'look':echo '看打练习';break;case 'listen': echo "听打练习";break;} ?></td>
                    <td class="font-center" style="width: 80px"><?php echo $suite['userName']; ?></td>
                    <td class="font-center" style="width: 80px">
                        <?php if ($suite['is_open'] == 0) { ?>
                            <a href="./index.php?r=teacher/ChangeSuiteClass&&exerciseID=<?php echo $suite['exerciseID']; ?>&&isOpen=0&&page=<?php echo $pages->currentPage + 1; ?>" style="color:green">发布</a>
                            <font style="color:grey">关闭</font>
                        <?php } else { ?>
                            <font style="color:grey">发布</font>
                            <a href="./index.php?r=teacher/ChangeSuiteClass&&exerciseID=<?php echo $suite['exerciseID']; ?>&&isOpen=1&&page=<?php echo $pages->currentPage + 1;  ?>" style="color:red">关闭</a>
                        <?php } ?>
                    </td>             
                    <td class="font-center" style="width: 100px">
                        <a href="./index.php?r=teacher/seeWork&&exerciseID=<?php echo $suite['exerciseID']; ?>"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"></a>

                        <a href="./index.php?r=teacher/modifyWork&&exerciseID=<?php echo $suite['exerciseID']; ?>&&type=choice"><img title="修改作业内容" src="<?php echo IMG_URL; ?>edit.png"></a>
                        <a href="#" onclick="dele(<?php echo $suite['exerciseID']; ?>,<?php echo $pages->currentPage + 1; ?>)"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>

                    </td>
                </tr>            
            <?php endforeach; ?> 
                 </form>
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
	    window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.info);
            document.getElementById("title").value="";
        }
    });
    function check_all(obj, cName)
    {
        var checkboxs = document.getElementsByName(cName);
        for (var i = 0; i < checkboxs.length; i++) {
            checkboxs[i].checked = obj.checked;
        }
    }
    function deleCheck() {
        var checkboxs = document.getElementsByName('checkbox[]');
        var flag = 0;
        for (var i = 0; i < checkboxs.length; i++) {
           if(checkboxs[i].checked){
                flag=1;
                break;
           }
        } 
        if(flag===0){
           window.wxc.xcConfirm('未选中任何试卷', window.wxc.xcConfirm.typeEnum.info);
        }else{
             var option = {
                    title: "警告",
                    btn: parseInt("0011",2),
                    onOk: function(){
                            $('#deleForm').submit();
                    }
            };
            window.wxc.xcConfirm("这将会删除此试卷，您确定这样吗？", "custom", option);
        }
    }
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
        window.wxc.xcConfirm("题目不能为空", window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
    if(usernameVal.length > 30){ //一个汉字算一个字符  
        window.wxc.xcConfirm("大于30个字符", window.wxc.xcConfirm.typeEnum.info);
        document.getElementById("title").value="";
        return false;
    }
        document.getElementById("add").submit();
}


</script>


