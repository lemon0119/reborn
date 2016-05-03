<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS_URL; ?>jquery.min.js"></script>
<body style="background-image: none;background-color: #fff">
    <div style="overflow: hidden" id="title"><select id="selectExercise" class="selectBox" onchange="optionOnclick()"></select><button style="margin: 10px;float:right"  class="btn btn-primary" id="back">返回上级</button><button onclick="next()" style="margin: 10px" id="next" class="fr btn btn-primary" <?php if (count($AllIsOpen) < 1) {
    echo 'disabled="disabled"';
} ?>>下一题</button>
        <button class="fr btn btn-primary" onclick="last()" id="last" style="margin: 10px;" disabled="disabled">上一题</button>

    </div>
    <div style="overflow: auto;width: 98%;height: 420px;position: relative;">
        <table id="table_of_analysis"  class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th >排名</th>
                    <th >学号</th>
                    <th >学生</th>
                    <th >平均速度</th>
                    <th >最高速度</th>
                    <th >正确率</th>
                    <th >进行时间(秒)</th>
                    <th >总击键</th>
                    <th >完成次数</th>
                </tr>
            </thead>
            <tbody> 
            </tbody>
        </table>
    </div>
</body>
<script>
    var flag = 0;
    var count = 0;
    var exericseID = <?php echo $_GET['exerciseID']; ?>;
    var exericseName = '<?php echo $ClassExercise['title']; ?>';
    var allExercise = new Array();
    var allExerciseName = new Array();
    allExercise[0] = exericseID;
    allExerciseName[0] = exericseName;
<?php foreach ($AllIsOpen as $k => $v) { ?>
        allExercise[<?php echo $k; ?> + 1] = <?php echo $v['exerciseID']; ?>;
        allExerciseName[<?php echo $k; ?> + 1] = '<?php echo $v['title']; ?>';
<?php } ?>

    $(document).ready(function () {
        checkAnalysis(exericseID);
        setInterval(function () {
            checkAnalysis(exericseID);
        }, 2000);
        selectBoxCheck(0);
    });

    function selectBoxCheck(numb) {
        var t = document.getElementById("selectExercise");
        t.options.length = 0;
        for (var i = 0; i < allExerciseName.length; i++) {
            var option = new Option();
            option.value = allExerciseName[i];
            option.text = allExerciseName[i];
            if (i === numb) {
                option.selected = true;
            }
            t.options.add(option);
        }
    }

    function optionOnclick() {
        var t = document.getElementById("selectExercise").options;
        for (var i = 0; i < t.length; i++) {
            if (t[i].selected) {
                count = i;
                exericseID = allExercise[count];
                exericseName = allExerciseName[count];
                checkAnalysis(exericseID);
            }
        }
        if (count == allExercise.length - 1) {
            $("#next").attr("disabled", "disabled");
        }
        if (exericseID !=<?php echo $_GET['exerciseID']; ?>) {
            $("#last").removeAttr("disabled");
        } else {
            $("#last").attr("disabled", "disabled");
        }
        if (count == 0) {
            $("#next").attr("disabled", "disabled");
        }
        if (count != allExercise.length - 1) {
            $("#next").removeAttr("disabled");
        }
        if (exericseID !=<?php echo $_GET['exerciseID']; ?>) {
            $("#last").removeAttr("disabled");
        } else {
            $("#last").attr("disabled", "disabled");

        }
    }

    $("#back").click(function () {
        window.parent.backToTableClassExercise4virtual();
    });

    function checkAnalysis(exericseID) {
        var classID = <?php echo $_GET['classID']; ?>;
        $.ajax({
            type: "POST",
            url: "index.php?r=teacher/getVirtualClassAnalysis",
            data: {exerciseID: exericseID, classID: classID},
            success: function (data) {
                for (i = 0; i < data.length; i++) {
                    $('#option' + i + '').remove();
                    var newRow = '<tr id="option' + i + '"><td >' + (i + 1) + '</td><td >' + data[i]['studentID'] + '</td><td >' + data[i]['studentName'] + '</td><td>' + data[i]['speed'] + '</td><td>' + data[i]['maxSpeed'] + '</td><td>' + data[i]['correct'] + '%</td><td>' + data[i]['time'] + '</td><td>' + data[i]['allKey'] + '</td><td>' + data[i]['allKey'] + '</td></tr>';
                    $('#table_of_analysis').append(newRow);
                }
                if (flag === 1) {
                    var title = data[0]['title'];
//                       var father = document.getElementById("title");
//                        var back = document.getElementById("back");
//                        var hh = document.createElement("h3");
//                        hh.setAttribute("id","hh");
//                        hh.setAttribute("class","fl");
//                        hh.innerHTML = title;
//                        father.insertBefore(hh,back);
                    if (allExerciseName[count] == title) {
                        selectBoxCheck(count);
                    }
                    flag = 0;
                }

            },
            error: function (xhr, type, exception) {
                console.log('GetAverageSpeed error', type);
                console.log(xhr, "Failed");
                console.log(exception, "exception");
            }
        });



    }


    function next() {
        flag = 1;
//        var h = document.getElementById("hh");
//        var father = document.getElementById("title");
//        father.removeChild(h);

        if (count < allExercise.length - 1) {
            count++;
        }
        exericseID = allExercise[count];
        exericseName = allExerciseName[count];
        checkAnalysis(exericseID);
        if (count == allExercise.length - 1) {
            $("#next").attr("disabled", "disabled");
        }
        if (exericseID !=<?php echo $_GET['exerciseID']; ?>) {
            $("#last").removeAttr("disabled");
        } else {
            $("#last").attr("disabled", "disabled");
        }
    }

    function last() {
        flag = 1;
//        var h = document.getElementById("hh");
//        var father = document.getElementById("title");
//        father.removeChild(h);

        if (count > 0) {
            count--;
        }
        exericseID = allExercise[count];
        exericseName = allExerciseName[count];
        checkAnalysis(exericseID);
        if (count == 0) {
            $("#next").attr("disabled", "disabled");
        }
        if (count != allExercise.length - 1) {
            $("#next").removeAttr("disabled");
        }
        if (exericseID !=<?php echo $_GET['exerciseID']; ?>) {
            $("#last").removeAttr("disabled");
        } else {
            $("#last").attr("disabled", "disabled");

        }
    }


</script>