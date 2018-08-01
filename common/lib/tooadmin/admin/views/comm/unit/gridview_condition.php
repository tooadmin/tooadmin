<?php
?>
<script>
	function loadSearchColumns(tableCollectionId,isChirld,obj,belongStr,markMuduleIdStr) {
		$.ajax({  
			type:'POST',
			dataType:'html',
			url:'<?php echo TDPathUrl::createUrl('tDAjax/conditionLoadTableColumns') ?>',  
			data:'tableCollectionId='+tableCollectionId+'&belongStr='+belongStr+'&markMuduleIdStr='+markMuduleIdStr,
        		success:function(data){  
				if(isChirld) {
					$(obj).parent().parent().children(".choInput").append(data);		
				} else {
					$("#fieldset"+markMuduleIdStr).html(data);
				}
        		}  
    		}); 
	}
	
	function loadSearchCon(obj,markMuduleIdStr) {
		if($(obj).val() == ''){
			$(obj).parent().parent().children(".choCondition").html("");
			$(obj).parent().parent().children(".choInput").html("");
			return;
		}
		var baseLinkUrl = $("#common-grid"+markMuduleIdStr).children(".keys").attr("title");
		$.ajax({  
			type:'POST',
			dataType:'json',
			url:'<?php echo TDPathUrl::createUrl('tDAjax/conditionLoadInputType') ?>',  
			data:"tableColumnId="+obj.options[obj.selectedIndex].value+"&baseLinkUrl="+baseLinkUrl,
        		success:function(data){  
				$(obj).parent().parent().children(".choCondition").html(data.typesSelect);
				$(obj).parent().parent().children(".choInput").html(data.inputHtml);
				var foreignTableId = obj.options[obj.selectedIndex].getAttribute("foreigntableid");
				var belongstr = obj.options[obj.selectedIndex].getAttribute("belongstr");
				if(foreignTableId != '') 
					loadSearchColumns(foreignTableId,true,obj,belongstr,markMuduleIdStr);	
        		}  
    		});
	}
	
	function addRow(obj) {
		var table = $(obj).parent().parent().parent().parent();
		var row = $("<tr></tr>");
		var td_labName = $("<td class='labName'></td>");
		var td_choColumns = $("<td class='choColumns'></td>");
		var td_choCondition = $("<td class='choCondition'></td>");
		var td_choInput = $("<td class='choInput'></td>");
		var td_opeButton = $("<td class='opeButton'></td>");
		var td_opeDelete = $("<td class='opeDelete' style='display:none;'></td>");
		var firstTr = table.find('tbody>tr:first'); 
		td_labName.append(firstTr.children(".labName").html());	
		td_labName.children("label").html("<?php echo TDLanguage::$choose_condition_column; ?>");
		td_choColumns.append(firstTr.children(".choColumns").html());	
		td_opeButton.append(firstTr.children(".opeButton").html());	
		td_opeDelete.append(firstTr.children(".opeDelete").html());	
		row.append(td_labName);
		row.append(td_choColumns);
		row.append(td_choCondition);
		row.append(td_choInput);
		row.append(td_opeButton);
		row.append(td_opeDelete);
		table.append(row);
		$(obj).parent().parent().children(".opeDelete").attr("style","display:block;");
		$(obj).parent().parent().children(".opeButton").attr("style","display:none;");
	}

	function combinationCond(obj,markMuduleIdStr) {
		var table = $(obj).parent().parent().parent().parent();	
		var trs = table.children().children("tr");
		var num = parseInt($("#combinationMaxNum"+markMuduleIdStr).val());
		var unSetNum = 0;
		var setnum = false;
		for(var i=trs.size()-1; i>=0; i--) {
			if(i == trs.size()-1) {
				if(trs.filter(":eq("+i+")").children("td.labName").children("input.choNum").val() == "") {
					setnum = true;
				} else {
					unSetNum = parseInt(trs.filter(":eq("+i+")").children("td.labName").children("input.choNum").val());	
					if(unSetNum < num) {
						num = num + 1;
					}
				}
			} 
			if(setnum) {
				if(trs.filter(":eq("+i+")").children("td.labName").children("input.choNum").val() == "") {
					trs.filter(":eq("+i+")").children("td.labName").children("input.choNum").val((num + 1));
					trs.filter(":eq("+i+")").children("td.labName").children("label").html("<?php 
					echo TDLanguage::$choose_condition_combination ?>c"+(num+1)+"b");
				}
			} else {
			 	if(trs.filter(":eq("+i+")").children("td.labName").children("input.choNum").val() == unSetNum) {
					trs.filter(":eq("+i+")").children("td.labName").children("input.choNum").val("");
					trs.filter(":eq("+i+")").children("td.labName").children("label").html("<?php echo TDLanguage::$choose_condition_column; ?>");
				}
			}
		}
		if(setnum) {
			$("#combinationMaxNum"+markMuduleIdStr).val((num + 1));
		} else {
			$("#combinationMaxNum"+markMuduleIdStr).val((num - 1));
		}
	}

	function combinationFormulaSearch(markMuduleIdStr) {
		var formula = $.trim($("#advSearch_combinationFormula"+markMuduleIdStr).val());
		if(formula != '') {
			$("#advSearch_useCombinationFormula"+markMuduleIdStr).val("1");
			$("#form"+markMuduleIdStr).submit();	
			$("#advSearch_useCombinationFormula"+markMuduleIdStr).val("0");
		}
	}
	
	function tchangeRadioEvent(markMuduleIdStr) {
		if(document.getElementById("condition_splite_page_0_"+markMuduleIdStr).checked) {
			$("#condition_splite_page_"+markMuduleIdStr).val("0");
			$("#condition_splite_page_0_"+markMuduleIdStr).parent().attr("class","checked");
			$("#condition_splite_page_1_"+markMuduleIdStr).parent().attr("class","");
		} else  if(document.getElementById("condition_splite_page_1_"+markMuduleIdStr).checked) {
			$("#condition_splite_page_"+markMuduleIdStr).val("1");
			$("#condition_splite_page_1_"+markMuduleIdStr).parent().attr("class","checked");
			$("#condition_splite_page_0_"+markMuduleIdStr).parent().attr("class","");
		}
	}

	$("div .search_checker").live("click",function() {
		if($(this).children("span").attr("class") == "undefined" || $(this).children("span").attr("class") == "") {
			$(this).children("span").attr("class","checked");		 
			$(this).children("span").children("input").attr("checked","checked");
		} else {
			$(this).children("span").attr("class","");		 
			$(this).children("span").children("input").removeAttr("checked");
		}
		return false;
	});
</script>
