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
        $review = $_FILES['AdditionalFiles']['name']['review'];
        $file_scan = $_FILES['AdditionalFiles']['name']['file_scan'];
        $model = new AdditionalFiles();
        if (Yii::$app->request->isPost) {
            if ($expert!='' and $review!='' and $file_scan!='')
            {
                $this->expert->saveAs('UploadDocumentExpert/' . $this->expert->baseName . '.' . $this->expert->extension);
                $this->review->saveAs('UploadDocumentReview/' . $this->review->baseName . '.' . $this->review->extension);
                $this->file_scan->saveAs('UploadDocumentFileScan/' . $this->file_scan->baseName . '.' . $this->file_scan->extension);
            }else if ($expert!='' or $review!='' and $file_scan==''){
                if ($expert!=''){
                    $this->expert->saveAs('UploadDocumentExpert/' . $this->expert->baseName . '.' . $this->expert->extension);
                }
                if ($review!=''){
                    $this->review->saveAs('UploadDocumentReview/' . $this->review->baseName . '.' . $this->review->extension);
                }
            }
            else if ($expert!=''or $file_scan!='' and $review==''){
                if ($file_scan!=''){
                    $this->file_scan->saveAs('UploadDocumentFileScan/' . $this->file_scan->baseName . '.' . $this->file_scan->extension);
                }
                if ($review!=''){
                    $this->review->saveAs('UploadDocumentReview/' . $this->review->baseName . '.' . $this->review->extension);
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
            'expert'=>'Экспертное заключение',
            'review'=>'Рецензия',
            'file_scan'=>'Файл статьи с подписями',

        ];
    }
}