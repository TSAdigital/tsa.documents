<?php
/**
 * @var app\models\AuthItemChild $model
 * @var app\models\AuthItem $authItem
 * @var int $index
 * @var integer $current_page
 * @var integer $page_size
 */

use yii\bootstrap4\Html;

?>

<tr>
    <th scope="row" style="text-align: center !important; vertical-align: middle; white-space: nowrap;"><?= ++$index + ($current_page > 0 ? ($current_page - 1) * $page_size : 0) ?></th>
    <td style="vertical-align: middle;"><?= Html::encode($model->child0->description) ?></td>
    <td style="text-align: center!important; vertical-align: middle;"><?= Html::a('<i class="fas fa-trash-alt text-danger"></i>', ['auth-item/permission-delete', 'parent' => $authItem->name, 'child' => $model->child], ['class' => 'btn m-0 p-0', 'data-pjax' => 0,  'data' => ['confirm' => 'Вы уверены, что хотите УДАЛИТЬ разрешение?', 'method' => 'post']]) ?></td>
</tr>
