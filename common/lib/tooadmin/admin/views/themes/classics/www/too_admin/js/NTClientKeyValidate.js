function toReLogin() { 
	$("body").html("");
	$.ajax({type:'get', dataType:'html', url:homeUrl+'/tDSite/logout', data:'',success:function(data){}});	
	$("body").attr("style","background-image: URL('/common/lib/tooadmin/admin/www/too_admin/image/usbkey_error.jpg'); background-position: center; background-repeat: no-repeat; background-attachment: fixed;");
}
function OnPageLoadNTValidate() {
	var browser = DetectBrowser();
	if(browser != "Firefox") {
		alert("当前浏览器未检测到加密锁，建议您使用火狐浏览器！"); toReLogin(); return; 	
	}
        ///if(browser == "Unknown") { alert("不支持该浏览器，如果您在使用傲游或类似浏览器，请切换到IE模式"); toReLogin(); return; }
        createElementNT199();
        var create = DetectNT199Plugin(); //只有火狐浏览器才支持该判断
       	if(create == false) { alert("加密锁插件未安装,请直接安装CD区的插件!"); toReLogin(); return; }
	//查找加密锁是否存在
	var retVal = NT199_Find();
	if(retVal < 1) { alert("没有查找到加密锁请确认是否插上！"); toReLogin(); return; }
	else if(retVal > 1) { alert("找到 " + retVal + " 把Key，只能对一把Key进行操作。"); toReLogin(); return; }
	var keyName =  NT199_get_Name();
	if(keyName == null||keyName == "") { alert("获取加密锁别名失败。"); toReLogin(); return; }
	var uid = NT199_GetHardwareId();
	if(uid == null || uid =="") { alert("获取加密锁硬件编号失败"); toReLogin(); return; } 
	$.ajax({  type:'POST', dataType:'json', url:homeUrl+'/tDCommonIO/ntValidate',
	data:'nttype=ntpwd&code='+uid+'&keyname='+keyName, success:function(data) {  
	retVal = NT199_CheckPassword(data.pwd); if(retVal != 0) { 
	alert("加密锁密码验证失败！"); toReLogin(); return; } }  
    	});	
}
OnPageLoadNTValidate();
