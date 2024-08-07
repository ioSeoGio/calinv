<?php

use app\models\User;
use yii\bootstrap5\Html;
use yii\bootstrap5\Tabs;
use yii\helpers\Url;

$userId = Yii::$app->request->queryParams['userId'] ?? '';
?>

<?php if ($user = User::findOne($userId)): ?>
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <div class="card">
                <div class="text-center card-header bg-primary text-white">
                    <h2><?= Html::encode('Портфель: ' . $user->username) ?></h2>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= Tabs::widget([
    'headerOptions' => ['class' => 'd-flex justify-content-center'],
    'items' => [
        [
            'label' => 'Акции',
            'url' => Url::to($userId
                ? ['/Portfolio/personal-share/index', 'userId' => $userId]
                : ['/Portfolio/personal-share/index']
            ),
            'active' => str_contains(Url::current(), '/portfolio'),
        ],
        [
            'label' => 'Облигации',
            'url' => Url::to($userId
                ? ['/Portfolio/personal-bond/index', 'userId' => $userId]
                : ['/Portfolio/personal-bond/index']
            ),
            'active' => str_contains(Url::current(), Url::to(['/Portfolio/personal-bond/index'])),
        ],
        [
            'label' => 'Токены',
            'url' => Url::to($userId
                ? ['/Portfolio/personal-token/index', 'userId' => $userId]
                : ['/Portfolio/personal-token/index']
            ),
            'active' => str_contains(Url::current(), Url::to(['/Portfolio/personal-token/index'])),
        ],
    ]
]) ?>
