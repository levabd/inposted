<?php
/** @var $this \site\controllers\AuthController */
/** @var $model \site\models\User */
$model = \site\models\User::model();
$countries = site\models\Country::model()->listData();

?>
<div class="modal"
     style="background:#efefef;"
     tabindex="-1"
     aria-labelledby="signupModalLabel"
    >
    <div class="modal-header my_modal1">
        <button type="button" class="close my_modal2" ng-click="close()" aria-hidden="true">x</button>
        <h3 id="signupModalLabel" class="my_modal3">
            <img src="<?= Yii()->baseUrl ?>/img/logo_icon.png">
            <span ng-show="step == 1">Присоединиться</span>
            <span ng-show="step == 2">Немножко больше о себе...</span>
        </h3>
    </div>
    <div class="modal-body mini_post_ser">
        <div class="join_us">
            <div ng-show="step == 1">
				<input
                    type="text"
                    name="nickname"
                    ng-model="user.nickname"
                    in-blur="validate('nickname')"
                    placeholder="<?= $model->getAttributeLabel('nickname') ?>"
                    />
                <div class="text-error error-message" ng-show="user.errors.nickname">{{user.errors.nickname}}</div>
				
                <input
                    type="text"
                    name="email"
                    ng-model="user.email"
                    in-blur="validate('email')"
                    placeholder="<?= $model->getAttributeLabel('email') ?>"
                    />
                <div class="text-error error-message" ng-show="user.errors.email">{{user.errors.email}}</div>

                <input
                    type="password"
                    name="newPassword"
                    ng-model="user.newPassword"
                    in-blur="validate('newPassword')"
                    placeholder="<?= $model->getAttributeLabel('newPassword') ?>"
                    />
                <div class="text-error error-message" ng-show="user.errors.newPassword">{{user.errors.newPassword}}</div>
            </div>
            <div ng-show="step == 2">
                <input type="text" name="name" ng-model="user.name" placeholder="<?= $model->getAttributeLabel('name') ?>"/>
                <div class="text-error error-message" ng-show="user.errors.name">{{user.errors.name}}</div>
                <?= CHtml::dropDownList('country', array_keys($countries)[0], $countries, ['style' => 'width:99%;', 'ng-model' => 'user.Country_id']) ?>

                <input type="file" in-file-upload="uploadAvatar" name="User[avatarUpload]" data-url="<?=Yii()->createUrl('user/avatarUpload')?>"/>
                <? //$form->fileField($model, 'avatarUpload') ?>
                <? //$form->error($model, 'avatarUpload') ?>
            </div>
            <button
                class="btn"
                style="width: 67px; text-align: left"
                ng-class="{'mypre' : step==1, 'mynext': step==2}"
                ng-click="submit()"
                ng-disabled="_wait"
                in-dots="_wait">
                Далее
            </button>
            <button class="btn mypre" ng-show="step==2" ng-click="close()" ng-disabled="_wait">Пропустить</button>
        </div>
    </div>
</div>