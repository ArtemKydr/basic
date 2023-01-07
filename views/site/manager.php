<?php
use kartik\datetime\DateTimePicker;
use yii\grid\GridView;
use yii\helpers\Html;

$gridColumns = [
    [
        'attribute' => 'id',
        'format' => 'text',
        'label' => '№',
    ],
    [
        'attribute' => 'fio',
        'format' => 'raw',
        'label' => 'ФИО',
        'headerOptions' => ['style' => 'width:10%'],
    ],
    ['class'=>'yii\grid\ActionColumn',
        'headerOptions' => ['style' => 'width:4%'],
        'template' => '{view} {update}',],
    [
        'attribute' => 'nr',
        'format' => 'text',
        'label' => 'Н/Р',
    ],
    [
        'attribute' => 'document_status',
        'format' => 'text',
        'label' => 'Статус',
        'value' => function ($data) {
            $rusDocumentStatus = ['Send for revision'=>"Отправить на доработку",
                'Reject'=>"Отклонить",
                'Send to Print'=>"Отправить в печать",
                'In the draft'=>"В черновике",
                'The article did not pass the originality test'=>"Статья не прошла проверку на оригинальность",
                'The article was checked for originality'=>"Статья проверена на оригинальность",
                'On proofreading'=>"На вычитке",
                'For revision'=>"На доработку",
                'The article was accepted'=>"Статья принята",
                'In processing'=>"В обработке",
                'Last change'=>"Последнее изменение"];
        $documentStatus = $rusDocumentStatus[$data->document_status];

        return $documentStatus ;
    },
    ],
    [
        'attribute' => 'originality',
        'format' => 'text',
        'label' => 'Оригинальность %',
    ],
    [
        'attribute' => 'university',
        'format' => 'text',
        'label' => 'ВУЗ',
    ],
    [
        'attribute' => 'datetime',
        'format' => 'text',
        'label' => 'Дата подачи',
    ],
    [
        'attribute' => 'source',
        'label' => 'Статьи',
        'format' => 'raw',
     'value'=>function ($data) {
        return Html::a(Html::encode("$data->title"),"http://basic/web/$data->source");}
    ],
    [
        'attribute' => 'comment',
        'format' => 'text',
        'label' => 'Комментарий',
    ],


]
?>
<div class ='site-index'>
    <h2>Поданные заявки</h2>
    <div style="margin-top: 50px;">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns'=>$gridColumns,
        ]) ?>
    </div>


</div>

