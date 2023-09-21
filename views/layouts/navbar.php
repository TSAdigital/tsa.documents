<?php

use yii\helpers\Html;

?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link sidebar-toggle" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            <?= Html::a( 'О проекте', ['site/about'], ['class' => Yii::$app->controller->route == 'site/about' ? 'nav-link active' : 'nav-link']); ?>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto inline">
        <?php if(Yii::$app->notification->getNotification()) : ?>
        <li class="nav-item">
            <?= Html::a( '<i class="far fa-envelope-open ' . (Yii::$app->notification->getNotification() > 9 ? 'mr-1' : null) . '"></i><span class="badge badge-danger navbar-badge text-bold">' .  (Yii::$app->notification->getNotification() > 99 ? '&infin; ' : Yii::$app->notification->getNotification()) . '</span>', ['site/account'], ['class' => Yii::$app->controller->route == 'site/account' ? 'nav-link active' : 'nav-link']) ?>
        </li>
        <?php endif; ?>
        <li class="nav-item d-none d-sm-block">
            <?= Html::a( !empty(Yii::$app->user->identity->username) ? '<i class="far fa-user"></i> ' . Yii::$app->user->identity->getEmployee_name()  : '<i class="far fa-user"></i> Гость' , ['site/account'], ['class' => Yii::$app->controller->route == 'site/account' ? 'nav-link active' : 'nav-link']) ?>
        </li>
        <li class="nav-item d-block d-sm-none">
            <?= Html::a( !empty(Yii::$app->user->identity->username) ? '<i class="far fa-user"></i> Профиль' : '<i class="far fa-user"></i> Гость' , ['site/account'], ['class' => Yii::$app->controller->route == 'site/account' ? 'nav-link active' : 'nav-link']) ?>
        </li>
        <li class="nav-item">
            <?= !empty(Yii::$app->user->identity->username) ? Html::a('<i class="fas fa-sign-out-alt"></i>', ['site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) : Html::a('<i class="fas fa-sign-in-alt"></i>', ['site/login'], ['class' => 'nav-link']) ?>
        </li>
    </ul>
</nav>