<?php
/** @var $this \site\widgets\user\User */
/** @var $user \site\models\User */
?>

<div class="span12 margin_left_0">
    <div class="well" id="block_info_user">
        <div class="info_user ">
            <a href="<?= Yii()->createProfileUrl($user) ?>">
                <?=$user->name?> (<?=$user->nickname?>)
            </a>
            <b>Reputation: <?=$user->reputation?></b>
            <b>Level: <?=$user->level?></b>
        </div>
        <div class="info_user_left ">
            <div class="avat_big">
                <a href="<?= Yii()->createProfileUrl($user) ?>">
                    <img alt="<?= $user->nickname ?>" class="face" src="<?= $user->getAvatarUrl(73) ?>" title="<?= $user->nickname ?>">
                </a>
            </div>
            <div class="flag_and_letter">
                <?php if ($user->country): ?>
                    <img alt="<?= $user->country ?>" src="<?= $user->country->flagUrl ?>" title="<?= $user->country ?>">
                <?php endif;#($user->country)?>

                <?php if (!Yii()->user->isGuest && $user->id != Yii()->user->id): ?>
                    <span class='clickable' ng-click="showPM(settings.page.owner)" style="outline: none">
                        <img alt="private message" src="<?=Yii()->baseUrl?>/img/let.png">
                    </span>
                <?php endif;#(!Yii()->user->isGuest && $user->id != Yii()->$user->id):?>
            </div>
        </div>
        <div class="info_user_right ">
            <p>
                <?=nl2br(strip_tags($user->info)) ? : 'нет информации'?>
            </p>
            <?php if ($user->homepage): ?>
                <p><a href="<?= $user->homepage ?>"><?=$user->homepage?></a></p>
            <?php endif;#($user->homepage)?>
        </div>
    </div>
</div>