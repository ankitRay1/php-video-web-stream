<?php
class SessionModel extends CActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{session}}';
	}

	public function relations() {
		return array(
			'rooms' => array(self::HAS_MANY, 'RoomModel', 'sessionid'),
		);
	}

	public function scopes() {
		return array(
			'expired' => array(
				'select' => 'id',
				'condition' => "modified_at < '". date("Y-m-d H:i:s", time() - 60) ."'",
			),
		);
	}

	public function beforeSave() {
		if($this -> isNewRecord) {
			$this -> modified_at = date("Y-m-d H:i:s");
		}
		return parent::beforeSave();
	}


	/*public function expired() {
		$this -> getDbCriteria() -> mergeWith(array(
			'select' => 'id',
			'condition' => 'modified_at'
		));
	}*/
}