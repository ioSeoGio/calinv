<?php

use lib\FrontendHelper\Icon;
use yii\helpers\Html;
use yii\helpers\Url; ?>

<footer id="footer" class="mt-auto py-3">
	<div class="container">
		<div class="row text-muted">
			<div class="col-6 text-center text-md-start">
                <div class="row text-muted">
                    &copy; CalInv <?= date('Y') ?> - агрегатор публичной информации об эмитентах ценных бумаг Республики Беларусь
                </div>
                <div class="row text-muted">
                    CalInv и его авторы/модераторы не несут ответственности за инвестиционные решения, принятые на основании данной информации
                </div>
            </div>
			<div class="col-2 text-center text-md-start">
                <div class="col-6 mb-1">
                    <?= Html::a("телеграм " . Icon::print('bi bi-telegram'), 'https://t.me/+VkMwRJV7LqoyZmYy', ['target' => '_blank', 'class' => 'btn btn-sm btn-danger']) ?>
                </div>
                <div class="col-6">
                    <?= Html::a("справка " . Icon::printFaq(), Url::to('/faq'), ['class' => 'btn btn-sm btn-primary']) ?>
                </div>
            </div>
			<div class="col-4 text-center text-md-start">
                <div class="row text-muted">
                    Информация может быть устаревшей и/или неточной
                </div>
                <div class="row text-muted">
                    Не является инвестиционной рекомендацией
                </div>
            </div>
		</div>
	</div>
</footer>