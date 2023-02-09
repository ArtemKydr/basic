<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;


$gridColumns = [
    [
        'attribute' => 'authors',
        'format' => 'text',
        'label' => 'Автор',
    ],
    [
        'attribute' => 'coauthor',
        'format' => 'text',
        'label' => 'Соавтор',
    ],
    [
        'attribute' => 'title',
        'format' => 'text',
        'label' => 'Cтатьи',
    ],

    [
        'attribute' => 'nr',
        'format' => 'text',
        'label' => 'Научный руководитель',
    ],

    [
        'attribute' => 'email',
        'format' => 'text',
        'label' => 'Электронная почта',
    ],
    [
        'attribute' => 'phone',
        'format' => 'text',
        'label' => 'Номер телефона',
    ],
    [
        'attribute' => 'originality',
        'format' => 'text',
        'label' => 'Оригинальность %',
    ],
    [
        'attribute' => 'comment',
        'format' => 'text',
        'label' => 'Комментарий',
    ],
    [
        'attribute' => 'source',
        'format' => 'raw',
        'label' => 'Скачать статью',
        'value'=>function ($data) {
            return Html::a(Html::encode("$data->title"),"/web/$data->source");}

    ],


];
$css =<<<CSS
th {
width: 25%;
}
CSS;
$this->registerCss($css);
?>
<h2 style="margin-bottom: 20px">Карточка студента: <?php echo $student['fio'] ?></h2>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'options' => [ 'style' => 'table-layout:fixed;' ],
    'columns' => $gridColumns,
]) ?>
<div style="margin-top: 20px">
    <h4>Изменения</h4>
    <?php
    if(!$additional_files){
        echo 'Дополнительные файлы пока не загружены...';
    }else {
        for($i=0;$i<count($additional_files);$i++){
            if($additional_files[$i]['expert_name']!=null or $additional_files[$i]['expert_name']!=''){
                $expert_name = $additional_files[$i]['expert_name'];
                $expert_source = $additional_files[$i]['expert_source'];
                echo '<div><a href="/web/'.$expert_source.'">'.$expert_name.'</a><br>';
            }
            if($additional_files[$i]['review_name']!=null or $additional_files[$i]['review_name']!=''){
                $review_name = $additional_files[$i]['review_name'];
                $review_source = $additional_files[$i]['review_source'];
                echo '<div><a href="/web/'.$review_source.'">'.$review_name.'</a><br>';
            }
            if($additional_files[$i]['file_scan_name']!=null or $additional_files[$i]['file_scan_name']!=''){
                $file_scan_name = $additional_files[$i]['file_scan_name'];
                $file_scan_source = $additional_files[$i]['file_scan_source'];
                echo '<div><a href="/web/'.$file_scan_source.'">'.$file_scan_name.'</a><br>';
            }
        }
    }
    ?>
</div>

