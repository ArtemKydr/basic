<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$css =<<<CSS
.form-group.field-documents-originality.required input{
width: 5%;
}
.form-control{
width: 50%;
}
CSS;
$this->registerCss($css);
$form = ActiveForm::begin([
    'id' => 'update-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>
<h2 style="margin-bottom: 20px">Карточка студента</h2>
<?= $form->field($model, 'originality') ?>
<?= $form->field($model, 'comment')?>
<?= $form->field($model, 'document_status')->dropDownList(['Send for revision'=>"Отправить на доработку",
    'Reject'=>"Отклонить",
    'Send to Print'=>"Отправить в печать",
    'In the draft'=>"В черновике",
    'The article did not pass the originality test'=>"Статья не прошла проверку на оригинальность",
    'The article was checked for originality'=>"Статья проверена на оригинальность",
    'On proofreading'=>"На вычитке",
    'For revision'=>"На доработку",
    'The article was accepted'=>"Статья принята",
    'In processing'=>"В обработке",
    'Last change'=>"Последнее изменение"]);?>
<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end() ?>

