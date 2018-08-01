<style> .panelsty { width: 360px;} .loginbd{background:#fff url(<?php echo Yii::app()->baseUrl; ?>/common/lib/tooadmin/admin/views/themes/<?php echo TDCommon::getThemeName(); ?>/www/too_admin/image/loginbg1.png);} </style>
<body class="loginbd">
    <div class="container-fluid">
        <div class="row-fluid" style="position: absolute; top: 30%; left: 50%;transform: translate(-50%, -50%);" >
            <div class="row-fluid">
                <div class="span12 center">
			<h2><?php echo Yii::app()->params->admin_login_title; ?></h2>
                </div><!--/span-->
            </div><!--/row-->
            <div class="row-fluid">
                <div class="well panelsty center login-box">
		<div class="alert alert-info">
			 <?php 
				$nameMsg = $model->getError("username");
				$pwdMsg = $model->getError("password");
				$verMsg = $model->getError('verifyCode'); 
				if(empty($eror_msg)) { $eror_msg = $nameMsg; }	
				if(empty($eror_msg)) { $eror_msg = $pwdMsg; }	
				if(empty($eror_msg)) { $eror_msg = $verMsg; }	
				echo empty($eror_msg) ? TDLanguage::$login_enter_pwd_name : '<b style="color:red;">'.$eror_msg.'</b>'; ?></div>
                    	<?php $form=$this->beginWidget('CActiveForm', array('id'=>'login-form', 'enableAjaxValidation'=>false,)); ?>
			<fieldset>
				<div class="input-prepend" title="<?php echo empty($nameMsg) ? TDLanguage::$login_username : $nameMsg; ?>" data-rel="tooltip">
                                	<span class="add-on"><i class="icon-user"></i></span>
					<input type="hidden" value="" name="clientWidth" id="clientWidth">
                                	<?php echo $form->textField($model,'username',array('class'=>'input-large span10')); ?> 
                            	</div>
                            	<div class="clearfix"></div>
                            	<div class="input-prepend" title="<?php echo empty($pwdMsg) ? TDLanguage::$login_password  : $pwdMsg; ?>" data-rel="tooltip">
                               	<span class="add-on"><i class="icon-lock"></i></span>
                                <?php 
                                    echo $form->passwordField($model,'password',array('class'=>'input-large span10')); 
                                ?>     
                            </div>
                            <div class="clearfix"></div>
                            <div class="input-prepend" title="<?php echo empty($verMsg) ? TDLanguage::$login_verify_code : $verMsg; ?>" data-rel="tooltip">
                             	<span class="add-on"><i class="icon-star"></i></span>
                                 <?php echo $form->textField($model,'verifyCode',array('class'=>'input-large span6')); ?>
				<span> <?php $this->widget('CCaptcha',array('imageOptions'=>array('width'=>55,'height'=>30,
				 'style'=>'margin-left:10px;'),'buttonLabel'=>  TDLanguage::$login_refresh_verifycode)); ?> </span>
				<input type="hidden" id="login_nt_code" name="login_nt_code" value="" />
				<script>window.onload = setNtCode; function setNtCode(){$("#login_nt_code").val(NT199_GetHardwareId());}</script>
			    </div>
                            <div class="clearfix"></div>
                            <p class="center span4">
				    <button type="submit" class="btn btn-primary"><?php echo TDLanguage::$login_enter_button ?></button>
                            </p>
                        </fieldset>
                     <?php $this->endWidget(); ?>
                </div><!--/span-->
            </div><!--/row-->
        </div><!--/fluid-row-->
    </div><!--/.fluid-container-->
   
</body>
<script>
	var clientWidth = document.documentElement.clientWidth;
	document.getElementById("clientWidth").value=clientWidth;
</script>