var needReIniTab = false;//是否重新初始化tab
var initTabObj = null;//重新初始化tab的a对象
var checkRes = null; 
var reSetAgain = null;
function reSetPopupWindowSize(customeWidth,customeHeight,isFromCur) {
	return false;//咱不做重置
	//clearTimeout(checkRes);	
	/*
	if(document.getElementsByTagName('iframe').length > 0) {
	var topMenuHeight = 120;
	var footerMenuHeight = 20;
	var Sys = {};
	var ua = navigator.userAgent.toLowerCase();
	var s;
	(s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
	(s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
	(s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
	(s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
	(s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
	if (Sys.ie) {
		topMenuHeight = 120;
		footerMenuHeight = 20;	
		if(ua.match(/wow/)) {//所属IE的360浏览器
			footerMenuHeight = 25;	
		}
	} else if (Sys.firefox) {
		topMenuHeight = 90;
		footerMenuHeight = 20;
	} else if (Sys.chrome) {
		topMenuHeight = 60;
		footerMenuHeight = 0;
		if(ua.match(/wow/)) {//所属IE的360浏览器,测试时发现会到这里
			topMenuHeight = 120;
			footerMenuHeight = 25;	
		}
	}
	var scH = 0;
	var scW = 0;
	try{
	if(document.getElementsByTagName('iframe')[0].contentWindow.document.body != null && document.getElementsByTagName('iframe')[0].contentWindow.document.body.scrollHeight != undefined) {
		scH = document.getElementsByTagName('iframe')[0].contentWindow.document.body.scrollHeight;	
	}
	if(document.getElementsByTagName('iframe')[0].contentWindow.document.body != null && document.getElementsByTagName('iframe')[0].contentWindow.document.body.scrollWidth != undefined) {
		scW = document.getElementsByTagName('iframe')[0].contentWindow.document.body.scrollWidth + 30;	
	}
	if(document.getElementsByTagName('iframe')[0].contentWindow.document.getElementById("popwindowWidth") != null) {
		scW = parseInt(document.getElementsByTagName('iframe')[0].contentWindow.document.getElementById("popwindowWidth").value); 
	}
	}catch(e){ scW=860; scH=700;}

	var minH = 700;
	var minW = 860;
	if(scH <= minH) {
		scH = minH;
	}
	if(scW <= minW) {
		scW = minW;
	}

	var width = scW;
	var height = scH;
	if(customeWidth != undefined && customeWidth > 0) {
		width = customeWidth;	
	}
	if(customeHeight != undefined && customeHeight > 0) {
		height = customeHeight;	
	}	
	var borderWidth = 30;
	var borderHeight = 80;
	var MaxWidth = window.screen.availWidth;
	var MaxHeight = window.screen.availHeight - topMenuHeight - footerMenuHeight;

	var margLeft = 0;
	var margTop  = 0;
	if(width + borderWidth >= MaxWidth) {
		width = MaxWidth - borderWidth;
		margLeft = MaxWidth * 0.5 * (-1);	
	} else {
		margLeft = (width + borderWidth) * 0.5 * (-1);		
	}
	if(height+borderHeight >= MaxHeight) {
		height = MaxHeight - borderHeight;
		margTop = MaxHeight * 0.5 * (-1);	
	} else {
		margTop = (height + borderHeight) * 0.5 * (-1);		
	}

	document.getElementsByTagName('iframe')[0].style.height = height+"px";
	document.getElementsByTagName('iframe')[0].style.width = width+"px";
	$('#myModal').css("margin-left",margLeft);
	$('#myModal').css("margin-top",margTop);

	//var ifhtml = document.getElementsByTagName('iframe')[0].contentWindow.document;//.body.innerHTML;
	//if(ifhtml != undefined) { }
	if(isFromCur == undefined) {
		checkRes = setTimeout("reSetPopupWindowSize(undefined,undefined,true)",popupPageRedrawWaitMillisecond);
	}

	}
	*/
}

function clearAllTimeout() {
clearTimeout(checkRes);
clearTimeout(reSetAgain);	
}
var afterCloseModelRunJs = "";
function popupWindow(title,url,customeWidth,customeHeight,isFixedSize,p_afterCloseModelRunJs) {
	clearAllTimeout();
	//customeWidth = undefined; customeHeight = undefined; 
	if(p_afterCloseModelRunJs != undefined) { afterCloseModelRunJs = p_afterCloseModelRunJs; } else { afterCloseModelRunJs = ""; }
	if(window.parent != null) {
		window.parent.clearAllTimeout();
	}
	if(window.parent.parent != null) {
		window.parent.parent.clearAllTimeout();
	}
	if(window.parent.parent.parent != null) {
		window.parent.parent.parent.clearAllTimeout();
	}

	var topMenuHeight = 120;
	var footerMenuHeight = 20;
	var Sys = {};
	var ua = navigator.userAgent.toLowerCase();
	var s;
	(s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
	(s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
	(s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
	(s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
	(s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
	if (Sys.ie) {
		topMenuHeight = 120;
		footerMenuHeight = 20;	
		if(ua.match(/wow/)) {//所属IE的360浏览器
			footerMenuHeight = 25;	
		}
	} else if (Sys.firefox) {
		topMenuHeight = 90;
		footerMenuHeight = 20;
	} else if (Sys.chrome) {
		topMenuHeight = 60;
		footerMenuHeight = 0;
	}
	//if (Sys.opera) document.write('Opera: ' + Sys.opera);
	//if (Sys.safari) document.write('Safari: ' + Sys.safari);

	var width = 820;
	var height = 600;
	
	if(customeWidth != undefined && customeWidth != 0) { width = customeWidth; }
	if(customeHeight != undefined && customeHeight != 0) { height = customeHeight; }

	var borderWidth = 30;
	var borderHeight = 80;

	var MaxWidth = window.screen.availWidth;
	var MaxHeight = window.screen.availHeight - topMenuHeight - footerMenuHeight;

	var margLeft = 0;
	var margTop  = 0;
	if(width + borderWidth >= MaxWidth) {
		width = MaxWidth - borderWidth;
		margLeft = width * 0.5 * (-1);	
	} else {
		margLeft = (width + borderWidth) * 0.5 * (-1);		
	}
	if(height+borderHeight >= MaxHeight) {
		height = MaxHeight - borderHeight;
		margTop = MaxHeight * 0.5 * (-1);	
	} else {
		margTop = (height + borderHeight) * 0.5 * (-1);		
	}
	if(customeWidth != undefined && customeHeight != undefined && customeWidth != 0 && customeHeight != 0) { 
		width = parseInt(customeWidth);
		height = parseInt(customeHeight);
		margLeft = (width + borderWidth) * 0.5 * (-1);
		margTop = (height + borderHeight) * 0.5 * (-1);	
	} else if(true) { //暂时不使用自动缩放,默认为最大
		//1920*1080 => 1920 * 1040
		//1280*1024 => 1280*976
		//1280*768 => 1280*720
		var MaxWidthBase = window.screen.availWidth;
		var MaxHeightBase = window.screen.availHeight;
		width = MaxWidthBase - 100;  //1800
		height = MaxHeightBase - 220; //780	 
		margLeft = (width + borderWidth) * 0.5 * (-1);
		margTop = (height + borderHeight) * 0.5 * (-1);	
	}

	//height = 100; width = 200; alert(width+"  "+height+"  margLeft="+margLeft+"  margTop="+margTop);

	$("#modal_title").html(title);	
	$('#myModal').modal('show');
	$("#model_content").html('<iframe id="fram" frameborder="0"  src="'+url
	+'" style="width:'+width+'px;height:'+height+'px;overflow:auto;"></iframe><script>document.getElementById("fram").onload=function(){reSetPopupWindowSize(); }</script>');

	//$('#myModal').css("width",width+20);//IE 7 必须加这个
	//$('#myModal').css("height",height+80); //IE 7 必须加这个
	
	$('#myModal').css("margin-left",margLeft);
	$('#myModal').css("margin-top",margTop);



	//弹出第二个框时使用,(一般情况第二个框为搜索数据页面) 通过 beforeModalHide()还原
	if($("#myModal").find("iframe").attr("src") != parent.$("#myModal").find("iframe").attr("src")) {
		parent.$("#myModal").attr("md_marginLeft",parent.$("#myModal").css("marginLeft"));
		parent.$("#myModal").attr("md_marginTop",parent.$("#myModal").css("marginTop"));
		parent.$("#myModal").find("iframe").attr("md_width",parent.$("#myModal").find("iframe").css("width"));
		parent.$("#myModal").find("iframe").attr("md_height",parent.$("#myModal").find("iframe").css("height"));

		parent.$("#myModal").find(".modal-header").css("display","none");	
		parent.$("#myModal").find(".modal-body_sun").css("padding","0px;");	

		parent.$("#myModal").css("marginLeft",((MaxWidth + borderWidth)*0.5*(-1))+"px");
		parent.$("#myModal").css("marginTop",((MaxHeight+(borderHeight*0.1))*0.5*(-1))+"px");
		parent.$("#myModal").find("iframe").css("width",(MaxWidth)+"px");
		parent.$("#myModal").find("iframe").css("height",(MaxHeight)+"px");
	}
	if(isFixedSize == undefined || isFixedSize == false) {
		//checkRes = setTimeout("reSetPopupWindowSize("+customeWidth+","+customeHeight+")",popupPageRedrawWaitMillisecond);
		//reSetAgain = setTimeout("reSetPopupWindowSize("+customeWidth+","+customeHeight+")",parseInt(popupPageRedrawWaitMillisecond)*2);	
	}
}
//关闭窗户
function  closeWindow() {
	$("#myModal").modal("hide");	
	afterModalHide();
}

//关闭弹出窗口之前的操作
function beforeModalHide(){
	//如果弹出了两个窗口，关闭第二个窗口时还原第一个窗口的样式
	if($("#myModal").find("iframe").attr("src") != parent.$("#myModal").find("iframe").attr("src")) {
		parent.$("#myModal").find(".modal-header").css("display","block");	
		parent.$("#myModal").find(".modal-body_sun").css("padding","15px;");	
		
		parent.$("#myModal").css("marginLeft",parent.$("#myModal").attr("md_marginLeft"));
		parent.$("#myModal").css("marginTop",parent.$("#myModal").attr("md_marginTop"));
		parent.$("#myModal").find("iframe").css("width",parent.$("#myModal").find("iframe").attr("md_width"));
		parent.$("#myModal").find("iframe").css("height",parent.$("#myModal").find("iframe").attr("md_height"));

		parent.$("#myModal").removeAttr("md_marginLeft");
		parent.$("#myModal").removeAttr("md_marginTop");
		parent.$("#myModal").find("iframe").removeAttr("md_width");
		parent.$("#myModal").find("iframe").removeAttr("md_height");
	}
}
//关闭弹出窗口之后的操作
function afterModalHide(){
	$("#modal_operate").html("");
	if(afterCloseModelRunJs != "") { setTimeout(afterCloseModelRunJs,200); }
}

//刷新gridview
function refashGridView(gridviewId) {
	//jQuery('#common-grid').yiiGridView('update');
	//$.fn.yiiGridView.update(id);

	if(gridviewId == '' || gridviewId == undefined || $("#"+gridviewId).size() == 0) {
		var grid = $(".grid-view");
		for(var i=0; i<grid.length; i++) { $.fn.yiiGridView.update(grid.filter(":eq("+i+")").attr("id")); }	
	} else {
		 $.fn.yiiGridView.update(gridviewId);
	}
}
//刷新listView
function refashListView(id) {
	$.fn.yiiListView.update(id);	
}
