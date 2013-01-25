<?php
/** @var $this \site\widgets\user\User */
/** @var $user \site\models\User */
?>
<div class="well" style="background:#ffffff;"><!--инфо о пользователе-->
    <div class="well" style="background:#fffd74;margin:-19px -19px -10px -19px ; border: 1px solid #fffd74;">
        <a href="" style="color:#54211d;font-size:18px;text-decoration: underline;"><b><?=$user->firstName?></b></a>
    </div>
    <br/>

    <div class="row-fluid">
        <div class="span4">
            <div class="avat">
                <img alt="bender" src="<?=$user->avatarUrl?>" title="bender" align="middle">
            </div>
        </div>
        <div class="span8">
            Reputation: <?=$user->reputation?><br/>
            Level: <?=$user->level?><br/>
            <?php if($user->country):?>
            <a href=""><img alt="bender" src="<?=$user->country->flagUrl?>" title="<?=$user->country->name?>"></a><br/>
            <?php endif;#($user->country)?>
        </div>
    </div>
</div><!--конец инфо о пользователе-->
