<?php

use yii\grid\GridView;
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

$rusDocumentStatus = ["The article did not pass the originality test"=>'Статья не прошла проверку на оригинальность',
    "The article has been checked for originality"=>'Статья прошла проверку на оригинальность',
    "The article does not meet the requirements"=>'Статья не соответствует требованиям',
    "The article was rejected as an incomplete set of documents"=>'Статья отклонена, так как неполный комплект документов',
    "The article has been accepted for review"=>'Статья принята к рецензированию',
    "The article has been accepted for publication"=>'Статья принята к публикации',
    "In processing"=> 'В процессе',
    "Article under consideration"=>'Статья на рассмотрении',
    "In the draft" => 'В черновике',
    null => ' ',
];
$form = ActiveForm::begin([
    'id' => 'update-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>
<h2 style="margin-bottom: 20px">Карточка студента: <?php echo $student['fio'] ?></h2>
<?= $form->field($model, 'originality') ?>
<?= $form->field($model, 'comment')->textarea()?>
<?= $form->field($model, 'document_status')->dropDownList(["The article did not pass the originality test"=>'Статья не прошла проверку на оригинальность',
    "The article has been checked for originality"=>'Статья прошла проверку на оригинальность',
    "The article does not meet the requirements"=>'Статья не соответствует требованиям',
    "The article was rejected as an incomplete set of documents"=>'Статья отклонена, так как неполный комплект документов',
    "The article has been accepted for review"=>'Статья принята к рецензированию',
    "The article has been accepted for publication"=>'Статья принята к публикации',
    "In processing"=> 'В процессе',
    "The article has been sent for Anti-plagiarism. The verification will take up to 3 days."=>'Статья отправлена на Антиплагиат. Проверка займет до 3 дней.',]);?>
<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end() ?>
<div style="margin-top: 20px">
    <h4>Изменения</h4>
    <?php
    if(!$data){
        echo 'Пока изменений нет...';
    }
    for($i=0;$i<count($data);$i++){
        if ($i == 0){
            $personal_data_early = 0;
            $document_status_change_early = 'Article under consideration';
            $comment_change_early = ' ';
        }else{
            $personal_data_early = $data[$i-1]['personal_data_status'];
            $document_status_change_early = $rusDocumentStatus[$data[$i-1]['document_status_change']];
            $comment_change_early = $data[$i-1]['comment'];

        }
        $personal_data = $data[$i]['personal_data_status'];

        $document_status_change = $rusDocumentStatus[$data[$i]['document_status_change']];

        $comment_change = $data[$i]['comment'];

        $date_change = $data[$i]['datetime'];
        $manager_fio = $data[$i]['manager_fio'];

        if($personal_data==1 and $personal_data!= $personal_data_early and $personal_data_early==null){
            echo $date_change .': Организатор ' . $manager_fio . ' принял документы "Персональные данные"'.'<br>';
        }
        else {
            $flag = 1;
        }
        if ($document_status_change != $document_status_change_early and $comment_change!=$comment_change_early) {
            if($document_status_change==null){
                echo $date_change .': Организатор ' . $manager_fio . ' оставил комментарий: '.$comment_change.'<br>';
            }else if($comment_change==null){
                echo $date_change .': Организатор ' . $manager_fio . ' изменил статус на "'.$document_status_change.'"<br>';
            } else
            {
                echo $date_change .': Организатор ' . $manager_fio . ' изменил статус на "'.$document_status_change.'", а также оставил комментарий: '.$comment_change.'<br>';
            }

        }
        else if($document_status_change == $document_status_change_early and $comment_change!=$comment_change_early){
            echo $date_change .': Организатор ' . $manager_fio . ' оставил комментарий: '.$comment_change.'<br>';
        }else if ($document_status_change != $document_status_change_early and $comment_change==$comment_change_early) {
            echo $date_change .': Организатор ' . $manager_fio . ' изменил статус на "'.$document_status_change.'"<br>';
        }

    }

    ?>
</div>

