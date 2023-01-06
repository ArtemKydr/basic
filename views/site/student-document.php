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
.btn.btn-primary::after{
    content: attr(data-tooltip); /* Главная часть кода, определяющая содержимое всплывающей подсказки */
	margin-top: -24px;
	opacity: 0; /* Наш элемент прозрачен... */
	padding: 3px 7px;
	position: absolute;
	visibility: hidden; /* ...и скрыт. */
	transition: all 0.4s ease-in-out; /* Добавить плавности по вкусу */
}
.btn.btn-primary::after:hover{
    opacity: 1; /* Показываем его */
	visibility: visible;
}
.help-block{
    color:red;
    margin-left: 5px;
}
.btn.btn-primary.draft {
border: none;
background: gray;
margin-left: 20px;
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

    <div class="col-lg-offset-1 col-lg-11" data-tooltip="I'm small tooltip. Don't kill me!" style="padding: 0">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('В черновик', ['class' => 'btn btn-primary draft']) ?>
    </div>

    <?php ActiveForm::end() ?>
    <div style="margin-top: 50px;">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
        ]) ?>
    </div>
</div>
