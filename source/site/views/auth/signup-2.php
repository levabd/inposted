<?php
/** @var $this \site\controllers\AuthController */
/** @var $model \site\models\User */
$countries = site\models\Country::model()->listData();
?>
<?php
/** @var $form \CActiveForm */
$form = $this->beginWidget(
    'CActiveForm',
    [
    'id'                     => 'signup-form',

    'enableAjaxValidation'   => false,
    'enableClientValidation' => false,
    'errorMessageCssClass'   => 'text-error',
    'htmlOptions'            => ['enctype' => 'multipart/form-data'],
    ]
);
?>
<div class="modal" style="z-index: 10002;background:#efefef;" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header my_modal1">
        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>-->

        <h3 id="myModalLabel" class="my_modal3">
            <img src="<?=Yii()->baseUrl?>/img/logo_icon.png"> A little more...
        </h3>
    </div>
    <div class="modal-body mini_post_ser">
        <div class="join_us">
            <?=$form->textField($model, 'name'); ?>
            <?=$form->error($model, 'name')?>

            <?=$form->dropDownList($model, 'Country_id', $countries, ['style' => 'width:99%;']); ?>
            <?=$form->error($model, 'Country_id')?>
            <?=$form->fileField($model, 'avatarUpload'); ?>
            <?=$form->error($model, 'avatarUpload')?>
            <button class="btn mynext" type="submit">Next</button>
            <button type="submit" class="btn mypre">Skip</button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
