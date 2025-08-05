<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\FlashMessagesWidget;
use app\widgets\YandexMetricsWidget;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$darkThemeEnabled = Yii::$app->request->cookies->getValue('darkTheme', false);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100" <?= $darkThemeEnabled ? 'data-bs-theme="dark"' : '' ?>>
<head>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
    <?= YandexMetricsWidget::widget() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header" class="container-fluid pb-5">
	<?= $this->render('nav'); ?>
</header>

<main id="main" class="wrap" role="main">
	<div class="container-fluid pt-4">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'],
                'homeLink' => isset($this->params['breadcrumbs.homeLink']) ? $this->params['breadcrumbs.homeLink'] : null,
            ]) ?>
        <?php endif ?>
        <?= $content ?>
        <?= FlashMessagesWidget::widget() ?>
	</div>
</main>

<?= $this->render('_footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
