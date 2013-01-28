<?php
/** @var $this \site\controllers\InterestController */
/** @var $interests \site\models\Interest[] */
/** @var $verb string */
?>
<?php foreach ($interests as $interest): ?>
    <label class="checkbox" style="line-height:18px;"><input type="checkbox"> <b><?=$interest?> </b>
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
        id="quicksearch" type="text" style="width:75%;"
        class="input" placeholder="Search"
        data-url="<?=$this->createUrl('search')?>"
        >
    <input id="go" type="submit">
</div>
<!--конец форма поиска-->

<div id="side_search_results">
    <?php
    if ($verb) {
        $this->widget(
            get_class($this),
            [
            'action'       => 'search',
            'actionParams' => compact('verb')
            ]
        );
    }
    ?>
</div>