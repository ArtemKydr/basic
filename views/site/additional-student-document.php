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
}
.form-student-document{
margin-top: 20px;
}
.control-label{
width: 32%;
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

?>
<div class="site-student-document" >
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div style="display: flex; justify-content: start; margin-bottom: 20px; flex-direction: column; margin-top: 40px">
        <?= $form->field($model, 'expert')->fileInput() ?>
        <?= $form->field($model, 'review')->fileInput() ?>
        <?= $form->field($model, 'file_scan')->fileInput() ?>
    </div>

    <?= Html::submitButton('Отправить', [
        'class' => 'btn btn-primary',
        'name'=>"action",
        'value'=>"clear"]) ?>

    <?php ActiveForm::end() ?>

</div>
