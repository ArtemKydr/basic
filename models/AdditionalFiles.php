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
            [['expert'], 'file','extensions' => 'pdf, jpg,png'],
            [['review'], 'file', 'extensions' => 'pdf, jpg,png'],
            [['file_scan'], 'file','extensions' => 'pdf, jpg,png'],

        ];
    }

    public function upload()
    {
        $timestamp = date('dmYHis');
        $expert = $_FILES['AdditionalFiles']['name']['expert'];
        $expert = mb_strtolower(UploadDocumentForm::transliterate($expert));
        $review = $_FILES['AdditionalFiles']['name']['review'];
        $review =  mb_strtolower(UploadDocumentForm::transliterate($review));
        $file_scan = $_FILES['AdditionalFiles']['name']['file_scan'];
        $file_scan =  mb_strtolower(UploadDocumentForm::transliterate($file_scan));
        if ($this->validate()) {
            if ($expert!='' and $review!='' and $file_scan!='')
            {
                $this->expert->saveAs('UploadDocumentExpert/'.$timestamp.'_'. $expert,false);
                $this->file_scan->saveAs('UploadDocumentFileScan/'.$timestamp.'_'. $file_scan,false);
                $this->review->saveAs('UploadDocumentReview/'.$timestamp.'_'. $review,false);
            }else if (($expert!='' or $review!='') and $file_scan==''){
                if ($expert!=''){
                    $this->expert->saveAs('UploadDocumentExpert/'.$timestamp.'_'. $expert,false);
                }
                if ($review!=''){
                    $this->review->saveAs('UploadDocumentReview/'.$timestamp.'_'. $review,false);
                }
            }
            else if (($expert!=''or $file_scan!='') and $review==''){
                if ($file_scan!=''){
                    $this->file_scan->saveAs('UploadDocumentFileScan/'.$timestamp.'_'. $file_scan,false);
                }
                if ($expert!=''){
                    $this->expert->saveAs('UploadDocumentExpert/'.$timestamp.'_'. $expert,false);
                }
            }
            else if (($review!=''or $file_scan!='') and $expert==''){
                if ($file_scan!=''){
                    $this->file_scan->saveAs('UploadDocumentFileScan/'.$timestamp.'_'. $file_scan,false);

                }
                if ($review!=''){
                    $this->review->saveAs('UploadDocumentReview/'.$timestamp.'_'. $review,false);
                }
            }
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