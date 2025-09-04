<?php

namespace src\Entity\User;

use lib\Exception\UserException;
use src\Action\Auth\SignupForm;

class UserSignupFactory
{
    public function signUp(
        SignupForm $signupForm,
    ): User {
		$user = new User();
		$user->username = $signupForm->nickname;
		$user->email = $signupForm->email;
        $user->isPortfolioVisible = true;
        $user->isPortfolioPublic = true;
		$user->setPassword($signupForm->password);
		$user->generateAuthKey();

        if (!$user->save()) {
            throw new UserException("Во время регистрации произошла ошибка, попробуйте позже.");
        }

		return $user;
    }
}