<div class="row-fluid sortable ui-sortable">
	<div class="box span12">
		<div class="box-header well" data-original-title="">
			<h2><i class="icon-edit"></i><?php echo TDLanguage::$unitAction_person_info; ?></h2>
			<div class="box-icon">
				<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
				<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
			</div>
		</div>
		<div class="box-content">
			<?php
			$form = $this->beginWidget('CActiveForm', array(
			    'id' => 'user-form',
			    'enableAjaxValidation' => false,
			    'htmlOptions' => array('class' => 'form-horizontal'),
			));
			?>
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="prependedInput">用户名</label>
					<div class="controls">
						<div class="input-prepend"> <?php echo TDSessionData::getUserName(); ?> </div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="appendedInput">姓名</label>
					<div class="controls">
						<div class="input-append">
							<input type="text" name="nickname" value="<?php echo $dataArray['nickname']; ?>" />
							<span class="error"><?php echo $dataArray['nickname_er']; ?></span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="appendedInput">原密码</label>
					<div class="controls">
						<div class="input-append">
							<input type="password" name="org_password" value="<?php echo $dataArray['org_password']; ?>" />
							<span class="error"><?php echo $dataArray['org_password_er']; ?></span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="appendedInput">新密码</label>
					<div class="controls">
						<div class="input-append">
							<input type="password" name="new_password" value="<?php echo $dataArray['new_password']; ?>" />
							<span class="error"><?php echo $dataArray['new_password_er']; ?></span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="appendedInput">确认密码</label>
					<div class="controls">
						<div class="input-append">
							<input type="password" name="check_password" value="<?php echo $dataArray['check_password']; ?>" />
							<span class="error"><?php echo $dataArray['check_password_er']; ?></span>
						</div>
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary">保存</button>
				</div>
			</fieldset>
			<?php $this->endWidget(); ?>
		</div>
	</div><!--/span-->

</div>