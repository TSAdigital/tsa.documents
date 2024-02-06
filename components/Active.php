<?php

namespace app\components;

use app\models\User;
use Yii;
use yii\base\Component;

class Active extends Component
{
    public function getActive()
    {
        if(Yii::$app->user->identity->id){
            $model = User::findOne(Yii::$app->user->identity->id);
            if($model){
                $model->active = time();
                $model->save();
            }
        }
    }
}
