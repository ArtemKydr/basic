<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.08.2015
 * Time: 15:38
 *
 * @var $user \app\models\User
 */
use yii\helpers\Html;

echo 'Здравствуйте, '.Html::encode($fio).'!';
echo 'Статус Вашей заявки "'.$title.'" на подачу материала изменился. Вся информация доступна в Личном кабинете.';
echo ' ';
echo 'Ваш, УЦСНКиВ.';