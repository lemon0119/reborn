<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li style="margin-top:10px">
                <?php if (isset($_GET['nobar'])) { ?>
                    <button onclick="backWindow()" style="height: 35px;top: 1px;left: 10px" class="btn_4big">返 回</button>
                    <button onclick="addExercise(1)" style="height: 35px;margin-left: 10px"  class="btn_4big">新 增</button>
                <?php } else { ?>
                    <button onclick="window.location.href = './index.php?r=teacher/startCourse&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>'" style="height: 35px;top: 1px;left: 10px" class="btn_4big">返 回</button>
                    <button  onclick="addExercise(0)" style="height: 35px;margin-left: 10px" class="btn_4big">新 增</button>
                <?php } ?>
            </li>
            </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing" style="position:relative;bottom:5px;left:"></i>课堂练习</li>
            <?php if (isset($_GET['nobar'])) { ?>
                <li <?php if ($_GET['type'] == 'key') {
                echo 'class="active"';
            } ?>><a href="./index.php?r=teacher/classExercise4Type&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>"><i class="icon-th" style="position:relative;bottom:5px;left:"></i> 键打练习</a></li>
                <li <?php if ($_GET['type'] == 'look') {
                echo 'class="active"';
            } ?> ><a href="./index.php?r=teacher/classExercise4Look&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>"><i class="icon-eye-open" style="position:relative;bottom:5px;left:"></i> 看打练习</a></li>
                <li <?php if ($_GET['type'] == 'listen') {
                echo 'class="active"';
            } ?> ><a href="./index.php?r=teacher/classExercise4Listen&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>"><i class="icon-headphones" style="position:relative;bottom:5px;left:"></i> 听打练习</a></li>
<?php } else { ?>
                <li <?php if ($_GET['type'] == 'key') {
        echo 'class="active"';
    } ?>><a href="./index.php?r=teacher/classExercise4Type&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>"><i class="icon-th" style="position:relative;bottom:5px;left:"></i> 键打练习</a></li>
                <li <?php if ($_GET['type'] == 'look') {
        echo 'class="active"';
    } ?> ><a href="./index.php?r=teacher/classExercise4Look&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>"><i class="icon-eye-open" style="position:relative;bottom:5px;left:"></i> 看打练习</a></li>
                <li <?php if ($_GET['type'] == 'listen') {
        echo 'class="active"';
    } ?> ><a href="./index.php?r=teacher/classExercise4Listen&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>"><i class="icon-headphones" style="position:relative;bottom:5px;left:"></i> 听打练习</a></li>
<?php } ?>
        </ul>
    </div>
</div>
<div class="span9" style="height: 800px">
<?php if (isset($_GET['nobar'])) { ?>
        <iframe src="./index.php?r=teacher/toOwnTypeExercise&&nobar=yes&&classID=<?php echo $classID; ?>&&type=<?php echo $type; ?>&&on=<?php echo $on; ?>" id="iframe1" frameborder="0" scrolling="no" width="770px" height="400px" name="iframe1"></iframe>
<?php } else { ?>
        <iframe src="./index.php?r=teacher/toOwnTypeExercise&&classID=<?php echo $classID; ?>&&type=<?php echo $type; ?>&&on=<?php echo $on; ?>" id="iframe1" frameborder="0" scrolling="no" width="770px" height="400px" name="iframe1"></iframe>
<?php } ?>
    <iframe src="./index.php?r=teacher/ToAllTypeWork&&classID=<?php echo $classID; ?>&&isExercise=1&&type=<?php echo $type; ?>&&on=<?php echo $on; ?>" id="iframe2" frameborder="0" scrolling="no" width="770px" height="400px" name="iframe2"></iframe>
</div >
<script>

    function dele(exerciseID, bar) {

        var option = {
            title: "警告",
            btn: parseInt("0011", 2),
            onOk: function () {
                if (bar) {
                    window.location.href = "./index.php?r=teacher/modifyClassExercise&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>&&type=<?php echo $_GET['type']; ?>&&delete=1&&exerciseID=" + exerciseID;
                } else {
                    window.location.href = "./index.php?r=teacher/modifyClassExercise&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>&&type=<?php echo $_GET['type']; ?>&&delete=1&&exerciseID=" + exerciseID;
                }
            }
        };
        window.wxc.xcConfirm("您确定删除吗？", "custom", option);
    }
    function refresh()
    {
<?php if (isset($_GET['nobar'])) { ?>
            $('#iframe1').attr("src", "./index.php?r=teacher/toOwnTypeExercise&&nobar=yes&&classID=<?php echo $classID; ?>&&type=<?php echo $type; ?>&&on=<?php echo $on; ?>");
<?php } else { ?>
            $('#iframe1').attr("src", "./index.php?r=teacher/toOwnTypeExercise&&classID=<?php echo $classID; ?>&&type=<?php echo $type; ?>&&on=<?php echo $on; ?>");
<?php } ?>

    }

    function backWindow() {
        var type = '<?php echo $type; ?>';
        switch (type) {
            case 'key':
                window.location.href = './index.php?r=teacher/classExercise4Type&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>&&type=key';
                break;
            case 'look':
                window.location.href = './index.php?r=teacher/classExercise4Look&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>&&type=look';
                break;
            case 'listen':
                window.location.href = './index.php?r=teacher/classExercise4Listen&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>&&type=listen';
                break;
        }
    }
    
     function addExercise(nobar) {
        var type = '<?php echo $type; ?>';
        switch (type) {
            case 'key':
                if(nobar){
                   window.location.href = './index.php?r=teacher/addKey4ClassExercise&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>'; 
                }else{
                    window.location.href = './index.php?r=teacher/addKey4ClassExercise&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>';
                }
                break;
            case 'look':
                if(nobar){
                   window.location.href = './index.php?r=teacher/addLook4ClassExercise&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>'; 
                }else{
                    window.location.href = './index.php?r=teacher/addLook4ClassExercise&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>';
                }
                break;
            case 'listen':
                if(nobar){
                   window.location.href = './index.php?r=teacher/addListen4ClassExercise&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>'; 
                }else{
                    window.location.href = './index.php?r=teacher/addListen4ClassExercise&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>';
                }
                break;
        }
    }
</script>