<?php $appdUrlstr = '?' . TDStaticDefined::$pageLayoutType . '=' .TDStaticDefined::$pageLayoutType_inner; ?>
<div class="tabbable">
	<ul class="nav nav-tabs"> 
		<li class="active"><a href="#menuItem1" onclick="<?php echo "loadMenuItemUrl('menuItem1','".
		TDPathUrl::createUrl('cmad_'.TDModule::getModuleIdByTableName(TDTable::$too_user).'_0').$appdUrlstr."')";  ?>"  data-toggle="tab"><?php echo TDLanguage::$menu_user_manage; ?></a></li>

		<li><a href="#menuItem2" onclick="<?php echo "loadMenuItemUrl('menuItem2','".
		TDPathUrl::createUrl('cmad_'.TDModule::getModuleIdByTableName(TDTable::$too_role).'_0').$appdUrlstr."')";  ?>"  data-toggle="tab"><?php echo TDLanguage::$menu_role_permission; ?></a></li>

		<li><a href="#menuItem3" onclick="<?php echo "loadMenuItemUrl('menuItem3','".
		TDPathUrl::createUrl('cmad_'.TDModule::getModuleIdByTableName(TDTable::$too_login_log).'_0').$appdUrlstr."')";  ?>"  data-toggle="tab"><?php echo TDLanguage::$menu_login_log; ?></a></li>
	</ul>
	<div class="tab-content"> 
		<div class="tab-pane active" id="menuItem1"></div>  
		<div class="tab-pane" id="menuItem2"></div>  
		<div class="tab-pane" id="menuItem3"></div> 
	</div>
</div>
<script> setTimeout("<?php echo "loadMenuItemUrl('menuItem1','".TDPathUrl::createUrl('cmad_'.TDModule::getModuleIdByTableName(TDTable::$too_user).'_0').$appdUrlstr."')";  ?>",1000);</script>