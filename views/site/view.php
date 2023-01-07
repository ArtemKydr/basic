<?php

use yii\widgets\DetailView;

$gridColumns = [
    [
        'attribute' => 'authors',
        'format' => 'text',
        'label' => 'Авторы',
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
        'format' => 'text',
        'label' => 'Скачать статью',
    ],


]
?>
<h2 style="margin-bottom: 20px">Карточка студента</h2>
<?php
echo DetailView::widget([
    'model' => $model,
    'attributes' => $gridColumns
]);
?>
<a href="$gridColumns[attribute]">Скачать</a>
