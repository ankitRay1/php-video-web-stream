<?php
class LoginForm extends CFormModel
{
	public $username;
	public $verifyCode;

	public function rules() {
		return array(
			array('username', 'required'),
			array('username', 'length', 'max' => 20),
			array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()),
			array('username', 'userExists'),
		);
	}

	public function attributeLabels() {
		return array(
			'username'=>Yii::t("app", "Username"),
		);
	}

	public function userExists() {
		if(!$this -> hasErrors()) {
			if(SessionModel::model() -> countByAttributes(array("username" => $this -> username)) > 0)
				$this -> addError('exists', Yii::t("app", "User with such name already exists"));
		}
	}
}


