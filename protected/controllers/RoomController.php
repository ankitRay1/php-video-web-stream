<?php
class RoomController extends Controller
{
	public $subscriber;
	public $publisher;
	public $ip;
	public $id;

	public function init()
	{
		parent::init();

		$this -> id = (string) Yii::app() -> request -> getQuery('id');

		if(empty($this -> id)) {
			throw new CHttpException(404, Yii::t("app", "The page you are looking for doesn't exists"));
		}

		$this -> subscriber = SessionModel::model() -> findByPk($this -> id);
		$this -> publisher = Yii::app() -> user;

		if(!$this -> subscriber) {
			throw new CHttpException(400, Yii::t("app", "Could not find user or user is offline"));
		}

		$this -> ip = Yii::app() -> request -> getUserHostAddress();

		Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/static/js/opentok.js', CClientScript::POS_END);
		Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/static/js/chat.js', CClientScript::POS_END);

        Yii::app() -> clientScript -> registerScriptFile('//static.opentok.com/webrtc/v2.2/js/opentok.min.js', CClientScript::POS_END);
		$this -> title = Yii::t("app", "Online Video Chat with {name}", array("{name}" => CHtml::encode($this -> subscriber -> username)));
	}

	public function actions()
	{
		return array(
			'create' => array(
				'class' => 'application.controllers.room.Create',
			),
			'join' => array(
				'class' => 'application.controllers.room.Join',
			),
		);
	}
}