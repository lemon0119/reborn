<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
            <form action="./index.php?r=teacher/searchListen" method="post" >
                <li>
                    <select name="type" >
                        <option value="exerciseID" selected="selected">编号</option>
<!--                        <option value="courseID" >科目号</option>-->
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
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
            <li ><a href="./index.php?r=teacher/choiceLst"><i class="icon-font"></i> 选择</a></li>
            <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width"></i> 填空</a></li>
            <li ><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left"></i> 简答</a></li>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
            <li ><a href="./index.php?r=teacher/keyLst"><i class="icon-th"></i> 键打练习</a></li>
            <li ><a href="./index.php?r=teacher/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li class="active"><a href="./index.php?r=teacher/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
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
                <label class="control-label" for="input02">文件</label>
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
        if (A === "") {
            window.wxc.xcConfirm('内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        $("#upload").show();
        setTimeout('fetch_progress()', 1000);


    });
</script>




