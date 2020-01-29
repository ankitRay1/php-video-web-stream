<?php
class Controller extends CController
{
	public $layout = 'main_column';
	public $title;

	public function init() {
		if(!Yii::app() -> request -> isAjaxRequest && !Yii::app() -> user -> isGuest) {
			$user = SessionModel::model() -> findByPk(Yii::app() -> user -> id);
			if($user === null) {
				Yii::app() -> user -> logout();
				$this -> redirect(Yii::app() -> user -> loginUrl);
			}
		}
		Yii::app() -> clientScript -> registerCssFile(Yii::app() -> baseUrl . '/static/css/app.bootstrap.min.css');
		Yii::app() -> clientScript -> registerCssFile(Yii::app() -> baseUrl . '/static/css/app.css');
	}

	public function filters() {
		return array('accessControl');
	}

	public function accessRules() {
		return array(
			array('allow',
				'users'=>array('@'),
			),
			array('allow',
				'controllers'=>array('videochat'),
				'actions'=>array('login', 'captcha'),
				'users'=>array('?'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
}