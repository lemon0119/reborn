/* 
 * Js AnalysisTool
 * Create By pengjingcheng @qq 390928903   date 2015_12_3
 */

    //统计平均速度 
    //@param $id：控件id
    //@param $startTime:设置平均速度统计的开始时间
    //@param $content：内容
    //@return $data: 获取平均速度 字/分钟
function AjaxGetAverageSpeed(id,startTime,content){
     $.ajax({
               type:"POST",
               dataType:"json",
               url:"index.php?r=api/getAverageSpeed",
               data:{startTime:startTime,content:content},
               success:function(data){
                   $("#"+id).html(data);
               },
               error:function(xhr, type, exception){
                   console.log('GetAverageSpeed error', type);
                   console.log(xhr, "Failed");
                   console.log(exception, "exception");
                   
               }
           });
}

    //统计瞬时速度 
    //@param $id：控件id
    //@param $setTime:设置瞬时速度统计的时间区间
    //@param $contentlength：区间内输入的字符长度，需要前端js计算，不再直接传入内容以减小服务器压力
    //@return $data: 获取瞬时速度  字/分钟
function AjaxGetMomentSpeed(id,setTime,contentlength){
    $.ajax({
               type:"POST",
               dataType:"json",
               url:"index.php?r=api/getMomentSpeed",
               data:{setTime:setTime,contentlength:contentlength},
               success:function(data){
                   $("#"+id).html(data);
               },
               error:function(xhr, type, exception){
                   console.log('GetAverageSpeed error', type);
                   console.log(xhr, "Failed");
                   console.log(exception, "exception");
               }
           });
}

    //统计回改字数
    //@param $id：控件id
    //@param $doneCount:已统计的回改字数
    //@param $keyType：键位码
    //@return $donecount: 新的总回改字数
function AjaxGetBackDelete(id,doneCount,keyType){
    $.ajax({
               type:"POST",
               dataType:"json",
               url:"index.php?r=api/getBackDelete",
               data:{doneCount:doneCount,keyType:keyType},
               success:function(data){
                   $("#"+id).html(data);
               },
               error:function(xhr, type, exception){
                   console.log('GetAverageSpeed error', type);
                   console.log(xhr, "Failed");
                   console.log(exception, "exception");
               }
           });
}

    //统计错误字数
    //@param $id1：正确字数控件id
    //@param $id2：错误字数控件id
    //@param $id3：正确率控件id
    //@param $originalContent:答案内容
    //@param $currentContent：用户输入内容 
function AjaxGetRight_Wrong_AccuracyRate(id1,id2,id3,originalContent,currentContent){
    $.ajax({
               type:"POST",
               dataType:"json",
               url:"index.php?r=api/getWrongFont",
               data:{originalContent:originalContent,currentContent:currentContent},
               success:function(data){
                   $("#"+id1).html(data[0]);
                   $("#"+id2).html(data[1]);
                   $("#"+id3).html(data[2]);
               },
               error:function(xhr, type, exception){
                   console.log('GetAverageSpeed error', type);
                   console.log(xhr, "Failed");
                   console.log(exception, "exception");
               }
           });
}

