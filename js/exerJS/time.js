var hh = 0;
var mm = 0;
var ss = 0;
var strTime = '';
var timer;
function startTime(){
	timer = window.setInterval(function(){
            changWordPS();
		strTime = "";
		if(++ss==60)
		{
			if(++mm==60)
			{
				hh++;
				mm=0;
			}
			ss=0;
		}
		strTime+=hh<10?"0"+hh:hh;
		strTime+=":";
		strTime+=mm<10?"0"+mm:mm;
		strTime+=":";
		strTime+=ss<10?"0"+ss:ss;
		document.getElementById('time').innerHTML = strTime;
	},1000);
};
function changWordPS(){
    var length = getWordLength();
    var timeAll = ss / 60 + mm + hh * 60;
    var wps = Math.round(length / timeAll);
    if(timeAll !== 0)
        document.getElementById("wordps").innerHTML = wps;
}
function getSeconds(){
    var seconds = ss + mm * 60 + hh * 3600;
    return seconds;
}
function reloadTime(){
	hh = 0;
	mm = 0;
	ss = 0;
	strTime = '00:00:00';
	document.getElementById('time').innerHTML = strTime;
	clearInterval(timer);
	startTime();
};
$(document).ready(function(){
    reloadTime();
});