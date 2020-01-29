<?php
class RoomModel extends CActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{room}}';
	}

	public function room($sessionid, $to_sessionid) {
		$this -> getDbCriteria() -> mergeWith(array(
			'condition' => '(sessionid=:sessionid AND to_sessionid=:to_sessionid) OR (to_sessionid=:sessionid AND sessionid=:to_sessionid)',
			'params' => array(':sessionid' => $sessionid, ':to_sessionid' => $to_sessionid),
			'limit' => 1,
		));
		return $this;
	}

	public function beforeSave() {
		return parent::beforeSave() && $this -> created_at = date("Y-m-d H:i:s");
	}

    /*public function validRoom($roomid, $sessionid, $to_sessionid) {
        $this -> getDbCriteria() -> mergeWith(array(
           'condition' => 'id=:id AND sessionid=:sessionid AND to_sessionid=:to_sessionid',
            'params' => array(':id' => $roomid, ':sessionid' => $sessionid, ':to_sessionid' => $to_sessionid),
        ));
        return $this;
    }*/
}