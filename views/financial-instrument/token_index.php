<?php

use app\models\FinancialInstrument\Bond;
use app\models\FinancialInstrument\Share;
use app\models\FinancialInstrument\Token;
use app\models\IssuerRating\IssuerRating;
use yii\base\Model;
use yii\bootstrap5\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var ArrayDataProvider $tokensDataProvider */
/** @var Model $tokensSearchForm */
/** @var Model $tokenForm */

?>
<?= $this->render('tabs', []); ?>
<?= $this->render('token_creation', [
    'tokenForm' => $tokenForm,
]); ?>
<?= GridView::widget([
    'dataProvider' => $tokensDataProvider,
    'filterModel' => $tokensSearchForm,
    'columns' => [
        [
            'label' => 'имя выпуска',
            'attribute' => 'name',
        ],
        [
            'label' => 'эмитент',
            'attribute' => 'issuerRating.issuer',
            'filter' => Html::activeDropDownList(
                $tokensSearchForm,
                'issuerId',
                array_merge(
                    ['All' => 'Все'],
                    ArrayHelper::map(
                        IssuerRating::find()->all(),
                        fn (IssuerRating $issuerRating) => (string) $issuerRating->_id,
                        'issuer'
                ),
            ), ['class' => 'form-control']),
        ],
        [
            'label' => 'номинал',
            'attribute' => 'denomination',
            'value' => function (Token $model) {
                return $model->denomination . ' р.';
            }
        ],
        [
            'label' => 'ставка',
            'attribute' => 'rate',
            'value' => function (Token $model) {
                return $model->rate . ' %';
            }
        ],
        [
            'label' => 'текущая цена',
            'attribute' => 'currentPrice',
            'value' => function (Token $model) {
                return $model->currentPrice . ' р.';
            }
        ],
        [
            'label' => 'объем выпуска',
            'attribute' => 'volumeIssued',
            'value' => function (Token $model) {
                return $model->volumeIssued . ' шт.';
            }
        ],
    ],
]) ?>
