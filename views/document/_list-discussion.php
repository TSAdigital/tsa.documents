<?php

/** @var app\models\Discussion $model */
/** @var app\models\Document $document */
/** @var integer $index */
/** @var integer $current_page */
/** @var integer $page_size */

use yii\bootstrap4\Html;

?>

<div class="card mt-2">
    <div class="card-body p-2">
        <div class="comment-text">
            <p class="mb-0 text-bold <?= $model->user_id == Yii::$app->user->identity->id ? 'text-primary' : null ?>" style="font-size: 1rem;"><?= Html::encode($model->user->getEmployee_name()) ?></p>
            <div class="comment-footer">
                <span class="date" style="font-size: 0.9rem"><?= Yii::$app->formatter->asDatetime($model->created_at, 'short') ?></span>
                <span class="dropdown">
                    <span class="dropdown-toggle ml-1" type="button"
                          data-toggle="dropdown" aria-expanded="false"></span>
                    <span class="dropdown-menu">

                        <?= $model->user_id != Yii::$app->user->identity->id ? Html::a('Профиль', ['site/profile', 'id' => $model->user->id], ['class' => 'dropdown-item']) : null ?>
                        <?= (Yii::$app->user->can('admin') or $model->user_id == Yii::$app->user->identity->id) ? Html::a('Удалить', ['document/discussion-delete', 'id' => $document->id, 'discussion' => $model->id], ['class' => 'dropdown-item']) : null ?>

                    </span>
                </span>
            </div>
            <p class="mt-2 mb-0"><?= Yii::$app->formatter->asNtext($model->message) ?></p>
        </div>
    </div>
</div>