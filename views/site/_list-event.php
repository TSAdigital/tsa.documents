<?php
/**
 * @var app\models\Event $model
 * @var int $index
 * @var integer $current_page
 * @var integer $page_size
 */

use yii\helpers\Html;

?>

<tr>
    <th scope="row" style="text-align: center !important; vertical-align: middle; white-space: nowrap;"><?= ++$index + ($current_page > 0 ? ($current_page - 1) * $page_size : 0) ?></th>
    <td style="vertical-align: middle;"><?= Html::a(Html::encode($model->name),  ['event/view','id' => $model->id], ['data-pjax' => 0]) ?></td>
    <td style="vertical-align: middle; text-align: center; white-space: nowrap"><?= Html::encode(Yii::$app->formatter->asDatetime(strtotime($model->start), 'php:d.m.y H:i')) ?></td>
</tr>