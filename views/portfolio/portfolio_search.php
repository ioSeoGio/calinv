<?php

/** @var \yii\data\DataProviderInterface $dataProvider */
/** @var \yii\base\Model $searchModel */

use app\models\User;
use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Поиск по портфелям';
?>

<div class="profiles-portfolio-search">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'владелец',
                'format' => 'raw',
                'value' => function (User $model) {
                    $linkBtn = Html::a(
                        text: 'посмотреть подробнее',
                        url: Url::to(['Portfolio/personal-share/index', 'userId' => $model->getId()]),
                        options: ['class' => 'btn btn-primary'],
                    );
                    return $model->username . "<br><br>" . $linkBtn;
                }
            ],
            [
                'label' => 'состав портфеля',
                'format' => 'raw',
                'value' => function (User $user) {
                    $info = $user->getSharesInfo();

                    return "
                        <table>
                            <tr class='row'>
                                <th class='col'>Акции</th>
                                <th class='col'>Облигации</th>
                                <th class='col'>Токены</th>
                            </tr>
                            <tr class='row'>
                                <td class='col'>
                                    {$info['shareAmount']} шт. <br> всего на: {$info['shareMoneyCost']} р. <br> доля в портфеле: {$info['sharePercentage']}%
                                </td>
                                <td class='col'>
                                    {$info['bondAmount']} шт. <br> всего на: {$info['bondMoneyCost']} р. <br> доля в портфеле: {$info['bondPercentage']}%
                                </td>
                                <td class='col'>
                                    {$info['tokenAmount']} шт. <br> всего на: {$info['tokenMoneyCost']} р. <br> доля в портфеле: {$info['tokenPercentage']}%
                                </td>
                            </tr>
                        </table>
                    ";
                },
            ],

        ],
    ]); ?>
</div>
