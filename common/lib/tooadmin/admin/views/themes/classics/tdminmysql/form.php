<?php if(TDSessionData::currentUserIsAdmin()) { ?>
<div id="operateTool" style="display: none;">
	<div class="btn-group" style="float:left;margin-left: -5px;padding-right: 5px;padding-top:3px;">
	<a style="cursor:pointer;" class="dropdown-toggle" data-toggle="dropdown"><i class="icon icon-blue icon-gear"></i></a>
	<ul class="dropdown-menu">
		<li><a href="javascript:document.getElementById('fram').contentWindow.postReloadCurrentForm();void(0);"><i class="icon icon-blue icon-refresh" title="<?php 
		echo TDLanguage::$to_refresh; ?>"></i><?php echo TDLanguage::$to_refresh; ?></a></li>
		<li><a href="javascript:document.getElementById('fram').contentWindow.to_form_admin(<?php echo $editForm->formModuleId; ?>);void(0);"><i class="icon icon-blue icon-clipboard" title="<?php 
		echo TDLanguage::$to_columns_admin; ?>"></i><?php echo TDLanguage::$to_columns_admin; ?></a></li>
	</ul>
	</div>
</div>
<script> parent.$("#modal_operate").html($("#operateTool").html()); </script>
<?php } ?>
<?php $editForm->createEditForm(); ?>