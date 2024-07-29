<?php

namespace app\commands;

use app\models\IssuerRating\Factory\IssuerRatingFactory;
use app\models\IssuerRating\IssuerRating;
use yii\console\Controller;
use yii\console\ExitCode;

class UpdateIssuersController extends Controller
{
    public function __construct(
        $id,
        $module,

        private IssuerRatingFactory $issuerRatingFactory,

        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): int
    {
        /** @var IssuerRating $issuerRating */
        foreach (IssuerRating::find()->all() as $issuerRating) {
            $this->issuerRatingFactory->update($issuerRating, true);
            $issuerRating->save();
        }

        return ExitCode::OK;
    }
}
