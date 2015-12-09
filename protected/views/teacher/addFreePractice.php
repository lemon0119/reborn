<div class="span3">
    <div class="well-bottomnoradius" style="padding: 8px 0;">
        <ul class="nav nav-list">                     
            <li class="nav-header"><i class="icon-knowlage"></i>当前班级</li>

            <?php foreach ($array_class as $class): ?>
            <li <?php if (Yii::app()->session['currentClass'] == $class['classID']) echo "class='active'"; ?> ><a style="cursor: default"><i class="icon-list"></i><?php echo $class['className']; ?></a></li>
            <?php endforeach; ?>

           </ul></div>
            <div class="well-topnoradius" style="padding: 8px 0;border-bottom-left-radius:0px;border-bottom-right-radius:0px;">
        <ul class="nav nav-list">   
            <li style="margin-top:10px">
                    <a href="./index.php?r=teacher/assignFreePractice&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo  Yii::app()->session['progress'];?>" id="bth_bigregray"></a>
                </li>
            <li class="divider"></li>
             <li style="pointer-events: none;" class="nav-header"><i class="icon-knowlage"></i>当前课时</li>
        </ul>
    </div>
<div class="well-topnoradius" style="padding: 8px 0;height:500px;overflow:auto; top:-40px;">
    <?php if($all=='no'){ ?>
         <ul class="nav nav-list">
            <?php foreach ($array_lesson as $lesson): ?>
         <li style="pointer-events: none;" <?php if (Yii::app()->session['currentLesson'] == $lesson['lessonID']) echo "class='active' "; ?> ><a  style="cursor: default"><i class="icon-list"></i><?php echo $lesson['lessonName']; ?></a></li>
            <?php endforeach; ?> 
         </ul>
   <?php }else{ ?>
    <ul class="nav nav-list">
        <li style="pointer-events: none;" class="active"><a><i class="icon-list"></i>当前添加的题目将作为全局题目</a></li>
         </ul>
   <?php }?>
     
</div>
    

</div>

<div class="span9">
    <?php if($type=='look'){ ?>
    <h1>添加看打练习</h1>
    
        
    <?php }else if($type=='listen'){ ?>
     <h1>添加听打练习</h1>
        
     
    <?php } ?> 
</div>


<script>
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


