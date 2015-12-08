<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script src="<?php echo JS_URL;?>exerJS/ocxJS.js"></script>
<link href="<?php echo CSS_URL; ?>ywStyle.css" rel="stylesheet" type="text/css" />
<table border="0">
    <tr>
      <td><div id="l_a" class = "key"><div>A</div></div></td>
      <td><div id="l_n" class = "key"><div>N</div></div></td>
      <td><div id="l_i" class = "key"><div>I</div></div></td>
      <td><div id="l_g" class = "key"><div>G</div></div></td>
      <td><div id="l_d" class = "key"><div>D</div></div></td>
              <td>&nbsp;</td>
      <td><div id="r_d" class = "key"><div>D</div></div></td>
      <td><div id="r_g" class = "key"><div>G</div></div></td>
      <td><div id="r_i" class = "key"><div>I</div></div></td>
      <td><div id="r_n" class = "key"><div>N</div></div></td>
      <td><div id="r_a" class = "key"><div>A</div></div></td>
    </tr>
    <tr>
      <td><div id="l_o" class = "key"><div>O</div></div></td>
      <td><div id="l_e" class = "key"><div>E</div></div></td>
      <td><div id="l_u" class = "key"><div>U</div></div></td>
      <td><div id="l_w" class = "key"><div>W</div></div></td>
      <td><div id="l_z" class = "key"><div>Z</div></div></td>
              <td>&nbsp;</td>
      <td><div id="r_z" class = "key"><div>Z</div></div></td>
      <td><div id="r_w" class = "key"><div>W</div></div></td>
      <td><div id="r_u" class = "key"><div>U</div></div></td>
      <td><div id="r_e" class = "key"><div>E</div></div></td>
      <td><div id="r_o" class = "key"><div>O</div></div></td>
    </tr>
    <tr><td></td></tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div id="l_b" class = "key"><div>B</div></div></td>
      <td><div id="l_x" class = "key"><div>X</div></div></td>
              <td>&nbsp;</td>
      <td><div id="r_x" class = "key"><div>X</div></div></td>
      <td><div id="r_b" class = "key"><div>B</div></div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
</table>
<script>
    function keySet(keyID,isRight){
        var obj = document.getElementById(keyID);
        if(isRight)
            obj.className = "key_right";
        else
            obj.className = "key_wrong";
    }
    
    function keyReSet(){
        var t=document.getElementsByTagName('*');
        for(var i=0,l=t.length;i<l;i++){
            var id=t[i].getAttribute('id');
            if(id == null) continue;
            if(id.indexOf("l_") == 0){
                t[i].className = "key";
            } else if(id.indexOf("r_") == 0){
                t[i].className = "key";
            }
        }
    }
    
    function storyKey(stenoString){
        var answer = document.getElementById("id_answer");
        var oldstr = answer.value;
        if(oldstr == "")
            oldstr = stenoString;
        else
            oldstr = oldstr + " "+stenoString;
        answer.value = oldstr;
    }
    
    function checkChar(char,isleft){
        var wordArr = yaweiCode.split("");
        var left = true;

        if(isleft){
            for(var i = 0; i < wordArr.length; i++){                
                if(char == wordArr[i])
                    return true;
                if(wordArr[i] == ':')
                    return false;
            }
        }
        else {
            var i = 0;
            while(wordArr[i] != ':')
                i++;
            while(++i < wordArr.length){
                if(char == wordArr[i])
                    return true;
            }
        }
        return false;
    }
    
    function onStenoPressKey(pszStenoString ,device){
        if(totalNum == currentNum){
            window.wxc.xcConfirm('键位练习已完成', window.wxc.xcConfirm.typeEnum.success,{
                onOk:function(){
                    currentNum = totalNum;
                },
                onClose:function(){
                    currentNum = totalNum;
                }
            });
            currentNum = totalNum;
            return ;
        }
        var charSet = pszStenoString.split("");
        var left = true;
        storyKey(pszStenoString);
        keyReSet();
            var left = true;
            for(var i = 0; i < yaweiCode.length; i++){
            if(yaweiCode[i] == ':'){
                left = false;
                continue;
            }
            var c = yaweiCode[i].toLowerCase();
            if(left){
                    keySet("l_"+ c, true);
            }else{
                    keySet("r_"+c , true);
            }
            }      
        changTemplet(pszStenoString);
        var correct = getCorrect()*100;
        document.getElementById("correctRate").innerHTML = correct.toFixed(2);       
    }
    var wordArray = new Array();
    var yaweiCodeArray = new Array();
    var totalNum = 0;
    var currentNum = -1;
    var nextWord = "";
    var yaweiCode = "";
    var numKeyDown = 0;
    var numKeyRight = 0;
    function startParse(){
        var content = document.getElementById("id_content").value;
        var cont_array = content.split("$$");
        for(var i = 0; i < cont_array.length; i += 1){
            var yaweiCode = cont_array[i].split(":0")[0];
            yaweiCodeArray.push(yaweiCode);   
            var word = cont_array[i].split(":0")[1];
            wordArray.push(word);
            totalNum += 1;
        }
        nextWord = getNextWord();
        setWordView(nextWord);
    }
    
    function setWordView(word){
        document.getElementById("word").innerHTML = word;
        $('#keyMode').fadeOut(50);
        $('#keyMode').fadeIn(50);
    }
    function changTemplet(pszStenoString){

        if(isSameWord(pszStenoString,yaweiCode)){     
            nextWord = "";
            nextWord = getNextWord();
            setWordView(nextWord);
            ++numKeyDown;
            ++numKeyRight;
        } else {               
            nextWord = "";
            nextWord = getNextWord();
            setWordView(nextWord);
            ++numKeyDown;
        }
    }
    
    function isSameWord(word1, word2){
        var wb = word2.split(':');
        var wa = word1.split(':');
        var left1 = wa[0];
        var left2 = wb[0];
        var right1 = wa[1];
        var right2 = wb[1];
//        var ls1 = left1.split('').sort();
//        var ls2 = left2.split('').sort();
//        var leftsame = isSameArray(ls1 , ls2);
//        var rs1 = right1.split('').sort();
//        var rs2 = right2.split('').sort();
//        var rightsame = isSameArray(rs1 , rs2);
//        return  leftsame && rightsame;
       if(left1 == left2 && right1 == right2)
           return true;
       else
           return false;
    }
    function isSameArray(a1,a2){
        for(var i = 0; i < a1.length && i < a2.length; ++i){
            if(a1[i] != a2[i])
                return false;
        }
        return true;
    }
    function getCorrect(){
        return numKeyRight / numKeyDown;
    }
    function getNextWord(){
        currentNum++;
        if(totalNum == currentNum){
            window.wxc.xcConfirm('键位练习完成', window.wxc.xcConfirm.typeEnum.success);
            return '';
        }       
        if(nextWord != "")
            return nextWord;
        var result = wordArray[currentNum];
        yaweiCode = yaweiCodeArray[currentNum];
        
        return result;
    }
    
    function getYaweiCode(){
        return yaweiCode[currentNum];
    }
    
</script>
<object id="typeOCX" type="application/x-itst-activex" 
        clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
        width ='0' height='0'
        event_OnStenoPress="onStenoPressKey">
</object>