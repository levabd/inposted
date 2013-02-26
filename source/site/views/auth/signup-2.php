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
    'focus'                  => array($model, 'name'),
    'errorMessageCssClass'   => 'text-error',
    'htmlOptions'            => ['enctype' => 'multipart/form-data'],
    ]
);
?>
<div class="modal" style="z-index: 10002;background:#f8f6ef;top:280px;" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-header" style="background:#fafaf5;">
        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>-->

        <h3 id="myModalLabel" style="display:inline-block;font-family:verdana;font-size:18px;color:#464646;">
            <img src="<?=Yii()->baseUrl?>/img/logo_icon.png"> &nbsp;
        </h3>
    </div>
    <div class="modal-body" style="background:#efefef;">
        <div class="join_us">
            <?=$form->textField($model, 'name', ['placeholder' => $model->getAttributeLabel('name'), 'style' => 'width:96%;']); ?>
            <?=$form->error($model, 'name')?>

            <?=$form->dropDownList($model, 'Country_id', $countries, ['style' => 'width:99%; #999999;']); ?>
            <?=$form->error($model, 'Country_id')?>
            <?=$form->fileField($model, 'avatarUpload'); ?>
            <?=$form->error($model, 'avatarUpload')?>
            <button class="btn" style="text-decoration: underline;float:right;margin-left:10px;" type="submit">Next</button>
            <button type="submit" class="btn" style="text-decoration: underline;float:right;">Skip</button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
