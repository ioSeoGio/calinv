<?php

namespace app\controllers;

use lib\BaseController;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Entity\Share\Deal\ShareDealRecord;
use src\Entity\Share\Share;
use src\Entity\Share\ShareRegisterNumber;
use src\Integration\Bcse\ShareInfo\BcseShareInfoFetcher;
use yii\filters\AccessControl;

class DevController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            '*',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        private BcseShareInfoFetcher $bcseShareInfoFetcher,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionView(): string
    {
        return $this->render('view');
    }

    public function actionTestData(int $shareId): string
    {
        $share = Share::getOneById($shareId);
        $data = ShareDealRecord::find()
            ->select(["timestamp", 'weightedAveragePrice'])
            ->andWhere(['share_id' => $shareId])
            ->addOrderBy(['timestamp' => SORT_ASC])
            ->asArray()->all();

        return json_encode([
            'shareName' => $share->getFormattedNameWithIssuer(),
            'values' => array_map(
                fn ($item) => array_values($item),
                $data
            )
        ]);
    }

    public function actionIndex(): string
    {
        $r = $this->bcseShareInfoFetcher->get(new PayerIdentificationNumber('692041378'), new ShareRegisterNumber('6-404-01-15990'));
        dd($r);
    }
}