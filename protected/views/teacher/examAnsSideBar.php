<div class="span3">
    <table id="tb" class="table table-bordered table-striped" style="border-style:none">       
        <tbody>
            <tr style="border-style:none">
                <th>姓名:</th>    
                <td><?php echo $student['userName'];?></td>
            </tr>
            <tr style="background: gray;">
                <th>学号:</th>
                 <td><?php echo $student['userID']?></td>
            </tr>
            <tr>
                <th>班级:</th>
                 <td><?php echo $class['className']?></td>
            </tr>      
        </tbody>   
    </table> 
    <div class="well" style="padding: 8px 0;">
    
    <a href="./index.php?r=teacher/NextStuExam&&studentID=<?php 
    $nextID=0;
    $flag=0;
    foreach ($array_accomplished as $a) {
        $nextID=$array_accomplished[0]['userID'];
        if($flag==1){
            $nextID=$a['userID'];
            break;
        }
        if($a['userID']==$student['userID'])
            $flag=1;
    }
    echo $nextID?>&&workID=<?php echo $work['workID']?>&&accomplish=<?php echo $accomplish?>&&classID=<?php echo $class['classID']?>&&examID=<?php echo $examID?>">下一人</a>
   <div>
    <ul class="nav nav-list">
        <li  <?php if($type == "choice") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $work['workID'];?>&&type=choice&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>&&classID=<?php echo $class['classID']?>"><i class="icon-font"></i><span style="position: relative;top: 6px"> 选择</span></a></li>
        <li  <?php if($type == "filling") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $work['workID'];?>&&type=filling&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>&&classID=<?php echo $class['classID']?>"><i class="icon-text-width"></i><span style="position: relative;top: 6px"> 填空</span></a></li>
        <li  <?php if($type == "question") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $work['workID'];?>&&type=question&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>&&classID=<?php echo $class['classID']?>"><i class="icon-align-left"></i> <span style="position: relative;top: 6px">简答</span></a></li>
            <li class="nav-header">键打练习</li>
            <?php foreach ($exercise['key'] as $keyType) :?>
                            <li id="li-key-<?php echo $keyType['exerciseID'];?>">
                                <a title="<?php echo $keyType['title']; ?>" href="./index.php?r=teacher/ansKeyType&&studentID=<?php echo $student['userID']?>&&workID=<?php echo $workID;?>&&accomplish=<?php echo $accomplish;?>&&type=key&&exerID=<?php echo $keyType['exerciseID'];?>">
                                        <i class="icon-th"></i>
                                        <span style="position: relative;top: 6px">
                                        <?php if (Tool::clength($keyType['title']) <= 13){
                                                    echo $keyType['title'];
                                                }else{
                                                    echo Tool::csubstr($keyType['title'], 0, 13) . "...";
                                                } ?>
                                        </span>
                                    </a>
                            </li>
                        <?php endforeach;?>
            <li class="nav-header">看打练习</li>
            <?php foreach ($exercise['look'] as $lookType) :?>
                            <li id="li-look-<?php echo $lookType['exerciseID'];?>">
                                <a title="<?php echo $lookType['title']; ?>" href="./index.php?r=teacher/ansKeyType&&studentID=<?php echo $student['userID']?>&&workID=<?php echo $workID;?>&&accomplish=<?php echo $accomplish;?>&&type=look&&exerID=<?php echo $lookType['exerciseID'];?>">
                                        <i class="icon-eye-open"></i>
                                        <span style="position: relative;top: 6px">
                                        <?php if (Tool::clength($lookType['title']) <= 13){
                                                    echo $lookType['title'];
                                               }else{
                                                    echo Tool::csubstr($lookType['title'], 0, 13) . "...";
                                               } ?>
                                        </span>
                                    </a>
                            </li>
                        <?php endforeach;?>
            <li class="nav-header">听打练习</li>   
            <?php foreach ($exercise['listen'] as $listenType) :?>
                        <li id="li-listen-<?php echo $listenType['exerciseID'];?>">
                            <a title="<?php echo $listenType['title']; ?>" href="./index.php?r=teacher/ansKeyType&&studentID=<?php echo $student['userID']?>&&workID=<?php echo $workID;?>&&accomplish=<?php echo $accomplish;?>&&type=listen&&exerID=<?php echo $listenType['exerciseID'];?>">
                                    <i class="icon-headphones"></i>
                                    <span style="position: relative;top: 6px">
                                    <?php if (Tool::clength($listenType['title']) <= 13){
                                                echo $listenType['title'];
                                           }else{
                                                echo Tool::csubstr($listenType['title'], 0, 13) . "...";
                                           } ?>
                                    </span>
                                </a>
                        </li>
                        <?php endforeach;?>
    </ul>
   </div>
      
    </div>
    <table class="table table-bordered table-striped">       
        <tbody>
            <tr>
                <th>成绩:</th>    
                <td><div id="score"><?php echo $score;?></div></td>
            </tr>    
        </tbody>   
    </table> 
    <a class="btn btn-primary" href="./index.php?r=teacher/stuExam">返回</a>
</div>