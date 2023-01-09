<?php

/** @var yii\web\View $this */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers;

$this->title = 'Загрузка документов';
$this->params['breadcrumbs'][] = $this->title;
$css =<<<CSS
.form-group {
display: flex;
justify-content: space-between;
}
.form-student-document{
justify-content: space-between;
margin-top: 20px;
}
.control-label{
width: 60%;
margin-right: 20px;
}
.col-lg-offset-1.col-lg-11{
display: flex;
justify-content: start;
}
.form-group.field-uploaddocumentform-file label{
width: 25%;
margin-right: 60px;
}
.group-list{
width: 50%;
margin-right: 60px;
}
.help-block{
    color:red;
    margin-left: 5px;
}
CSS;
$this->registerCss($css);

$gridColumns = $grid_columns = [
    [
        'attribute' => 'title',
        'format' => 'text',
        'label' => 'Название',
    ],
    [
        'attribute' => 'authors',
        'format' => 'text',
        'label' => 'Авторы',
    ],[
        'attribute' => 'nr',
        'format' => 'text',
        'label' => 'Н/Р',
    ],[
        'attribute' => 'university',
        'format' => 'text',
        'label' => 'ВУЗ',
    ],[
        'attribute' => 'collection',
        'format' => 'text',
        'label' => 'Сборник',
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
        'attribute' => 'comment',
        'format' => 'text',
        'label' => 'Комментарий организатора',
    ],

]
?>
<div class="site-student-document">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div class="form-student-document" style="display: flex">
        <div class="group-list">
            <?= $form->field($model, 'title')->textInput() ?>
            <?= $form->field($model, 'authors')->textInput() ?>
            <?= $form->field($model, 'coauthor')->textInput() ?>
            <?= $form->field($model, 'nr')->textInput() ?>
            <?= $form->field($model, 'university')->textInput() ?>
            <?= $form->field($model, 'file')->fileInput() ?>
        </div>
        <div class="group-list">
            <?= $form->field($model, 'fio')->textInput() ?>
            <?= $form->field($model, 'organization')->textInput() ?>
            <?= $form->field($model, 'city')->textInput() ?>
            <?= $form->field($model, 'email')->textInput() ?>
            <?= $form->field($model, 'phone')->textInput() ?>
        </div>
    </div>

    <div class="col-lg-offset-1 col-lg-11" style="padding: 0">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end() ?>
    <div style="margin-top: 50px;">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
        ]) ?>
    </div>
</div>
