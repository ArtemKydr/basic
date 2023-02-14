<?php

/** @var yii\web\View $this */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Личная информация';
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    [
        'class'=>'yii\grid\ActionColumn',
        'headerOptions' => ['style' => 'color:#337ab7'],
        'headerOptions' => ['style' => 'width:3%'],
        'template' => '{update}',
        'urlCreator' => function ($action, $model, $key, $index) {

            if ($action === 'update') {
                $url ='/update-personal-information?id='.$model->id;
                return $url;
            }
        }
    ],
    [
        'attribute' => 'fio',
        'format' => 'text',
        'label' => 'ФИО',
    ],
    [
        'attribute' => 'email',
        'format' => 'text',
        'label' => 'Электронная почта',
    ],
    [
        'attribute' => 'phone',
        'format' => 'text',
        'label' => 'Номер телефона',
    ],
    [
        'attribute' => 'city',
        'format' => 'text',
        'label' => 'Город',
    ],
    [
        'attribute' => 'organization',
        'format' => 'text',
        'label' => 'Организация',
    ],




];
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
<div>

</div>
<div class="personal-information">
    <div>
        При необходимости изменения личных данных необходимо написать на <b>csn@itmo.ru</b> с темой письма <i>“Изменение личной информации”.</i>
    </div>
    <div style="margin-top: 20px">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
        ]) ?>
    </div>
    <p>
        <b>Персональные данные</b><br>
        <br>
        (а) Договор необходимо заполнить, распечатать и подписать <b>в двух экземплярах</b>.<br>
        (б) Согласие о персональных данных заполнить, распечатать и подписать <b>в одном экземпляре</b>.<br>
        <br>
        Распечатанные и подписанные файлы необходимо принести в часы работы УЦСНКиВ <b>до 27 марта 2023 года</b>.<br>
        <br>
        Время приема УЦСНКиВ: 11:00 - 17:00 (пн-пт)

    </p>
    <a href="https://drive.google.com/drive/folders/13dtCYw8L_V5DcLsk8SuCD7YWu7J2EMKP"><b>Шаблон документов</b></a>

</div>
