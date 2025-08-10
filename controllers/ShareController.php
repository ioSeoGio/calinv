<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Share\ShareCreateForm;
use src\Action\Share\ShareDealSearchForm;
use src\Action\Share\ShareSearchForm;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerFullnessState;
use src\Entity\Share\Deal\ShareDealRecord;
use src\Entity\Share\Share;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class ShareController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'toggle-moderation',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'toggle-moderation',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $shareSearchForm = new ShareSearchForm();
        $shareSearchForm->isActive = true;
        $shareDataProvider = $shareSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'showClosingDate' => false,
            'shareCreateForm' => new ShareCreateForm(),
            'shareSearchForm' => $shareSearchForm,
            'shareDataProvider' => $shareDataProvider,
        ]);
    }

    public function actionAllShares(): string
    {
        $shareSearchForm = new ShareSearchForm();
        $shareSearchForm->isActive = false;
        $shareDataProvider = $shareSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'showClosingDate' => true,
            'shareCreateForm' => new ShareCreateForm(),
            'shareSearchForm' => $shareSearchForm,
            'shareDataProvider' => $shareDataProvider,
        ]);
    }

    public function actionDealInfo(int $id): string
    {
        $share = Share::getOneById($id);

        $searchForm = new ShareDealSearchForm();
        $dataProvider = $searchForm->search($share, Yii::$app->request->queryParams);

        return $this->render('deal-info', [
            'share' => $share,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAllSharesCompare(): string
    {
        return $this->render('all-shares-compare', [
        ]);
    }

    public function actionShareDealAllData(int $shareId): string
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

    public function actionToggleModeration(int $issuerId): Response
    {
        $issuer = Issuer::getOneById($issuerId);

        if ($issuer->dateShareInfoModerated !== null) {
            $issuer->markShareInfoNotModerated();
        } else {
            $issuer->removeFullnessState(IssuerFullnessState::sharesWithException);
            $issuer->dateShareInfoModerated = new \DateTimeImmutable();
        }
        $issuer->save();

        return $this->redirect(Yii::$app->request->referrer);
    }
}
