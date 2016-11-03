
<html lang="zh-cn"><!--<![endif]--> 
        <head>
            <meta charset="utf-8">
            <title>亚伟速录</title>
            <link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
            <link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
            <script src="<?php echo JS_URL; ?>jquery.min.js"></script>
            <script src="<?php echo JS_URL; ?>bootstrap.min.js"></script>
            <script src="<?php echo JS_URL; ?>site.js"></script>
        </head>
        <body align = "center">
            <div class="span9" style="width: 250px;height: 270px;">
                <form id="form-level" method="post" action="./index.php?r=teacher/selectLevelInfo&&exerciseID=<?php echo $exerciseID ?>&&classID=<?php echo $classID;?>&&lessonID=<?php echo $lessonID;?>">
                    <fieldset>
                    <input type="hidden" name="flag" value="1" />
                    <h3>选择等级</h3>
            <input type="checkbox" name="select[]" value="初级"><span style="position: relative;top: 4px; font-size: 20px">&nbsp;&nbsp;初级</span><br><br>
            <input type="checkbox" name="select[]" value="中级"><span style="position: relative;top: 4px;font-size: 20px">&nbsp;&nbsp;中级</span><br><br>
            <input type="checkbox" name="select[]" value="高级"><span style="position: relative;top: 4px;font-size: 20px">&nbsp;&nbsp;高级</span><br><br>
            <input type="checkbox" name="select[]" value="未分组"><span style="position: relative;top: 4px;font-size: 20px">&nbsp;&nbsp;未分组</span><br><br><br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit"  class="btn btn-primary" style="width: 120px">确定</button>
                    </fieldset>
                </form>
            </div>
        </body>
</html>
<script>
    $(document).ready(function(){
       <?php if(isset($_POST['flag'])){ ?>
            window.opener.location.reload();
            window.close();
        <?php  }?>
    });
</script>
