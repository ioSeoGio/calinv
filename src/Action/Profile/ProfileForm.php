<?php

namespace src\Action\Profile;

use src\Entity\User\User;
use yii\base\Model;

class ProfileForm extends Model
{
	public ?string $username = null;
	public ?string $email = null;
	public ?string $newPassword = null;
	public ?string $confirmPassword = null;

    public function __construct(
        User $user,
    ) {
        parent::__construct();

        $this->username = $user->username;
        $this->email = $user->email;
    }

    public function rules(): array
    {
		return [
			[['username', 'email'], 'required'],

			[['username'], 'string', 'min' => 2, 'max' => 255],
			[['email'], 'email'],

            [['newPassword', 'confirmPassword'], 'string', 'min' => 6],
			[['confirmPassword'], 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Пароли не совпадают.'],
		];
	}
}
