<?php

namespace src\Action\Auth;

use src\Entity\User\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
	public ?string $email = null;
	public ?string $password = null;
	public ?bool $rememberMe = true;
	private ?User $_user = null;

	public function rules(): array
    {
		return [
			[['email', 'password'], 'required'],
			['email', 'email'],
			['rememberMe', 'boolean'],
			['password', 'validatePassword'],
		];
	}

    public function attributeLabels(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

	public function login(): bool
    {
		if ($this->validate() && $this->validatePassword()) {
			return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
		}

		return false;
	}

	public function validatePassword(): bool
    {
        $user = $this->getUser();

        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError('password', 'Неправильно указан логин и/или пароль.');
            return false;
        }

        return true;
	}

	private function getUser(): ?User
    {
		if ($this->_user === null) {
			$this->_user = User::findByEmail($this->email);
		}

		return $this->_user;
	}
}
