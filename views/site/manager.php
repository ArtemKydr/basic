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
        'headerOptions' => ['style' => 'width:12%'],
    ],
    ['class'=>'yii\grid\ActionColumn',
        'headerOptions' => ['style' => 'width:4%'],
        'template' => '{update} {view}',
        'buttons'=>
            [
                'update' => function ($url, $query, $key) {
                    return $query->draft_status =='draft' ? '': Html::a('<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"/></svg>', $url);
                }
            ],
    ],
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
            $rusDocumentStatus = ["The article did not pass the originality test"=>'Статья не прошла проверку на оригинальность',
                "The article has been checked for originality"=>'Статья прошла проверку на оригинальность',
                "The article does not meet the requirements"=>'Статья не соответствует требованиям',
                "The article was rejected as an incomplete set of documents"=>'Статья отклонена, так как неполный комплект документов',
                "The article has been accepted for review"=>'Статья принята к рецензированию',
                "The article has been accepted for publication"=>'Статья принята к публикации',
                "In processing"=> 'В процессе',
                "In the draft"=>'В черновике',
                "Article under consideration"=>'Статья отправлена на Антиплагиат. Проверка займет до 3 дней.',
                ];
        $documentStatus = $rusDocumentStatus[$data->document_status];

        return $documentStatus ;
    },
    ],
    [
        'attribute' => 'originality',
        'format' => 'raw',
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
    <div class="top" style="display: flex; justify-content: space-between">
        <h2>Поданные заявки</h2>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
    <div style="margin-top: 50px;">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns'=>$gridColumns,
        ]) ?>
    </div>


</div>

