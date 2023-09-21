<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use hail812\adminlte3\assets\AdminLteAsset;
use hail812\adminlte3\assets\PluginAsset;
use yii\bootstrap4\Html;

AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700');
$this->registerCssFile('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css');
PluginAsset::register($this)->add(['fontawesome', 'icheck-bootstrap']);
AppAsset::register($this);
?>

<?php $this->beginPage() ?>

    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= Html::encode(Yii::$app->name)?> | <?= Html::encode($this->title) ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition login-page">

    <?php  $this->beginBody() ?>

        <?= $content ?>

    <?php $this->endBody() ?>

    </body>
    </html>

<?php $this->endPage() ?>