<?php ?>
<script type="text/javascript">
function disableSelection(target){
if (typeof target.onselectstart!="undefined") //IE route
	target.onselectstart=function(){ return false;}
else if (typeof target.style.MozUserSelect!="undefined") //Firefox route
	target.style.MozUserSelect="none";
else 
	target.onmousedown=function(){ return false;}
	target.style.cursor = "default";
}
var orderItemAId = 0;
var orderItemBId = 0;
var orderColumnId = 0;
function chooseOrderItem(columnId,id) { 
	if(orderItemAId == 0) {
		orderColumnId = columnId;
		orderItemAId = id;	
	} else {
		orderItemBId = id;
	}
	if(orderItemAId == orderItemBId) {
		orderItemAId = 0;	
		orderItemBId = 0;
	} else {
		chooseedReOrder();
	}
}
function chooseedReOrder() {
	if(orderItemAId != 0 && orderItemBId != 0 && orderItemAId != orderItemBId) {
		var u_orderItemAId = orderItemAId;
		var u_orderItemBId = orderItemBId;	
		var items = $("a[name=orderItem]");
		orderItemAId = 0;
		orderItemBId = 0;	
		var aObj = $("a[orderid="+u_orderItemAId+"]").filter(":eq(0)");
		var bObj = $("a[orderid="+u_orderItemBId+"]").filter(":eq(0)"); 
		var changeAChildToParent = false;
		var changeAParentToChild= false;

		if(aObj.attr("orderpid") != bObj.attr("orderpid")) {
			var isFind = false;
			var aParent = aObj;
			for(var i=0; i<3; i++) { // max 3 
				if($("a[orderid="+aParent.attr("orderpid")+"]").length > 0) {
					aParent = $("a[orderid="+aParent.attr("orderpid")+"]").filter(":eq(0)"); 
					if(aParent.attr("orderpid") == bObj.attr("orderpid")) {
						isFind = true;
						break;
					}
				}
			}
			if(!isFind) {
				var bParent = bObj;
				for(var i=0; i<3; i++) { // max 3 
					if($("a[orderid="+bParent.attr("orderpid")+"]").length > 0) {
						bParent = $("a[orderid="+bParent.attr("orderpid")+"]").filter(":eq(0)"); 
						if(bParent.attr("orderpid") == aObj.attr("orderpid")) {
							isFind = true;
							break;
						}
					}
				}	
				if(!isFind) { 
					return; 
				} else {
					changeAParentToChild = true;
				}
			} else {
				aObj = aParent;	
				changeAChildToParent = true;
			}
		}
		
		var orderItemANum = parseInt(aObj.attr("ordernum"));
		var orderItemBNum = parseInt(bObj.attr("ordernum"));	
		var isUp = orderItemANum > orderItemBNum ? true : false;
		var ids = "";
		var orders = "";
		if(changeAChildToParent || changeAParentToChild) {
			ids = u_orderItemAId;	
			orders = 999999999;
		}
		for(var i=0; i<items.length; i++) {
			if(items.filter(":eq("+i+")").attr("orderpid") != bObj.attr("orderpid")) {
				continue;
			}
			var num = parseInt(items.filter(":eq("+i+")").attr("ordernum"));
			var orderid = items.filter(":eq("+i+")").attr("orderid"); 
			if(changeAChildToParent || changeAParentToChild) {
				if(num >= orderItemBNum) {
					if(ids !== "") { ids += ","; } ids += orderid;	
					if(orders !== "") { orders += ","; } orders += num;	
				}
			} else {
				if((isUp && num >= orderItemBNum && num <= orderItemANum) || (!isUp && num <= orderItemBNum && num >= orderItemANum)) {
					if(ids !== "") { ids += ","; } ids += orderid;	
					if(orders !== "") { orders += ","; } orders += num;	
				}
			}
		}
		if(ids != "" && orders != "") {
			$.ajax({  
				type:"GET",
				dataType:"json",
				url:homeUrl+"/tDAjax/reorderRows/ids/"+ids+"/orders/"+orders+"/orderAId/"+u_orderItemAId+"/orderBId/"+u_orderItemBId
				+"/orderColumnId/"+orderColumnId+"/changeAChildToParent/"+(changeAChildToParent ? 1 : 0)+"/changeAParentToChild/"+(changeAParentToChild ? 1 : 0),  
				data:"",
        			success:function(data){  
					if(data.result == "success") {
						refashGridView();
					} else {
						alert("<?php echo TDLanguage::$tip_msg_operate_fail; ?> "+data.msg);
					}
        			}  
    			});				
		}
	}
}
//window.document.onmouseup = chooseOrderCancel;
</script>
<span id="tempSpan" style="display: none;"></span>
<script type="text/javascript"> 
//var somediv=document.getElementById("tempSpan"); disableSelection(somediv); 
</script>