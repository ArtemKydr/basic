<?php

/** @var yii\web\View $this */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers;

$this->title = 'Загрузка дополнительных документов';
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
.help-block{
width: 20%;
}
.form-control{
width: 48%;
}
CSS;
$this->registerCss($css);

?>
<div class="site-student-document" >
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div style="display: flex; justify-content: start; margin-bottom: 20px; flex-direction: column; margin-top: 40px">
        <?= $form->field($document_model, 'title')->textInput() ?>
    </div>
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
    <h4 style="margin-top: 20px">Загруженные документы</h4>
    <?php
    if(!$additional_files){
        echo 'Дополнительные файлы пока не загружены...';
    }else {
        for($i=0;$i<count($additional_files);$i++){
            if($additional_files[$i]['expert_name']!=null or $additional_files[$i]['expert_name']!=''){
                $expert_name = $additional_files[$i]['expert_name'];
                $expert_source = $additional_files[$i]['expert_source'];
                echo 'Экcпертное заключение: '.'<a href="/web/'.$expert_source.'">'.$expert_name.'</a><br>';
            }
            if($additional_files[$i]['review_name']!=null or $additional_files[$i]['review_name']!=''){
                $review_name = $additional_files[$i]['review_name'];
                $review_source = $additional_files[$i]['review_source'];
                echo 'Рецензия: '.'<a href="/web/'.$review_source.'">'.$review_name.'</a><br>';
            }
            if($additional_files[$i]['file_scan_name']!=null or $additional_files[$i]['file_scan_name']!=''){
                $file_scan_name = $additional_files[$i]['file_scan_name'];
                $file_scan_source = $additional_files[$i]['file_scan_source'];
                echo 'Файл статьи с подписями: '.'<a href="/web/'.$file_scan_source.'">'.$file_scan_name.'</a><br>';
            }
        }
    }
    ?>
</div>
