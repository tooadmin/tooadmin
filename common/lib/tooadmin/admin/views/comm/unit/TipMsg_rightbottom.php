<?php ?>
<style type="text/css"> 
.msgTipDiv { position: absolute; right: 0px; bottom: 0px; height: 0px; width: 220px; border: 1px solid #CCCCCC; 
background-color: #eeeeee; padding: 1px; overflow: hidden; display: none; font-size: 12px; z-index: 10; } 
.msgTipDiv p { padding: 6px; } 
.msgTipDiv h1, #detail h1 { font-size: 14px; height: 25px; line-height: 25px; background-color: #0066CC; 
color: #FFFFFF; padding: 0px 3px 0px 3px; filter: Alpha(Opacity=100); } 
.msgTipDiv h1 a, #detail h1 a { float: right; text-decoration: none; color: #FFFFFF; } 
</style> 
<script type="text/javascript"> 
function voiceTipMsg() {
	if(document.all){
            var wav = document.getElementByIdx_x("tipMsg_embed");
            if (wav != null) {
                //wav.Stop();
                wav.Play();
            }
	}else {
		tipMsg_player.play();
	}
}

var msgTipHandle; 
function showMsgTip(msgCode,num) { 
	//voiceTipMsg();
	var obj=document.all?document.all["msgTipDivId"+num] : document.getElementById("msgTipDivId"+num); 
	var html = "<h1><a href='javascript:void(0)' onclick='closeMsgTip("+num+")'>×</a>消息</h1><p>"+msgCode+"</p>";
	if(obj == null) {
		var msgDivTip = document.createElement("div"); 
		msgDivTip.id="msgTipDivId"+num; 
		msgDivTip.setAttribute("class","msgTipDiv");
		msgDivTip.innerHTML= html; 
		msgDivTip.style.height='0px'; 
		msgDivTip.style.bottom='0px'; 
		msgDivTip.style.position='fixed'; 
		document.body.appendChild(msgDivTip); 	
		obj = document.getElementById("msgTipDivId"+num); 	
	} else {
		obj.innerHTML= html; 
		obj.setAttribute("class","msgTipDiv");
		obj.style.height='0px'; 
		obj.style.bottom='0px'; 
		obj.style.position='fixed'; 
	}
	obj.style.display="block"; 
	msgTipHandle = setInterval("msgTipUpOrDown('up',"+num+")",20); 
	/*	
	if (parseInt(obj.style.height)==0) { 
		obj.style.display="block"; 
		msgTipHandle = setInterval("msgTipUpOrDown('up')",20); 
	} else { 
		msgTipHandle = setInterval("msgTipUpOrDown('down')",20) 
	} 
	*/
} 

function closeMsgTip(num) {
	msgTipHandle = setInterval("msgTipUpOrDown('down',"+num+")",20) 	
}

function msgTipUpOrDown(str,num) { 
	var obj=document.all?document.all["msgTipDivId"+num] : document.getElementById("msgTipDivId"+num); 
	if(str=="up") { 
		if (parseInt(obj.style.height)>200) 
			clearInterval(msgTipHandle); 
		else 
			obj.style.height=(parseInt(obj.style.height)+8).toString()+"px"; 
	} 
	if(str=="down") { 
		if (parseInt(obj.style.height)<8) { 
			clearInterval(msgTipHandle); 
			obj.style.display="none"; 
		} else 
			obj.style.height=(parseInt(obj.style.height)-8).toString()+"px"; 
	} 
} 
</script> 
<audio controls="controls" id="tipMsg_player" style="display:none;">
<source src="<?php echo Yii::app()->baseUrl."/common/lib/tooadmin/admin/www/too_admin/voice/msg.wav"; ?>" >
</audio>
<!--
<embed id="tipMsg_embed" src="<?php echo Yii::app()->baseUrl."/common/lib/tooadmin/admin/www/too_admin/voice/msg.wav"; ?>" loop="false" width="0px" height="0px" /></embed>
-->