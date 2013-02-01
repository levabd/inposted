<?php
/** @var $this \site\controllers\InterestController */
/** @var $parent \site\models\Interest */
/** @var $interests \site\models\Interest[] */
/** @var $verb string */
/** @var $checked array */

$except = [];
$parentId = $parent ? $parent->id : null;

isset($filter) || ($filter = false);
?>
<?php foreach ($interests as $interest): ?>
    <label class="checkbox" style="line-height:18px;">
        <?=
        CHtml::checkBox(
            'interests[]',
            in_array($interest->id, $checked),
            [
            'value'      => $interest->id,
            'class'      => 'own-interest' . ($filter ? ' posts-filter' : ''),
            'data-group' => $this->widgetId,
            'id'         => null,
            ]
        );

        ?>
        <b><?=$interest->fullName?> </b>
        <button
            class="btn btn-1mini attach-interest"
            data-url="<?=$this->createUrl('attach', ['id' => $interest->id, 'detach' => true])?>"
            data-parent-id="<?=$parentId?>"
            >
            x
        </button>
    </label>
<?php endforeach;#($interests as $interest)?>

<br>
<div class="poisk"> <!--форма поиска-->
    <?php if ($parent): ?>
        <a class="btn parent" style="float:left;" href="#" title="clear parent"><?=CHtml::encode('<')?></a>
    <?php endif;#($parent)?>
    <input
        class="quicksearch" type="text" style="width:<?=$parent ? 50 : 75?>%;"
        class="input" placeholder="<?='Search' . ($parent ? " in $parent->name" : '')?>"
        data-url="<?=$this->createUrl('search', ['parentId' => $parentId])?>"
        data-except='<?=CJSON::encode($except)?>'
        value="<?=$verb?>"
        >
    <input class="go" type="submit">
</div>
<!--конец форма поиска-->

<div class="side_search_results">
    <?php
    if ($verb) {
        $this->widget(
            get_class($this),
            [
            'action'       => 'search',
            'actionParams' => compact('verb', 'except', 'parentId')
            ]
        );
    }
    ?>
</div>