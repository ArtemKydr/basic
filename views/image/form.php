<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var \app\models\image\form\UploadForm $model
 */

$form = ActiveForm::begin([
    'id' => 'image-form',
]) ?>
<?= $form->field($model, 'images[]')->fileInput(['multiple' => true])?>


<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
