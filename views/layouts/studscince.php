<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use app\models\LoginForm;

AppAsset::register($this);

$css = <<<CSS

.navbar.navbar-expand-md.navbar-dark.bg-dark.fixed-top.navbar{
display: block;
width: 100%;
}
.fixed-top {
position: relative;
}
.logoItmo{
height: 100px;
width: 337px;
}
.container{
max-width: 1920px !important;
padding: 0px 340px 0px 340px;
}

.bg-dark{
background: white !important;
}
.navbar-dark .navbar-nav .nav-link{
color: black;
font-weight: normal;
}
a:focus{
color: grey;
}
.nav-link:hover{
color: #0b72b8 !important;
}
.nav-link.active{
color: black !important;
font-weight: bold !important;;
}
.navbar-collapse{
justify-content: flex-end;
margin-top: 16px;
}
.container-body{
margin: 20px 21% 20px 21%;
}
.navbar-dark .navbar-toggler {
  color: rgba(0,0,0,0.5);
  border-color: rgba(255, 255, 255, 0.1);
}

.navbar-dark .navbar-toggler-icon {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.5%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}
@media screen and (max-width: 480px) {
.logoItmo{
width: 280px;
}
    
    }


CSS;
$this->registerCss($css);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="RU-ru" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => '<img src="/web/images/LogoItmo.svg" class="logoItmo"/>',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    if (!Yii::$app->user->isGuest){
        $role = Yii::$app->user->identity->role;
        if ($role == 'admin') {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    ['label' => 'Личная информация', 'url' => ['/site/personal-information']],
                    ['label' => 'Требования', 'url' => ['/site/requirements']],
                    ['label' => 'Подать документы', 'url' => ['/site/student-document']],
                    ['label' => 'Поданные заявки', 'url' => ['/site/manager']],
                    ['label' => 'Контакты', 'url' => ['/site/contact']],
                    ['label' => 'Выход (' . Yii::$app->user->identity->email . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'get']],
                ]]);
        }elseif ($role == 'user'){
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    ['label' => 'Личная информация', 'url' => ['/site/personal-information']],
                    ['label' => 'Требования', 'url' => ['/site/requirements']],
                    ['label' => 'Подать документы', 'url' => ['/site/student-document']],
                    ['label' => 'Контакты', 'url' => ['/site/contact']],
                    ['label' => 'Выход (' . Yii::$app->user->identity->email . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'get']],
                ]]);
        } else if ($role == 'manager'){

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    ['label' => 'Личная информация', 'url' => ['/site/personal-information']],
                    ['label' => 'Поданные заявки', 'url' => ['/site/manager']],
                    ['label' => 'Выход (' . Yii::$app->user->identity->email . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'get']],
                ]]);
        }
    }else {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                ['label' => 'Требования', 'url' => ['/site/requirements']],
                ['label' => 'Регистрация', 'url' => ['/site/sign-up']],
                ['label' => 'Вход', 'url' => ['/site/login']],
            ]]);
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container-body">
        <?= Breadcrumbs::widget([
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
