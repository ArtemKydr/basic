<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;


$gridColumns = [
    [
        'attribute' => 'authors',
        'format' => 'text',
        'label' => 'Автор',
    ],
    [
        'attribute' => 'coauthor',
        'format' => 'text',
        'label' => 'Соавтор',
    ],
    [
        'attribute' => 'title',
        'format' => 'text',
        'label' => 'Cтатьи',
    ],

    [
        'attribute' => 'nr',
        'format' => 'text',
        'label' => 'Научный руководитель',
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
        'attribute' => 'originality',
        'format' => 'text',
        'label' => 'Оригинальность %',
    ],
    [
        'attribute' => 'comment',
        'format' => 'text',
        'label' => 'Комментарий',
    ],
    [
        'attribute' => 'source',
        'format' => 'raw',
        'label' => 'Скачать статью',
        'value'=>function ($data) {
            return Html::a(Html::encode("$data->title"),"http://basic/web/$data->source");}

    ],


];
$css =<<<CSS
th {
width: 25%;
}
CSS;
$this->registerCss($css);
?>
<h2 style="margin-bottom: 20px">Карточка студента</h2>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
]) ?>

