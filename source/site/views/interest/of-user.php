<?php
$user = Yii()->user->model;
?>

<?php foreach($interests as $interest):?>
<label class="checkbox">
    <b><?=$interest?></b>
    <?php if($user && !$user->hasInterest($interest)):?>
        <button class="btn btn-1mini">+</button>
    <?php endif;#($user && !$user->hasInterest($interest))?>
</label>
<?php endforeach;#($interests as $interest)?>
