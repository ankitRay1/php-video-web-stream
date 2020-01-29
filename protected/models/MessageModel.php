<?php
class MessageModel extends CActiveRecord {
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function rules() {
		return array(
		);
	}

	public function tableName() {
		return '{{message}}';
	}

	public function relations() {
		return array(
			'user' => array(self::BELONGS_TO, 'SessionModel', 'sessionid', 'together' => false),
		);
	}

	public function recently($roomid, $id) {
		$alias = $this -> getTableAlias();
		$this -> getDbCriteria() -> mergeWith(array(
			'condition' => 'roomid=:roomid AND '. $alias .'.id>:id',
			'params' => array(':roomid' => $roomid, ':id' => $id),
			'order' => 'created_at ASC',
		));
		return $this;
	}

	public function beforeSave() {
		return parent::beforeSave() && $this -> created_at = date("Y-m-d H:i:s");
	}
}
