<!--欢迎界面-->
<div >
    <h3 class="welcome" align="center"> 欢 迎 来 到 亚 伟 速 录 教 学 平 台 ！</h3>
    <button id="b1">test</button>
    <a id="b2">123</a>
</div>

<script>
    $(document).ready(function (){
       var time = <?php echo time();?>;
       var content = "asdfasdfasdfasdfasdf";
       $("#b1").click(function(){
           $.ajax({
               type:"POST",
               dataType:"json",
               url:"index.php?r=api/getAverageSpeed",
               data:{time:time,content:content},
               success:function(data){
                   $("#b2").html(data);
               },
               error:function(xhr, type, exception){
                   console.log('GetAverageSpeed error', type);
                   console.log(xhr, "Failed");
                   console.log(exception, "exception");
                   
               }
               
           });
       });
        
    });
    
</script>
