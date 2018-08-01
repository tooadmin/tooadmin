<?php ?>
<!-- 
规则
删除：删除的是整行,可以同时删除多行
拆分：向左边拆分出一个span的大小，可以同时拆分多行
合并：项左边合并，只能合并同一行的单元格，多行之间的合并暂不考虑
-->
<script>
	var beChooseedBorderColor = "border-color:#FF0000;";

	var panelRowIndex=0;
	function getCreateAPanelRowHtml() {
		var rowIndex = panelRowIndex++; 
		var html = '<div rowindex="'+rowIndex+'" class="row-fluid sortable ui-sortable">';
		html += getCreatePanelItemHtml(rowIndex,12);
		html += '</div>';
		return html;
	}
	var panleItemIndex = 0;
	function getCreatePanelItemHtml(rowIndex,spanNum) {
		return '<div rowindex="'+rowIndex+'" itemindex="'+(panleItemIndex++)+'" class="box span'+spanNum+'" onclick="chooseOrCancelPanel(this)"></div>';
	} 
	
	var chooseedPanelItems = new Array();
	function reCheckChooseedPanelItems() {
		var obj = $("div[onclick='chooseOrCancelPanel(this)']");
		chooseedPanelItems = new Array();
		for(var i=0; i<obj.length; i++) {
			var item = obj.filter(":eq("+i+")");
			var itemStyle = obj.filter(":eq("+i+")").attr("style"); 
			if(itemStyle != null && itemStyle.indexOf(beChooseedBorderColor) != -1) {
				chooseedPanelItems[chooseedPanelItems.length] = item; 
			}
		}
	}

	function chooseOrCancelPanel(divObj) {
		var style = $(divObj).attr("style");
		if(style == null) { style = ""; }
		if(style.indexOf(beChooseedBorderColor) != -1) {
			style = replaceStr(beChooseedBorderColor,"",style); 
		} else {
			style += beChooseedBorderColor;
		}
		$(divObj).attr("style",style);	
		reCheckChooseedPanelItems();	
	}

	function addPanelRow() {
		$("#customePage").append(getCreateAPanelRowHtml());	
	}

	function deletePanelRow() {
		if(chooseedPanelItems.length > 0 && window.confirm("<?php echo TDLanguage::$to_tip_confirm_delete_panel_row; ?>")) {
			for(var i=0; i<chooseedPanelItems.length; i++) {
				$(chooseedPanelItems[i]).parent().remove();	
			}
			reCheckChooseedPanelItems();
		}
	}

	function splitePanelRow(left0right1) {
		if(chooseedPanelItems.length > 0 && window.confirm("<?php echo TDLanguage::$to_tip_confirm_splite_panel_row; ?>")) {
			for(var i=0; i<chooseedPanelItems.length; i++) {	
				var spanNum = $(chooseedPanelItems[i]).attr("class");	
				spanNum = replaceStr("box span","",spanNum); 
				spanNum = parseInt(spanNum);
				if(spanNum > 1) {
					$(chooseedPanelItems[i]).attr("class","box span"+(spanNum-1));	
					if(left0right1 == 0) {
						$(chooseedPanelItems[i]).before(getCreatePanelItemHtml(chooseedPanelItems[i].attr("rowindex"),1));	
					} else {
						$(chooseedPanelItems[i]).after(getCreatePanelItemHtml(chooseedPanelItems[i].attr("rowindex"),1));	
					}
				}
			}
		}	
	}

	function mergerPanelItems() {
		if(chooseedPanelItems.length > 1 && window.confirm("<?php echo TDLanguage::$to_tip_confirm_merger_panel_items; ?>")) {
			var isRowMerger = false;
			var isColumnMerger = false;
			for(var i=0; i<chooseedPanelItems.length-1; i++) {
				var aIndex = chooseedPanelItems[i].attr("rowindex");		
				var bIndex = chooseedPanelItems[i+1].attr("rowindex");
				if(aIndex == bIndex) { 
					isRowMerger = true; 
				} else { 
					isColumnMerger = true; 
				}
			}
			if(!isRowMerger || (isRowMerger && isColumnMerger)) { 
				alert("<?php echo TDLanguage::$to_tip_illegal_merger; ?>");
				return;
			}
			for(var i=1; i<chooseedPanelItems.length; i++) {
				var spanNum = $(chooseedPanelItems[0]).attr("class");	
				spanNum = replaceStr("box span","",spanNum); 
				spanNum = parseInt(spanNum);	
				var spanNumI = $(chooseedPanelItems[i]).attr("class");	
				spanNumI = replaceStr("box span","",spanNumI); 
				spanNumI = parseInt(spanNumI);	
				$(chooseedPanelItems[0]).attr("class","box span"+(spanNum+spanNumI));	
				chooseedPanelItems[0].append(chooseedPanelItems[i].html());
				chooseedPanelItems[i].remove();
			}
			reCheckChooseedPanelItems();
		}
	}
</script>
<div class="btn-group pull-right" style="margin-left: 10px;padding-top:9px;">
	<a style="cursor:pointer;" class="dropdown-toggle" data-toggle="dropdown"><i class="icon icon-white icon-gear"></i></a>
	<ul class="dropdown-menu">
		<li><a href="javascript:addPanelRow();void(0);"><i class="icon icon-blue icon-plus" title="<?php 
		echo TDLanguage::$to_add_panel_row; ?>"></i><?php echo TDLanguage::$to_add_panel_row; ?></a></li>

		<li><a href="javascript:splitePanelRow(0);void(0);"><i class="icon icon-blue icon-unlink" title="<?php 
		echo TDLanguage::$to_split_panel_row_left; ?>"></i><?php echo TDLanguage::$to_split_panel_row_left; ?></a></li>

		<li><a href="javascript:splitePanelRow(1);void(0);"><i class="icon icon-blue icon-unlink" title="<?php 
		echo TDLanguage::$to_split_panel_row_right; ?>"></i><?php echo TDLanguage::$to_split_panel_row_right; ?></a></li>

		<li><a href="javascript:mergerPanelItems();void(0);"><i class="icon icon-blue icon-attachment" title="<?php 
		echo TDLanguage::$to_merger_panel_items; ?>"></i><?php echo TDLanguage::$to_merger_panel_items; ?></a></li>
				
		<li><a href="javascript:deletePanelRow();void(0);"><i class="icon icon-blue icon-close" title="<?php 
		echo TDLanguage::$to_delete_panel_items; ?>"></i><?php echo TDLanguage::$to_delete_panel_items; ?></a></li>
	</ul>
</div>

<div id="customePage"> </div>
