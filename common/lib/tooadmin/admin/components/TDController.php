<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
abstract class TDController extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to 'column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//themes/default/layouts/common_page';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public abstract function actionsRemark();
	
	public function __construct($id) {
		if(!Yii::app()->user->isGuest) { $uid = TDSessionData::getUserId(); if(empty($uid)) { Yii::app()->user->logout(); } }
		$this->layout = TDLayout::getComonPage();	
		parent::__construct($id);
	} 
	
	public function render($view,$data=null,$return=false) {
		parent::render(TDCommon::getRender($view,$this),$data,$return);	
	}
		
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	private $allowAction = array( //注意这里写的全部都是小写的
		'tDSite/index',
		'tDSite/captcha',
		'tDSite/login',
		'tDSite/logout',
	    	'tDCommon/custome',
	    	'tDCommon/captcha',
	);
	
	public function accessRules() {
		$actionName = $this->getAction()->getController()->getAction()->getId();	
		$controllerName =  $this->getId();
		if(in_array(strtolower($controllerName).'/'.strtolower($actionName),$this->allowAction)) {
			return array( array('allow', 'actions' => array($actionName), 'users' => array('*'),),
			array('deny', 'users'=>array('*'),),);
		} 
		$user_id = TDSessionData::getUserId();
		$isManager = TDSessionData::currentUserIsManager();
		if (Yii::app()->user->isGuest || empty($user_id) || !$isManager) { $this->redirect(TDPathUrl::createUrl('tDSite/logout')); }
		if(TDPermission::checkActionPermission($controllerName,$actionName)) {
			return array(
				array('allow', 'actions'=>array($actionName),'users'=>array('@'),),
				array('deny', 'users'=>array('*'),),
			);
		} else {
			TDCommon::tipActionNoPermission($controllerName, $actionName);
			/*
			return array(
				array('allow', 'actions'=>array('noPermission'),'users'=>array('@'),),
				array('deny', 'users'=>array('*'),),
			);
			*/
		}
	}
	
}