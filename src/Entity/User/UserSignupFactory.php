<?php

namespace src\Entity\User;

use src\Action\Auth\SignupForm;

class UserSignupFactory
{
    public function singup(
        SignupForm $signupForm,
    ): User {
		$user = new User();
		$user->username = $this->username;
		$user->email = $this->email;
		$user->setPassword($this->password);
		$user->generateAuthKey();
		$user->generateAccessToken();
		$user->created_at = time();

		return $user->save() ? $user : null;
    }
}