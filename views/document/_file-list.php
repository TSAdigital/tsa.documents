<?php
/**
 * @var app\models\Upload $model
 * @var app\models\Document $document
 * @var int $index
 * @var integer $current_page
 * @var integer $page_size
 */

use app\models\SignFile;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;

?>

<tr>
    <th scope="row" style="text-align: center!important; vertical-align: middle; white-space: nowrap;"><?= ++$index + ($current_page > 0 ? ($current_page - 1) * $page_size : 0) ?></th>
    <td style="vertical-align: middle;"><?= Html::a(Html::encode($model->name), ['document/download', 'id' => $document->id, 'file' => $model->id], ['data-pjax' => 0]) ?></td>
    <td style="vertical-align: middle; text-align: center; min-width: 150px">
        <?= $model->file_extensions === 'pdf' ? Html::a('<i class="far fa-eye text-dark pr-1"></i>',  '@web/upload/' . $model->dir . '/' . $model->file_name . '.' . $model->file_extensions, ['title' => 'Просмотреть', 'data-pjax' => 0, 'target' => '_blank']) : null ?>
        <?= Html::a('<i class="fas fa-download text-primary pr-1"></i>', ['document/download', 'id' => $document->id, 'file' => $model->id], ['data-pjax' => 0, 'title' => 'Скачать']) ?>
        <?= !SignFile::findOne(['file_id' => $model->id, 'user_id' => Yii::$app->user->identity->id]) ? Html::a('<i class="fas fa-signature text-success pr-1"></i>', '#', ['data-pjax' => 0, 'data-toggle' => 'modal', 'data-id' => $model->id, 'class' => 'open-modal', 'title' => 'Подписать']) : null ?> <?= SignFile::findOne(['file_id' => $model->id]) ? Html::a('<i class="far fa-file-alt text-info pr-1"></i>', '#', ['data-pjax' => 0, 'data-toggle' => 'modal', 'title' => 'Список подписей', 'data-target' => '#file-' . $model->id]) : null ?>
        <?= (Yii::$app->user->can('admin') or $model->user_id == Yii::$app->user->identity->id) ? Html::a('<i class="fas fa-trash-alt text-danger pr-1"></i>', ['document/file-delete', 'id' => $document->id, 'file' => $model->id], ['data-pjax' => 0, 'title' => 'Удалить', 'data' => ['confirm' => 'Вы уверены, что хотите УДАЛИТЬ файл?', 'method' => 'post']]) : null ?>
    </td>
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