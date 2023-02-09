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
    public $coauthor;


    public function rules()
    {
        return [
            [['title','fio','organization','city','email','phone','file'],'required','message'=>'*']
        ];
    }

    public function upload()
    {
        $name = mb_strtolower($this->file->baseName);
        $name = self::transliterate($name);
            $this->file->saveAs('UploadDocument/' . $name . '.' . $this->file->extension);
            return true;
    }
    public function attributeLabels()
    {
        return [
            'email' => 'Доп. электронная почта',
            'fio'=>'ФИО',
            'phone'=>'Телефон',
            'city'=>'Город',
            'organization'=>'Организация',
            'nr'=>'Научный руководитель',
            'title'=>'Название статьи',
            'authors'=>'Автор',
            'university'=>'ВУЗ',
            'file'=>"Статья      (docx)",
            'coauthor'=>'Соавторы',
            'draft'=>'Черновик',
            'expert'=>'Экспертное заключение',
            'review'=>'Рецензия',
            'file_scan'=>'Файл статьи с подписями',

        ];
    }
    public static function transliterate($name) {
        $cyr = array(' ',
            'ё',  'ж',  'х',  'ц',  'ч',  'щ',   'ш',  'ъ',  'э',  'ю',  'я',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'ь',
            'Ё',  'Ж',  'Х',  'Ц',  'Ч',  'Щ',   'Ш',  'Ъ',  'Э',  'Ю',  'Я',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Ь');
        $lat = array('_',
            'yo', 'zh', 'kh', 'ts', 'ch', 'shh', 'sh', '``', 'eh', 'yu', 'ya', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', '`',
            'Yo', 'Zh', 'Kh', 'Ts', 'Ch', 'Shh', 'Sh', '``', 'Eh', 'Yu', 'Ya', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', '`');
        $name = str_replace($cyr, $lat, $name);
        return str_replace($cyr, $lat, $name);
    }
}