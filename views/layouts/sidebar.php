<?php

use hail812\adminlte\widgets\Menu;

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="/" class="brand-link text-center">
        TSA <span class="brand-text font-weight-light"><em><sup><small>Documents</small></sup></em></span>
    </a>

    <div class="sidebar">
        <nav class="mt-2 mb-5">
            <?= Menu::widget(['linkTemplate' => '<a class="nav-link {active}" href="{url}" {target}>{label}</a>',
                'options' => [
                    'class' => 'nav nav-pills nav-sidebar flex-column nav-legacy nav-flat',
                    'data-widget' => 'treeview',
                    'role' => 'menu',
                    'data-accordion' => 'false'
                ],
                'items' => [
                    ['label' => 'НАВИГАЦИЯ', 'header' => true, 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewDocuments') or Yii::$app->user->can('viewTasks') or Yii::$app->user->can('viewNews') or Yii::$app->user->can('viewEvents')],
                    ['label' => 'Документы', 'url' => ['document/index'], 'active'=> $this->context->getUniqueId() == 'document', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewDocuments')],
                    ['label' => 'Задачи', 'url' => ['task/index'], 'active'=> $this->context->getUniqueId() == 'task', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewTasks')],
                    ['label' => 'Новости', 'url' => ['news/index'], 'active'=> $this->context->getUniqueId() == 'news', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewNews')],
                    ['label' => 'Мероприятия', 'url' => ['event/index'], 'active'=> $this->context->getUniqueId() == 'event', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewEvents')],

                    ['label' => 'СПРАВОЧНИКИ', 'header' => true, 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewGroups') or Yii::$app->user->can('viewPositions') or Yii::$app->user->can('viewEmployees') or Yii::$app->user->can('viewDocumentsType')],
                    ['label' => 'Группы', 'url' => ['group/index'], 'active'=> $this->context->getUniqueId() == 'group', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewGroups')],
                    ['label' => 'Должности', 'url' => ['position/index'], 'active'=> $this->context->getUniqueId() == 'position', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewPositions')],
                    ['label' => 'Сотрудники', 'url' => ['employee/index'], 'active'=> $this->context->getUniqueId() == 'employee', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewEmployees')],
                    ['label' => 'Типы документов', 'url' => ['document-type/index'], 'active'=> $this->context->getUniqueId() == 'document-type', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewDocumentsType')],
                    ['label' => 'НАСТРОЙКИ', 'header' => true, 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'Пользователи', 'url' => ['user/index'], 'active'=> $this->context->getUniqueId() == 'user', 'icon' => '', 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'Роли', 'url' => ['auth-item/index'], 'active'=> $this->context->getUniqueId() == 'auth-item', 'icon' => '', 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'Телеграм', 'url' => ['telegram/index'], 'active'=> $this->context->getUniqueId() == 'telegram', 'icon' => '', 'visible' => Yii::$app->user->can('admin') and Yii::$app->params['telegram'] === true],
                ],
            ]);
            ?>
        </nav>
    </div>

</aside>