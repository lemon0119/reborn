/* 
 * Js AnalysisTool
 * Create By pengjingcheng @qq 390928903   date 2015_12_3
 * 
 * 请在主view中设置全局变量 
 * @param G_setEndTime 设置统计的轮询刷新开始到结束的时间，如果你想让JS1000秒后结束统计请设置1000
 * 
 * 请在主view中声明全局变量  ！不设置任何值！ 
 * @param G_startTime 
 * @param G_countAllKey 
 * @param G_countMomentKey
 * @param G_startFlag 
 * @param G_content = "";
 * @param G_keyContent= "";
 */

var G_oldContentLength = 0;
var G_oldKeyContentLength = 0;
//统计逻辑
$(document).ready(function(){
    var highstCountKey  = 0;
    var highstSpeed     = 0;
    var highstKeySpeed  = 0;
    var newKeyContent   = "";
    //2s内统计统计瞬时击键 次/分钟
    //@param id=getMomentKeyType 请将瞬时击键统计的控件id设置为getMomentKeyType
    //2s内统计统计最高击键 
    //@param id=getHighstCountKey 请将最高击键统计的控件id设置为getHighstCountKey
    //2s内统计统计平均击键 
    //@param id=getAverageKeyType 请将最高击键统计的控件id设置为getAverageKeyType
    //2s内统计统计总按键数 
    //@param id=getcountAllKey 请将最高击键统计的控件id设置为getcountAllKey
    //2s内统计统计平均速度 
    //@param id=getAverageSpeed 请将平均速度统计的控件id设置为getAverageSpeed
    //2s内统计统计平均速度 
    //@param id=getHighstSpeed 请将最高平均速度统计的控件id设置为getHighstSpeed
    //2s内统计瞬时速度 
    //@param id=getMomentSpeed 请将最高平均速度统计的控件id设置为getMomentSpeed 
    //2s内统计回改字数
    //@param id=getBackDelete 请将最高平均速度统计的控件id设置为getBackDelete
    
    //2s内统计瞬时码长
    //@param id=getMomentKeyLength 请将最高平均速度统计的控件id设置为getMomentKeyLength 
    //2s内统计最低码长
    //@param id=getLowstKeyLength 请将最高平均速度统计的控件id设置为getLowstKeyLength   
    //2s内统计平均码长
    //@param id=getAverageKeyLength 请将最高平均速度统计的控件id设置为getAverageKeyLength   
    //2s内统计最高瞬时码长
    //@param id=getHighstMomentKeyLength 请将最高平均速度统计的控件id设置为getHighstMomentKeyLength
    var timer = setInterval(function(){
        var content        = window.G_content;
        var keyContent     = window.G_keyContent;
        var setEndTime     = window.G_setEndTime;
        var startTime      = window.G_startTime;
        var countAllKey    = window.G_countAllKey;
        var countMomentKey = window.G_countMomentKey;
        var myDate         = new Date();
        var nowTime        = myDate.getTime();
        $("#getcountAllKey").html(countAllKey);
        if(nowTime>startTime){
            var averageKeyType = parseInt(countAllKey/(nowTime-startTime)*60000);
            if(averageKeyType === 0){
                averageKeyType = 1;
            }
            $("#getAverageKeyType").html(averageKeyType);
        }else{
            $("#getAverageKeyType").html(0);
        }
        
        if(countMomentKey>0){
           $("#getMomentKeyType").html(countMomentKey/2*60); 
           if((countMomentKey/2*60)>highstCountKey){
               highstCountKey = countMomentKey/2*60;
               $("#getHighstCountKey").html(highstCountKey);
           }
           window.G_countMomentKey=0;
        }else{
            $("#getMomentKeyType").html(0); 
        }
         countMomentKey=0;
         
         if(typeof(content)!=="undefined"){
          //瞬时速度 字/分钟
          if(content.length>0){
            var IntervalContentLength = (content.length - window.G_oldContentLength);
            if(IntervalContentLength<0){
                IntervalContentLength = 0;
            }
            window.G_oldContentLength = content.length;
            var momentSpeed = (IntervalContentLength/2)*60;
            $("#getMomentSpeed").html(momentSpeed);
            //最高瞬时速度 字/分钟
            if(momentSpeed>highstSpeed){
                  highstSpeed = momentSpeed;
                  $("#getHighstSpeed").html(momentSpeed);
              }
          }
        
            //平均速度 字/分钟
            if(content.length>0){
                var averageSpeed = parseInt(content.length/(nowTime-startTime)*60000);
                if(averageSpeed ===0){
                    averageSpeed = 1;
                }
                $("#getAverageSpeed").html(averageSpeed); 
           }
        }
           //统计回改字数
           var CountBackDelete = 0;
           var array_keyContent = keyContent.split("&");
           if(array_keyContent.length>3){
                for(var i=3;i<array_keyContent.length;i++){
                var lastHaveLeft = true;
                var lastNo_W = true;
                var array_singleKeyContentLast = array_keyContent[i-1].split("");
                
                for(var k=0;k<array_singleKeyContentLast.length;k++){
                    if(array_singleKeyContentLast[k]===":"){
                        if(k===0){
                            lastHaveLeft = false;
                        }
                    }
                    if(array_singleKeyContentLast[k]===":"){
                        if(k>0){
                            if(array_singleKeyContentLast[k-1]==="W"){
                                lastNo_W = false;
                            }
                        }
                    }
                }
                var array_singleKeyContent = array_keyContent[i].split("");
                for(var j=0;j<array_singleKeyContent.length;j++){
                    var left;
                    var right;
                    if(array_singleKeyContent[j]!=="W"&&array_singleKeyContent[j]!==":"){
                        left = 0;
                        right = 0;
                        break;
                    }
                    if(array_singleKeyContent[j]===":"){
                        if(array_singleKeyContent[j-1]==="W"&&lastHaveLeft&&lastNo_W){
                             left = 1;
                        }
                        if(array_singleKeyContent[j+1]==="W"){
                             right = 1;
                        }
                    }
                }
                if(left===1){
                    CountBackDelete++;
                    left=0;
                }
                if(right===1){
                    CountBackDelete++;
                    right=0;
                }
            }
                $("#getBackDelete").html(CountBackDelete); 
           }
           
           if(typeof(keyContent)!=="undefined"){
           //统计平均码长 个/分钟
            if((nowTime-startTime)>0){
                newKeyContent = (keyContent.split("&").join('')).split(":").join('');
                var averagekeyLength = parseInt((newKeyContent.length/(nowTime-startTime))*60000); 
                 if(averagekeyLength ===0){
                    averagekeyLength = 1;
                }
                $("#getAverageKeyLength").html(averagekeyLength);
            }
            //统计瞬时码长  个/分钟
            if(newKeyContent.length>0){
                console.log(newKeyContent);
              var IntervalKeyContentLength = (newKeyContent.length - window.G_oldKeyContentLength);
              if(IntervalKeyContentLength<0){
                  IntervalKeyContentLength = 0;
              }
              window.G_oldKeyContentLength = newKeyContent.length;
              var momentKeySpeed = (IntervalKeyContentLength/2)*60;
              $("#getMomentKeyLength").html(momentKeySpeed);
              //最高瞬时码长 个/分钟
              if(momentSpeed>highstKeySpeed){
                    highstKeySpeed = momentSpeed;
                    $("#getHighstMomentKeyLength").html(momentKeySpeed);
                }
              //最低码长 个/分钟
              var lowestKeyLength;
              if(window.G_startFlag === 0){
                  lowestKeyLength = momentSpeed;
              }
              if(momentSpeed<lowestKeyLength){
                    lowestKeyLength = momentSpeed;
                    $("#getLowstKeyLength").html(lowestKeyLength);
                }
            }
          
           }
           
           
           //判断统计结束
         if(((nowTime-startTime))>(setEndTime*1000)){
              $("#getMomentKeyType").html(0);
              $("#getHighstSpeed").html(0);
             clearInterval(timer);
         }
    },2000);
    
    
});
//拿取键码值

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

   



