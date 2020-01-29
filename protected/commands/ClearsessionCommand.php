<?php
class ClearsessionCommand extends CConsoleCommand {
	public function actionRemoveall() {
		
		$transaction = Yii::app() -> db -> beginTransaction();
		try {

		$builder = Yii::app() -> db -> createCommand();
		$userid = array(); $roomid = array();

		$dataReader = $builder
		-> select("id")
		-> from("{{session}}")
		-> where("modified_at < :modified_at", array("modified_at" => date("Y-m-d H:i:s", time() - 60)))
		-> queryAll();
		foreach($dataReader as $row) {
			$userid[] = $row['id'];
		}

		if(empty($userid)) {
			return 0;
		}
		$builder -> reset();

		$dataReader = $builder
		-> select("id")
		-> from("{{room}}")
		-> orWhere(array("in", "sessionid", $userid))
		-> orWhere(array("in", "to_sessionid", $userid))
		-> queryAll();
		foreach($dataReader as $row) {
			$roomid[] = $row['id'];
		}
		$builder -> reset();

		$builder -> delete("{{informer}}", 'sessionid in('.implode(",", $userid).') OR to_sessionid in('.implode(",", $userid).')');
		$builder -> reset();
		if(!empty($roomid)) {
			$builder -> delete("{{room}}", 'id in('.implode(",", $roomid).')');
			$builder -> reset();
			$builder -> delete("{{message}}", 'roomid in('.implode(",", $roomid).')');
			$builder -> reset();
		}
		$builder -> delete("{{session}}", 'id in('.implode(",", $userid).')');
		$builder -> reset();

		$transaction -> commit();

		} catch(Exception $e) {
			$transaction -> rollback();
			return 1;
		}
		return 0;
	}
}