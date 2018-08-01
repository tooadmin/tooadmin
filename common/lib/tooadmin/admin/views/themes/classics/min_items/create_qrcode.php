<div class="row-fluid sortable ui-sortable">
	<div class="box span12">
		<div class="box-header well" data-original-title="">
			<h2><i class="icon-edit"></i>创建二维码</h2>
			<div class="box-icon">
				<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
				<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
			</div>
		</div>
		<div class="box-content">
			<table style="width:80%;">
				<tr>
					<td>
			<?php
			$form = $this->beginWidget('CActiveForm', array('id' => 'qrcode-form',
			    'enableAjaxValidation' => false,'htmlOptions' => array('class' => 'form-horizontal','target'=>'tmpValidateFrameQr'),));
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="prependedInput">图片大小</label>
					<div class="controls">
						<div class="input-prepend"><?php echo $sizeField; ?></div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="appendedInput">内容</label>
					<div class="controls"> <?php echo $contentField; ?> </div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary">创建二维码</button>
				</div>
				<?php echo $qrCodeImgField; ?>
			</fieldset>
			<?php $this->endWidget(); ?>
					</td>
					<td>
						<iframe name="tmpValidateFrameQr" style="width:400px;height: 400px;padding-left:50px;border:none;"></iframe>
					</td>
				</tr>
			</table>	
		</div>
	</div><!--/span-->

</div>