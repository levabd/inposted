<?php
/** @var $this \site\controllers\AuthController */
/** @var $model \site\models\forms\Signin */

/** @var $form \CActiveForm */

$baseUrl = Yii()->baseUrl;
?>

<div class="well mini_post_white" ng-controller="inposted.controllers.auth">
    <div class="well yellow">
        <span class="ref_main">
            <b ng-show="state.is('signin')">Зайти</b>
            <b ng-show="state.is('restore')">Отправить новый пароль</b>
        </span>
    </div>
    <br/>

    <div class="text-error" ng-show="error">Приносим извинения. Произошла ошибка. Пожалуйста, попробуйте еще раз или отправьте письмо на info@inposted.com</div>

    <div style="text-align:center;" ng-show="state.is('signin')">
        <form action="<?= $this->createUrl('auth/signin') ?>">
            <input placeholder="E-Mail" style="width:85%;" name="Signin[username]" type="text" ng-model="user.username" auto-fill-sync="#signin-button">

            <div class=" text-error error-message" ng-show="errors.username">
                {{errors.username}}
            </div>

            <input placeholder="Password" style="width:85%;" name="Signin[password]" type="password" ng-model="user.password" auto-fill-sync="#signin-button">

            <div class="text-error error-message" ng-show="errors.password">
                {{errors.password}}
            </div>

            <div class="button_log">
                <button
                    id="signin-button"
                    class="btn"
                    type="button"
                    in-dots="_wait"
                    ng-disabled="_wait"
                    ng-click="signin()"
                    style="width: 83px; text-align: left; margin-right: 7px;"
                    >
                    Sign in
                </button>
            </div>

            <div class="text-success" ng-show="info">{{info}}</div>
        </form>

        <div style="text-align:center;color:#000000;clear:both;">Зайти из :</div>
        <span class="soc_seti">
            <a href="<?= Yii()->createUrl('/auth/oauth', ['provider' => 'Facebook']) ?>">
                <img src="<?= $baseUrl ?>/img/f.png">
            </a>
            <? /*<a href=""><img src="<?= $baseUrl ?>/img/b.png"></a> */ ?>
            <a href="<?= Yii()->createUrl('/auth/oauth', ['provider' => 'Twitter']) ?>">
                <img src="<?= $baseUrl ?>/img/t.png">
            </a>
            <a href="<?= Yii()->createUrl('/auth/oauth', ['provider' => 'Google']) ?>">
                <img src="<?= $baseUrl ?>/img/g.png">
            </a>
            <? /*<a href=""><img src="<?= $baseUrl ?>/img/h.png"></a> */ ?>
        </span><br/>
        <span class="ref_mess clickable" ng-click="state.set('restore')">
            Forgot your password?
        </span>
        <br/>

        Not registered?
        <span class="clickable ref_mess" ng-click="initSignup()">
            Join us!
        </span>
    </div>

    <div class="send_np" ng-show="state.is('restore')">
        <input
            type="text"
            name="username"
            ng-model="user.username"
            style="width: 85%;"
            placeholder="<?= $model->getAttributeLabel('username') ?>"
            />

        <div class=" text-error error-message" ng-show="errors.username">
            {{errors.username}}
        </div>
        <button
            type="button"
            class="btn"
            ng-click="state.set('signin')"
            ng-disabled="_wait"
            >
            Отменить
        </button>
        <button
            type="button"
            class="btn"
            in-dots="_wait"
            ng-disabled="_wait"
            ng-click="restore()"
            style="width: 83px; text-align: left;"
            >
            Выслать
        </button>
    </div>
</div>
