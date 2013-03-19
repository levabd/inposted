<?php
use site\models\Post;

/** @var $this \site\controllers\PostController */
/** @var $model Post */

?>


<div class="modal hide" style="background:#f8f6ef;" id="createPost" tabindex="-1" role="dialog" aria-labelledby="createPostLabel" aria-hidden="true">

    <div class="modal-header my_modal1">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>

        <h3 id="createPostLabel" class="my_modal3">
            <img src="<?=Yii()->baseUrl?>/img/logo_icon.png">
            <span id="create-post-left"><?=Post::MAX_POST_SIZE - $model->contentLength?></span> characters left
        </h3>
    </div>
    <div class="modal-body mini_post_ser">
        <div class="row-fluid">
            <?php
            /** @var $form \CActiveForm */
            $form = $this->beginWidget(
                'CActiveForm',
                [
                'id'                     => 'create-post-form',
                'enableAjaxValidation'   => false,
                'enableClientValidation' => false,
                'errorMessageCssClass'   => 'text-error',
                ]
            );
            ?>
            <div class="span8">
                <?=$form->textArea($model, 'content', ['id' => 'create-post-textarea', 'class' => 'span12', 'rows' => '10'])?>
                <?=$form->error($model, 'content')?>

                <?=CHtml::errorSummary($model)?>
                <span class="hint">Shift+Enter to submit</span>
            </div>
            <?php $this->endWidget()?>
            <div class="span4">
                <?php $this->widget(
                    'site\controllers\InterestController',
                    [
                    'widgetId'     => 'new-post-interests',
                    'actionParams' => [
                        'checked' => CHtml::listData($model->interests, 'id', 'id'),
                        'filter' => false,
                    ]
                    ]
                );
                ?>
            </div>
        </div>
    </div>

</div>

