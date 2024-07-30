<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
	public $email;
	public $password;
	public $rememberMe = true;

	private $_user = false;

	public function rules()
	{
		return [
			[['email', 'password'], 'required'],
			['email', 'email'],
			['rememberMe', 'boolean'],
			['password', 'validatePassword'],
		];
	}

	public function validatePassword($attribute, $params): void
    {
		if (!$this->hasErrors()) {
			$user = $this->getUser();

			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError($attribute, 'Неправильно указан логин и/или пароль.');
			}
		}
	}

	public function login(): bool
    {
		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
		}
		return false;
	}

	protected function getUser(): ?User
    {
		if ($this->_user === false) {
			$this->_user = User::findByEmail($this->email);
		}

		return $this->_user;
	}
}
