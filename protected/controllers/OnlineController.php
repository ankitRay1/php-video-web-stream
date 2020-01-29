<?php
class OnlineController extends Controller
{
	protected $user;

	public function filters()
	{
		return array(
			'ajaxOnly'
		);
	}

	public function init()
	{
		$this -> user = Yii::app() -> user;
	}

	public function actionSession() {
		SessionModel::model() -> updateByPk($this -> user -> id, array('modified_at' => date("Y-m-d H:i:s")));
	}

	public function actionUsers() {
		$online = SessionModel::model() -> findAll();
		$this -> renderPartial("online", array(
			"online" => $online,
			"user" => $this -> user,
		));
	}

	public function actionSync() {
		$result = array();

		$online = SessionModel::model() -> findAll();
		$onlineHTML = $this -> renderPartial("online", array(
			"online" => $online,
			"user" => $this -> user,
		), true);

		$informs = InformerModel::model() -> recently($this -> user -> id, date("Y-m-d H:i:s", time() - 60)) -> with('informator') -> findAll();
		$informerHTML = $this -> renderPartial("//informer/index", array(
			"informs" => $informs,
		), true);

		$result['online'] = $onlineHTML;
		$result['informer'] = $informerHTML;
		$result['online_cnt'] = count($online);
		$result['informer_cnt'] = count($informs);

		header('Content-type: application/json');
		echo json_encode($result);
		Yii::app() -> end();
	}
}