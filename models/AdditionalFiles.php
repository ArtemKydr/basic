<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class AdditionalFiles extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $expert;
    public $review;
    public $file_scan;

    public function rules()
    {
        return [

        ];
    }

    public function upload()
    {
        $expert = $_FILES['AdditionalFiles']['name']['expert'];
        $expert = mb_strtolower(UploadDocumentForm::transliterate($expert));
        $review = $_FILES['AdditionalFiles']['name']['review'];
        $review =  mb_strtolower(UploadDocumentForm::transliterate($review));
        $file_scan = $_FILES['AdditionalFiles']['name']['file_scan'];
        $file_scan =  mb_strtolower(UploadDocumentForm::transliterate($file_scan));
        $model = new AdditionalFiles();
        if (Yii::$app->request->isPost) {
            if ($expert!='' and $review!='' and $file_scan!='')
            {
                $this->expert->saveAs('UploadDocumentExpert/' . $expert);
                $this->review->saveAs('UploadDocumentReview/' . $review);
                $this->file_scan->saveAs('UploadDocumentFileScan/' . $file_scan);
            }else if ($expert!='' or $review!='' and $file_scan==''){
                if ($expert!=''){
                    $this->expert->saveAs('UploadDocumentExpert/' . $expert);
                }
                if ($review!=''){
                    $this->review->saveAs('UploadDocumentReview/' . $review);
                }
            }
            else if ($expert!=''or $file_scan!='' and $review==''){
                if ($file_scan!=''){
                    $this->file_scan->saveAs('UploadDocumentFileScan/' . $file_scan);
                }
                if ($review!=''){
                    $this->review->saveAs('UploadDocumentReview/' . $review);
                }
            }
        }
        if ($this->validate()) {
            return true;
        } else {
            return false;
        }
    }
    public function attributeLabels()
    {
        return [
            'expert'=>'Экспертное заключение (pdf, jpg,png)',
            'review'=>'Рецензия (pdf, jpg,png)',
            'file_scan'=>'Файл статьи с подписями (pdf, jpg,png)',

        ];
    }
}