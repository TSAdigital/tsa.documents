<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use hail812\adminlte3\assets\AdminLteAsset;
use hail812\adminlte3\assets\FontAwesomeAsset;
use yii\helpers\Html;

FontAwesomeAsset::register($this);
AdminLteAsset::register($this);
AppAsset::register($this);

$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
$publishedRes = Yii::$app->assetManager->publish('@vendor/hail812/yii2-adminlte3/src/web/js');
$this->registerJsFile($publishedRes[1].'/control_sidebar.js', ['depends' => '\hail812\adminlte3\assets\AdminLteAsset']);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no" />
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<div class="preloader"></div>
<body class="hold-transition">

<?php $this->beginBody() ?>

<div class="wrapper">

    <?= $this->render('navbar', ['assetDir' => $assetDir]) ?>

    <?= $this->render('sidebar', ['assetDir' => $assetDir]) ?>

    <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>

    <?= $this->render('control-sidebar') ?>

    <?= $this->render('footer') ?>

</div>

<?php $this->endBody() ?>

</body>
</html>

<?php $this->endPage() ?>
