       <script>
    $(document).ready(function(){
            $("div.span9").find("a").click(function(){
                var url = $(this).attr("href");
                //$(this).attr("href","#");
                if(url.indexOf("index.php") > 0){
                    $("#cont").load(url);
                    return false;//阻止链接跳转
                }
            });
    });
</script>
<div class="span9">
        <div class="hero-unit">
            
        <h3>老师信息</h3>
        
        <p>工号:<?php echo $id; echo '&nbsp; &nbsp;';?> 姓名:<?php echo $name;?></p>
        <br>
        <p>所带班级</p>
        <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>班号</th>
                <th>班级名</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($posts as $model):?>
                    <tr>
                        <td style="width: 75px"><?php echo $model['classID'];?></td>
                        <td><?php echo $model['className'];?></td>
                        <td>  
                            <a href="./index.php?r=admin/infoTea&&flag=<?php echo $flag;?>&&action=delete&&id=<?php echo $id;?>&&name=<?php echo $name;?>&&classID=<?php echo $model['classID'];?>"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
        </table>
        <div align=center>
        <?php   
        $this->widget('CLinkPager',array('pages'=>$pages));
        ?>
        </div>
        
        <button type="button" onclick="back()">确定</button>
        
        <script>
        function back()
        {
             $("#cont").load(<?php
                                    if ($flag == 1) {
                                         echo "'./index.php?r=admin/TeaLst'";
                                    } else if ($flag == 2) {
                                        echo "'./index.php?r=admin/classLst'";
                                    } else if ($flag == 3)
                                    {
                                      echo "'./index.php?r=admin/infoClass&&classID=$classID'";
                                    }
                                    ?>); 
        }
        </script>
        
            
        </div>
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
    <script>
        if(result != null){
            alert(result);
            result = null;
        }
    </script>

