<?php

use yii\db\Migration;

/**
 * Class m231122_185547_rbac
 */
class m231122_185547_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $viewEvent = $auth->createPermission('viewEvents');
        $viewEvent->description = 'Просматривать мероприятия';
        $auth->add($viewEvent);

        $createEvent = $auth->createPermission('createEvents');
        $createEvent->description = 'Добавлять мероприятия';
        $auth->add($createEvent);

        $updateEvent = $auth->createPermission('updateEvents');
        $updateEvent->description = 'Редактировать мероприятия';
        $auth->add($updateEvent);

        $eventsAdmin = $auth->createPermission('eventsAdmin');
        $eventsAdmin->description = 'Администратор мероприятий';
        $auth->add($eventsAdmin);

        $editor = $auth->getRole('editor');
        $user = $auth->getRole('user');

        $auth->addChild($editor, $viewEvent);
        $auth->addChild($editor, $createEvent);
        $auth->addChild($editor, $updateEvent);

        $auth->addChild($user, $viewEvent);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
