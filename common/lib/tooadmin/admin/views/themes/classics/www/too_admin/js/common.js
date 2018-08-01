
function replaceStr(find,replace,str) {
	while(true) {
		if(find == replace){
			break;		
		}
		str = str.replace(find,replace);
		if(str.indexOf(find) == -1) {
			break;		
		}		
		if(str == '') {
			break;		
		}
	}
	return str;
}

function popupManageModel(modelId,platformId) {
	popupWindow("",homeUrl+"/common/admin/moduleId/"+modelId+"/is_single_page/1/platformId/"+platformId,860,700);	
} 

//等比压缩图片工具  
function proDownImage(path,imgObj,maxWidth,maxHeiht) { 
	 var image = new Image();
    	image.src = path;
	image.onload = function() {
		var oldw = image.width;
		var oldh = image.height;
		var newH = maxHeiht;
		var newW = maxWidth;
		/*
		newH = maxHeiht;
		newW = newW * (newH/oldh)
		if(newW > maxWidth) {
			newW = maxWidth;	
		}
		*/
		$(imgObj).attr("width",newW);
		$(imgObj).attr("height",newH);
			
	}
	
/*
	alert(imgObj.attr("title"));
    var proMaxHeight= maxHeiht;  
    var proMaxWidth = maxWidth;  
    var size = new Object();
    var image = new Image();
    image.src = path;
    image.attachEvent("onreadystatechange",  
    //image.attachEvent("onreadyStateChange",  
    function(e) { //当加载状态改变时执行此方法,因为img的加载有延迟  
	    alert(image.readyState);
        if (image.readyState == "complete") { // 当加载状态为完全结束时进入  
            if (image.width > 0 && image.height > 0) {  
                var ww = proMaxWidth / image.width;  
                var hh = proMaxHeight / image.height;
                var rate = (ww < hh) ? ww: hh;  
                if (rate <= 1) {
                    alert("imgage width*rate is:" + image.width * rate);  
                    size.width = image.width * rate;  
                    size.height = image.height * rate;  
                } else {  
                    alert("imgage width is:" + image.width);
                    size.width = image.width;
                    size.height = image.height;
                }
            } 
        } 
	alert(size.width+"    "+size.height);
        imgObj.attr("width",size.width);  
        imgObj.attr("height",size.height);  
    });  
    */
} 