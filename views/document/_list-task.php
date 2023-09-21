<?php
/**
 * @var app\models\DocumentTask $model
 * @var app\models\Document $document
 * @var int $index
 * @var integer $current_page
 * @var integer $page_size
 */

use yii\bootstrap4\Html;

?>

<tr>
    <th scope="row" style="text-align: center !important; vertical-align: middle; white-space: nowrap;"><?= ++$index + ($current_page > 0 ? ($current_page - 1) * $page_size : 0) ?></th>
    <td style="vertical-align: middle;"><?= Html::a(Html::encode($model->task->name), ['task/view', 'id' => $model->task->id], ['data-pjax' => 0]) ?></td>
    <?php if(Yii::$app->user->can('admin') or $document->user_id == Yii::$app->user->identity->id): ?>
        <td style="text-align: center!important; vertical-align: middle;"><?= Html::a('<i class="fas fa-trash-alt text-danger"></i>', ['document/task-delete', 'id' => $document->id, 'task' => $model->task_id], ['class' => 'btn m-0 p-0', 'data-pjax' => 0,  'data' => ['confirm' => 'Вы уверены, что хотите УДАЛИТЬ документ?', 'method' => 'post']]) ?></td>
    <?php endif; ?>
</tr>