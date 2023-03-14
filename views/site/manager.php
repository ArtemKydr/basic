<?php
use kartik\datetime\DateTimePicker;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\TabularForm;
use kartik\export\ExportMenu;

$css =<<<CSS

.form-check{
margin-bottom: 100%;
}
.dropdown-item{
display: block !important;
}
.kv-align-top{
vertical-align: middle !important;
}
.select-on-check-all{
display: none;
}
.kv-all-select.kv-align-center.kv-align-middle.skip-export{
display: none;
}
.skip-export.kv-align-center.kv-align-middle.w5.kv-row-select{
display: none;
}
.checkbox-count{
width: 20px;
height: 20px;
}
.col-lg-offset-1.col-lg-11{
width: 100%;
display: flex;
justify-content: end;
margin: 0;
}
.btn.btn-search {
position: relative;
background: gray;
color:white;
top: 130px;
right: -177px;
}
.form-control-plaintext{
font-size: 14px;
max-width: 200px;
}
.skip-export.kv-align-center.kv-align-middle.w6.kv-row-select{
display: none;
}
.kv-panel-after{
position: fixed;
top: 50%;
right: 180px;
padding: 0;
}
.form-group.highlight-addon.field-documents-count_additional_document{
margin-top: -32px;
}

CSS;
$this->registerCss($css);
$gridColumns = [
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value'=>function ($data) {
            return Html::a(Html::encode("$data->title"),"/web/$data->source");},
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
        'value' => function ($data) {

            $rusCollection = ["Almanac"=>'Альманах',
            ];
            $documentCollection = $rusCollection[$data->collection];

            return $documentCollection ;
        },
    ],
    [
        'attribute' => 'document_status',
        'format' => 'text',
        'label' => 'Статус',
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
    [
        'attribute' => 'organization',
        'format' => 'text',
        'label' => 'ВУЗ',
    ],
    [
        'attribute' => 'datetime',
        'format' => 'text',
        'label' => 'Дата',
    ],


];

$this->title = 'Поданные заявки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class ='site-index'>
    <div class="top" style="display: flex; justify-content: space-between">
        <h1>Поданные заявки</h1>
    </div>
    <div>
        <h5><b>Выгрузка</b></h5>
        <?php
        echo ExportMenu::widget(['dataProvider' => $dataProvider,
            'dropdownOptions' => [
                'label' => 'Формат',
                            ],
            'columnSelectorOptions' => [
                'label' => 'Колонки',
                            ],
            'columns' => $gridColumns,

        ]);
        ?>
        <div style="margin-top: -92px">
            <?php $searchForm = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Поиск', [
                    'class' => 'btn btn-search',
                    'name'=>"action",
                    'value'=>"search"]) ?>
            </div>
            <div style="display: flex; margin-top: 60px; justify-content: end">
                <div style="margin-right: 40px;width: 30%">
                    <?= $searchForm->field($model, 'fio')->textInput() ?>
                </div>
                <div style="margin-right: 40px;width: 30%">
                    <?= $searchForm->field($model, 'title')->textInput() ?>
                </div>
                <div style="margin-right: 40px;width: 30%">
                    <?= $searchForm->field($model, 'document_status',['enableClientValidation' => false])->dropDownList(["-1"=>'Не имеет значения',"The article did not pass the originality test"=>'Статья не прошла проверку на оригинальность',
                        "The article has been checked for originality"=>'Статья прошла проверку на оригинальность',
                        "The article does not meet the requirements"=>'Статья не соответствует требованиям',
                        "The article was rejected as an incomplete set of documents"=>'Статья отклонена, так как неполный комплект документов',
                        "The article has been accepted for review"=>'Статья принята к рецензированию',
                        "The article has been accepted for publication"=>'Статья принята к публикации',
                        "In processing"=> 'В процессе',
                        "Article under consideration"=>'Статья на рассмотрении',]);?>
                </div>
                <div style="margin-right: 40px;width: 24%;">
                    <p>Доп.док-ты</p>
                    <?= $searchForm->field($model, 'count_additional_document',['enableClientValidation' => false])->dropDownList(["-1"=>'Не имеет значения',"3"=>'Всё',
                        "2"=>'Не все',]);?>
                </div>
            </div>

            <?php ActiveForm::end() ?>
        </div>
    </div>
    <div style="margin-top: 50px; width: 120%;margin-left: -120px">
        <?php
        $form = ActiveForm::begin();
        $attribs = $model->id;
        unset($attribs['attributes']['color']);
        $attribs['fio'] = [
            'type'=>TabularForm::INPUT_STATIC,
            'widgetClass'=>\kartik\widgets\SwitchInput::classname()
        ];

        $attribs['personal_data'] = [
            'type'=>TabularForm::INPUT_CHECKBOX,
            'widgetClass'=>\kartik\widgets\SwitchInput::classname()
        ];
        $attribs['nr'] = [
            'type'=>TabularForm::INPUT_STATIC,
            'widgetClass'=>\kartik\widgets\SwitchInput::classname()
        ];
        $attribs['document_status'] = [
            //'type'=>TabularForm::INPUT_DROPDOWN_LIST,
            'type'=>function ($data) {
            if ($data->draft_status!='draft'){
                return TabularForm::INPUT_DROPDOWN_LIST;
            }else {
                return TabularForm::INPUT_STATIC;
            }
                },
            'items'=>["The article did not pass the originality test"=>'Статья не прошла проверку на оригинальность',
                "The article has been checked for originality"=>'Статья прошла проверку на оригинальность',
                "The article does not meet the requirements"=>'Статья не соответствует требованиям',
                "The article was rejected as an incomplete set of documents"=>'Статья отклонена, так как неполный комплект документов',
                "The article has been accepted for review"=>'Статья принята к рецензированию',
                "The article has been accepted for publication"=>'Статья принята к публикации',
                "In processing"=> 'В процессе',
                "Article under consideration"=>'Статья на рассмотрении',
            ],
            'widgetClass'=>\kartik\widgets\SwitchInput::classname(),
        ];
        $attribs['originality']['originality'] = [
            'type'=>TabularForm::INPUT_WIDGET,
            'widgetClass'=>\kartik\widgets\SwitchInput::classname(),
        ];
        $attribs['datetime'] = [
            'type'=>TabularForm::INPUT_STATIC,
            'widgetClass'=>\kartik\widgets\SwitchInput::classname(),

        ];
        $attribs['count_additional_document'] = [
            'type'=>TabularForm::INPUT_STATIC,
            'widgetClass'=>\kartik\widgets\SwitchInput::classname(),
            'value'=>function ($data) {
                if ($data->count_additional_document==3){
                    return '<div><img src="/web/images/checkmark_green.png" class="checkbox-count"/></div>';
                }else{
                    return '<div></div>';
                }
            },
        ];
        $attribs['source'] = [
            'type'=>TabularForm::INPUT_STATIC,
            'value'=>function ($data) {
                return Html::a(Html::encode("$data->title"),"/web/$data->source");},
        ];
        $attribs['comment']['comment'] = [
            'type'=>TabularForm::INPUT_WIDGET,
            'widgetClass'=>\kartik\widgets\SwitchInput::classname()
        ];


        echo TabularForm::widget([
            'dataProvider'=>$dataProvider,
            'form'=>$form,
            'actionColumn'=>[
    'class' => '\kartik\grid\ActionColumn',
    'deleteOptions' => ['style' => 'display:none !important;'],
                'updateOptions' => ['style' => 'display:inline;','label'=>'Изменить'],
    'width' => '60px',
                'dropdown'=>true,
                'viewOptions'=>['label'=>'Просмотр']
],
            'attributes'=>$attribs,
            'gridSettings'=>[
                'condensed'=>true,
                'floatHeader'=>true,
                'panel'=>[
                    'before' => false,
                    'after'=>
                        Html::submitButton('<i class="fas fa-save"></i> Сохранить', ['class'=>'btn btn-primary'])
                ]
            ]
        ]);
        ActiveForm::end();
       ?>

    </div>


</div>

