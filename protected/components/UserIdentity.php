<?php

class UserIdentity extends CUserIdentity {
	private $_user;

	public function authenticate() {
		return $this -> errorCode == self::ERROR_NONE;
	}

	public function getId() {
		return $this -> _user -> id;
	}

	public function setUser($user) {
		$this -> _user = $user;
	}
}