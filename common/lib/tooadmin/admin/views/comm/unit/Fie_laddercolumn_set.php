<script>
	
function ladderColumnChoose(defaultTableId,fieldColumnIdStr,fieldColumnIdValue,fieldTextId,fieldTextValue,operateKey,operateType) {
	popupWindow("<?php echo TDLanguage::$choose_column ?>",homeUrl+'/tDModule/commonChooseColumns/defaultTableId/'
	+defaultTableId+'/'+fieldColumnIdStr+'/'+fieldColumnIdValue+'/'+fieldTextId+'/'+fieldTextValue+'/'+operateKey+'/'+operateType);		
}

function ladderColumnChooseed(fieldColumnId,fieldTextId,pkIdUrl,btnObj) {
	$.ajax({  
		type:'GET',
		dataType:'json',
		url:homeUrl+'/tDAjax/getPopupLadderColumn'+pkIdUrl,  
		data:'belongIds='+$(btnObj).parent().parent().find("td").filter(":eq(0)").find("input[expand=belongid]").filter(":eq(0)").val(),
        	success:function(data){  
			parent.$("#"+fieldColumnId).val(data.belongColumn); 
			parent.$("#"+fieldTextId).val(data.fieldText); 
			parent.$("#myModal").modal("hide");	
        	}  
    	}); 	
}	

function ladderColumnChooseCancel(fieldColumnId,fieldTextId) {
	//$("#"+fieldColumnId).val(""); when input display:none  the code unwork
	$("#"+fieldColumnId).attr("value",""); 
	$("#"+fieldTextId).val(""); 	
}

</script>