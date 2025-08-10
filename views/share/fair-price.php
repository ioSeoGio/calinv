<?php

use kartik\slider\Slider;
use src\Entity\Issuer\Issuer;
use src\Entity\Share\Share;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var Issuer $issuer */
/** @var Share[] $shares */

$this->registerJsVar('recalculateUrl', Url::to(['share/ajax-fair-price-recalculate', 'issuerId' => $issuer->id]));
$this->registerJsFile('@web/js/fair-price/fair-price-recalculate.js');
?>

<div>
    <div class="text-center">
        <h3>Расчет справедливой цены акции</h3>
        <table id="w2" class="table table-striped table-bordered detail-view">
            <thead>
            <tr>
                <td colspan="<?= count($shares) + 1 ?>">
                    Изменяйте цену акций, коэффициенты рассчитаются из нее автоматически
                </td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($shares as $share): ?>
                <tr>
                    <td>
                        <?= \lib\FrontendHelper\DetailViewCopyHelper::renderValueColored($share->getFormattedNameWithIssuer()); ?>
                    </td>
                    <td>
                        <?php if ($share->minPrice === null || $share->maxPrice === null): ?>
                            Нет данных
                        <?php else: ?>
                            <?= Html::tag('b', $share->minPrice . ' р.', ['class' => 'badge']); ?>
                            <?= Slider::widget([
                                    'options' => [
                                        'class' => 'fair-price-slider',
                                        'data-share-id' => $share->id,
                                        'data-share-amount' => $share->totalIssuedAmount,
                                    ],
                                    'name' => 'rating_1',
                                    'value' => $share->currentPrice,
                                    'pluginOptions' => [
                                        'min' => $share->minPrice,
                                        'max' => $share->maxPrice,
                                        'step' => ($share->maxPrice - $share->minPrice) / 20, // шаг в 5% от разницы min-max
                                        'tooltip' => 'hide', // Attempt to show tooltip
                                    ],
                            ]); ?>
                            <?= Html::tag('b', $share->maxPrice . ' р.', ['class' => 'badge']); ?>
                            <div class="current-value-test">
                                <?= $share->currentPrice ?> р.
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2">
                    <?= Html::button('Рассчитать', [
                        'id' => 'recalculate-fair-price-btn',
                        'class' => 'btn btn-primary',
                    ]) ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->render('@views/coefficient/_coefficient-part', [
    'model' => $issuer,
]); ?>
