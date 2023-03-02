<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadChangeDocumentForm extends Model
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
            [['file'], 'file', 'extensions' => 'docx'],

        ];
    }

    public function upload($timestamp)
    {
        $name = mb_strtolower($this->file->baseName);
        $name = self::transliterate($name);
            $this->file->saveAs('UploadDocument/' . $name .'_'.$timestamp. '.' . $this->file->extension);
            return true;
    }
    public function attributeLabels()
    {
        return [
            'email' => 'Доп. электронная почта',
            'fio'=>'ФИО',
            'phone'=>'Телефон',
            'city'=>'Город',
            'organization'=>'Организация (Полное название)',
            'nr'=>'Научный руководитель (ФИО)',
            'title'=>'Название статьи',
            'authors'=>'Автор',
            'university'=>'ВУЗ (Полное название)',
            'file'=>"Статья      (docx)",
            'coauthor'=>'Соавторы (ФИО)',
            'draft'=>'Черновик',
            'expert'=>'Экспертное заключение',
            'review'=>'Рецензия',
            'file_scan'=>'Файл статьи с подписями',

        ];
    }
    public static function transliterate($name) {
        $cyr = array('ы',' ',
            'ё',  'ж',  'х',  'ц',  'ч',  'щ',   'ш',  'ъ',  'э',  'ю',  'я',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'ь',
            'Ё',  'Ж',  'Х',  'Ц',  'Ч',  'Щ',   'Ш',  'Ъ',  'Э',  'Ю',  'Я',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Ь');
        $lat = array('i','_',
            'yo', 'zh', 'kh', 'ts', 'ch', 'shh', 'sh', '``', 'eh', 'yu', 'ya', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', '`',
            'Yo', 'Zh', 'Kh', 'Ts', 'Ch', 'Shh', 'Sh', '``', 'Eh', 'Yu', 'Ya', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', '`');
        $name = str_replace($cyr, $lat, $name);
        return str_replace($cyr, $lat, $name);
    }
}