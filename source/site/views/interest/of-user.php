<?php
$user = Yii()->user->model;
?>

<?php foreach($interests as $interest):?>
<label class="checkbox">
    <?=
    CHtml::checkBox(
        'interests[]',
        null,
        [
        'value'      => $interest->id,
        'class'      => 'posts-filter',
        'data-group' => $this->widgetId,
        'id'         => null,
        ]
    );

    ?>
    <b><?=$interest?></b>
    <?php if($user && !$user->hasInterest($interest)):?>
        <button class="btn btn-1mini">+</button>
    <?php endif;#($user && !$user->hasInterest($interest))?>
</label>
<?php endforeach;#($interests as $interest)?>
