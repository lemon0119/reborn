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
        var nextWord = getNextWord();
        if(nextWord == "" || nextWord == " ")
            return false;
        var wordArr = nextWord.split("");
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
        var charSet = pszStenoString.split("");
        var left = true;
        storyKey(pszStenoString);
        keyReSet();
        for(var i = 0; i < charSet.length; i++){
            if(charSet[i] == ':'){
                left = false;
                continue;
            }
            var c = charSet[i].toLowerCase();
            if(left){
                if(checkChar(charSet[i],true))
                    keySet("l_"+ c, true);
                else
                    keySet("l_"+c , false);
            }else{
                if(checkChar(charSet[i],false))
                    keySet("r_"+c , true);
                else
                    keySet("r_"+c , false);
            }
        }
        changTemplet(pszStenoString);
    }
    
    function getNextWord(){
        var nextWord = document.getElementById("id_wrong").firstChild.nodeValue.trim();
        if(nextWord != " " && nextWord != "")
            return nextWord;
        var strarr= new Array();
        var newWord = document.getElementById("id_new").firstChild.nodeValue;
        strarr=newWord.split(" ");
        return strarr[0];
    }
    function clearWord(){
        var answer = document.getElementById("id_answer");
        answer.value = '';
    }
    function clearTemplate(){
        document.getElementById("id_new").firstChild.nodeValue=document.getElementById("id_content").value;
        document.getElementById("id_right").firstChild.nodeValue = '';
        document.getElementById("id_wrong").firstChild.nodeValue = ' ';
    }
    function changTemplet(thisWord){
        var newWord = document.getElementById("id_new").firstChild.nodeValue;
        var rightWord = document.getElementById("id_right").firstChild.nodeValue;
        var wrongWord = document.getElementById("id_wrong").firstChild.nodeValue;
        var nextWord = getNextWord();
        if(thisWord == nextWord){
            document.getElementById("id_right").firstChild.nodeValue = rightWord + " " + thisWord;
            if(wrongWord == " "){
                document.getElementById("id_new").firstChild.nodeValue = newWord.substr(nextWord.length + 1);
            }else {
                document.getElementById("id_wrong").firstChild.nodeValue = " ";
            }
        } else {
            if(wrongWord == " "){
                document.getElementById("id_wrong").firstChild.nodeValue = " " + nextWord + " ";
                document.getElementById("id_new").firstChild.nodeValue = newWord.substr(nextWord.length + 1);
            }
        }
    }
</script>
<object id="typeOCX" type="application/x-itst-activex" 
        clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
        width ='0' height='0'
        event_OnStenoPress="onStenoPressKey">
</object>