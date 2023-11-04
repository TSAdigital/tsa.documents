<?php
/**
 * @var app\models\News $model
 * @var int $index
 * @var integer $current_page
 * @var integer $page_size
 */


use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\helpers\StringHelper;

?>
<div class="card mt-3 mb-0">
    <div class="card-body">
        <p class="small mb-0"><?= Html::encode(Yii::$app->formatter->asDatetime($model->created_at)) ?></p>
        <p class="h5 mb-0"><?= Html::a(Html::encode($model->title), '#', ['data-pjax' => 0, 'data-toggle' => 'modal', 'data-target' => '#news-' . $model->id]) ?></p>
        <p class="mb-0"><?= StringHelper::truncate(Yii::$app->formatter->asNtext($model->text), 100); ?></p>
    </div>
</div>

<?php
Modal::begin([
    'title' => Html::encode($model->title),
    'id' => 'news-' . $model->id,
    'size' => 'modal-lg',
    'closeButton' => [
        'id' => 'close-button',
        'class' => 'close',
        'data-dismiss' => 'modal',
    ],
    'clientOptions' => ['backdrop' => false]
]);
?>

<p class="small mb-2"><?= Html::encode(Yii::$app->formatter->asDatetime($model->created_at)) ?> / <?= Yii::$app->formatter->asNtext($model->user->getEmployee_name()) ?></p>
<?= Yii::$app->formatter->asNtext($model->text) ?>

<?php Modal::end(); ?>


