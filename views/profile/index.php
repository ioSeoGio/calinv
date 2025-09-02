<?php

use app\assets\AppAsset;
use lib\FrontendHelper\DetailViewCopyHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \src\Entity\User\User $model */

$this->title = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("var csrfParam = '" . Html::encode(Yii::$app->request->csrfParam) . "';");
$this->registerJs("var csrfToken = '" . Html::encode(Yii::$app->request->csrfToken) . "';");
$this->registerJs("var togglePortfolioUrl = '" . Html::encode(Url::to(['/profile/toggle-visibility'])) . "';");

$this->registerJs(<<<JS
$(document).ready(function() {
    $('#portfolio-toggle').on('change', function() {
        const isVisible = $(this).is(':checked');
        var data = {};
        data[csrfParam] = csrfToken;
        data.visible = isVisible;
        $.ajax({
            url: togglePortfolioUrl,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    flashMessage('success', 'Портфель ' + (isVisible ? 'показан' : 'скрыт') + '!');
                    $("#portfolio-toggle-text").text('Портфель ' + (isVisible ? 'показан' : 'скрыт'));
                } else {
                    flashMessage('error', 'Ошибка при обновлении статуса портфеля.');
                    $('#portfolio-toggle').prop('checked', !isVisible); // Revert toggle on error
                }
            },
            error: function() {
                flashMessage('error', 'Ошибка соединения с сервером.');
                $('#portfolio-toggle').prop('checked', !isVisible); // Revert toggle on error
            }
        });
    });
});
JS
); ?>

<div class="site-profile">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2><?= Html::encode($this->title) ?></h2>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">Логин:</dt>
                            <dd class="col-sm-8">
                                <?= DetailViewCopyHelper::renderValueColored(Html::encode($model->username)) ?>
                            </dd>

                            <dt class="col-sm-4">Электронная почта:</dt>
                            <dd class="col-sm-8">
                                <?= DetailViewCopyHelper::renderValueColored(Html::encode($model->email)) ?>
                            </dd>

                            <dt class="col-sm-4">Дата регистрации:</dt>
                            <dd class="col-sm-8">
                                <?= DetailViewCopyHelper::renderValueColored(
                                        Yii::$app->formatter->asDate($model->created_at, 'long')
                                ) ?>
                            </dd>
                            <dt class="col-sm-4">Показать портфель:</dt>
                            <dd class="col-sm-8">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="portfolio-toggle"
                                            <?= $model->isPortfolioPublic ? 'checked' : '' ?>>
                                    <label id="portfolio-toggle-text" class="form-check-label" for="portfolio-toggle">
                                        <?= $model->isPortfolioPublic ? 'Портфель виден' : 'Портфель скрыт' ?>
                                    </label>
                                </div>
                            </dd>
                        </dl>
                    </div>
                    <div class="card-footer text-end">
                        <?= Html::a('Редактировать', ['profile/edit'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>