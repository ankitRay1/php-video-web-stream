<?php
class ClearController extends CController {
	public function init() {
		$app_key = Yii::app() -> params['clearKey'];
		$get_key = Yii::app() -> request -> getQuery('key');
		if($app_key == false) {
			throw new CHttpException(400, Yii::t("app", "Access Denied"));
		}
		if($app_key != $get_key) {
			throw new CHttpException(400, Yii::t("app", "Access Denied"));
		}
	}
	
	public function actionIndex() {
		// params
		$args = array('yiic', 'clearsession', 'removeall');
		
		// Get command path
		$commandPath = Yii::app() -> getBasePath() . DIRECTORY_SEPARATOR . 'commands';

		// Create new console command runner
		$runner = new CConsoleCommandRunner();

		// Adding commands
		$runner -> addCommands($commandPath);

		// If something goes wrong return error
		$runner -> run ($args);
	}
}