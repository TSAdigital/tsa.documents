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
                    ['label' => 'НАВИГАЦИЯ', 'header' => true],
                    ['label' => 'Документы', 'url' => ['document/index'], 'active'=> $this->context->getUniqueId() == 'document', 'icon' => ''],
                    ['label' => 'Задачи', 'url' => ['task/index'], 'active'=> $this->context->getUniqueId() == 'task', 'icon' => ''],

                    ['label' => 'СПРАВОЧНИКИ', 'header' => true, 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('editor')],
                    ['label' => 'Группы', 'url' => ['group/index'], 'active'=> $this->context->getUniqueId() == 'group', 'icon' => '', 'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('editor')],
                    ['label' => 'Должности', 'url' => ['position/index'], 'active'=> $this->context->getUniqueId() == 'position', 'icon' => '', 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'Сотрудники', 'url' => ['employee/index'], 'active'=> $this->context->getUniqueId() == 'employee', 'icon' => '', 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'Типы документов', 'url' => ['document-type/index'], 'active'=> $this->context->getUniqueId() == 'document-type', 'icon' => '', 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'НАСТРОЙКИ', 'header' => true, 'visible' => Yii::$app->user->can('admin')],
                    ['label' => 'Пользователи', 'url' => ['user/index'], 'active'=> $this->context->getUniqueId() == 'user', 'icon' => '', 'visible' => Yii::$app->user->can('admin')],
                ],
            ]);
            ?>
        </nav>
    </div>

</aside>