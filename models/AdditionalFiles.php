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