<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script src="<?php echo JS_URL; ?>exerJS/AnalysisTool.js"></script>
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <br/>
        <ul >
            <li style="padding:0px 0px;" class="nav-header"><a href="./index.php?r=student/freePractice"><img src="<?php echo IMG_URL; ?>../on_btn_bigret.png"></a></li>

        </ul>
        <br/>
        <ul class="nav nav-list">
            <li class="nav-header"><img src="<?php echo IMG_UIStu_URL; ?>keyb.png">课 时 列 表</li>
            <?php foreach ($lessons as $less) { ?>
                <li id="<?php echo $less['lessonID'] ?>">
                    <a href="./index.php?r=student/freePractice&&lessonID=<?php echo $less['lessonID'] ?>">
                        <img class="act" src="<?php echo IMG_UIStu_URL ?>listOf.png"><?php echo $less['lessonName'] ?>
                    </a>
                </li>
            <?php } ?>

        </ul>
    </div>
</div>
<?php if (isset($_GET['lessonID'])) { ?>

<?php } else { ?>
    <div class="span9">
        <h3 >自 由 练 习</h3>
        <br/>
        <div class="fr" style="width:300px; position: relative;right: 10px">
            <table cellpadding="8" style="margin: 0px auto;">
                <tr>
                    <td><span class="fl"  style="font-weight: bolder">平均速度：</span><span style="color: #f46500" id="getAverageSpeed">&nbsp;&nbsp;0&nbsp;&nbsp;</span><span class="fr" style="color: gray"> 字/分</span> </td>
                    <td><span class="fl"  style="font-weight: bolder">平均击键：</span><span style="color: #f46500" id="getAverageKeyType">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 次/分</span></td></tr>
                <tr><td><span class="fl"  style="font-weight: bolder">瞬时速度：</span><span style="color: #f46500" id="getMomentSpeed">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 字/分</span></td>
                    <td><span class="fl"  style="font-weight: bolder">瞬时击键：</span><span style="color: #f46500" id="getMomentKeyType">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 次/秒</span></td></tr>
                <tr><td><span class="fl"  style="font-weight: bolder">最高速度：</span><span style="color: #f46500" id="getHighstSpeed">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 字/分</span></td>
                <td><span class="fl"  style="font-weight: bolder">最高击键：</span><span style="color: #f46500" id="getHighstCountKey">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 次/秒</span></td></tr>
                <tr><td><span class="fl"  style="font-weight: bolder">击键间隔：</span><span style="color: #f46500" id="getIntervalTime">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                <td><span class="fl"  style="font-weight: bolder">最高间隔：</span><span style="color: #f46500" id="getHighIntervarlTime">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 秒&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
                <tr><td><span class="fl"  style="font-weight: bolder">总击键数：</span><span style="color: #f46500" id="getcountAllKey">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 次&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                <td><span class="fl"  style="font-weight: bolder">回改字数：</span><span style="color: #f46500" id="getBackDelete">&nbsp;&nbsp;0&nbsp;&nbsp;</span ><span class="fr" style="color: gray"> 字&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
            </table>
        </div>
        <div>
            <?php require Yii::app()->basePath . "\\views\\student\\keyboard_freePractice.php"; ?>
        </div>
    </div>
<?php } ?>
