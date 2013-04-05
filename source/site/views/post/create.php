<?php
use site\models\Post;

/** @var $this \site\controllers\PostController */
/** @var $model Post */

?>
<div class="modal"
     style="background:#efefef;"
     tabindex="-1"
     aria-labelledby="createPostLabel"

     in-key-up="createNewPost()"
     in-key-up-key="13"
     in-key-up-mod="ctrl"

    >

    <div class="modal-header my_modal1">
        <button type="button" class="close" ng-click="close()" aria-hidden="true">x</button>

        <h3 id="createPostLabel" class="my_modal3">
            <img src="<?= Yii()->baseUrl ?>/img/logo_icon.png">
            <span id="create-post-left">{{<?=Post::MAX_POST_SIZE?> - newPost.content.length}}</span> characters left
        </h3>
    </div>
    <div class="modal-body mini_post_ser">
        <div class="row-fluid">
            <div class="span8">
                <textarea name="create-post-textarea" class="span12" rows="10" ng-model="newPost.content"></textarea>
                <button class="btn" ng-click="createNewPost()" ng-disabled="!enabled">Post</button>
                <span class="text-error" ng-show="newPost.error">{{newPost.error}}</span>
            </div>
            <div class="span4">
                <div class="well" id="mini_post_white">
                    <span class="myint"><b>Interests</b></span>
                    <?php $this->controllerWidget('interest/own', ['actionParams' => ['layout' => false, 'searchWidth' => 128]])?>
                </div>
            </div>
        </div>
    </div>
</div>

