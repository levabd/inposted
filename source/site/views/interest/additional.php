<?php
/** @var $this \site\controllers\InterestController */
/** @var $interests \site\models\Interest[] */
?>
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