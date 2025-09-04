<?php
namespace src\Action\Auth;

use src\Entity\User\User;
use yii\base\Model;

class SignUpForm extends Model
{
	public ?string $nickname = null;
	public ?string $email = null;
	public ?string $password = null;
	public ?string $passwordRepeat = null;

	public function rules(): array
    {
		return [
			[['nickname', 'email', 'password', 'passwordRepeat'], 'required'],
			[['nickname', 'email', 'password', 'passwordRepeat'], 'string', 'min' => 4, 'max' => 255],
			[['email'], 'email'],
			[['nickname'], 'match', 'pattern' => '/^[a-zA-Z0-9_]+$/', 'message' => 'Допустимые символы: латиница, цифры, _'],
			[['nickname'], 'unique', 'targetClass' => User::class, 'targetAttribute' => ['nickname' => 'username'], 'message' => 'Этот логин уже занят.'],
			[['email'], 'unique', 'targetClass' => User::class, 'message' => 'Этот email уже используется.'],
			[['password'], 'compare', 'compareAttribute' => 'passwordRepeat', 'message' => 'Пароли не совпадают.'],
		];
	}

    public function attributeLabels(): array
    {
        return [
            'nickname' => 'Никнейм',
            'password' => 'Пароль',
            'passwordRepeat' => 'Пароль повторно',
        ];
    }
}
