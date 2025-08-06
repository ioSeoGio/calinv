<?php
namespace src\Action\Auth;

use src\Entity\User\User;
use yii\base\Model;

class SignUpForm extends Model
{
	public ?string $username = null;
	public ?string $email = null;
	public ?string $password = null;
	public ?string $passwordRepeat = null;

	public function rules(): array
    {
		return [
			[['username', 'email', 'password', 'passwordRepeat'], 'required'],
			[['username', 'email', 'password', 'passwordRepeat'], 'string', 'min' => 4, 'max' => 255],
			[['email'], 'email'],
			[['username'], 'match', 'pattern' => '/^[a-zA-Z0-9_]+$/', 'message' => 'Допустимые символы: латиница, цифры, _'],
			[['username'], 'unique', 'targetClass' => User::class, 'message' => 'Этот логин уже занят.'],
			[['email'], 'unique', 'targetClass' => User::class, 'message' => 'Этот email уже используется.'],
			[['password'], 'compare', 'compareAttribute' => 'passwordRepeat', 'message' => 'Пароли не совпадают.'],
		];
	}

    public function attributeLabels(): array
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'passwordRepeat' => 'Пароль повторно',
        ];
    }
}
