<?php
/** @var $this \site\controllers\InterestController */
/** @var $parent \site\models\Interest */
/** @var $interests \site\models\Interest[] */
/** @var $verb string */
?>
<?php if ($parent): ?>
    <a href="" class="btn"><?=$parent?></a>
<?php endif;#($parent)?>

<?php foreach ($interests as $interest): ?>
    <b style="line-height:25px;"><?=$interest?></b>
    <button
        class="btn btn-1mini attach-interest"
        data-url="<?=$this->createUrl('attach', ['id' => $interest->id])?>"
        >
        +
    </button>
    <br>
<?php endforeach#($interests as $interest:?>
<input
    type="text"
    class="input-medium create-new-interest-input"
    style="width:80%;"
    id="<?=$this->widgetId?>"
    value="<?=$verb?>"
    data-verb="<?=$verb?>"
    placeholder="Create a new category"
    data-url="<?=$this->createUrl('create')?>"
    >
<button class="btn btn-1mini create-new-interest-button" style="margin-top:-10px;" data-input-id="#<?=$this->widgetId?>">+</button>