<style> .panelsty { width: 360px;} .loginbd{background:#fff url(<?php echo Yii::app()->baseUrl; ?>/common/lib/tooadmin/admin/www/too_admin/image/loginbg1.png);} </style>
<body class="loginbd">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="row-fluid">
                <div class="span12 center login-header">
			<h2><?php echo TDLanguage::$install_title; ?></h2>
                </div><!--/span-->
            </div><!--/row-->
            <div class="row-fluid">
                <div class="well panelsty center login-box">
		<div class="alert alert-info"> <?php echo TDLanguage::$install_remark; ?></div>
                    	<?php $form=$this->beginWidget('CActiveForm', array('id'=>'login-form', 'enableAjaxValidation'=>false,)); ?>
                        <fieldset>
				<div class="input-prepend" title="" data-rel="tooltip">
					<span class="add-on"><?php echo TDLanguage::$install_db_name; ?></span>
					<input type="text" class="input-large span7" name="" value="">
                            	</div>
                            	<div class="clearfix"></div>
				<div class="input-prepend" title="" data-rel="tooltip">
					<span class="add-on"><?php echo TDLanguage::$install_db_user; ?></span>
					<input type="text" class="input-large span7" name="" value="">
                            	</div>
                            	<div class="clearfix"></div>
                            	<div class="input-prepend" title="" data-rel="tooltip">
					<span class="add-on"><?php echo TDLanguage::$install_db_pwd; ?></span>
					<input type="text" class="input-large span7" name="" value="">
                            	</div>
				<div class="clearfix"></div>
				<div class="clearfix"></div>
				<div class="input-prepend" title="" data-rel="tooltip">
					<span class="add-on">初始&nbsp;开发账号</span>
					<input type="text" class="input-large span7" name="" value="">
                            	</div>
				<div class="clearfix"></div>
				<div class="input-prepend" title="" data-rel="tooltip">
					<span class="add-on">初始&nbsp;开发密码</span>
					<input type="text" class="input-large span7" name="" value="">
                            	</div>
				
                            	<div class="clearfix"></div>
                            	<p class="center span4">
					<button type="submit" class="btn btn-primary"><?php echo TDLanguage::$install_install ?></button>
                            	</p>
			</fieldset>
			<?php $this->endWidget(); ?>
                </div><!--/span-->
            </div><!--/row-->
        </div><!--/fluid-row-->
    </div><!--/.fluid-container-->
</body>