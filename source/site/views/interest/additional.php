<?php
/** @var $this \site\controllers\InterestController */
/** @var $parent \site\models\Interest */
/** @var $interests \site\models\Interest[] */

/** @var $user \site\models\User */
$user = Yii()->user->model;
?>
    <li class="additional fixing_res"><?=$parent?></li>
<?php foreach ($interests as $interest): ?>
    <li class="additional">
        <?php if($user && !$user->hasInterest($interest)):?>
            <button
                class="btn btn-1mini attach-interest"
                data-url="<?=$this->createUrl('attach', ['id' => $interest->id, 'parentId' => $parent ? $parent->id : null])?>"
                data-parent-id="<?=$parent ? $parent->id : null?>"
                data-id=<?=$interest->id?>
                >
                +
            </button>
        <?php endif;#($user && !$user->hasInterest($interest))?>
        <?=$interest?>
    </li>
<?php endforeach#($interests as $interest:?>