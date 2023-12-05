<?php

/** @var app\models\Telegram $model */
/** @var integer $index */
/** @var integer $current_page */
/** @var integer $page_size */

use yii\bootstrap4\Html;

?>

<div class="card mt-2 mb-2">
    <div class="card-body p-2">
        <div class="row">
            <div class="col-12"><span class="badge badge-primary">#<?= ++$index + ($current_page > 0 ? ($current_page - 1) * $page_size : 0) ?></span><span class="badge badge-success ml-1"><?= Html::encode($model->telegram) ?></span></div>
            <div class="col-12"><?= Html::encode($model->first_name . ' ' . $model->last_name . ' ') ?><?= $model->username ? Html::encode('@' . $model->username) : null ?></div>
        </div>
    </div>
</div>