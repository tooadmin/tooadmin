<?php

class KindEditor {

	private $is_include_css_js = false; //是否include编辑器 true 是，false 否
	private $kindeditor_url = "";
	
	function __construct(){
		$this->kindeditor_url = Yii::app()->baseUrl."/common/plugins/items/KindEditor/";
	}

	function __destruct(){
	}

	/**
	 * include编辑器所需的css与js文件
	 *
	 */
	public function get_include_css_js(){
			
		$css_js = '<link rel="stylesheet" href="' . $this->kindeditor_url . 'themes/default/default.css"/>
					<script charset="utf-8" src="' . $this->kindeditor_url . 'kindeditor.js"></script>
					<script charset="utf-8" src="' . $this->kindeditor_url . 'lang/zh_CN.js"></script>';
		return $css_js;
	}

	/**
	 * 单个创建编辑器实例
	 *
	 * @param unknown_type $textarea_name 为需创建编辑器的textarea的name
	 * @param unknown_type $width 编辑器宽
	 * @param unknown_type $height 编辑器高
	 * @param unknown_type $mode 编辑器模式 默认是全部功能； [simple] 简单模式，功能比较少 
	 * @param unknown_type $after_change_fun 当编辑器内容有改变时，需执行的js函数，函数会传入一个编辑器对象的参数，用于控制编辑器操作，例如：js函数 function fun(){}， $after_change_fun 参数值为'fun'
	 * 说明：1、$mode 可自定义编辑器需要的功能， 编辑器所有功能项为：
	 *         items 配置编辑器的工具栏，其中"-"表示换行，"|"表示分隔符。
	 *         ['source', '|', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste','plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright','justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript','superscript', '|', 'selectall', '-','title', 'fontname', 'fontsize', '|', 'textcolor', 'bgcolor', 'bold','italic', 'underline', 'strikethrough', 'removeformat', '|', 'image','flash', 'media', 'advtable', 'hr', 'emoticons', 'link', 'unlink', '|', 'about']
	 *      2、如果不设宽或高，则编辑器默认为textarea对应的宽或高
	 */
	public function create_editor($textarea_name,$width=null,$height=null,$mode=null,$after_change_fun=null){
		
		$editor_code = '';
		$width_height = '';
		if(!$this->is_include_css_js){
			$css_js = $this->get_include_css_js();
			$editor_code .= $css_js;
			$this->is_include_css_js = true;
		}
		//设置宽
		$width = intval($width);
		if(!empty($width)){
			$width_height .= 'width : ' . $width . ',';
		}
		//设置高
		$height = intval($height);
		if(!empty($height)){
			$width_height .= 'height : ' . $height . ',';
		}
		//设置模式
		$editor_items = '';
		if(!empty($mode)){
			switch ($mode) {
				case 'simple':
					$editor_items = "items : [
						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', 'link'],";
					break;
				//在这里可以定义其他所需的模式
			}
		}
		//设置编辑器内容改变后需执行的js函数
		$after_change_fun_code = '';
		if(!empty($after_change_fun)){
			$after_change_fun_code = "afterChange : function(){ " . trim($after_change_fun) . "(this); },";
		}
		//变量 editor_obj_".$textarea_name." 用于对编辑器的控制，例如同步编辑器到textarea:editor_obj_*.sync(); 获取编辑器的字符数：editor_obj_*.count('text');更多函数查看：http://www.kindsoft.net/docs/editor.html
		$editor_code .= "<script>
		    var editor_obj_".$textarea_name.";
			KindEditor.ready(function(K) {
			editor_obj_".$textarea_name." = K.create('textarea[name=\"" . $textarea_name . "\"]', {				
				uploadJson : '" . $this->kindeditor_url . "phpExt/upload_json.php?textarea_name=".$textarea_name."',"
		. $width_height . $editor_items . $after_change_fun_code . 
		"allowFileManager : false,afterBlur: function(){this.sync();}
			});
		});
		</script>";		
		return $editor_code;
	}

	/**
	 * 批量创建编辑器实例
	 *
	 * @param unknown_type $textarea_name_arr  例如 array(array('name_1',$width,$height,$mode),array('name_2',$width,$height,$mode));
	 */
	public function batch_create_editor($textarea_name_arr){
		if(!is_array($textarea_name_arr)){
			return ;
		}
		$batch_editor_code = '';
		foreach ($textarea_name_arr as $textarea){
			$textarea_name = $textarea[0];
			if(isset($textarea[1])){
				$width = $textarea[1];
			}else{
				$width = null;
			}
			if(isset($textarea[2])){
				$height = $textarea[2];
			}else {
				$height = null;
			}
			if(isset($textarea[3])){
				$mode = $textarea[3];
			}else {
				$mode = null;
			}
			if(isset($textarea[4])){
				$after_change = $textarea[4];
			}else {
				$after_change = null;
			}
			$editor_code = $this->create_editor($textarea_name,$width,$height,$mode,$after_change);
			$batch_editor_code .= $editor_code;
		}
		return $batch_editor_code;
	}

}

/*
编辑器使用方法
$KindEditor_obj = new KindEditor();
//创建单个编辑器
$editor_code = $KindEditor_obj->create_editor('name_1',600,400,'simple');//编辑器宽600，高400
//创建多个编辑器
$batch_editor_code = $KindEditor_obj->batch_create_editor(array(
array('name_1',600,400),
array('name_2',null,null,'simple'),
));
*/
