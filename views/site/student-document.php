<?php

/** @var yii\web\View $this */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers;

$this->title = 'Загрузка документов';
$this->params['breadcrumbs'][] = $this->title;
$visible = 'visible';
$message ='';
if ($count_clear_document>=2){
    $visible = 'hidden';
    $message = 'Извините, количество документов, не прошедших оригинальность больше или равно двух. Прием документов на данный конкурс закрыт';
}
$css =<<<CSS
.form-group {
display: flex;
justify-content: space-between;
width: 140%;
}
.form-student-document{
justify-content: space-between;
margin-top: 20px;
}
.control-label{
width: 60%;
margin-right: 0px;
}
.col-lg-offset-1.col-lg-11{
display: flex;
justify-content: start;
}
.form-group.field-uploaddocumentform-file label{
width: 25%;
margin-right: 84px;
}
.form-group.field-uploaddocumentform-review label{
width: 40%;
}
.form-group.field-uploaddocumentform-expert label{
width: 40%;
}
.form-group.field-uploaddocumentform-file_scan label{
width: 40%;
}
.group-list{
width: 50%;
}
.help-block{
    color:red;
    margin-left: 5px;
}
.form-group.field-uploaddocumentform-file.required{
display: flex;
justify-content: start;
}
.btn.btn-primary.draft {
background: grey;
margin-left: 20px;
border: none;
visibility: $visible;
}
.btn.btn-primary{
visibility: $visible;
}
.help-block{
width: 20%;
}
.document_status_forms{
visibility: visible;
}
.additional-documents{
visibility: $visible;
}
CSS;
$this->registerCss($css);

$gridColumns = $grid_columns = [
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value'=>function ($data) {
            return Html::a(Html::encode("$data->title"),"/web/$data->source");},
        'label' => 'Название',

    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value'=>function ($data) {
            return Html::a(Html::encode("Загрузить"),"/additional-student-document?id=$data->id");},
        'label' => 'Комплект док-ов',

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
        'value' => function ($data) {

            $rusCollection = ["Almanac"=>'Альманах',
            ];
            $documentCollection = $rusCollection[$data->collection];

            return $documentCollection ;
        },
    ],
    [
        'attribute' => 'document_status',
        'format' => 'text',
        'label' => 'Статус',
        'headerOptions' => ['style' => 'width:16%'],
        'value' => function ($data) {

            $rusDocumentStatus = ["The article did not pass the originality test"=>'Статья не прошла проверку на оригинальность',
                "The article has been checked for originality"=>'Статья прошла проверку на оригинальность',
                "The article does not meet the requirements"=>'Статья не соответствует требованиям',
                "The article was rejected as an incomplete set of documents"=>'Статья отклонена, так как неполный комплект документов',
                "The article has been accepted for review"=>'Статья принята к рецензированию',
                "The article has been accepted for publication"=>'Статья принята к публикации',
                "In processing"=> 'В процессе',
                "Article under consideration"=>'Статья на рассмотрении',
                "In the draft" => 'В черновике'
            ];
            $documentStatus = $rusDocumentStatus[$data->document_status];

            return $documentStatus ;
        },
    ],
    [
        'attribute' => 'comment',
        'format' => 'text',
        'label' => 'Комментарий организатора',
    ],

];
?>
<div class="site-student-document" >
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div class="form-student-document" style="display: flex; justify-content: space-between">
        <div class="group-list" style="display: flex; justify-content: space-between;">
            <div style="margin-right: 40px">
                <div class="field-author" style="display: flex; justify-content: start; margin-bottom: 20px">
                    <div class="control-label-author" style="width: 57%; margin-right: 80px">
                        Автор
                    </div>
                    <div style="width: 380px; text-align: start">
                        <?php echo $username[0]?>
                    </div>
                </div>
                <?= $form->field($model, 'title')->textInput() ?>
                <?= $form->field($model, 'coauthor')->textInput() ?>
                <?= $form->field($model, 'nr')->textInput() ?>
                <?= $form->field($model, 'university')->textInput() ?>
                <?= $form->field($model, 'file')->fileInput() ?>
            </div>
            <div>
            </div>

        </div>
    </div>

    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Отправить', [
                'data' => ['confirm' => 'Данный файл будет опубликован при оформлении полного комплекта документов.
Прикладывайте, пожалуйста, итоговую версию статьи.
Для проверки статьи на оригинальность дается только 2 попытки.
'],
                'class' => 'btn btn-primary',
                'name'=>"action",
                'value'=>"clear"]) ?>
        <?= Html::submitButton('В черновик', [
                'class' => 'btn btn-primary draft',
                'name'=>"action", 'value'=>"draft" ]) ?>
    </div>

    <?php ActiveForm::end() ?>
    <div style="margin-top: 50px;">
        <?php echo 'Количество статей, не прошедших проверку на оригинальность: '.$count_clear_document.'<br>'.$message ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
        ]) ?>
    </div>
</div>
