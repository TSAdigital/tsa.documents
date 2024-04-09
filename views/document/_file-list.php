<?php
/**
 * @var app\models\Upload $model
 * @var app\models\Document $document
 * @var int $index
 * @var integer $current_page
 * @var integer $page_size
 */

use app\models\SignFile;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;

?>

<tr>
    <th scope="row" style="text-align: center!important; vertical-align: middle; white-space: nowrap;"><?= ++$index + ($current_page > 0 ? ($current_page - 1) * $page_size : 0) ?></th>
    <td style="vertical-align: middle;"><?= Html::a(Html::encode($model->name), ['document/download', 'id' => $document->id, 'file' => $model->id], ['data-pjax' => 0]) ?></td>
    <td style="vertical-align: middle; text-align: center"><?= !SignFile::findOne(['file_id' => $model->id, 'user_id' => Yii::$app->user->identity->id]) ? Html::a('<i class="fas fa-signature text-success"></i>', '#', ['data-pjax' => 0, 'data-toggle' => 'modal', 'data-id' => $model->id, 'class' => 'open-modal']) : null ?> <?= SignFile::findOne(['file_id' => $model->id]) ? Html::a('<i class="far fa-file-alt text-primary"></i>', '#', ['data-pjax' => 0, 'data-toggle' => 'modal', 'data-target' => '#file-' . $model->id]) : null ?></td>
    <?php if(Yii::$app->user->can('admin') or $model->user_id == Yii::$app->user->identity->id): ?>
    <td style="text-align: center!important; vertical-align: middle;"><?= Html::a('<i class="fas fa-trash-alt text-danger"></i>', ['document/file-delete', 'id' => $document->id, 'file' => $model->id], ['class' => 'btn m-0 p-0', 'data-pjax' => 0,  'data' => ['confirm' => 'Вы уверены, что хотите УДАЛИТЬ файл?', 'method' => 'post']]) ?></td>
    <?php endif; ?>
</tr>

<?php
Modal::begin([
    'title' => Html::encode($model->name),
    'id' => 'file-' . $model->id,
    'size' => 'modal-lg',
    'closeButton' => [
        'id' => 'close-button',
        'class' => 'close',
        'data-dismiss' => 'modal',
    ],
    'clientOptions' => ['backdrop' => false]
]);
$signs = SignFile::find()->where(['file_id' => $model->id])->all();
?>
<?php if($signs) : ?>

<?php foreach ($signs as $sign):?>

<div class="row my-1">
    <div class="col-9 col-md-7"><?= $sign->user->employee_name ?></div>
    <div class="col-md-3  text-nowrap text-center d-none d-md-block"><?= Html::encode(Yii::$app->formatter->asDatetime($sign->created_at)) ?></div>
    <div class="col-3 col-md-2 text-nowrap text-right"><?= Html::a('<i class="far fa-save text-primary"></i>', ['document/sig-download', 'id' => $sign->id], ['class' => 'btn m-0 p-0', 'data-pjax' => 0]) ?></div>
</div>

<?php endforeach; ?>

<?php endif; ?>

<?php Modal::end(); ?>