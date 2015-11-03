<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<?php


require 'workAnsSideBar.php';
?>
<div class="span9">
<div id="ziji">
    <div class="hero-unit">
        <h2>键位练习</h2>
                <?php if($ansWork['answer'] == "")
                    {
                        echo "<font color=red>未作答</font>";
                        echo '</br>';
                    } ?>
        <table border = '0px' width="100%">
            <tr>
                <td width = '50%;' align='center'><?php echo $exer['title']?></td>
                <td width = '100px' align='center'><td align='center'> 正确率：<span id="correct"><?php printf('%2.1f',$ansWork['ratio_correct'] * 100);echo '%';?></span></td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text1'>学生答案：</div>
                    <div ><?php echo $ansWork['answer'];?></div>
                </td>
            </tr>
        </table>
    </div>
</div>
</div>
    

