<?php
/**
 * @var app\models\News $model
 * @var int $index
 * @var integer $current_page
 * @var integer $page_size
 */


use yii\bootstrap4\Modal;
use yii\helpers\Html;

?>

<tr>
    <th scope="row" style="text-align: center !important; vertical-align: middle; white-space: nowrap;"><?= ++$index + ($current_page > 0 ? ($current_page - 1) * $page_size : 0) ?></th>
    <td style="vertical-align: middle;"><?= Html::a(Html::encode($model->title), '#', ['data-pjax' => 0, 'data-toggle' => 'modal', 'data-target' => '#news-' . $model->id]) ?></td>
    <td style="vertical-align: middle; text-align: center; white-space: nowrap"><?= Html::encode(Yii::$app->formatter->asDate($model->created_at)) ?></td>
</tr>

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

<p class="mb-2"><?= Html::encode(Yii::$app->formatter->asDatetime($model->created_at)) ?> / <?= Yii::$app->formatter->asNtext($model->user->getEmployee_name()) ?></p>
<?= Yii::$app->formatter->asNtext($model->text) ?>

<?php Modal::end(); ?>


