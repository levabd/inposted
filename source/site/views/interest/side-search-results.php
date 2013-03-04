<?php
/** @var $this \site\controllers\InterestController */
/** @var $parent \site\models\Interest */
/** @var $interests \site\models\Interest[] */
/** @var $verb string */

$user = Yii()->user->model;
?>

    <div class="result_search">
        <ul>
            <li>
                <input
                    type="text"
                    class="input-medium create-new-interest-input"
                    style="width:80%;"
                    id="create-<?=$this->widgetId?>"
                    value="<?=$verb?>"
                    data-verb="<?=$verb?>"
                    data-parent-id="<?=$parent ? $parent->id : null?>"
                    placeholder="Create a new category"
                    data-url="<?=$this->createUrl('create', ['parentId' => $parent ? $parent->id : null])?>"
                    >
                <button class="btn btn-1mini create-new-interest-button" style="margin-top:-10px;" data-input-id="#create-<?=$this->widgetId?>">+</button>
            </li>

            <?php foreach ($interests as $interest): ?>

                <li class="main" data-id="<?=$interest->id?>"
                    data-additional-url="<?=$this->createUrl('/interest/additional', ['parentId' => $interest->id])?>">
                    <?php if (!$user->hasInterest($interest)): ?>
                        <button
                            class="btn btn-1mini attach-interest"
                            data-url="<?=$this->createUrl('attach', ['id' => $interest->id, 'parentId' => $parent ? $parent->id : null])?>"
                            data-parent-id="<?=$parent ? $parent->id : null?>"
                            data-id=<?=$interest->id?>
                            >
                            +
                        </button>
                    <?php endif;#(!$user->hasInterest($interest)) ?>

                    <a href="#" data-parent-id="<?=$interest->id?>" class="lock-parent-interest">
                        <?=$interest?>
                    </a>
                    <button class="btn btn-2mini but_sear" data-id="<?=$interest->id?>">
                        <img src="<?=Yii()->baseUrl?>/img/sear.png">
                    </button>
                </li>

                <?php /*
    <a href="#" data-parent-id="<?=$interest->id?>" class="lock-parent-interest">
        <b style="line-height:25px;"><?=$interest->name?></b>
    </a>
    <?php if ($user): ?>
        <?php if (!$user->hasInterest($interest)): ?>
            <button
                class="btn btn-1mini attach-interest"
                data-url="<?=$this->createUrl('attach', ['id' => $interest->id, 'parentId' => $parent ? $parent->id : null])?>"
                data-parent-id="<?=$parent ? $parent->id : null?>"
                data-id=<?=$interest->id?>
                >
                +
            </button>
        <?php elseif($parent && !$interest->hasParent($parent)): ?>
            <button
                class="btn btn-1mini attach-interest"
                data-url="<?=$this->createUrl('attach', ['id' => $interest->id, 'parentId' => $parent->id])?>"
                data-parent-id="<?=$parent ? $parent->id : null?>"
                >
                ^
            </button>
        <?php endif;#(!$user->hasInterest($interest)) ?>
    <?php endif#($user)?>
    <br>
 */
                ?>
            <?php endforeach#($interests as $interest:?>
        </ul>
    </div>
<?php /*
<input
    type="text"
    class="input-medium create-new-interest-input"
    style="width:80%;"
    id="create-<?=$this->widgetId?>"
    value="<?=$verb?>"
    data-verb="<?=$verb?>"
    data-parent-id="<?=$parent ? $parent->id : null?>"
    placeholder="Create a new category"
    data-url="<?=$this->createUrl('create', ['parentId' => $parent ? $parent->id : null])?>"
    >
<button class="btn btn-1mini create-new-interest-button" style="margin-top:-10px;" data-input-id="#create-<?=$this->widgetId?>">+</button>
    */