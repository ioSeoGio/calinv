<?php

namespace app\widgets;

use lib\FlashType;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class FlashMessagesWidget extends Widget
{
    public function run()
    {
        $flashes = Yii::$app->session->getAllFlashes();

        if (empty($flashes)) {
            return false;
        }

        $htmlMessages = '';
        foreach ($flashes as $type => $flash) {
            $messages = is_array($flash) ? $flash : [$flash];
            $messageClass = match ($type) {
                FlashType::success->value => 'alert alert-success',
                FlashType::error => 'alert alert-error',
                FlashType::warning => 'alert alert-warning',
                default => 'alert',
            };

            foreach ($messages as $message) {
                $htmlMessages .= "
                    <div class=\"$messageClass\" style=\"display: none;\">
                        <div class=\"alert-message\">$message</div>
                        <div class=\"alert-point\">
                            <p>+</p>
                        </div>
                    </div>
                ";
            }
        }

        return Html::tag('div', $htmlMessages, ['class' => 'alert-wrapper']);
    }
}
