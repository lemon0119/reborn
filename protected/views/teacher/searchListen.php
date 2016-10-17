<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
            <form action="./index.php?r=teacher/searchListen" method="post">
                <li>
                    <select name="type" >
                        <option value="exerciseID" >编号</option>
                        <option value="title"  selected="selected">题目名</option>
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
            <li class="nav-header"><i class="icon-knowlage"></i><span style="position: relative;top: 6px">基础知识</span></li>
            <li ><a href="./index.php?r=teacher/choiceLst"><i class="icon-font"></i> <span style="position: relative;top: 6px">选择</span></a></li>
            <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width"></i><span style="position: relative;top: 6px"> 填空</span></a></li>
            <li ><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left"></i> <span style="position: relative;top: 6px">简答</span></a></li>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing"></i><span style="position: relative;top: 6px">打字练习</span></li>
            <li ><a href="./index.php?r=teacher/keyLst"><i class="icon-th"></i><span style="position: relative;top: 6px"> 键打练习</span></a></li>
            <li ><a href="./index.php?r=teacher/lookLst"><i class="icon-eye-open"></i> <span style="position: relative;top: 6px">看打练习</span></a></li>
            <li class="active"><a href="./index.php?r=teacher/listenLst"><i class="icon-headphones"></i> <span style="position: relative;top: 6px">听打练习</span></a></li>
        </ul>
    </div>
</div>

<?php
//得到老师ID对应的名称
foreach ($teacher as $model):
    $teacherID = $model['userID'];
    $teachers["$teacherID"] = $model['userName'];
endforeach;
$code = mt_rand(0, 1000000);
?>

<div class="span9">
    <!-- 键位习题列表-->
    <?php if ($listenLst->count() != 0) { ?>
        <h2>查询结果</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="font-center">选择</th>
                    <th class="font-center">编号</th>

                    <th class="font-center">题目</th>
                    <th class="font-center">速度</th>
                    <th class="font-center">创建人</th>
                    <th class="font-center">创建时间</th>
                    <th class="font-center">操作</th>
                </tr>
            </thead>
            <tbody>        
            <form id="copyForm" method="post" action="./index.php?r=teacher/copyListen" >
                <?php foreach ($listenLst as $model): ?>
                    <tr>
                        <td class="font-center" style="width: 50px"> <input type="checkbox" name="checkbox[]" value="<?php echo $model['exerciseID']; ?>" /> </td>
                        <td class="font-center" style="width: 50px"><?php echo $model['exerciseID']; ?></td>

                        <td class="font-center" title="<?php echo $model['title']; ?>"><?php
                            if (Tool::clength($model['title']) <= 7)
                                echo $model['title'];
                            else
                                echo Tool::csubstr($model['title'], 0, 7) . "...";
                            ?></td>
                        <td class="font-center" title="<?php echo $model['speed']; ?>"><?php
                            echo $model['speed'] ;
                        ?></td>
                        <td class="font-center"><?php
                            if ($model['createPerson'] == "0"){
                                echo "管理员";
                            }else if(isset($teachers[$model['createPerson']])){
                                echo $teachers[$model['createPerson']];
                            }else{
                                echo "未知";
                            }
                            ?></td>
                        <td class="font-center"><?php echo $model['createTime']; ?></td>
                        <td class="font-center" style="width: 100px">
                            <a href="./index.php?r=teacher/editListen&&exerciseID=<?php echo $model['exerciseID']; ?>&&action=look"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"></a>
                            <?php if ($model['createPerson'] == Yii::app()->session['userid_now']) { ?>
                                <a href="./index.php?r=teacher/editListen&&exerciseID=<?php echo $model['exerciseID']; ?>"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
                                <a href="#"  onclick="dele(<?php echo $model['exerciseID']; ?>)"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
                            <?php } else { ?>
                                <a href="./index.php?r=teacher/copyListen&&code=<?php echo $code; ?>&&exerciseID=<?php echo $model['exerciseID']; ?>"><img title="复制" src="<?php echo IMG_URL; ?>copy.png"></a>
                            <?php } ?>     
                        </td>
                    </tr>            
                <?php endforeach; ?> 
            </form>
            </tbody>
        </table>
        <!-- 学生列表结束 -->
        <!-- 显示翻页标签 -->
        <div align=center>
            <?php
            $this->widget('CLinkPager', array('pages' => $pages));
            ?>
        </div>
        <!-- 翻页标签结束 -->
    <?php } else { ?>
        <h2>查询结果为空！</h2>
    <?php } ?>

</div>

<script>
    $(document).ready(function () {
        var result = <?php if (isset($result)) echo "'$result'";
    else echo'1'; ?>;
        if (result === '1')
            window.wxc.xcConfirm('复制成功！', window.wxc.xcConfirm.typeEnum.success);
        else if (result === '0')
            window.wxc.xcConfirm('复制失败！', window.wxc.xcConfirm.typeEnum.error);
        else if (result === '2')
            window.wxc.xcConfirm('文件已存在', window.wxc.xcConfirm.typeEnum.info);

        result = "";
    }
    );
    function dele(exerciseID) {

        var option = {
            title: "警告",
            btn: parseInt("0011", 2),
            onOk: function () {
                window.location.href = "./index.php?r=teacher/deleteListen&&exerciseID=" + exerciseID;
            }
        }
        window.wxc.xcConfirm("您确定删除吗？", "custom", option);
    }

</script>