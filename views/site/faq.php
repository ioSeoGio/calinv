<?php
use yii\bootstrap5\Html;
use yii\bootstrap5\BootstrapAsset;

$this->title = 'Справочник сокращений';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container my-5">
    <h1 class="display-4 mb-4"><?= Html::encode($this->title) ?></h1>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 mb-0">Справочник</h2>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Сокращение</th>
                        <th scope="col">Расшифровка</th>
                        <th scope="col">Описание</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>ОПиУ</strong></td>
                        <td>Отчет о прибылях и убытках</td>
                        <td>Финансовый документ, отражающий доходы, расходы и чистую прибыль (убыток) компании за определенный период.</td>
                    </tr>
                    <tr>
                        <td><strong>ОББ</strong></td>
                        <td>Отчет о бухгалтерском балансе</td>
                        <td>Документ, показывающий финансовое состояние компании на определенную дату, включая активы, обязательства и капитал.</td>
                    </tr>
                    <tr>
                        <td><strong>ОДДС</strong></td>
                        <td>Отчет о движении денежных средств</td>
                        <td>Отчет, отражающий поступления и выбытия денежных средств компании за период, разделенные на операционную, инвестиционную и финансовую деятельность.</td>
                    </tr>
                    <tr>
                        <td><strong>ДС</strong></td>
                        <td>Денежные средства</td>
                        <td>Наличные деньги, средства на банковских счетах и другие ликвидные активы, доступные для немедленного использования.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
