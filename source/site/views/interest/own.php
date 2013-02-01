<?php
/** @var $this \site\controllers\InterestController */
/** @var $interests \site\models\Interest[] */
/** @var $verb string */
/** @var $checked array */

$except = array_values(CHtml::listData($interests, 'id', 'id'));
?>
<?php foreach ($interests as $interest): ?>
    <label class="checkbox" style="line-height:18px;">
        <?=
        CHtml::checkBox(
            'interests[]',
            in_array($interest->id, $checked),
            [
            'value'      => $interest->id,
            'class'      => 'own-interest',
            'data-group' => $this->widgetId,
            'id' => null,
            ]
        );

        ?>
        <b><?=$interest?> </b>
        <button
            class="btn btn-1mini attach-interest"
            data-url="<?=$this->createUrl('attach', ['id' => $interest->id, 'detach' => true])?>"
            >
            x
        </button>
    </label>
<?php endforeach;#($interests as $interest)?>

<br>
<div class="poisk"> <!--форма поиска-->
    <input
        class="quicksearch" type="text" style="width:75%;"
        class="input" placeholder="Search"
        data-url="<?=$this->createUrl('search')?>"
        data-except='<?=CJSON::encode($except)?>'
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
            'actionParams' => compact('verb', 'except')
            ]
        );
    }
    ?>
</div>