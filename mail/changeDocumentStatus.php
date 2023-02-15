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

echo 'Здравствуйте,  '.Html::encode($fio).'!';
?>
    <br>
    <br>
<?echo 'Статус Вашей заявки "'.$title.'" на подачу материала изменился. Вся информация доступна в <a href="https://studnauka.itmo.ru/">Личном кабинете</a>.';?>
    <br>
    <br>
<?echo 'Ваш, УЦСНКиВ.';?>
    <br>
<?echo '8(812) 480-10-91';?>
    <br>
<?echo 'csn@itmo.ru';?>