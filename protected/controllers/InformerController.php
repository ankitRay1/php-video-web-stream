<?php
class InformerController extends Controller
{
	protected $sessionid;

	public function init()
	{
		$this -> sessionid = Yii::app() -> user -> id;
	}

	public function actionIndex() {
		$informs = InformerModel::model() -> recently($this -> sessionid, date("Y-m-d H:i:s")) -> with('informator') -> findAll();

		$informer = $this -> renderPartial("index", array(
			"informs" => $informs,
		));
	}

	public function actionDelete($id) {
		InformerModel::model() -> deleteByPk($id);
	}
}