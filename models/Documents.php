<?php

namespace app\models;
use yii\db\ActiveRecord;
class Documents extends ActiveRecord
{
    public function rules()
    {
        return [
            [['document_status'],'required']
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
            'nr'=>'Н/Р',
            'title'=>'Название статьи',
            'authors'=>'Автор',
            'university'=>'ВУЗ',
            'file'=>'Статья',
            'originality'=>'Оригинальность',
            'comment'=>'Комментарий',
            'document_status'=>'Статус статьи',
            'datetime'=>'Дата подачи',
            'source'=>'Ссылка',
            'personal_data'=>'П/Д',
            'user_id'=>'Регистрационный номер студента',
            'coauthor'=>'соавторы',
            'collection'=>'Сборник',
            'draft_status'=>'Черновик/чистовик',
            'count_additional_document'=>''

        ];
    }
}