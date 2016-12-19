
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-navsearch" style="position:relative;bottom:5px;left:"></i>搜索</li>
            <form action="./index.php?r=teacher/searchListen" method="post" >
                <li>
                    <select name="type" >
                        <option value="exerciseID" selected="selected">编号</option>
                        <option value="title" >题目名</option>
                        <option value="createPerson" >创建人</option>
                        <option value="content">内容</option>
                    </select>
                </li>
                <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
                </li>
                <li style="margin-top:10px">
                    <button type="submit" class="btn_4big">搜 索</button>
                    <button onclick="window.location.href = './index.php?r=teacher/addListen'" type="button" class="btn_4big">添 加</button>
                </li>
            </form>
<!--            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:5px;left:"></i>基础知识</li>
            <li ><a href="./index.php?r=teacher/choiceLst"><i class="icon-font" style="position:relative;bottom:5px;left:"></i> 选择</a></li>
            <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width" style="position:relative;bottom:5px;left:"></i> 填空</a></li>
            <li ><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left" style="position:relative;bottom:5px;left:"></i> 简答</a></li>-->
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing" style="position:relative;bottom:5px;left:"></i>打字练习</li>
            <li ><a href="./index.php?r=teacher/keyLst"><i class="icon-th" style="position:relative;bottom:5px;left:"></i> 键打练习</a></li>
            <li ><a href="./index.php?r=teacher/lookLst"><i class="icon-eye-open" style="position:relative;bottom:5px;left:"></i> 看打练习</a></li>
            <li class="active"><a href="./index.php?r=teacher/listenLst"><i class="icon-headphones" style="position:relative;bottom:5px;left:"></i> 听打练习</a></li>
        </ul>
    </div>
</div>

<div class="span9">        
    <h3 style="display:inline-block;">添加听打练习题</h3>
    <span>(支持mp3及wav格式,最大1G)</span>
    <form class="form-horizontal" method="post" action="./index.php?r=teacher/AddListen" id="myForm" enctype="multipart/form-data"> 
        <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="test" />
        <fieldset>
            <legend>填写题目</legend>
            <div class="control-group">
                <label class="control-label" for="input01">题目</label>
                <div class="controls">
                    <textarea name="title" style="width:450px; height:20px;" id="input01"><?php echo $title; ?></textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input02">音频文件</label>
                <div class="controls">
                    <input type="file" name="file" id="input02">      
                    <div id="upload" style="display:inline;" hidden="true">
                        <img src="./img/default/upload-small.gif"  alt="正在努力上传。。"/>
                        正在上传，请稍等...
                        <div id="number">0%</div>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input04">上传答案</label>
                <div class="controls">
                    <input type="file" name="myfile" id="myfile" onchange="getImgURL(this)"  >
                    <!--<input class="btn btn-primary"  type="button" onclick ="uplodes()" value="上传">-->
                </div>
            </div>
            <div class="control-group" id="div2">
                <label class="control-label" >速度</label>
                <div class="controls">
                    <input type="text" name="speed" style="width:40px; height:15px;" id="input2" maxlength="3"  value="">         
                    词/分钟
                </div>            
            </div>
            <div class="control-group" id="answers">
                <label class="control-label" for="input03">听打答案</label>
                <div class="controls">               
                    <textarea name="content" style="width:450px; height:200px;" id="input03"><?php echo $content; ?></textarea>
                </div>
            </div> 
            <div class="form-actions">
                <?php if (!isset($action)) { ?> 
                    <button type="submit" class="btn btn-primary">添加</button>
                <?php } ?>
                <a href="./index.php?r=teacher/returnFromAddListen&&page=<?php echo Yii::app()->session['lastPage']; ?>" class="btn btn-primary">返回</a>
            </div>
        </fieldset>
    </form>   
</div>
<script>
    function getImgURL(node) {      
//        var file = null;  
//        if(node.files && node.files[0] ){  
//            file = node.files[0];   
//        }else if(node.files && node.files.item(0)) {                                  
//            file = node.files.item(0);     
//        }     
//            //Firefox8.0以上                                
//        imgURL = window.URL.createObjectURL(file);  
//          alert("//Firefox8.0以上"+imgRUL);
//        alert(imgURL); 
    document.getElementById("answers").style.display = "none";
}  

//  function uplodes(){
//      var uploadTxt = $("#myfile")[0].value;
//        if (uploadTxt === "")
//        {
//            window.wxc.xcConfirm('上传txt不能为空', window.wxc.xcConfirm.typeEnum.warning);
//            return false;
//        }
//    var request=null;
//    if(window.XMLHttpRequest){
//        request=new XMLHttpRequest();
//    }else if(window.ActiveXObject)
//    {
//        request=new ActiveXObject("Microsoft.XMLHTTP");
//    }
//        if(request){
//            //var file = fso.OpenTextFile(url,1,false,-1);
//            request.open("GET",imgURL , true);//测试读取1.txt的内容
//           request.setRequestHeader('Content-type','application/x-www-form-urlencoded');
//         request.overrideMimeType("text/plain; charset = utf-8");
//            //request.responseType="text";
//            
//            request.onreadystatechange = function(data){
//            if(request.readyState===4){
//                if (request.status === 200 || request.status === 0){
//                    console.log(request);
//                    document.getElementById("input03").innerHTML = request.responseText;
//            }
//        }
//    }
//    alert(request.responseText);
//        request.send(null);
//    }else{
//        alert("error");
//    }
//  }
  
    $(document).ready(function () {
        $("#upload").hide();
        var result = <?php echo "'$result'"; ?>;
        if (result === '1')
            window.wxc.xcConfirm('添加听打练习成功！', window.wxc.xcConfirm.typeEnum.success, {
                onOk: function () {
                    window.location.href = "./index.php?r=teacher/addListen";
                }
            });
        else if (result === '0')
            window.wxc.xcConfirm('添加听打练习失败！', window.wxc.xcConfirm.typeEnum.error);
        else if (result != 'no')
        {
            window.wxc.xcConfirm(result, window.wxc.xcConfirm.typeEnum.info);
        }
    });
     function fetch_progress(){
        $.get('./index.php?r=teacher/getProgress',{ '<?php echo ini_get("session.upload_progress.name"); ?>' : 'test'}, function(data){
                var progress = parseInt(data);   
                $('#number').html(progress + '%');
                if(progress < 100){
                        setTimeout('fetch_progress()', 100);
                }else{           
        }
        }, 'html');
    }
    $("#myForm").submit(function () {

        var requirements = $("#input01")[0].value;
        if (requirements === "") {
            window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        var uploadFile = $("#input02")[0].value;
        if (uploadFile === "")
        {
            window.wxc.xcConfirm('上传文件不能为空', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }

        var A = $("#input03")[0].value;
        var files =  document.getElementById("myfile").value;
        if (A === "" && files === "") {
            window.wxc.xcConfirm('内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        $("#upload").show();
        setTimeout('fetch_progress()', 1000);


    });
</script>




