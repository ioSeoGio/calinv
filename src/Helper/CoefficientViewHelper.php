<?php

namespace src\Helper;

use app\models\IssuerRating\IssuerRating;
use yii\bootstrap5\Html;

class CoefficientViewHelper
{
    public static function execute(IssuerRating $model, string $coefficientName): string
    {
        $values = '';
        $standardPropertyName = "{$coefficientName}_standard";
        $standardValue = $model->$standardPropertyName;

        foreach ($model->getIndicator() as $indicator) {
            $coefficientValue = $indicator[$coefficientName] ?? null;

            if ($coefficientValue !== null) {
                if ($coefficientName === 'k3') {
                    $class = $coefficientValue <= $standardValue ? 'text-success' : 'text-danger';
                } else {
                    $class = $coefficientValue >= $standardValue ? 'text-success' : 'text-danger';
                }

                $values .= Html::tag('div', round($coefficientValue, 2), ['class' => $class]);
            } else {
                $values .= Html::tag('div', 'Не задано', ['class' => 'text-primary']);
                break;
            }

        }

        return Html::tag('div', $standardValue, ['class' => 'text-primary']) . $values;
    }
}
