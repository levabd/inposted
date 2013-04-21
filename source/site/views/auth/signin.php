<?php
/** @var $this \site\controllers\AuthController */
/** @var $model \site\models\forms\Signin */

/** @var $form \CActiveForm */


$baseUrl = Yii()->baseUrl;
?>

<div class="well mini_post_white">
    <?php if ($error = User()->getError()): ?>
        <div class="alert alert-error"><?=$error?></div>
    <?php endif?>
    <div class="well yellow">
        <span class="ref_main"><b>Sign in</b></span>
    </div>
    <br/>

    <div style="text-align:center;">
        <?php
        $form = $this->beginWidget(
            'CActiveForm',
            [
            'id'                     => 'signin-form',
            'enableAjaxValidation'   => false,
            'enableClientValidation' => false,
            'focus'                  => [$model, 'username'],
            'errorMessageCssClass'   => 'text-error',
            ]
        );
        ?>
        <?=$form->textField($model, 'username', ['placeholder' => $model->getAttributeLabel('username'), 'style' => 'width:85%;'])?>
        <?=$form->error($model, 'username')?>

        <?=$form->passwordField($model, 'password', array('placeholder' => $model->getAttributeLabel('password'), 'style' => 'width:85%;')); ?>
        <?=$form->error($model, 'password')?>
        <div class="button_log">
            <input class="btn" type="submit" value="Sign in"/>
        </div>
        <?php $this->endWidget(); ?>


        <div style="text-align:center;color:#000000;clear:both;">Login with :</div>
        <span class="soc_seti">
            <a href="<?=Yii()->createUrl('/auth/oauth', ['provider' => 'Facebook'])?>">
                <img src="<?= $baseUrl ?>/img/f.png">
            </a>
            <?/*<a href=""><img src="<?= $baseUrl ?>/img/b.png"></a> */?>
            <a href="<?=Yii()->createUrl('/auth/oauth', ['provider' => 'Twitter'])?>">
                <img src="<?= $baseUrl ?>/img/t.png">
            </a>
            <a href="<?=Yii()->createUrl('/auth/oauth', ['provider' => 'Google'])?>">
                <img src="<?= $baseUrl ?>/img/g.png">
            </a>
            <?/*<a href=""><img src="<?= $baseUrl ?>/img/h.png"></a> */?>
        </span><br/>
        <a href="<?= $this->createUrl('restore') ?>" class="ref_mess ajax" data-no-loader="true">
            Forgot your password?
        </a>
        <br/>

        Not registered?
        <a href="#signup" data-toggle="modal" class="ref_mess">
            Join us!
        </a>
    </div>
</div>

