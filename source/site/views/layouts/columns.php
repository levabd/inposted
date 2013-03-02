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
                $this->controllerWidget('interest/ofUser', ['actionParams' => ['id' => $this->author->id]]);
            } elseif (!Yii()->user->isGuest) {
                $this->widget('site\widgets\user\User');
                $this->controllerWidget('interest/index', ['widgetId' => 'sidebar-interests', 'actionParams' => ['filter' => true]]);
                $this->controllerWidget('post/favorites', ['widgetId' => 'favorites']);
            }
            ?>

            <div class="well" style="background:#ffffff;">
                <div class="well" style="background:#fffd74;margin:-19px -19px -10px -19px ; border: 1px solid #fffd74;">
                    <a href="" style="color:#54211d;font-size:18px;text-decoration: underline;"><b>About</b></a>
                </div>
                <br/>

                (c)2012 Copyright
            </div>
        </div>
        <!--конец правая часть контента-->
    </div>
<?php $this->endContent() ?>