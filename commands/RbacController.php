<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $user = $auth->createRole('user');
        $user->description = 'Пользователь';
        $auth->add($user);

        $editor = $auth->createRole('editor');
        $editor->description = 'Редактор';
        $auth->add($editor);

        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';
        $auth->add($admin);

        $auth->assign($admin, 1);
    }

}
