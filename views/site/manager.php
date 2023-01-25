<?php
use kartik\datetime\DateTimePicker;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\TabularForm;

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
.skip-export.kv-align-center.kv-align-middle.w1.kv-row-select{
display: none;
width: 0;
}


CSS;
$this->registerCss($css);

?>
<div class ='site-index'>
    <div class="top" style="display: flex; justify-content: space-between">
        <h2>Поданные заявки</h2>
    </div>
    <div style="margin-top: 50px;">
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
            'columnOptions'=>['width'=>'185px']
        ];
        $attribs['originality']['originality'] = [
            'type'=>TabularForm::INPUT_WIDGET,
            'widgetClass'=>\kartik\widgets\SwitchInput::classname(),
        ];
        $attribs['university'] = [
            'type'=>TabularForm::INPUT_STATIC,
            'widgetClass'=>\kartik\widgets\SwitchInput::classname()
        ];
        $attribs['datetime'] = [
            'type'=>TabularForm::INPUT_STATIC,
            'widgetClass'=>\kartik\widgets\SwitchInput::classname()
        ];
        $attribs['source'] = [
            'type'=>TabularForm::INPUT_STATIC,
            'columnOptions'=>['width'=>'150px',],
            'value'=>function ($data) {
                return Html::a(Html::encode("$data->title"),"http://basic/web/$data->source");},

        ];
        $attribs['comment']['comment'] = [
            'type'=>TabularForm::INPUT_WIDGET,
            'widgetClass'=>\kartik\widgets\SwitchInput::classname()
        ];

        echo TabularForm::widget([
            'dataProvider'=>$dataProvider,
            'form'=>$form,
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

