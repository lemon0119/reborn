
    
<?php
//require 'studentBar.php';
if ($isExam == FALSE) {
    require 'suiteSideBar.php';
} else {
    require 'OnExam.php';
    require 'examSideBar.php';
}
$host = Yii::app()->request->hostInfo;
$path = Yii::app()->request->baseUrl;
$rout = 'student/saveChoice';
$page = '/index.php?r=' . $rout;
$SNum = 0;
?>
                <div class="row" style="min-height: 700px">
              <div class="span9"style="height:790px; overflow:auto;">
    <form id="klgAnswer" name="na_knlgAnswer" method="post" action = "<?php echo $host . $path . $page; ?>">
        <div class="hero-unit">
            <input name ="qType" type="hidden" value="choice"/>
            <?php
            $n = 2 * ($pages->currentPage + 1) - 1;
            foreach ($choiceLst as $value) {

                echo ($n) . '. ';
                echo $value['requirements'];
                echo '<br/>';
                $opt = $value['options'];
                $optArr = explode("$$", $opt);
                $mark = 'A';
                foreach ($optArr as $aOpt) {
                    $f = 0;
                    foreach ($number as $s) {
                        if ($value['exerciseID'] == $s['exerciseID']) {
                            if ($ansChoice[$s['exerciseID']] == $mark) {
                                $f = 1;
                                echo '<input type="radio" checked="true" value="' . $mark . '" name="choice' . $value["exerciseID"] . '">&nbsp' . $mark . '.' . $aOpt . '</input><br/>';
                            }
                        }
                    }
                    if ($f == 0) {
                        echo '<input type="radio" value="' . $mark . '" name="choice' . $value["exerciseID"] . '">&nbsp' . $mark . '.' . $aOpt . '</input><br/>';
                    }
                    $mark++;
                }

                echo '<br/>';
                $n++;
            }
            ?>
        </div>
    </form>
</div>
                </div>
            </div>
            <div  class="copyright">
                2015 &copy;南京兜秘网络科技有限公司.&nbsp;&nbsp;&nbsp;<span onclick="legalNotice()" class="copyright">法律声明</span><span  onclick="contact()" class="copyright">联系我们</span><span onclick="getHelp()" class="copyright">获得帮助</span>
            </div>   
            </body>
</html>    
    <script>

        $(document).ready(function () {

            $("div.span9").find("a").click(function () {
                var url = $(this).attr("href");
                if (url.indexOf("index.php") > 0) {
                    $.post($('#klgAnswer').attr('action'), $('#klgAnswer').serialize(), function (result) {
                        window.location.href = url;
                    });
                    return false;
                }
            });
            $("li#li-choice").attr('class', 'active');

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