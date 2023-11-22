<?php

use yii\db\Migration;

/**
 * Class m231116_194348_rbac
 */
class m231116_194348_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $viewDocument = $auth->createPermission('viewDocuments');
        $viewDocument->description = 'Просматривать документы';
        $auth->add($viewDocument);

        $createDocument = $auth->createPermission('createDocuments');
        $createDocument->description = 'Добавлять документы';
        $auth->add($createDocument);

        $updateDocument = $auth->createPermission('updateDocuments');
        $updateDocument->description = 'Редактировать документы';
        $auth->add($updateDocument);

        $viewTask = $auth->createPermission('viewTasks');
        $viewTask->description = 'Просматривать задачи';
        $auth->add($viewTask);

        $createTask = $auth->createPermission('createTasks');
        $createTask->description = 'Добавлять задачи';
        $auth->add($createTask);

        $updateTask = $auth->createPermission('updateTasks');
        $updateTask->description = 'Редактировать задачи';
        $auth->add($updateTask);

        $viewNews = $auth->createPermission('viewNews');
        $viewNews->description = 'Просматривать новости';
        $auth->add($viewNews);

        $createNews = $auth->createPermission('createNews');
        $createNews->description = 'Добавлять новости';
        $auth->add($createNews);

        $updateNews = $auth->createPermission('updateNews');
        $updateNews->description = 'Редактировать новости';
        $auth->add($updateNews);

        $viewGroups = $auth->createPermission('viewGroups');
        $viewGroups->description = 'Просматривать группы';
        $auth->add($viewGroups);

        $createGroups = $auth->createPermission('createGroups');
        $createGroups->description = 'Добавлять группы';
        $auth->add($createGroups);

        $updateGroups = $auth->createPermission('updateGroups');
        $updateGroups->description = 'Редактировать группы';
        $auth->add($updateGroups);

        $viewPositions = $auth->createPermission('viewPositions');
        $viewPositions->description = 'Просматривать должности';
        $auth->add($viewPositions);

        $createPositions = $auth->createPermission('createPositions');
        $createPositions->description = 'Добавлять должности';
        $auth->add($createPositions);

        $updatePositions = $auth->createPermission('updatePositions');
        $updatePositions->description = 'Редактировать должности';
        $auth->add($updatePositions);

        $viewEmployees = $auth->createPermission('viewEmployees');
        $viewEmployees->description = 'Просматривать сотрудников';
        $auth->add($viewEmployees);

        $createEmployees = $auth->createPermission('createEmployees');
        $createEmployees->description = 'Добавлять сотрудников';
        $auth->add($createEmployees);

        $updateEmployees = $auth->createPermission('updateEmployees');
        $updateEmployees->description = 'Редактировать сотрудников';
        $auth->add($updateEmployees);

        $viewDocumentsType = $auth->createPermission('viewDocumentsType');
        $viewDocumentsType->description = 'Просматривать типы документов';
        $auth->add($viewDocumentsType);

        $createDocumentsType = $auth->createPermission('createDocumentsType');
        $createDocumentsType->description = 'Добавлять типы документов';
        $auth->add($createDocumentsType);

        $updateDocumentsType = $auth->createPermission('updateDocumentsType');
        $updateDocumentsType->description = 'Редактировать типы документов';
        $auth->add($updateDocumentsType);

        $editor = $auth->getRole('editor');
        $user = $auth->getRole('user');

        $auth->addChild($editor, $viewDocument);
        $auth->addChild($editor, $createDocument);
        $auth->addChild($editor, $updateDocument);
        $auth->addChild($editor, $viewTask);
        $auth->addChild($editor, $createTask);
        $auth->addChild($editor, $updateTask);
        $auth->addChild($editor, $viewGroups);
        $auth->addChild($editor, $createGroups);
        $auth->addChild($editor, $updateGroups);

        $auth->addChild($user, $viewDocument);
        $auth->addChild($user, $viewTask);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAllPermissions();
    }
}
