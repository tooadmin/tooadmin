//grid导出exl
function outputAddress(grid, strMethod) {
    try {
        var xls = new ActiveXObject("Excel.Application");
    }
    catch (e) {
        alert("要打印该表，您必须安装Excel电子表格软件，同时浏览器须使用“ActiveX 控件”，您的浏览器须允许执行控件。 请点击【帮助】了解浏览器设置方法！");
        return;
    }
    xls.visible = true; //设置excel为可见   
    var xlBook = xls.Workbooks.Add;
    var xlSheet = xlBook.Worksheets(1);

    var cm = grid.getColumnModel();
    var colCount = cm.getColumnCount();
    var temp_obj = [];
    //只下载没有隐藏的列(isHidden()为true表示隐藏,其他都为显示)   
    //临时数组,存放所有当前显示列的下标
    for (i = 0; i < colCount; i++) {
        if (cm.isHidden(i) != true || (strMethod != null && (strMethod.indexOf("#" + cm.getColumnById(i).dataIndex.toString() + "#") > -1))) {
            temp_obj.push(i);
        }
    }
    for (i = 1; i <= temp_obj.length; i++) {
        //显示列的列标题
        xlSheet.Cells(1, i).Value = (cm.getColumnHeader(temp_obj[i - 1])).toString().replace('<center>', "").replace('</center>', "").replace('<middle>', "").replace('</middle>', "").replace('<br>', "");
        if (cm.getColumnHeader(temp_obj[i - 1]).indexOf('<center>') > -1) {
            xlSheet.Cells(1, i).HorizontalAlignment = -4108;
        }
    }
    var store = grid.getStore();
    var recordCount = store.getCount();
    var view = grid.getView();
    for (i = 1; i <= recordCount; i++) {
        for (j = 1; j <= temp_obj.length; j++) {
            //EXCEL数据从第二行开始,故row = i + 1;   
            xlSheet.Cells(i + 1, j).Value = view.getCell(i - 1, temp_obj[j - 1]).innerText;
        }
    }
    xlSheet.Columns.AutoFit;
    xls.ActiveWindow.Zoom = 100
    xls.UserControl = true; //很重要,不能省略,不然会出问题 意思是excel交由用户控制   
    xls = null;
    xlBook = null;
    xlSheet = null;
}

function replaceHtml(replacedStr, repStr, endStr) {
    var replacedStrF = "";
    var replacedStrB = "";
    var repStrIndex = replacedStr.indexOf(repStr);
    while (repStrIndex != -1) {
        replacedStrF = replacedStr.substring(0, repStrIndex);
        replacedStrB = replacedStr.substring(repStrIndex, replacedStr.length);
        replacedStrB = replacedStrB.substring(replacedStrB.indexOf(endStr) + 1, replacedStrB.length);
        replacedStr = replacedStrF + replacedStrB;
        repStrIndex = replacedStr.indexOf(repStr);
    }
    return replacedStr;
}

function replaceHtml1(replacedStr, repStr, endStr) {
    var replacedStrF = "";
    var replacedStrB = "";
    var repStrIndex = replacedStr.indexOf(repStr);
    while (repStrIndex != -1) {
        replacedStrF = replacedStr.substring(0, repStrIndex);
        replacedStrB = replacedStr.substring(repStrIndex, replacedStr.length);
        replacedStrB = replacedStrB.substring(replacedStrB.indexOf(endStr) + 1, replacedStrB.length);
        replacedStr = replacedStrF + replacedStrB;
        repStrIndex = replacedStr.indexOf(repStr);
    }
    return replacedStr;
}

//elTalbeOut 这个为导出内容的外层表格，主要是设置border之类的样式，elDiv则是整个导出的html部分
function htmlToExcel(GridPanel_ID, ColumnWidth, Border) {
    try {
        if (navigator.userAgent.indexOf("MSIE") > 0) {
            ToExcel_IE(GridPanel_ID, ColumnWidth, Border);
        }
        else {
            ToExcel_FF(GridPanel_ID, Border);
        }
        ColumnWidth = null;
    } catch (e) {
	    alert(e.message);
        ///alert(e.description);
    }
}

/*
function exportTbToExcel() {
var arrStr = new Array(); //可以通过数组的形式设置列宽，如果不设置传null可以
arrStr.push("1,120");
arrStr.push("2,120");
arrStr.push("4,120");
htmlToExcel("common-grid", arrStr, 1);//这里的1代表的是border=1的意思。
}
*/

function ToExcel_IE(GridPanel_ID, ColumnWidth, Border) {

    //获取需要导出的内容
    var elDiv = document.getElementById(GridPanel_ID);
    //设置导出前的数据，为导出后返回格式而设置
    var elDivStrBak = elDiv.innerHTML;

    //过滤elDiv内容
    var elDivStr = elDiv.innerHTML;
    elDivStr = replaceHtml(elDivStr, "<A", ">");
    elDivStr = replaceHtml(elDivStr, "</A", ">");
    elDivStr = replaceHtml(elDivStr, "<IMG", ">");
    //设置table的border=1，这样到excel中就有表格线 ps:感谢双面提醒
    if (Border != null) {
        elDivStr = elDivStr.replace(/<TABLE/g, "<TABLE border=" + Border);
    }
    elDiv.innerHTML = elDivStr;
    elDivStr = "";

    var oRangeRef = document.body.createTextRange();
    oRangeRef.moveToElementText(elDiv);
    oRangeRef.execCommand("Copy");

    //返回格式变换以前的内容   
    elDiv.innerHTML = elDivStrBak;
    //内容数据可能很大，所以赋空   
    elDivStrBak = "";
    elDiv = null;

    var oXL = new ActiveXObject("Excel.Application")
    var oWB = oXL.Workbooks.Add;
    var oSheet = oWB.ActiveSheet;
    oSheet.Paste();
    //oSheet.Cells.NumberFormatLocal = "@";
    oSheet.Columns("D:D").Select
    oSheet.Columns.AutoFit;
    ColumnWidth = (ColumnWidth == null ? '' : ColumnWidth);
    for (i = 0; i < ColumnWidth.length; i++) {
        oSheet.Columns(parseInt(ColumnWidth[i].split(",")[0])).ColumnWidth = parseInt(ColumnWidth[i].split(",")[1]);
    }
    //oXL.Selection.ColumnWidth = 20
    oXL.ActiveWindow.Zoom = 100
    oXL.Visible = true;
    oXL.UserControl = true; //很重要,不能省略,不然会出问题 意思是excel交由用户控制   
    oSheet = null;
    oWB = null;
    appExcel = null;
    oXL = null;
}

var ToExcel_FF = (function() {
    var uri = 'data:application/vnd.ms-excel;base64,',
      template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
        base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) },
        format = function(s, c) {
            return s.replace(/{(\w+)}/g,
            function(m, p) { return c[p]; })
        }
    return function(GridPanel_ID, Border) {
        //var elDiv = document.getElementById(GridPanel_ID);

        var elDiv = $("#"+GridPanel_ID).find("table");
        var elDivStr = elDiv.html();

        //过滤elDiv内容
        //var elDivStr = elDiv.innerHTML;
        elDivStr = replaceHtml(elDivStr, "<a", ">");
        elDivStr = replaceHtml(elDivStr, "</a", ">");
        elDivStr = replaceHtml(elDivStr, "<img", ">");
        //设置table的border=1，这样到excel中就有表格线 ps:感谢双面提醒
        if (Border != null) {
            elDivStr = elDivStr.replace(/<table/g, "<table border=" + Border);
        }
        var ctx = { worksheet: '' || 'Worksheet', table: elDivStr }
        window.location.href = uri + base64(format(template, ctx));
        //返回格式变换以前的内容
        elDivStr = "";
        elDiv = null;
    }
})();


