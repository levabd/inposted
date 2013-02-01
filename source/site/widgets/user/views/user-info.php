<?php
/** @var $this \site\widgets\user\User */
/** @var $user \site\models\User */
?>

<div class="span12" style="margin-left:0px;">
    <div class="well" style="background:#ffffff;margin-top:10px;">
        <div class="info_user ">
            <a href="<?=Yii()->createUrl('/user/view', ['nickname' => $user->nickname])?>">
                <?=$user->name?> (<?=$user->nickname?>)
            </a>
            &nbsp;&nbsp;&nbsp; Reputation: <?=$user->reputation?>&nbsp;&nbsp;&nbsp; Level: <?=$user->level?></div>
        <div class="row-fluid">
            <div class="info_user_left ">
                <div class="avat_big">
                    <img alt="bender" class="face" src="<?=$user->avatarUrl?>" title="fry">
                </div>
                <div class="flag">
                    <?php if ($user->country): ?>
                        <a href=""><img alt="<?=$user->country?>" src="<?=$user->country->flagUrl?>" title="<?=$user->country?>"></a><br>
                    <?php endif;#($user->country)?>
                </div>
            </div>
            <div class="info_user_right ">
                <p>
                    <?=nl2br(strip_tags($user->info)) ?: 'no info'?>
                </p>
            </div>

        </div>
    </div>
</div>