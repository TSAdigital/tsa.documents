<?php

namespace app\components;

use app\models\Document;
use app\models\Task;
use Yii;
use yii\base\Component;

class Notification extends Component
{
    public function getNotification()
    {
        $user_id = Yii::$app->user->identity->id;

        $documents = Document::find()
            ->select('document.id')
            ->leftJoin('view', "view.record_id=document.id AND view.type='document' AND view.user_id=$user_id")
            ->where(['like', 'document.resolution', sprintf('"%s"', $user_id)])
            ->andWhere(['document.status' => Document::STATUS_ACTIVE])
            ->andWhere(['view.id' => NULL])
            ->orWhere(['document.executor_id' => $user_id])
            ->andWhere(['document.status' => Document::STATUS_ACTIVE])
            ->andWhere(['view.id' => NULL])
            ->count();

        $tasks = Task::find()
            ->select('task.id')
            ->leftJoin('view', "view.record_id=task.id AND view.type='task' AND view.user_id=$user_id")
            ->where(['like', 'task.resolution', sprintf('"%s"', $user_id)])
            ->andWhere(['task.status' => Task::STATUS_ACTIVE])
            ->andWhere(['view.id' => NULL])
            ->orWhere(['task.executor_id' => $user_id])
            ->andWhere(['task.status' => Task::STATUS_ACTIVE])
            ->andWhere(['view.id' => NULL])
            ->count();

        $result = $documents + $tasks;

        return ($result > 0) ? $result : false;
    }
}
