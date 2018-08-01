<script>
	function createStaticFile() {
		$.ajax({  
			type:'get',
			url:'<?php echo TDPathUrl::createUrl('tDAjax/reInitStaticData'); ?>', 
        		success:function(html){  
				alert(html);
        		}  
    		});  
	}

	function backupDataBase() {
		$("#tipMsgBackup").text("数据备份中....");
		$.ajax({  
			type:'get',
			dataType:'json',
			url:'<?php echo TDPathUrl::createUrl('tDAjax/backupDataBase') ?>',  
        		success:function(data){  
				if(data.result == 'success') {
					$("#tipMsgBackup").text("");
					window.open('/tooadmin/'+data.file);
					///alert('<?php //echo TDLanguage::$tip_msg_operate_ok; ?>');
				} else {
					alert('<?php echo TDLanguage::$tip_msg_operate_fail; ?>');
				}
        		} 
		});
	}
</script>
<table>
	<tr>
		<td>
			<button type="button" class="btn btn-primary" onclick="backupDataBase()"><?php echo TDLanguage::$main_topbar_backupDataBase; ?></button>
			<button type="button" class="btn btn-primary" onclick="createStaticFile()"><?php echo TDLanguage::$sys_operate_button_create_static_file; ?></button>
			<span id="tipMsgBackup"></span>
		</td>
	</tr>
</table>