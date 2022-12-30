<?php

namespace app\models;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $password;
    public $fio;
    public $phone;
    public $city;
    public $organization;

    public function rules()
    {
        return [
            [['username','password','fio','phone','city','organization'],'required','message'=>'Заполните поле'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => 'Электронная почта',
            'password'=>'Пароль',
            'fio'=>'ФИО',
            'phone'=>'Телефон',
            'city'=>'Город',
            'organization'=>'Организация'
        ];
    }

}