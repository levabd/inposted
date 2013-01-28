<?php
/** @var $this \site\controllers\InterestController */
/** @var $interests \site\models\Interest[] */
/** @var $verb string */
?>
<?php
$this->renderPartial('additional', get_defined_vars());
?>
<input
    type="text"
    class="input-medium "
    style="width:80%;"
    id="create-new-interest-input"
    value="<?=$verb?>"
    data-verb="<?=$verb?>"
    placeholder="Create a new category"
    data-url="<?=$this->createUrl('create')?>"
    >
<button class="btn btn-1mini" style="margin-top:-10px;" id="create-new-interest-button">+</button>