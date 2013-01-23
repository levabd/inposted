<?php
/** @var $this \site\controllers\AuthController */
/** @var $model \site\models\User */

$countries = CHtml::listData(site\models\Country::model()->sort()->findAll(), 'id', 'name');
?>
<div class="span5 well signupForm">
    <?php
    /** @var $form \CActiveForm */
    $form = $this->beginWidget(
        'CActiveForm',
        [
        'id'                     => 'signup-form',

        'enableAjaxValidation'   => false,
        'enableClientValidation' => false,
        'focus'                  => array($model, 'name'),
        'errorMessageCssClass'   => 'help-block',
        'htmlOptions'            => ['enctype' => 'multipart/form-data'],
        ]
    );
    ?>
    <fieldset>
        <div class="control-group <?=$model->hasErrors('name') ? 'error' : ''?>">
            <?=$form->textField($model, 'name', ['placeholder' => $model->getAttributeLabel('name'), 'class' => 'span5']); ?>
            <?=$form->error($model, 'name')?>
        </div>
        <div class="control-group <?=$model->hasErrors('Country_id') ? 'error' : ''?>">
            <?=$form->dropDownList($model, 'Country_id', $countries, ['class' => 'span5']); ?>
            <?=$form->error($model, 'Country_id')?>
        </div>
        <div class="control-group <?=$model->hasErrors('avatarUpload') ? 'error' : ''?>">
            <?=$form->fileField($model, 'avatarUpload'); ?>
            <?=$form->error($model, 'avatarUpload')?>
        </div>
        <div class="control-group">
            <button type="submit" class="btn btn-primary">Next</button>
            <button type="submit" class="btn btn-primary">Skip</button>
        </div>
    </fieldset>
    <?php $this->endWidget(); ?>
</div>