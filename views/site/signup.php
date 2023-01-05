<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


?>

<h1>Регистрация</h1>
<?php $form = ActiveForm::begin() ?>
<?= $form->field($model,'email') ?>
<?= $form->field($model,'password')->passwordInput() ?>
<?= $form->field($model,'fio') ?>
<?= $form->field($model,'phone') ?>
<?= $form->field($model,'city') ?>
<?= $form->field($model,'organization') ?>
<div class="form-group">
    <div class="offset-lg-1 col-lg-11">
        <?= Html::submitButton('Регистрация', ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
