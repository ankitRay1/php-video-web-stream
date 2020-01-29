<?php
class MessageController extends Controller
{
	public $room,
				 $to_sessionid,
				 $lastid,
				 $publisher;

	public function filters()
	{
		return array(
			'ajaxOnly'
		);
	}

	public function init()
	{
		parent::init();
		$roomid = (int) Yii::app() -> request -> getPost('roomid', 13);
		$this -> to_sessionid = (int) Yii::app() -> request -> getPost('to_sessionid', 14);
		$this -> lastid = (int) Yii::app() -> request -> getPost('lastid', 0);

		$this -> publisher = Yii::app() -> user;

		$this -> room = RoomModel::model() -> room($this -> publisher -> id, $this -> to_sessionid) -> find();

		if(!$this -> room OR $this -> room -> id != $roomid) {
				throw new CHttpException(400, Yii::t("app", "You don't have permissions to access this session"));
		}
	}

	public function actionIndex()
	{
		$subscriber = SessionModel::model() -> findByPk($this -> to_sessionid);
		$messageModel = new MessageModel();
		$messages = $messageModel -> recently($this -> room -> id, $this -> lastid) -> with('user') -> findAll();

		$arr = array(); $i = 0;
		foreach($messages as $message) {
			$arr[$i]['id'] = (int) $message -> id;
			$arr[$i]['name'] = CHtml::encode($message -> user -> username);
			$arr[$i]['self'] = $message -> sessionid == $this -> publisher -> id;
			$arr[$i]['created_at'] = $message -> created_at;
			$arr[$i]['message'] = CHtml::encode($message -> message);
			$i++;
		}
		header('Content-type: application/json');
		echo json_encode($arr);
		Yii::app() -> end();
	}

	public function actionInsert() {
		$messageModel = new MessageModel();
		$messageModel -> roomid = $this -> room -> id;
		$messageModel -> sessionid = $this -> publisher -> id;
		$messageModel -> message = (string) Yii::app() -> request -> getPost('message');
		$messageModel -> save();
	}
}