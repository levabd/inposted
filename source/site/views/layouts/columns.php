<?php
/** @var $this site\components\Controller */
/** @var $content string */

$baseUrl = Yii()->baseUrl;

$this->beginContent('//layouts/main');
?>
<?php if ($this->author) {
    $this->widget('site\widgets\user\User', ['user' => $this->author, 'view' => 'user-info']);
} ?>

    <div class="row-fluid">
        <div class="span9"> <!--левая часть контента-->
            <?=$content?>
        </div>
        <!-- конец левая часть контента-->

        <div class="span3"> <!--правая часть контента-->
            <?php
            if (Yii()->user->isGuest) {
                $this->controllerWidget('auth/signin');
                $this->controllerWidget('auth/signup');
            }
            if ($this->author) {
                if(!($this->id == 'pm' && $this->getAction()->id == 'index')){
                    $this->controllerWidget('interest/ofUser', ['actionParams' => ['id' => $this->author->id]]);
                }
            } elseif (!Yii()->user->isGuest) {
                $this->widget('site\widgets\user\User');
                $this->controllerWidget('interest/own', ['widgetId' => 'sidebar-interests', 'actionParams' => ['filter' => true]]);
                $this->controllerWidget('post/favorites', ['widgetId' => 'favorites']);
            }
            ?>

            <div class="well mini_post_white"><!--о нас-->
                <div class="well yellow">
                    <span class="ref_main"><b>About</b></span>
                </div>
                <br/>
                &copy; 2012 Copyright
            </div>
            <!--конец о нас-->
        </div>
        <!--конец правая часть контента-->
    </div>
<?php $this->endContent() ?>