<?php

namespace app\models;

use Yii;
use yii\base\Model;
class SendEmailForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email','filter','filter'=>'trim'],
            ['email','required'],
            ['email','email'],
            ['email', 'exist',
                'targetClass' => User::className(),
                'message' => 'Данный емайл не зарегистрирован.'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта'
        ];
    }

    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne(
            [
                'email' => $this->email
            ]
        );

        if($user):
            $user->generateSecretKey();
            if($user->save()):
                return Yii::$app->mailer->compose('resetPassword', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (отправлено роботом)'])
                    ->setTo($this->email)
                    ->setSubject('Сброс пароля для '.Yii::$app->name)
                    ->send();
            endif;
        endif;

        return false;
    }
    public function sendEmailChangeDocumentStatus($document_id)
    {

        $student_id = Documents::find()->select('fio,title,email')->where(['id'=>$document_id])->one();
        $user = $student_id->email;
        $title = $student_id->title;
        $fio = $student_id->fio;
        if($user){
            Yii::$app->mailer->compose('changeDocumentStatus', ['user' => $user,'fio'=>$fio,'title'=>$title])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (отправлено роботом)'])
                ->setTo($user)
                ->setSubject('Смена статуса документа '.$title)
                ->send();
        }
        return false;
    }

    public function sendEmailChangeDocumentStatusUpdate($id)
    {

        $student_id = Documents::find()->select('fio,title,email')->where(['id'=>$id])->one();
        $user = $student_id->email;
        $title = $student_id->title;
        $fio = $student_id->fio;
        if($user):
            return Yii::$app->mailer->compose('changeDocumentStatus', ['user' => $user,'fio'=>$fio,'title'=>$title])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (отправлено роботом)'])
                ->setTo($user)
                ->setSubject('Смена статус о принятии материала "'.$title.'" к опубликованию.')
                ->send();
        endif;
        return false;
    }

}