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
                    ['label' => 'НАВИГАЦИЯ', 'header' => true, 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewDocuments') or Yii::$app->user->can('viewTasks') or Yii::$app->user->can('viewNews')],
                    ['label' => 'Документы', 'url' => ['document/index'], 'active'=> $this->context->getUniqueId() == 'document', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewDocuments')],
                    ['label' => 'Задачи', 'url' => ['task/index'], 'active'=> $this->context->getUniqueId() == 'task', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewTasks')],
                    ['label' => 'Новости', 'url' => ['news/index'], 'active'=> $this->context->getUniqueId() == 'news', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewNews')],

                    ['label' => 'СПРАВОЧНИКИ', 'header' => true, 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewGroups') or Yii::$app->user->can('viewPositions') or Yii::$app->user->can('viewEmployees') or Yii::$app->user->can('viewDocumentsType')],
                    ['label' => 'Группы', 'url' => ['group/index'], 'active'=> $this->context->getUniqueId() == 'group', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewGroups')],
                    ['label' => 'Должности', 'url' => ['position/index'], 'active'=> $this->context->getUniqueId() == 'position', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewPositions')],
                    ['label' => 'Сотрудники', 'url' => ['employee/index'], 'active'=> $this->context->getUniqueId() == 'employee', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewEmployees')],
                    ['label' => 'Типы документов', 'url' => ['document-type/index'], 'active'=> $this->context->getUniqueId() == 'document-type', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('viewDocumentsType')],
                    ['label' => 'НАСТРОЙКИ', 'header' => true, 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'Пользователи', 'url' => ['user/index'], 'active'=> $this->context->getUniqueId() == 'user', 'icon' => '', 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'Роли', 'url' => ['auth-item/index'], 'active'=> $this->context->getUniqueId() == 'auth-item', 'icon' => '', 'visible' => Yii::$app->user->can('admin')],
                ],
            ]);
            ?>
        </nav>
    </div>

</aside>