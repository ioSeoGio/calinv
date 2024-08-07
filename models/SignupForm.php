<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
	public $username;
	public $email;
	public $password;
	public $password_repeat;

	public function rules(): array
    {
		return [
			[['username', 'email', 'password', 'password_repeat'], 'required'],
			[['username', 'email', 'password', 'password_repeat'], 'string', 'min' => 4, 'max' => 255],
			['email', 'email'],
			['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Этот логин уже занят.'],
			['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Этот email уже используется.'],
			['password', 'compare', 'compareAttribute' => 'password_repeat', 'message' => 'Пароли не совпадают.'],
		];
	}

	public function signup()
	{
		if (!$this->validate()) {
			return null;
		}

		$user = new User();
		$user->username = $this->username;
		$user->email = $this->email;
		$user->setPassword($this->password);
		$user->generateAuthKey();
		$user->generateAccessToken();
		$user->created_at = new \MongoDB\BSON\UTCDateTime();

		return $user->save() ? $user : null;
	}
}
