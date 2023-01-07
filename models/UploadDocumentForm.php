<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadDocumentForm extends Model
{
/**
* @var UploadedFile
*/
    public $file;
    public $authors;
    public $university;
    public $name;
    public $nr;
    public $title;
    public $fio;
    public $organization;
    public $city;
    public $email;
    public $phone;

    public function rules()
    {
        return [
            [['title','fio','organization','city','email','phone','file'],'required','message'=>'*']
        ];
    }

    public function upload()
    {
            $name = strtolower($this->file->baseName);
            $this->file->saveAs('UploadDocument/' . $this->file->baseName . '.' . $this->file->extension);
            return true;
    }
    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'fio'=>'ФИО',
            'phone'=>'Телефон',
            'city'=>'Город',
            'organization'=>'Организация',
            'nr'=>'Научный руководитель',
            'title'=>'Название статьи',
            'authors'=>'Автор',
            'university'=>'ВУЗ',
            'file'=>'Статья'

        ];
    }
}