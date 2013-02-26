<?php
/** @var $this \site\widgets\user\User */
/** @var $user \site\models\User */
?>
<div class="well" style="background:#ffffff;"><!--инфо о пользователе-->
    <div class="well" style="background:#fffd74;margin:-19px -19px -10px -19px ; border: 1px solid #fffd74;">
        <a href="<?=Yii()->createUrl('/user/view', ['nickname' => $user->nickname])?>" style="color:#54211d;font-size:18px;text-decoration: underline;">
            <b><?=$user->firstName?></b>
        </a>
    </div>
    <br>

    <div class="row-fluid">
        <div class="span5">
            <div class="avat">
                <img alt="<?=$user->nickname?>" src="<?=$user->getAvatarUrl(56)?>" title="bender" align="middle">
            </div>
        </div>
        <div class="span7">
            Reputation: <?=$user->reputation?><br>
            Level: <?=$user->level?><br>
            <?php if ($user->country): ?>
                <a href=""><img alt="<?=$user->country?>" src="<?=$user->country->flagUrl?>" title="<?=$user->country->name?>"></a><br>
            <?php endif;#($user->country)?>
        </div>
    </div>
</div>