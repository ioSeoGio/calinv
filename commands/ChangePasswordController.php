<?php

namespace app\commands;

use src\Entity\User\User;
use yii\console\Controller;
use yii\console\ExitCode;

class ChangePasswordController extends Controller
{
    public function __construct(
        $id,
        $module,

        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionChange(int $userId, string $password): int
    {
        $user = User::getOneById($userId);
        $user->setPassword($password);
        $user->save();

        return ExitCode::OK;
    }
}
