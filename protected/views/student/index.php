<div>
    <h3 align="center" class="welcome" >欢 迎 来 到 亚 伟 速 录 教 学 平 台 ！</h3>
    <button onclick="insert()">导入数据库</button>
</div>
<script>
    function insert() {
            var s= <?php
//            $data=[123,33,22];
//                    file_put_contents("E:/data.txt", json_encode($data));
//                    //获取数据
//                    $datas = json_decode(file_get_contents("E:/data.txt"));
//                    error_log(file_get_contents("E:/data.txt"));
            $date = date('Ymd');
            $date = base_convert($date,10,8);
            echo $date2;
            ?>;
                    console.debug(s);
    }
</script>