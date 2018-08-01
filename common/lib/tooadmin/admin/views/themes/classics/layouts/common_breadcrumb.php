<div> <?php $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink' => CHtml::link('首页',TDPathUrl::createUrl('tDSite/index')),
        'links' => $this->breadcrumbs,)); ?>  
</div>