<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

?>

<?php

Pjax::begin([
    'id' => 'count-messages',
]);

$count = Yii::$app->notification->getNotification();

$script = <<< JS
    var count = $count;
    var title = document.title;    
    document.title = '(' + count + ') ' + title;
JS;

if($count > 0){
    $this->registerJs($script);
}

?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link sidebar-toggle" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            <?= Html::a( 'О проекте', ['site/about'], ['class' => Yii::$app->controller->route == 'site/about' ? 'nav-link active' : 'nav-link', 'data-pjax' => 0]); ?>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto inline">
        <?php if($count) : ?>
        <li class="nav-item">
            <?= Html::a( '<i class="far fa-envelope-open ' . ($count > 9 ? 'mr-1' : null) . '"></i><span class="badge badge-danger navbar-badge text-bold">' .  ($count > 99 ? '&infin; ' : $count) . '</span>', ['site/account'], ['class' => Yii::$app->controller->route == 'site/account' ? 'nav-link active' : 'nav-link', 'data-pjax' => 0]) ?>
        </li>
        <?php endif; ?>
        <li class="nav-item d-none d-sm-block">
            <?= Html::a( !empty(Yii::$app->user->identity->username) ? '<i class="far fa-user"></i> ' . Yii::$app->user->identity->getEmployee_name()  : '<i class="far fa-user"></i> Гость' , ['site/account'], ['class' => Yii::$app->controller->route == 'site/account' ? 'nav-link active' : 'nav-link', 'data-pjax' => 0]) ?>
        </li>
        <li class="nav-item d-block d-sm-none">
            <?= Html::a( !empty(Yii::$app->user->identity->username) ? '<i class="far fa-user"></i> Профиль' : '<i class="far fa-user"></i> Гость' , ['site/account'], ['class' => Yii::$app->controller->route == 'site/account' ? 'nav-link active' : 'nav-link', 'data-pjax' => 0]) ?>
        </li>
        <li class="nav-item">
            <?= !empty(Yii::$app->user->identity->username) ? Html::a('<i class="fas fa-sign-out-alt"></i>', ['site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) : Html::a('<i class="fas fa-sign-in-alt"></i>', ['site/login'], ['class' => 'nav-link', 'data-pjax' => 0]) ?>
        </li>
    </ul>
</nav>

<?php

Pjax::end();

$this->registerJs(
    <<<JS
        function updateList() {
          $.pjax.reload({container: '#count-messages'});
        }
        setInterval(updateList, 15000);
    JS
);

?>
