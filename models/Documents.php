<?php

namespace app\models;
use yii\db\ActiveRecord;
class Documents extends ActiveRecord
{
    public function rules()
    {
        return [
            [['comment','originality','document_status'],'required']
        ];
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
            'file'=>'Статья',
            'originality'=>'Оригинальность',
            'comment'=>'Комментарий',
            'document_status'=>'Статус статьи',

        ];
    }
}