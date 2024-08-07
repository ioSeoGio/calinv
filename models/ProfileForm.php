<?php

namespace app\models;

use yii\base\Model;

class ProfileForm extends Model
{
	public $username;
	public $email;
	public $new_password;
	public $confirm_password;

	public function rules()
	{
		return [
			[['username', 'email'], 'required'],
			[['username'], 'string', 'min' => 2, 'max' => 255],
			['email', 'email'],
			[['new_password', 'confirm_password'], 'string', 'min' => 6],
			['confirm_password', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Пароли не совпадают.'],
		];
	}
}
