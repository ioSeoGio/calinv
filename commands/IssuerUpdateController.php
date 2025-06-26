<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

class IssuerUpdateController extends Controller
{
    public function __construct(
        $id,
        $module,

        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): int
    {
        return ExitCode::OK;
    }
}
