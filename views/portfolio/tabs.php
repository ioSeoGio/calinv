<?php

use src\Entity\User\User;
use yii\bootstrap5\Html;
use yii\bootstrap5\Tabs;
use yii\helpers\Url;

$userId = Yii::$app->request->queryParams['userId'] ?? Yii::$app->user->id;
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
                ? ['/personal-share/index', 'userId' => $userId]
                : ['/personal-share/index']
            ),
            'active' => str_contains(Url::current(), '/personal-share/index') || str_contains(Url::current(), '/portfolio'),
        ],
        [
            'label' => 'Графики',
            'url' => Url::to($userId
                ? ['/personal-share/charts', 'userId' => $userId]
                : ['/personal-share/charts']
            ),
            'active' => str_contains(Url::current(), '/personal-share/charts'),
        ],
    ]
]) ?>
