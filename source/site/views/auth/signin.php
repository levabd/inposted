<?php
/** @var $this \site\controllers\AuthController */
/** @var $model \site\models\forms\Signin */

/** @var $form \CActiveForm */
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

$baseUrl = Yii()->baseUrl;
?>

<div class="well" style="background:#ffffff;margin-top:10px;"><!--вход на сайт-->
    <?php if ($error = User()->getError()): ?>
        <div class="alert alert-error"><?=$error?></div>
    <?php endif?>
    <div class="well" style="background:#fffd74;margin:-19px -19px -10px -19px ; border: 1px solid #fffd74;">
        <a href="" style="color:#54211d;font-size:18px;text-decoration: underline;"><b>Sign in</b></a>
    </div>
    <br/>

    <div style="text-align:center;">
        <?=$form->textField($model, 'username', ['placeholder' => $model->getAttributeLabel('username'), 'style' => 'width:85%;'])?>
        <?=$form->error($model, 'username')?>

        <?=$form->passwordField($model, 'password', array('placeholder' => $model->getAttributeLabel('password'), 'style' => 'width:85%;')); ?>
        <?=$form->error($model, 'password')?>
        <input class="btn" type="submit" style="text-decoration:underline;" value="Sign in"/>
        <br/>
<?php /*
        <div style="text-align:center;color:#000000;clear:both;">Login with :</div>
					<span class="soc_seti">
                        <a href=""><img src="<?=$baseUrl?>/img/f.png"></a>
						<a href=""><img src="<?=$baseUrl?>/img/b.png"></a>
						<a href=""><img src="<?=$baseUrl?>/img/t.png"></a>
						<a href=""><img src="<?=$baseUrl?>/img/g.png"></a>
						<a href=""><img src="<?=$baseUrl?>/img/h.png"></a>
                    </span><br/>
*/?>
        <a href="<?=$this->createUrl('restore')?>" class="ajax" style="color:#686968;text-decoration: underline;">
            Forgot your password?
        </a>
        <br/>
        Not registered?
        <a href="#signup" data-toggle="modal" style="color:#686968;text-decoration: underline;">
            Join us!
        </a>
    </div>
</div>
<?php $this->endWidget(); ?>
<!--конец вход на сайт-->
<script type="text/javascript">
    $(function () {
        var $form = $('#signin-form');
        var $username = $form.find('#Signin_username');
        var $restore = $('#restore');
        $restore.click(function (e) {
            var name = $username.val();
            var url = e.target.href;

            if (name) {
                url += '?user=' + encodeURIComponent(name);
            }
            window.location = url;
            e.preventDefault();
        })
    })
</script>