<?php
class InformerModel extends CActiveRecord {
	public $status;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{informer}}';
	}

	public function relations() {
		return array(
			'informator' => array(self::BELONGS_TO, 'SessionModel', 'sessionid'),
		);
	}

	/*public function primaryKey() {
		return 'sessionid';
	}*/

	public function issetInform($sessionid, $to_sessionid) {
		$this -> getDbCriteria() -> mergeWith(array(
			'select' => 'count(*)',
			'condition' => 'sessionid=:sessionid AND to_sessionid=:to_sessionid',
			'params' => array(':sessionid' => $sessionid, ':to_sessionid' => $to_sessionid),
			'limit' => 1,
		));
		return $this;
	}

	public function recently($to_sessionid, $last_modif) {
		$this -> getDbCriteria() -> mergeWith(array(
			'select' => array(
				"*",
				"if(created_at >= '{$last_modif}', 'new', 'old') as status",
			),
			'condition' => "to_sessionid=:to_sessionid",
			'params' => array('to_sessionid' => $to_sessionid),
		));
		return $this;
	}

	public function beforeSave() {
		return parent::beforeSave() && $this -> created_at = date("Y-m-d H:i:s");
	}
}