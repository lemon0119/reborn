<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ($isExam == false) {
    require 'suiteSideBar.php';
} else {
    require 'OnExam.php';
    require 'examSideBar.php';
}
$host = Yii::app()->request->hostInfo;
$path = Yii::app()->request->baseUrl;
$rout = 'student/saveFilling';
$page = '/index.php?r=' . $rout;
$SNum = 0;
?>
<div class="span9"style="height:790px; overflow:auto;">
    <form id="klgAnswer" name="na_knlgAnswer" method="post" action = "<?php echo $host . $path . $page; ?>" onkeypress="if(event.keyCode==13){return false;}">
        <div class="hero-unit">
            <input name ="qType" type="hidden" value="filling"/>
            <?php
            $n = 2 * ($pages->currentPage + 1) - 1;
            foreach ($fillingLst as $value) {
                echo ($n) . '. ';
                $str = $value['requirements'];
                $answer = $value['answer'];
                $ansArr = explode('$$', $answer);
                echo $str . '<br/>';
                $i = 1;
                $m = 0;
                while ($i < count($ansArr) + 1) {
                    $f = 0;

                    echo '(' . $i . ') ';
                    foreach ($number as $s) {
                        if ($value['exerciseID'] == $s['exerciseID']) {
                            $f = 1;
                            $str = $ansFilling[$s['exerciseID']];
                            $arr = Array();
                            if (strstr($str, "$$")) {
                                $arr = explode("$$", $str);
                                echo '<input type="text" value="' . $arr[$m] . '" name="' . $i . 'filling' . $value["exerciseID"] . '"></input><br/>';
                                $m++;
                            } else {
                                echo '<input type="text" style ="height:30px;position:relative;top:8px;" value="' . $str . '" name="' . $i . 'filling' . $value["exerciseID"] . '"></input><br/>';
                            }
                        }
                    }
                    if ($f == 0) {
                        echo '<input type="text" style ="height:30px;position:relative;top:8px;" name="' . $i . 'filling' . $value["exerciseID"] . '"></input><br/>';
                    }


                    $i++;
                }
                echo '<br/>';
                $n++;
            }
            ?>
                </form>
            <!-- 显示翻页标签 -->
            <div align=center>
                <?php
                $this->widget('CLinkPager', array('pages' => $pages));
                ?>
            </div>
            <!-- 翻页标签结束 -->
        </div>
        </div>
            </div>
            <div  class="copyright">
                2015 &copy;南京兜秘网络科技有限公司.&nbsp;&nbsp;&nbsp;<span onclick="legalNotice()" class="copyright">法律声明</span><span  onclick="contact()" class="copyright">联系我们</span><span onclick="getHelp()" class="copyright">获得帮助</span>
            </div>   
            </body>


</div>
<script>    
    $(document).ready(function () {  
        
        $("div.span9").find("a").click(function () {
            var url = $(this).attr("href");
            if (url.indexOf("index.php") > 0) {
                $.post($('#klgAnswer').attr('action'), $('#klgAnswer').serialize(), function (result) {
                    console.log(result);
                    window.location.href = url;
                });
                return false;
            }
        });

        $("li#li-filling").attr('class', 'active');
    });
    function submitSuite() {
        var isExam = <?php if ($isExam) {
                    echo 1;
                } else {
                    echo 0;
                } ?>;
        var option = {
            title: "提交",
            btn: parseInt("0011", 4),
            onOk: function () {
                formSubmit2();
                $.post($('#klgAnswer').attr('action'), $('#klgAnswer').serialize(), function (result) {
                });
                $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam; ?>', function () {
                    if (isExam)
                        window.location.href = "index.php?r=student/classExam";
                    else
<?php if (isset($_GET['lessonID'])) { ?>
                        window.location.href = "index.php?r=student/myCourse&&lessonID=<?php echo $_GET['lessonID']; ?>";
<?php } else { ?>
                        window.location.href = "index.php?r=student/myCourse";
<?php } ?>
                });
            }
        };
        window.wxc.xcConfirm("提交以后，不能重新进行答题，你确定提交吗？", "custom", option);

    }
    function formSubmit() {
        $.post($('#klgAnswer').attr('action'), $('#klgAnswer').serialize(), function (result) {
            var option = {
                title: "提示",
                btn: parseInt("001", 2),
                onOk: function () {
                    location.reload();
                }
            };
            window.wxc.xcConfirm(result, "custom", option);


        });
    }
    function formSubmit2() {
        $.post($('#klgAnswer').attr('action'), $('#klgAnswer').serialize(), function (result) {
            var option = {
                title: "提示",
                btn: parseInt("001", 2),
                onOk: function () {
                    location.reload();
                }
            };
            window.wxc.xcConfirm(result, "custom", option);

        });
    }
</script>