<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header">查询</li>
                <form action="./index.php?r=admin/searchListen" method="post">
                        <li>
                                <select name="type" style="width: 185px">
                                        <option value="exerciseID" selected="selected">编号</option>
                                        <option value="courseID" >课程号</option>
                                        <option value="title" >题目名</option>
                                        <option value="createPerson" >创建人</option>
                                </select>
                        </li>
                        <li>
                                <input name="value" type="text" class="search-query span2" placeholder="Search" />
                        </li>
                        <li style="margin-top:10px">
                                <button type="submit" class="btn btn-primary">查询</button>
                                <a href="./index.php?r=admin/addListen" class="btn">添加</a>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header">基础知识</li>
                        <li ><a href="./index.php?r=admin/choiceLst"><i class="icon-font"></i> 选择</a></li>
                        <li ><a href="./index.php?r=admin/fillLst"><i class="icon-text-width"></i> 填空</a></li>
                        <li ><a href="./index.php?r=admin/questionLst"><i class="icon-align-left"></i> 简答</a></li>
                        <li class="divider"></li>
                        <li class="nav-header">打字练习</li>
                        <li ><a href="./index.php?r=admin/keyLst"><i class="icon-th"></i> 键位练习</a></li>
                        <li ><a href="./index.php?r=admin/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li class="active"><a href="./index.php?r=admin/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
                </ul>
        </div>
</div>

<div class="span9"> 
            
        <h3>添加听打练习</h3>
        <form id="myForm" method="post" action="./index.php?r=admin/listenLst&&action=add" enctype="multipart/form-data"> 
        名称：<input type="text" name="title">
        <label for="file">Filename:</label>
        <input type="file" name="file" id="file" /> 
        <br />
        <br>
        内容：
        <br>
        <textarea name="content" style="width:600px; height:300px;" ></textarea>
        <br>
        <input type="submit" name="submit" value="提交"> 
        </form>   
        <?php
        if(isset($shao))
        {
             echo $shao;
        }
        ?>
        </div>

    
    <?php
       //显示操作结果
       if(isset($result))
       {
           if(!empty($result))
           {
               echo "<script>var result = '$result';</script>";
           }
       }
    ?>
  
    


