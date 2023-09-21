<?php

/** @var app\models\Document $model */
/** @var integer $index */
/** @var integer $current_page */
/** @var integer $page_size */

use yii\bootstrap4\Html;

if($model->status == $model::STATUS_ACTIVE){
    $color = 'success';
}elseif($model->status == $model::STATUS_INACTIVE){
    $color= 'danger';
}elseif($model->status == $model::STATUS_DRAFT) {
    $color = 'dark';
}else{
    $color = 'secondary';
}
?>

<div class="card mt-2 mb-2">
    <div class="card-body p-2">
        <div class="row">
            <div class="col-12"><span class="badge badge-primary">#<?= ++$index + ($current_page > 0 ? ($current_page - 1) * $page_size : 0) ?></span><span class="badge badge-<?= $color ?> ml-1"><?= Html::encode($model->getStatusName()) ?></span><span class="badge badge-secondary ml-1"><?= Html::encode($model->number) ?></span></div>
            <div class="col-12"><?= Html::a(Html::encode($model->name), ['document/view','id' => $model->id], ['data-pjax' => 0]) ?></div>
        </div>
    </div>
</div>
