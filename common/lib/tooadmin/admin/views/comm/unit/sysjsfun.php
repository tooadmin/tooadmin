<?php
echo '<script> '
. 'var homeUrl="' . Yii::app()->baseUrl . '";'
. 'var moduleManageId=' . TDStaticDefined::$moduleManageId . ';'
. 'var gridviewColumnsModuleId=' . TDStaticDefined::$gridviewColumnsModuleId . ';'
. 'var moduleCopyModuleId=' . TDStaticDefined::$moduleCopyModuleId . ';'
. 'var popupPageRedrawWaitMillisecond="' . Yii::app()->params->reset_layout_millisecond . '";'
. '</script>';//js 内部引用时调用 


$baseUrl = Yii::app()->baseUrl . '/common/lib/tooadmin/admin/views/themes/'.TDCommon::getThemeName().'/www/bootcss/';
$bimUrl = Yii::app()->baseUrl . '/common/lib/tooadmin/admin/views/themes/'.TDCommon::getThemeName().'/www/too_admin/';
$strapUrl = Yii::app()->baseUrl . '/common/lib/tooadmin/admin/views/themes/'.TDCommon::getThemeName().'/www/bootstrap/';

$bimPath  = './common/lib/tooadmin/admin/views/themes/'.TDCommon::getThemeName().'/www/too_admin/';
$jsFileBasePath = './common/lib/tooadmin/admin/views/themes/'.TDCommon::getThemeName().'/www/bootcss/';
$jsFielArray = array(
$jsFileBasePath."js/jquery-ui.min.js",
$jsFileBasePath."js/bootstrap-transition.js",
$jsFileBasePath."js/bootstrap-alert.js",
$jsFileBasePath."js/bootstrap-modal.js",
$jsFileBasePath."js/bootstrap-dropdown.js",
$jsFileBasePath."js/bootstrap-scrollspy.js",
$jsFileBasePath."js/bootstrap-tab.js",
$jsFileBasePath."js/bootstrap-tooltip.js",
$jsFileBasePath."js/bootstrap-popover.js",
$jsFileBasePath."js/bootstrap-button.js",
$jsFileBasePath."js/bootstrap-collapse.js",
$jsFileBasePath."js/bootstrap-carousel.js",
$jsFileBasePath."js/bootstrap-typeahead.js",
$jsFileBasePath."js/bootstrap-tour.js",
$jsFileBasePath."js/jquery.cookie.js",
$jsFileBasePath."js/fullcalendar.min.js",
$jsFileBasePath."js/jquery.dataTables.min.js",
$jsFileBasePath."js/excanvas.js",
$jsFileBasePath."js/jquery.flot.min.js",
$jsFileBasePath."js/jquery.flot.pie.min.js",
$jsFileBasePath."js/jquery.flot.stack.js",
$jsFileBasePath."js/jquery.flot.resize.min.js",
$jsFileBasePath."js/jquery.chosen.min.js",
$jsFileBasePath."js/jquery.colorbox.min.js",
$jsFileBasePath."js/jquery.cleditor.min.js",
$jsFileBasePath."js/jquery.noty.js",
$jsFileBasePath."js/jquery.elfinder.min.js",
$jsFileBasePath."js/jquery.raty.min.js",
$jsFileBasePath."js/jquery.iphone.toggle.js",
$jsFileBasePath."js/jquery.autogrow-textarea.js",
//"js/jquery.uploadify-3.1.min.js"
$jsFileBasePath."js/jquery.history.js",
$jsFileBasePath."js/charisma.js",
$jsFileBasePath."js/bootstrap-myself.js",
$jsFileBasePath."js/bootstrap.min.js",
"./common/plugins/items/codemirror/lib/codemirror.js",
"./common/plugins/items/codemirror/mode/css/css.js",
$bimPath."js/popup-window.js",
$bimPath."js/common.js",
$bimPath."js/excel.js",
$bimPath."js/jscolor/jscolor.js",
);
?>
<script src="<?php echo TDDataFiles::getCompressFile($jsFielArray,'sysjsfun_common.js'); ?>"></script>
<!-- NT加密锁的验证 start-->
<?php 
/////if (Yii::app()->params->is_use_key) {  <script src=" echo $bimUrl js/NTClientJavascript.js"></script>
///<script src=" echo $bimUrl js/NTClientKeyValidate.js"></script> }
?>
<!-- NT加密锁的验证 end-->
<script src="<?php echo $bimUrl ?>js/wdate/WdatePicker.js"></script> 

<script>

    $('#bs-css').attr('href', '<?php echo $baseUrl; ?>css/bootstrap-cerulean.css');
    var tabIndex = <?php if (isset($_GET['tabIndex'])) { echo $_GET['tabIndex']; } else { echo 1; }?>;

    $('.grid-view a.view,a.update').live('click', function () {
        //popupWindow($(this).attr("title") == undefined ? '<?php echo TDLanguage::$title_edit; ?>' : $(this).attr("title"), this.href);
        popupWindow($(this).attr("title") == undefined ? '&nbsp;&nbsp;&nbsp;' : $(this).attr("title"), this.href);
        return false;
    });
    $('a.popup').live('click', function () {
		var trname = $(this).parent().parent().attr("name");
		var bshref = this.href; 
		if(bshref.indexOf("tDCommon/edit") != -1 && trname != null && trname != undefined && trname.indexOf("treetr") != -1) {
			var treeAhref = $("#"+trname.replace(/treetr/g,'oca')).attr("href");
			var treeAhref1 = treeAhref.replace(/javascript:/g,"");
			var treeAhref2 = treeAhref1.substr(0,treeAhref1.indexOf(";"));
			bshref += '/<?php echo TDStaticDefined::$PARAM_AFTER_CLOSE_FORM_TREE_JS; ?>/'+encodeURIComponent(treeAhref2);
		}
        popupWindow($(this).attr("title") == undefined ? '<?php echo '&nbsp;&nbsp;&nbsp;'; ?>' : $(this).attr("title"),bshref,$(this).attr("pwidth"),$(this).attr("pheight"));
        return false;
    });
    $('.grid-view a.ajaxUrl').live('click', function () {
        $.ajax({
            type: 'GET', url: this.href, success: function (data) {
                refashGridView();
            }
        });
        return false;
    });
    function ajaxUrlAndReloadUrl(ajaxUrl, reloadUrl, confirmStr) {
        if (confirmStr != '') {
            if (!window.confirm(confirmStr)) {
                return;
            }
        }
        $.ajax({
            type: 'GET', url: ajaxUrl, success: function (data) {
                window.location.href = reloadUrl;
            }
        });
    }
    function reloadFormToModuleForm(url) {
        //parent.popupWindow("<?php echo TDLanguage::$title_edit; ?>",url);
        parent.refashGridView();
        $("#common-form").attr("action", url);
        postReloadCurrentForm();

    }

    function validateForGridviewTmpForm(data, gridviewId) {
        if (data.validateResult) {
            refashGridView(gridviewId);
        } else {
            for (var i = 0; i < data.datas.length; i++) {
                $("#" + data.datas[i].fieldID).css("border", "<?php echo TDCommonCss::$error_field_border_css ?>");
                $("#" + data.datas[i].fieldID + "_error").attr("data-original-title", data.datas[i].msg);
                $("#" + data.datas[i].fieldID + "_error").css("display", "block");
            }
            for (var i = 0; i < data.normal.length; i++) {
                $("#" + data.normal[i].fieldID).css("border", "");
                $("#" + data.normal[i].fieldID + "_error").css("display", "none");
            }
            if (data.otherErrors != '') {
                alert(data.otherErrors);
            }
        }
    }

    function validateSaveResultFromPopup(data, isClose, gridviewId,isRefreshGridView) {
        if (data.validateResult && isClose) {
			if(isRefreshGridView) {
            	parent.refashGridView(gridviewId);
			}
            alert("<?php echo TDLanguage::$tip_msg_save_success; ?>");
            window.close();
            parent.closeWindow();
			if(typeof parent.afterEditFormClosed == 'function') {
				parent.afterEditFormClosed();	
			}
        } else {
            for (var i = 0; i < data.datas.length; i++) {
                $("#" + data.datas[i].fieldID).css("border", "<?php echo TDCommonCss::$error_field_border_css ?>");
                $("#" + data.datas[i].fieldID + "_error").attr("data-original-title", data.datas[i].msg);
                $("#" + data.datas[i].fieldID + "_error").css("display", "block");
            }
            for (var i = 0; i < data.normal.length; i++) {
                $("#" + data.normal[i].fieldID).css("border", "");
                $("#" + data.normal[i].fieldID + "_error").css("display", "none");
            }
            if (data.otherErrors != '') {
                alert(data.otherErrors);
            }
        }
    }
    function validateSaveResultFromCurrent(data, ifAlertSuc) {
        if (data.validateResult) {
            if (ifAlertSuc == undefined || ifAlertSuc == true) {
                alert("<?php echo TDLanguage::$tip_msg_save_success; ?>");
                parent.window.location.href = replaceStr('id/', '', parent.window.location.href);
            }
        } else {
            for (var i = 0; i < data.datas.length; i++) {
                $("#" + data.datas[i].fieldID).css("border", "<?php echo TDCommonCss::$error_field_border_css ?>");
                $("#" + data.datas[i].fieldID + "_error").attr("data-original-title", data.datas[i].msg);
                $("#" + data.datas[i].fieldID + "_error").css("display", "block");
            }
            for (var i = 0; i < data.normal.length; i++) {
                $("#" + data.normal[i].fieldID).css("border", "");
                $("#" + data.normal[i].fieldID + "_error").css("display", "none");
            }
            if (data.otherErrors != '') {
                alert(data.otherErrors);
            }
        }
    }
    function postReloadCurrentForm() {
        $("form").attr("target", "");
        $("form").append('<input type="hidden" value="yes" name="postreload" >');
        $("form").submit();
    }

    var expandPageAlltree = false;
    function expandAllTree(p_expandPageAlltree) {
	expandPageAlltree = p_expandPageAlltree;  
       var plus = $(".icon-plus");
       for(var i=0; i<plus.size(); i++) {
           var aobj = plus.filter(":eq("+i+")").parent().parent().find('a');
           if(aobj != undefined &&
               aobj.attr("href") != undefined &&
               aobj.attr("href").indexOf("expandTableTreeData") !== -1) {
               setTimeout(aobj.attr("href"),200 * (i+1));
           }
       }
    }

    var isLoackExpand = false;
    function expandTableTreeData(moduleId, columnIds, columnValues, pkId, rdNum, apendParams) {
		if(isLoackExpand) {
			return false;
		}
		isLoackExpand = true;
        var aId = "oca" + rdNum;
        var belongInputId = "belongIds" + rdNum;
        if ($("#" + aId).parent().parent().parent().find("tr[name=treetr" + rdNum + "]").size() > 0) {
            var baseItems = $("#" + aId).parent().parent().parent().find("tr[name=treetr" + rdNum + "]");
            var needCheckArra = new Array();
            var needIndex = 0;
            for (bi = 0; bi < baseItems.size(); bi++) {
                var checkHtml = baseItems[bi].innerHTML;
                if (checkHtml.indexOf('class="icon-minus"') !== -1) {
                    if (checkHtml.split("expandTableTreeData").length > 1) {
                        var checkNum = checkHtml.split("expandTableTreeData")[1].split("void(0)")[0].split(",")[4];
                        checkNum = checkNum.replace(/'/g, "");
                        checkNum = checkNum.replace(/"/g, "");
                        needCheckArra[needIndex] = checkNum;
                        needIndex++;
                    }
                }
            }
            for (var ind = 0; ind < needCheckArra.length; ind++) {
				isLoackExpand = false;
                expandTableTreeData("", "", "", "", needCheckArra[ind], "");
            }
            $("#" + aId).parent().parent().parent().find("tr[name=treetr" + rdNum + "]").remove();
            $("#" + aId).html('<?php echo TDCommonCss::$tree_closeed_icon ?>');
			isLoackExpand = false;
            return;
        }
        if (moduleId == "" && columnIds == "" && columnValues == "" && pkId == "") {
			isLoackExpand = false;
            return;
        }
		$("#" + aId).html('<?php echo TDCommonCss::$tree_opened_icon ?>');
        var ajaxUrl = '<?php echo TDPathUrl::createUrl('tDAjax/expandTableTreeData'); ?>';
        $.ajax({
            type: 'GET', url: ajaxUrl
            , data: 'moduleId=' + moduleId + '&columnIds=' + columnIds + '&columnValues=' + columnValues + apendParams
            , dataType: 'html'
            , success: function (data) {
                var currentCid = $("#" + belongInputId).val();
                if (currentCid != "") {
                    currentCid += ",";
                }
                currentCid += pkId;
                var extr = $(data).find("table>tbody");
                extr.children("tr").attr("name", "treetr" + rdNum);
                var firstNameIndex = 0;
                if ($("#" + aId).parent().parent().children("td").filter(":eq(0)").find("input[type=checkbox]").size() > 0) {
                    firstNameIndex = 1;
                }
                var marginCount = $("#" + aId).parent().parent().children("td").filter(":eq(" + firstNameIndex + ")").find("span").size();
                var marginSpan = "<span style='margin-left:15px;'><i class=' icon-arrow-right'></i>&nbsp;</span>";
                var baseMarginSpan = "<span style='margin-left:15px;'><i class=' icon-arrow-right'></i>&nbsp;</span>";
                for (var i = 0; i < marginCount; i++) {
                    marginSpan = replaceStr("<i class=' icon-arrow-right'></i>", "", marginSpan);
                    marginSpan += baseMarginSpan;
                }
                for (var i = 0; i < extr.children("tr").size(); i++) {
                    var tdCB = extr.children("tr").filter(":eq(" + i + ")").children("td").filter(":eq(" + firstNameIndex + ")");
                    tdCB.find("input[expand=belongid]").val(currentCid);
                    var td = extr.children("tr").filter(":eq(" + i + ")").children("td").filter(":eq(" + firstNameIndex + ")");
                    td.html(marginSpan + td.html());
                }
                $("#" + aId).parent().parent().after(extr.html());
		isLoackExpand = false;
		if(expandPageAlltree) {
			expandAllTree(expandPageAlltree);
		}
            }
        });
    }

    function getChoooseedID(forMethodName, toolModuleId, forModuleId, forJsMethodTableId,markMuduleIdStr) {
        var idstr = "";
        var boxs = $("input[name='checkboxid"+markMuduleIdStr+"[]']");
        for (var i = 0; i < boxs.size(); i++) {
            if (boxs.filter(":eq(" + i + ")").attr("checked") == "checked") {
                if (idstr != "") {
                    idstr += "<?php echo TDSearch::$expand_tree_column_key_column ?>";
                }
                var belogToColumnId = 0;
                var chooseTD = '';
                if (boxs.filter(":eq(" + i + ")").parent().filter("td").size() == 1) {
                    chooseTD = boxs.filter(":eq(" + i + ")").parent().filter("td");
                } else if (boxs.filter(":eq(" + i + ")").parent().parent().parent().filter("td").size() == 1) {
                    chooseTD = boxs.filter(":eq(" + i + ")").parent().parent().parent().filter("td");
                }
                if (chooseTD != '') {
                    belogToColumnId = chooseTD.parent().find("td").filter(":eq(1)").find("input[expand=belongid]").filter(":eq(0)").val();
                    if (belogToColumnId == '') {
                        belogToColumnId = 0;
                    }
                }
                idstr += belogToColumnId + "<?php echo TDSearch::$expand_tree_str_key_str; ?>" + boxs.filter(":eq(" + i + ")").val();
            }
        }
        if (idstr != "") {
            if (forMethodName == 'columnIdsForModule') {
                parent.closeWindow();
                parent.columnIdsForModule(toolModuleId, forModuleId, idstr);
            } else if (forMethodName == 'mysqlReloadTableData') {
                mysqlReloadTableData(forJsMethodTableId, idstr);
            }
        }
    }
    
	//多选
    function popupSearchChooseedMore(popupSearchColumnId,foreignFieldId,markModuleIdStr) {
        var idstr = "";
        var boxs = $("input[name='checkboxid"+markModuleIdStr+"[]']");
        for (var i = 0; i < boxs.size(); i++) {
            if (boxs.filter(":eq(" + i + ")").attr("checked") == "checked") {
                if (idstr != "") {
                    idstr += ",";
                }
                idstr += boxs.filter(":eq(" + i + ")").val();
            }
        }
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: homeUrl + '/tDAjax/getPopupDataMore',
            data: 'popupSearchColumnId=' + popupSearchColumnId + '&foreignIds=' + idstr,
            success: function (data) {
                parent.$("#" + foreignFieldId).val(data.foreignIds);
                var textFieldId = foreignFieldId + "_foreigntext";
                if (parent.$("#" + textFieldId).length > 0) {
                    if (checkHasHtmlTag(data.fieldText) || parent.document.getElementById(textFieldId).outerHTML.indexOf("<div") != -1) {
                        parent.document.getElementById(textFieldId).outerHTML = '<div id="' + textFieldId + '">' + data.fieldText + '</div>';
                    } else {
                        parent.$("#" + textFieldId).val(data.fieldText);
                    }
                }
                parent.$("#myModal").modal("hide");
            }
        });
    }

    //单选
    function popupSearchChooseed(popupSearchColumnId, foreignFieldId,pkId,needReload) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: homeUrl + '/tDAjax/getPopupData' + pkId,
            data: 'popupSearchColumnId=' + popupSearchColumnId,
            success: function (data) {
				parent.$("#" + foreignFieldId).val(data.foreignId);
                var textFieldId = foreignFieldId + "_foreigntext";
                if (parent.$("#" + textFieldId).length > 0) {
					//foreignkey
                    if (checkHasHtmlTag(data.fieldText)) {
                    	parent.document.getElementById(textFieldId).outerHTML = '<div id="' + textFieldId + '">' + data.fieldText + '</div>';
                        //parent.$("#"+textFieldId).html(data.fieldText);
                   	} else {
                       parent.$("#" + textFieldId).val(data.fieldText);
                   }
               } else {
                  //selectdb
                  if (parent.$("#" + foreignFieldId).val() != data.foreignId) {
                   	parent.$("#" + foreignFieldId).append("<option value='" + data.foreignId + "'>" + data.fieldText + "</option>", data.foreignId);
                    parent.$("#" + foreignFieldId).val(data.foreignId);
                    parent.parent.document.getElementById('fram').contentWindow.postReloadCurrentForm();
                  }
               }
               parent.$("#myModal").modal("hide");
				if(needReload) {
					parent.postReloadCurrentForm();
				}	
            }
        });
    }

 	function controlPopupChooseed(pkId,expFun) {
		parent.$("#myModal").modal("hide");
		setTimeout("parent."+expFun+"('"+pkId+"')",200);		
    }

	function controlPopupChooseedMore(expFun,markModuleIdStr) {
		var idstr = "";
        var boxs = $("input[name='checkboxid"+markModuleIdStr+"[]']");
        for (var i = 0; i < boxs.size(); i++) {
            if (boxs.filter(":eq(" + i + ")").attr("checked") == "checked") {
                if (idstr != "") {
                    idstr += ",";
                }
                idstr += boxs.filter(":eq(" + i + ")").val();
            }
        }
		parent.$("#myModal").modal("hide");
		setTimeout("parent."+expFun+"('"+idstr+"')",200);		
    }

    function columnIdsForModule(toolModuleId, moduleId, idstr) {
        var ajaxUrl = '<?php echo TDPathUrl::createUrl('tDModule/columnsForModule'); ?>';
        $.ajax({
            type: 'POST', url: ajaxUrl
            , data: 'toolModuleId=' + toolModuleId + '&moduleId=' + moduleId + '&columnIds=' + idstr
            , dataType: 'html'
            , success: function (data) {
                refashGridView();
            }
        });
    }

    function commonModuleColumns(toolModuleId, forModuleId) {
        popupWindow("<?php echo TDLanguage::$choose_column ?>", homeUrl + "/<?php echo 'tDModule/moduleColumns/forModuleId/' ?>" + forModuleId + "/toolModuleId/" + toolModuleId);
    }

    function popupSearch(title, idStr, tableColumnId, fieldIdStr, fieldIdValue, operateKey, operateType, primaryKey, expandparam) {
        popupWindow(title, homeUrl + '/tDCommon/popupSearch/' + idStr + '/' + tableColumnId + '/' + fieldIdStr + '/' + fieldIdValue + '/' + operateKey + '/' + operateType + '/<?php
                echo TDStaticDefined::$popupSearchFormPrimaryKey ?>/' + primaryKey + expandparam);
    }

	function controlPupupChoose(title,moduleId,expFun,one_or_more,paramStr) {
		popupWindow(title,homeUrl+"/tDCommon/controlPopup/<?php echo TDStaticDefined::$OPERATE_TYPE_KEY.'/'.TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE; ?>/<?php 
		echo TDStaticDefined::$popupControlModuleId ?>/"+moduleId+"/<?php echo TDStaticDefined::$popupControlExpandFun ?>/"+expFun+"/<?php 
		echo TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE_TYPE ?>/"+one_or_more+"/<?php echo TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE_PARAM ?>/"+paramStr);	
	}
	function controlPopupFormEdit(title,moduleId,pkId,expFun,paramStr) {
		popupWindow(title,homeUrl+"/tDCommon/edit/moduleId/"+moduleId+"/id/"+pkId+"/<?php echo TDStaticDefined::$OPERATE_TYPE_KEY.'/'.TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_EDIT; ?>/<?php 
		echo TDStaticDefined::$popupControlExpandFun ?>/"+expFun+"/<?php echo TDStaticDefined::$OPERATE_TYPE_POPUP_CONTROL_CHOOSE_PARAM ?>/"+paramStr);
	}

    function popupSearchForUnique(title, idStr, tableColumnId, fieldIdStr, fieldIdValue, operateKey, operateType, primaryKey, expandparam, uniqueColumnIds, uniqueColumnFieldIds, uniqueColumnDefaultValues, uniqueColumnLabels) {
        var uniqueData = '';
        if (uniqueColumnIds != '') {
            uniqueColumnFieldIds = uniqueColumnFieldIds.split("---");
            var tmpUniqueColumnDefaultValues = uniqueColumnDefaultValues.split("---");
            var uniqueColumnLabelsAr = uniqueColumnLabels.split("---");
            var newUniqueColumnDefaultValues = "";
            for (var i = 0; i < uniqueColumnFieldIds.length; i++) {
                if ($("#" + uniqueColumnFieldIds[i]).val() != undefined) {
                    if ($("#" + uniqueColumnFieldIds[i]).val() == '') {
                        alert(uniqueColumnLabelsAr[i] + "<?php echo TDLanguage::$common_not_allow_empty; ?>");
                        return;
                    }
                }
            }
            for (var i = 0; i < uniqueColumnFieldIds.length; i++) {
                if (newUniqueColumnDefaultValues != "") {
                    newUniqueColumnDefaultValues += "---";
                }
                if ($("#" + uniqueColumnFieldIds[i]).val() != undefined) {
                    newUniqueColumnDefaultValues += $("#" + uniqueColumnFieldIds[i]).val();
                    if ($("#" + uniqueColumnFieldIds[i]).prop('outerHTML').indexOf("select") != -1) {
                        $("#" + uniqueColumnFieldIds[i]).prop("outerHTML", "<input type='hidden' value='" + $("#" + uniqueColumnFieldIds[i]).val() + "' id='"
                            + uniqueColumnFieldIds[i] + "' name='" + $("#" + uniqueColumnFieldIds[i]).attr("name") + "'><input type='text' value='"
                            + $("#" + uniqueColumnFieldIds[i]).find("option:selected").text() + "' disabled='disabled'>");
                    } else {
                        $("#" + uniqueColumnFieldIds[i]).attr("readonly", "readonly");
                    }
                } else {
                    newUniqueColumnDefaultValues += tmpUniqueColumnDefaultValues[i]
                }
            }
            uniqueData = '/<?php echo TDStaticDefined::$popupSearchUniqueColumnIdsStr ?>/' + uniqueColumnIds + '/<?php echo TDStaticDefined::$popupSearchUniqueColumnIdsValue ?>/' + newUniqueColumnDefaultValues;
        }
        popupSearch(title, idStr, tableColumnId, fieldIdStr, fieldIdValue, operateKey, operateType, primaryKey, expandparam + uniqueData);
    }

    function checkHasHtmlTag(chekStr) {
        var reg = /<[^>]+>/g;
        return reg.test(chekStr);
    }

    function AfterActionTmpFormTip(tipMsg, isRefresh) {
        if (tipMsg != '') {
            alert(tipMsg);
        }
        if (isRefresh) {
            location.href = location.href;
        }
    }

    function switch_theme(theme_name) {
        //alert(theme_name); cerulean
       // $('#bs-css').attr('href',' echo $baseUrl; /css/bootstrap-'+theme_name+'.css');
    }

    function loadingStart() {
        $('#loadingModal').modal('show');
    }
    function loadingFinish() {
        $('#loadingModal').modal('hide');
    }

    function commonOperate(apendUrlStr, isUseConfirm, confirmStr) {
        if (!isUseConfirm || (isUseConfirm && window.confirm(confirmStr))) {
            loadingStart();
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: homeUrl + '/tDAjax/commonOperate/' + apendUrlStr,
                data: '',
                success: function (data) {
                    loadingFinish();
                    if (data.result == "success") {
                        alert("<?php echo TDLanguage::$tip_msg_operate_ok; ?>");
                        if (parent.document.getElementById('fram')) { //when delete file
                            parent.document.getElementById('fram').contentWindow.postReloadCurrentForm();
                        }
                    } else {
                        alert("<?php echo TDLanguage::$tip_msg_operate_fail; ?> " + data.msg);
                    }
                }
            });
        }
    }

    function checkboxChooseUnChooseAll(name, isChoose) {
        $("input[name='" + name + "']").attr("checked", isChoose);
        for (var i = 0; i < $("input[name='" + name + "']").size(); i++) {
            $("input[name='" + name + "']:eq(" + i + ")").parent().attr("class", isChoose ? "checked" : "");
        }
    }
    function deleteChooseedAllRow(name, tableId) {
        var choooseedIds = "";
        for (var i = 0; i < $("input[name='" + name + "']").size(); i++) {
            if ($("input[name='" + name + "']:eq(" + i + ")").attr("checked")) {
                if (choooseedIds != "") {
                    choooseedIds += ",";
                }
                choooseedIds += $("input[name='" + name + "']:eq(" + i + ")").val();
            }
        }
        if (choooseedIds == "") {
            alert("<?php echo TDLanguage::$Operate_tip_chooseed_empty; ?>");
            return;
        }
        if (window.confirm("<?php echo TDLanguage::$Operate_tip_delete_chooseed; ?>")) {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: homeUrl + '/tDAjax/commonOperate/<?php echo TDOperate::$PARAM_OPERATE_TYPE ?>/<?php
                    echo TDOperate::$TYPE_DELETE_CHOOSEED_ROWS ?>/<?php echo TDOperate::$PARAM_TABLE_ID ?>/' + tableId
                + '/<?php echo TDOperate::$PARAM_CHOOSEED_IDS ?>/' + choooseedIds,
                data: '',
                success: function (data) {
                    if (data.result == "success") {
                        alert("<?php echo TDLanguage::$tip_msg_operate_ok; ?>");
                        refashGridView();
                    } else {
                        alert("<?php echo TDLanguage::$tip_msg_operate_fail; ?> " + data.msg);
                    }
                }
            });
        }
    }
    function refreshTableStructure(tableId) {
        commonOperate('<?php echo TDOperate::$PARAM_OPERATE_TYPE . '/' . TDOperate::$TYPE_REFRESH_TABLE_STRUCTURE . '/' . TDOperate::$PARAM_TABLE_ID ?>/' + tableId, false, '');
    }
    function websitePublish(websiteId, isAll) {
        commonOperate('<?php echo TDOperate::$PARAM_OPERATE_TYPE . '/' . TDOperate::$TYPE_WEBSITE_PUBLISH . '/' .
                TDOperate::$PARAM_WEBSITE_ID ?>/' + websiteId + '<?php echo "/" . TDOperate::$PARAM_PUBLISH_ALL; ?>/' + isAll, false, '');
        refashGridView();
    }
    function refreshAllTablesStructure() {
        commonOperate('<?php echo TDOperate::$PARAM_OPERATE_TYPE . '/' . TDOperate::$TYPE_REFRESH_ALL_TABLES_STRUCTURE ?>', true, "<?php
            echo TDLanguage::$to_tip_refreshAllTablesStructure ?>");
    }

    function to_form_admin(mdid) {
        commonModuleColumns(<?php echo TDStaticDefined::$editColumnsModuleId  ?>, mdid);
    }

    function to_gridview_refresh() {
        refashGridView();
    }
    function to_gridview_admin(mdid) {
        commonModuleColumns(gridviewColumnsModuleId, mdid);
    }
    function to_gridview_set(mdid) {
        popupWindow('<?php echo TDLanguage::$title_edit; ?>', homeUrl + '/tDCommon/edit/moduleId/' + moduleManageId + '/id/' + mdid);
    }

    function too_exportSysSQL() {
        $.ajax({
            type: 'POST',
            dataType: 'text',
            url: homeUrl + '/tDUnitAction/exportSysTable',
            success: function (data) {
                window.open(data);
            }
        });
    }

    function exportTbToExcel(markMuduleIdStr) {
        var queryUrl = $("#common-grid" + markMuduleIdStr).children(".keys").attr("title");
        var params = "condition_expert_excel=1";
        if (queryUrl.indexOf("?") == -1) {
            queryUrl += "?" + params;
        } else {
            if (queryUrl.indexOf("&", queryUrl.length - 1) == -1) {
                queryUrl += "&" + params;
            } else {
                queryUrl += params;
            }
        }
        window.open(queryUrl);
    }

	function exportTbToExcelByCondition(markMuduleIdStr) {
		$("#condition_splite_page_"+markMuduleIdStr).val("0");
		var params = $("#form"+markMuduleIdStr).serialize();
 		var queryUrl = $("#common-grid" + markMuduleIdStr).children(".keys").attr("title");
        params = params == "" ? "" : params+"&";
        params += "condition_expert_excel=1";
        if (queryUrl.indexOf("?") == -1) {
            queryUrl += "?";
        } else if (queryUrl.indexOf("inner?") != -1) {
			queryUrl = queryUrl.substring(0,parseInt(queryUrl.indexOf("?"))+1);
		} else if (queryUrl.indexOf("inner&") != -1) {
			queryUrl = queryUrl.substring(0,parseInt(queryUrl.indexOf("inner&"))+6);
        }
        queryUrl += params;
        window.open(queryUrl);
		$("#condition_splite_page_"+markMuduleIdStr).val("1");
        $("#condition_expert_excel"+markMuduleIdStr).val("0");
	}

	function setFieldIdHtml(id,html) {
        $("#" + id).html(html);
	}

    function formLoadModuleFormModule(divid, moduleFormModuleId, moduleFormRowId, ntableModuleId, moduleReadOnly) {
        if ($("#" + divid).html() != "") {
            return;
        }
        var qurl = homeUrl + '/tDCommon/admin/moduleId/' + ntableModuleId + '/mnInd/0/topmnInd/0/<?php echo TDStaticDefined::$pageLayoutType . '/' . TDStaticDefined::$pageLayoutType_inner; ?>';
        qurl += '/<?php echo TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID ?>/' + moduleFormModuleId + '/<?php echo TDStaticDefined::$PARAM_MODULE_ROW_PKID ?>/' + moduleFormRowId;
        qurl += '/<?php echo TDStaticDefined::$PARAM_MODULE_READONLY ?>/' + moduleReadOnly;
        $.ajax({
            type: "get", url: qurl, data: "", dataType: "html", success: function (data) {
                $("#" + divid).html(data);
            }
        });
    }

	function formLoadModuleFormCustomPage(divid,moduleFormModuleId,moduleFormRowId,appendUrlParmas) {
        if ($("#" + divid).html() != "") {
            return;
        }
        var qurl = homeUrl + '/tDCommon/formModuleCustome/<?php echo TDStaticDefined::$pageLayoutType . '/' . TDStaticDefined::$pageLayoutType_single; ?>';
        qurl += '/<?php echo TDStaticDefined::$PARAM_MODULE_FORM_MODULE_ID ?>/' + moduleFormModuleId + '/<?php echo TDStaticDefined::$PARAM_MODULE_ROW_PKID ?>/' + moduleFormRowId+appendUrlParmas;
        $.ajax({
            type: "get", url: qurl, data: "", dataType: "html", success: function (data) {
                $("#" + divid).html(data);
            }
        });
    }

    function loadMenuItemUrl(divid, url) {
        if ($("#" + divid).html() != "") {
            return;
        }
        $.ajax({
            type: "get", url: url, data: "", dataType: "html", success: function (data) {
                $("#" + divid).html(data);
            }
        });
    }

    function gridviewColumnsMerge(gridviewId, colindexStrs) {
        var colindexs = colindexStrs.split(",");
        for (var i = 0; i < colindexs.length; i++) {
            var colindex = parseInt(colindexs[i]);
            var lastrowText = "";
            var spanCount = 1;
            var rowIndex = 0;
            var lastSpanCount = 1;
            $("#" + gridviewId + " table tbody tr").each(function () {
                var curText = $(this).find("td:eq(" + (colindex - i) + ")").text();
                if (rowIndex == 0) {
                    curText = $(this).find("td:eq(" + colindex + ")").text();
                }
                if (lastrowText != "" && lastrowText == curText) {
                    $(this).find("td:eq(" + (colindex - i) + ")").remove();
                    spanCount++;
                } else {
                    $(this).parent().find("tr:eq(" + (rowIndex - spanCount) + ")").find("td:eq(" + colindex + ")").attr("rowspan", spanCount);
                    spanCount = 1;
                    lastrowText = curText;
                }
                lastSpanCount = spanCount;

                rowIndex++;
            });
            if (lastSpanCount > 1) {
                $("#" + gridviewId + " table tbody").find("tr:eq(" + (rowIndex - lastSpanCount) + ")").find("td:eq(" + colindex + ")").attr("rowspan", lastSpanCount);
            }
        }
    }

    //在gridview中直接编辑表单
    var gridviewEdit_tmptrIndex = 1;
    function gridviewEdit_edit(gridviewModuleId, gridviewId, pkid, editAction) {
        var tmpTrId = "tmptrid" + gridviewEdit_tmptrIndex;
        gridviewEdit_tmptrIndex++;
        $("#" + gridviewId + "_add").attr("style", "display:none;");
        $("#" + gridviewId).find("[editbt='tmpformEditBt_" + gridviewModuleId + "'] img").attr("style", "width:0px;");
        var table = $("#" + gridviewId + " table tbody");
        var head = $("#" + gridviewId + " table thead");
        var row = $("<tr id='" + tmpTrId + "'></tr>");
        var isAdd = pkid == 0 ? true : false;
        var editrowLastHtml = "<table style='display:none;' tmplasthtml='editrowLastHtml'><tr></tr></table>";
        if (!isAdd) {
            var editBts = $("#" + gridviewId).find("[editbt='tmpformEditBt_" + gridviewModuleId + "']");
            for (var bti = 0; bti < editBts.size(); bti++) {
                if (editBts.eq(bti).attr("href").indexOf("," + pkid + ",") != -1) {
                    var editRow = editBts.eq(bti).parent().parent();
                    editrowLastHtml = "<table style='display:none;' tmplasthtml='editrowLastHtml'><tr>" + editRow.html() + "</tr></table>";
                    editRow.html("");
                    editRow.attr("id", tmpTrId);
                    row = editRow;
                }
            }
        }
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: "moduleId=" + gridviewModuleId + "&pkId=" + pkid,
            url: homeUrl + '/tDAjax/gridviewEditAdd',
            success: function (data) {
                var columnSize = head.find("tr:eq(0)").find("th").size();
                var hidformField = "";
                var fieldIdStr = "";
                for (var ri = 0; ri < columnSize; ri++) {
                    var trid = head.find("tr:eq(0)").find("th:eq(" + ri + ")").attr("columnid");
                    var htmlcode = "";
                    for (var i = 0; i < data.length; i++) {
                        if (data[i].columnId == trid) {
                            htmlcode = $("<td>" + data[i].tmpForEdit + "</td>");
                            hidformField += data[i].tmpForHid;
                            fieldIdStr += fieldIdStr != "" ? "," : "";
                            fieldIdStr += data[i].fieldId;
                            break;
                        }
                    }
                    if (ri + 1 == columnSize) {
                        htmlcode = $("<td style='text-align:center;'><a href=\"javascript:gridviewEdit_save('" + tmpTrId + "','" + gridviewId + "');void(0);\"><span class='icon icon-color icon-save' title='<?php echo TDLanguage::$common_button_save;
                                ?>'></span></a>&nbsp;<a href=\"javascript:gridviewEdit_cancel('" + tmpTrId + "','" + gridviewId + "','" + gridviewModuleId + "');void(0);\"><span class='icon icon-color icon-cancel' title='<?php
                                echo TDLanguage::$common_button_cancel; ?>'></span></a>" + editrowLastHtml + "</td>");
                    }
                    row.append(htmlcode);
                }
                if (isAdd) {
                    table.append(row);
                }
                if (hidformField != "") {
                    hidformField += '<input type="hidden" value="<?php echo TDStaticDefined::$formModelName; ?>" name="modelName" />';
                    hidformField += '<input type="hidden" value="1" name="isGridviewTmpForm" />';
                    hidformField += '<input type="hidden" id="tmpFormfieldIdStr" value="' + fieldIdStr + '" />';
                    hidformField += '<iframe name="tmpFormValidateFrame" style="display:none;"></iframe>';
                    hidformField = '<form id="tmpform' + gridviewId + '" action="' + editAction + '" method="post" enctype="multipart/form-data" target="tmpFormValidateFrame">' + hidformField + '</form>';
                    $("#forGridviewTmpForm").attr("style", "height:0px;float:left;overflow:hidden;");
                    $("#forGridviewTmpForm").html(hidformField);
                }

            }
        });
    }
    function gridviewEdit_cancel(tmpTrId, gridviewId, gridviewModuleId) {
        $("#" + gridviewId + "_add").attr("style", "display:block;");
        $("#" + gridviewId).find("[editbt='tmpformEditBt_" + gridviewModuleId + "'] img").attr("style", "");
        var html = $("#" + tmpTrId).find("[tmplasthtml='editrowLastHtml']").find("tr").eq(0).html();
        if (html == "") {
            $("#" + tmpTrId).remove();
        } else {
            $("#" + tmpTrId).html(html);
        }
    }
    function gridviewEdit_save(tmpTrId, gridviewId) {
        var idstr = $("#tmpFormfieldIdStr").val().split(",");
        for (var i = 0; i < idstr.length; i++) {
            $("#" + idstr[i] + "_tmp").val($("#" + idstr[i]).val());
        }
        $("#tmpform" + gridviewId).submit();
    }
    function inputFieldChooseCancel(fieldColumnId, fieldTextId) {
        $("#" + fieldColumnId).attr("value", "");
		if (document.getElementById(fieldTextId).outerHTML.indexOf("<div") != -1) {
			document.getElementById(fieldTextId).outerHTML = '<div id="' + fieldTextId + '"></div>';
		} else {
        	$("#" + fieldTextId).val("");
		}
    }
    function refreshCash() {
        $.ajax({
            type: "get", url: homeUrl + "/tDAjax/clearnCashDB", data: "", dataType: "text", success: function (data) {
                if (data == "success") {
                    alert("<?php echo TDLanguage::$tip_msg_operate_cash_refresh_ok; ?>");
                } else {
                    alert("<?php echo TDLanguage::$tip_msg_operate_fail; ?>");
                }
            }
        });
    }
	function downLoadFile(strRemoteURL){
		try { 
		var strLocalURL = "c:\\";
		 var xmlHTTP=new ActiveXObject("Microsoft.XMLHTTP"); 
		 xmlHTTP.open("Get",strRemoteURL,false);
		 xmlHTTP.send();
		 var adodbStream=new ActiveXObject("ADODB.Stream");
		 adodbStream.Type=1; //1=adTypeBinary 
		 adodbStream.Open(); 
		 adodbStream.write(xmlHTTP.responseBody); 
		 adodbStream.SaveToFile(strLocalURL,2); 
		 adodbStream.Close(); 
		 adodbStream=null; 
		 xmlHTTP=null;
	 	} catch(e) {
			window.confirm("下载URL出错!");
		}
		//window.confirm("下载完成."); 
    }
	function canclePopupPageForCust() {
		window.close();
        parent.closeWindow();
	}
	function refClosePopupPageForCust() {
		window.close();
		parent.to_gridview_refresh();
        	parent.closeWindow();
	}
	function popupImgView(url,maxWidth,maxHeight) {
		popupWindow('&nbsp;&nbsp;&nbsp;',"/tDTool/imgView?imgUrl="+encodeURIComponent(url)+"&maxWidth="+maxWidth+"&maxHeight="+maxHeight,maxWidth,maxHeight);	
	}

</script>

<!-- only for gridview admin use , but in form has gridview so put code here -->
<script>
    $('.grid-view [timeajax=1]').live('change', function () {
        var tmpId = "fieldTmpId";
        $(this).attr("id", tmpId);
        $.ajax({
            type: 'GET', dataType: 'text', url: $(this).attr("urlstr") + this.value,
            success: function (data) {
                if (data == 'success') {
                    refashGridView();
                } else {
                    alert(data);
                    $("#" + tmpId).focus();
                    $("#" + tmpId).attr("id", "");
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                refashGridView();
            }
        });
        return false;
    });
</script>