<?php

/** @var DataProviderInterface $dataProvider */
/** @var PortfolioSearch $searchModel */

use src\Action\Portfolio\PortfolioSearch;
use src\Entity\User\User;
use yii\bootstrap5\Html;
use yii\data\DataProviderInterface;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Поиск по портфелям';
?>

<div class="profiles-portfolio-search">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
        ],
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'владелец',
                'attribute' => 'username',
                'format' => 'raw',
                'value' => function (User $model) {
                    return Html::a(
                        text: $model->username,
                        url: Url::to(['/personal-share/index', 'userId' => $model->getId()]),
                        options: ['class' => 'btn btn-primary'],
                    );
                }
            ],
            [
                'label' => 'состав портфеля',
                'format' => 'raw',
                'value' => function (User $user) {
                    $info = $user->getShareInfo();

                    return "
                        <table>
                            <tr class='row'>
                                <th class='col'>Акции</th>
                            </tr>
                            <tr class='row'>
                                <td class='col'>
                                    {$info['amount']} шт. <br> всего на: {$info['moneyCost']} р.
                                </td>
                            </tr>
                        </table>
                    ";
                },
            ],
        ],
    ]); ?>
</div>
