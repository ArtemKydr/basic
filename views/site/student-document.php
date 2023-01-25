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
margin-right: 0px;
}
.col-lg-offset-1.col-lg-11{
display: flex;
justify-content: start;
}
.form-group.field-uploaddocumentform-file label{
width: 25%;
margin-right: 60px;
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
.btn.btn-primary.draft {
background: grey;
margin-left: 20px;
border: none;
}
.document_status_forms{
visibility: visible;
}
CSS;
$this->registerCss($css);

$gridColumns = $grid_columns = [
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value'=>function ($data) {
            return Html::a(Html::encode("$data->title"),"http://basic/web/$data->source");},
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

]
?>
<div class="site-student-document" >
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div class="form-student-document" style="display: flex; justify-content: space-between">
        <div class="group-list" style="display: flex; justify-content: space-between;">
            <div style="margin-right: 40px">
                <div class="field-author" style="display: flex; justify-content: start; margin-bottom: 20px">
                    <div class="control-label-author" style="width: 40%">
                        Автор
                    </div>
                    <div>
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

    <div class="col-lg-offset-1 col-lg-11" style="padding: 0">
        <?= Html::submitButton('Отправить', [
                'data' => ['confirm' => 'Данный файл будет опубликован при оформлении полного комплекта документов. Прикладывайте пожалуйста итоговую версию статьи. У вас есть только 2 попытки для отправки материалов.'],
                'class' => 'btn btn-primary',
                'name'=>"action",
                'value'=>"clear"]) ?>
        <?= Html::submitButton('В черновик', [
                'class' => 'btn btn-primary draft',
                'name'=>"action", 'value'=>"draft" ]) ?>
        <div style="margin-left: 20px">
            <a href="/web/site/additional-student-document">Загрузить дополнительные документы</a>
        </div>
    </div>

    <?php ActiveForm::end() ?>
    <div style="margin-top: 50px;">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
        ]) ?>
    </div>
</div>
