<?php

namespace app\models;

use yii\base\Model;

class ProfileForm extends Model
{
	public $username;
	public $fio;
	public $email;
	public $phone_number;
	public $new_password;
	public $confirm_password;

	public function rules()
	{
		return [
			[['username', 'fio', 'email', 'phone_number'], 'required'],
			[['username', 'fio'], 'string', 'min' => 2, 'max' => 255],
			['email', 'email'],
			['phone_number', 'string', 'max' => 15],
			[['new_password', 'confirm_password'], 'string', 'min' => 6],
			['confirm_password', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Пароли не совпадают.'],
		];
	}
}
