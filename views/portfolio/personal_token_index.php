<?php

use app\models\FinancialInstrument\Token;
use app\models\IssuerRating\IssuerRating;
use app\models\Portfolio\PersonalToken;
use src\Helper\DateRounder;
use src\IssuerRatingCalculator\Static\YieldToMaturityCalculator;
use yii\base\Model;
use yii\bootstrap5\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var ArrayDataProvider $personalTokenDataProvider */
/** @var Model $personalTokenSearchForm */
/** @var Model $personalTokenForm */

?>
<?= $this->render('tabs', []); ?>
<?= $this->render('personal_token_creation', [
    'personalTokenForm' => $personalTokenForm,
]); ?>
<?= $sharesContent = GridView::widget([
    'dataProvider' => $personalTokenDataProvider,
    'filterModel' => $personalTokenSearchForm,
    'columns' => [
        [
            'label' => 'имя выпуска',
            'attribute' => 'token.name',
            'filter' => Html::activeDropDownList(
                $personalTokenSearchForm,
                'tokenId',
                array_merge(
                    ['All' => 'Все'],
                    ArrayHelper::map(
                        Token::find()->all(),
                        fn (Token $model) => (string) $model->_id,
                        fn (Token $model) => $model->issuerRating?->issuer .' - '.$model->name,
                ),
            ), ['class' => 'form-control']),
        ],
        [
            'label' => 'эмитент',
            'attribute' => 'token.issuerRating.issuer',
            'filter' => Html::activeDropDownList(
                $personalTokenSearchForm,
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
            'value' => function (PersonalToken $model) {
                return $model->token->rate . ' %';
            }
        ],
        [
            'label' => 'прибыль, всего',
            'value' => function (PersonalToken $model) {
                $maturityDate = new DateTimeImmutable($model->token->maturityDate);
                $timeToMaturity = $maturityDate->diff(new DateTimeImmutable());

                return round($model->token->rate * $timeToMaturity->days / 365, 1) . ' %';
            }
        ],
        [
            'label' => 'к погашению, % год',
            'format' => 'raw',
            'value' => function (PersonalToken $model) {
                $value = YieldToMaturityCalculator::calculate(
                    $model->token->denomination,
                    $model->buyPrice,
                    $model->token->rate,
                    new DateTime($model->token->maturityDate),
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
            'value' => function (PersonalToken $model) {
                $value = YieldToMaturityCalculator::calculate(
                    $model->token->denomination,
                    $model->buyPrice,
                    $model->token->rate,
                    new DateTime($model->token->maturityDate),
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
            'attribute' => 'token.denomination',
            'format' => 'raw',
            'value' => function (PersonalToken $model) {
                return Html::tag(
                    name: 'span',
                    content: round($model->token->denomination, 2) . ' руб.',
                    options: ['class' => $model->token->denomination >= $model->token->currentPrice ? 'text-success' : 'text-danger']
                );
            }
        ],
        [
            'label' => 'цена покупки',
            'attribute' => 'buyPrice',
            'format' => 'raw',
            'value' => function (PersonalToken $model) {
                return Html::tag(
                    name: 'span',
                    content: round($model->buyPrice, 2) . ' руб.',
                    options: ['class' => $model->buyPrice <= $model->token->currentPrice  ? 'text-success' : 'text-danger']
                );
            }
        ],
        [
            'label' => 'текущая цена',
            'attribute' => 'token.currentPrice',
            'format' => 'raw',
            'value' => function (PersonalToken $model) {
                return Html::tag(
                    name: 'span',
                    content: round($model->token->currentPrice, 2) . ' руб.',
                    options: ['class' => 'text-primary']
                );
            }
        ],
        [
            'label' => 'погашение',
            'value' => function (PersonalToken $model) {
                $diff = (new DateTime($model->token->maturityDate))->diff(new DateTime());
                return \Yii::$app->formatter->asRelativeTime(DateRounder::roundDateInterval($diff));
            }
        ],
        [
            'label' => 'дата покупки',
            'attribute' => 'buyDate',
        ],
        [
            'label' => 'выпуск',
            'attribute' => 'token.issueDate',
        ],
        [
            'label' => 'погашение',
            'attribute' => 'token.maturityDate',
        ],
        [
            'label' => 'объем выпуска',
            'attribute' => 'token.volumeIssued',
            'value' => function (PersonalToken $model) {
                return $model->token->volumeIssued . ' шт.';
            }
        ],
    ],
]) ?>
