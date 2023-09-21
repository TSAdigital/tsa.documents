<?php
/* @var $content string */

use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\helpers\Inflector;

?>

<div class="content-wrapper">
    <div class="content-header pb-0">
        <div class="container-fluid pb-0">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-7 pb-0">
                    <h1 class="m-0 p-0 overflow-wrap text-truncate">
                        <?php
                        if (!is_null($this->title)) {
                            echo Html::encode($this->title);
                        } else {
                            echo Inflector::camelize($this->context->id);
                        }
                        ?>
                    </h1>
                    <?php
                    echo Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'options' => [
                            'class' => 'mb-2 pb-0'
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-5 pb-0">
                    <p class="text-md-right mb-0" style="margin-right:-5px">
                        <?php
                        if(isset($this->params['buttons'])){
                            foreach ($this->params['buttons'] as $button){
                                echo $button;
                            }
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="content text-break">
        <?= $content ?>
    </div>
</div>