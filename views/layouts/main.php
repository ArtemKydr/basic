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
$role = Yii::$app->user->identity->role;

$css =<<<CSS
@media only screen and (min-width : 1200px) {
 .container, .container-sm, .container-md, .container-lg, .container-xl {max-width: 1220px} 
}
.fixed-top {
position: relative;
}
.navbar.navbar-expand-md.navbar-dark.bg-dark.fixed-top.navbar {
display: block;
width: 100%;
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
        'brandLabel' => 'ITMO SCIENCE. Исследования и разработки',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    if (!Yii::$app->user->isGuest){
    if ($role == 'admin') {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                ['label' => 'О науке', 'url' => ['/site/index']],
                ['label' => 'Инфраструктура', 'url' => ['/site/about']],
                ['label' => 'Руководителю', 'url' => ['/site/contact']],
                ['label' => 'Подать документы', 'url' => ['/site/student']],
                ['label' => 'Поданные заявки', 'url' => ['/site/manager']],
                ['label' => 'Выход (' . Yii::$app->user->identity->email . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'get']],
            ]]);
    }elseif ($role == 'user'){
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    ['label' => 'О науке', 'url' => ['/site/index']],
                    ['label' => 'Инфраструктура', 'url' => ['/site/about']],
                    ['label' => 'Руководителю', 'url' => ['/site/contact']],
                    ['label' => 'Подать документы', 'url' => ['/site/student']],
                    ['label' => 'Выход (' . Yii::$app->user->identity->email . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'get']],
                ]]);
        } else if ($role == 'manager'){
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    ['label' => 'О науке', 'url' => ['/site/index']],
                    ['label' => 'Инфраструктура', 'url' => ['/site/about']],
                    ['label' => 'Руководителю', 'url' => ['/site/contact']],
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
                ['label' => 'Регистрация', 'url' => ['/site/sign-up']],
                ['label' => 'Вход', 'url' => ['/site/login']],
                ]]);
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
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
