<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>

<div class="login-box">
    <div class="error-content text-center align-middle" style="margin-left: auto;">
        <h3><i class="fas fa-exclamation-triangle text-danger"></i> <?= Html::encode($name) ?></h3>
            <p><?= nl2br(Html::encode($message)) ?></p>
            <h3>Упс! Что-то пошло не так :(</h3>
            <p><?= Html::a('Вернуться на главную', Yii::$app->homeUrl); ?></p>
    </div>
</div>
