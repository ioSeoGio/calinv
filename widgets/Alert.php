<?php
namespace app\widgets;

use Yii;
use yii\bootstrap5\Html;
use yii\bootstrap5\Widget;

class Alert extends Widget
{
    public array $alertTypes = [
        'error'   => 'alert-error',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];

    public function run(): void
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        if ($flashes) {
            $content = '';
            foreach ($flashes as $type => $flash) {
                if (!isset($this->alertTypes[$type])) {
                    continue;
                }

                foreach (json_decode($flash) as $i => $messages) {
                    foreach ($messages as $message) {
                        $content .= \yii\bootstrap5\Alert::widget([
                            'body' => $message,
                            'options' => array_merge($this->options, [
                                'id' => $this->getId() . '-' . $type . '-' . $i,
                                'class' => $this->alertTypes[$type] . $appendClass,
                            ]),
                        ]);
                    }
                }

                $session->removeFlash($type);
            }

            echo Html::tag('div', $content, ['class' => 'alert-wrapper']);
        }
    }
}
