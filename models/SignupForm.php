<?php

namespace app\models;
use yii\base\Model;

class SignupForm extends Model
{
    public $email;
    public $password;
    public $fio;
    public $phone;
    public $city;
    public $organization;

    public function rules()
    {
        return [
            [['email','password','fio','phone','city','organization'],'required','message'=>'Заполните поле'],
            ['password', 'match', 'pattern' => '#\d.*\d#s', 'message' => 'Пароль должен содержать минимум 2 буквы и 2 цифры.'],
            ['password', 'match', 'pattern' => '#[a-z].*[a-z]#is', 'message' => 'Пароль должен содержать минимум 2 буквы и 2 цифры.'],
            ['email', 'unique', 'targetClass' => User::className(),  'message' => 'Этот логин уже занят'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'password'=>'Пароль',
            'fio'=>'ФИО',
            'phone'=>'Телефон',
            'city'=>'Город',
            'organization'=>'Организация (Полное название)'
        ];
    }

}