<?php

use app\models\FinancialInstrument\Bond;
use app\models\IssuerRating\IssuerRating;
use app\models\Portfolio\PersonalBond;
use src\Helper\DateRounder;
use src\IssuerRatingCalculator\Static\YieldToMaturityCalculator;
use yii\base\Model;
use yii\bootstrap5\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var ArrayDataProvider $personalBondDataProvider */
/** @var Model $personalBondSearchForm */
/** @var Model $personalBondForm */

?>
<?= $this->render('tabs', []); ?>
<?php if (!isset(Yii::$app->request->queryParams['userId'])) {
    echo $this->render('personal_bond_creation', [
        'personalBondForm' => $personalBondForm,
    ]);
} ?>
<?= $sharesContent = GridView::widget([
    'dataProvider' => $personalBondDataProvider,
    'filterModel' => $personalBondSearchForm,
    'columns' => [
        [
            'label' => 'имя выпуска',
            'attribute' => 'bond.name',
            'filter' => Html::activeDropDownList(
                $personalBondSearchForm,
                'bondId',
                array_merge(
                    ['All' => 'Все'],
                    ArrayHelper::map(
                        Bond::find()->all(),
                        fn (Bond $bond) => (string) $bond->_id,
                        fn (Bond $bond) => $bond->issuerRating?->issuer .' - '.$bond->name,
                ),
            ), ['class' => 'form-control']),
        ],
        [
            'label' => 'эмитент',
            'attribute' => 'bond.issuerRating.issuer',
            'filter' => Html::activeDropDownList(
                $personalBondSearchForm,
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
            'label' => 'ставка',
            'attribute' => 'rate',
            'value' => function (PersonalBond $model) {
                return $model->bond->rate . ' %';
            }
        ],
        [
            'label' => 'прибыль, всего',
            'value' => function (PersonalBond $model) {
                $maturityDate = new DateTimeImmutable($model->bond->maturityDate);
                $timeToMaturity = $maturityDate->diff(new DateTimeImmutable());

                return round($model->bond->rate * $timeToMaturity->days / 365, 1) . ' %';
            }
        ],
        [
            'label' => 'от сегодня <br>к погашению, % год',
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function (PersonalBond $model) {
                $value = YieldToMaturityCalculator::calculate(
                    $model->bond->denomination,
                    $model->buyPrice,
                    $model->bond->rate,
                    new DateTime($model->bond->maturityDate),
                );

                return Html::tag(
                    name: 'span',
                    content: round($value, 1) . ' %',
                    options: ['class' => $value > 0 ? 'text-success' : 'text-danger']
                );
            }
        ],
        [
            'label' => 'от покупки <br>к погашению, % год',
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function (PersonalBond $model) {
                $value = YieldToMaturityCalculator::calculate(
                    $model->bond->denomination,
                    $model->buyPrice,
                    $model->bond->rate,
                    new DateTime($model->bond->maturityDate),
                    new DateTime($model->buyDate),
                );

                return Html::tag(
                    name: 'span',
                    content: round($value, 1) . ' %',
                    options: ['class' => $value > 0 ? 'text-success' : 'text-danger']
                );
            }
        ],
        [
            'label' => 'номинал',
            'attribute' => 'bond.denomination',
            'format' => 'raw',
            'value' => function (PersonalBond $model) {
                return Html::tag(
                    name: 'span',
                    content: round($model->bond->denomination, 2) . ' руб.',
                    options: ['class' => $model->bond->denomination >= $model->bond->currentPrice ? 'text-success' : 'text-danger']
                );
            }
        ],
        [
            'label' => 'цена покупки',
            'attribute' => 'buyPrice',
            'format' => 'raw',
            'value' => function (PersonalBond $model) {
                return Html::tag(
                    name: 'span',
                    content: round($model->buyPrice, 2) . ' руб.',
                    options: ['class' => $model->buyPrice <= $model->bond->currentPrice  ? 'text-success' : 'text-danger']
                );
            }
        ],
        [
            'label' => 'текущая цена',
            'attribute' => 'bond.currentPrice',
            'format' => 'raw',
            'value' => function (PersonalBond $model) {
                return Html::tag(
                    name: 'span',
                    content: round($model->bond->currentPrice, 2) . ' руб.',
                    options: ['class' => 'text-primary']
                );
            }
        ],
        [
            'label' => 'погашение',
            'value' => function (PersonalBond $model) {
                $diff = (new DateTime($model->bond->maturityDate))->diff(new DateTime());
                return \Yii::$app->formatter->asRelativeTime(DateRounder::roundDateInterval($diff));
            }
        ],
        [
            'label' => 'дата покупки',
            'attribute' => 'buyDate',
        ],
        [
            'label' => 'выпуск',
            'attribute' => 'bond.issueDate',
        ],
        [
            'label' => 'погашение',
            'attribute' => 'bond.maturityDate',
        ],
        [
            'label' => 'объем выпуска',
            'attribute' => 'bond.volumeIssued',
            'value' => function (PersonalBond $model) {
                return $model->bond->volumeIssued . ' шт.';
            }
        ],
    ],
]) ?>
