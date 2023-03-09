<?php

/** @var yii\web\View $this */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers;

$this->title = 'Изменение личных данных';
$this->params['breadcrumbs'][] = $this->title;
$message ='';
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
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-student-document" >
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div class="form-student-document" style="display: flex; justify-content: space-between">
        <div class="group-list" style="display: flex; justify-content: space-between;">
            <div style="margin-right: 40px">
                <h4 style="margin-top: 40px; margin-bottom: 40px"><?= Html::encode($this->title) ?></h4>
                <?= $form->field($personal_information_model, 'fio',)->textInput() ?>
                <?= $form->field($personal_information_model, 'email')->textInput() ?>
                <?= $form->field($personal_information_model, 'phone')->textInput() ?>
                <?= $form->field($personal_information_model, 'organization')->textInput() ?>
            </div>
        </div>

    </div>
    <div>
        <?= Html::submitButton('Отправить', [
            'class' => 'btn btn-primary',
            'name'=>"action",
            'value'=>"clear"]) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>
