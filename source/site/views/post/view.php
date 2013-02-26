<?php
/** @var $this \site\controllers\PostController */
/** @var $post \site\models\Post */

$user = Yii()->user->model;
$interests = $post->interests;

isset($style) || ($style = 'background:#f4f2e7;margin-top:10px;');
isset($necessarily) || ($necessarily = null);
isset($thanks) || ($thanks = null);
?>

<div class="well" style="<?=$style?>"><!--мини-пост-->
    <?php if ($necessarily): ?>
        <div class="necessarily"><?=$necessarily?></div>
    <?php endif;#($necessarily)?>

    <?php if ($thanks): ?>
        <div class="thanks"><?=$thanks?></div>
    <?php endif;#($thanks)?>
    <div class="row-fluid">

        <?php if (!$this->author && ($author = $post->author)): ?>
            <div class="span1">
                <a
                    href="<?=$this->createUrl('user/view', ['nickname' => $author->nickname])?>"
                    style="color:#54211d;text-decoration: underline;display:block;text-align:center;"
                    >
                    <b><?=$author->nickname?></b>
                </a>

                <div class="avat">
                    <img alt="<?=$author->nickname?>" class="face" src="<?=$author->getAvatarUrl(56)?>" title="<?=$author->nickname?>">
                </div>
            </div>
        <?php endif;#($this->author)?>

        <div class="<?=$this->author ? 'span11' : 'span10'?>" style="padding-left:20px;">
            <b>
                <?php foreach ($interests as $index => $interest): ?>
                    <?php
                    $comma = isset($interests[$index + 1]);
                    echo $interest;
                    if ($user && !$user->hasInterest($interest)):
                        ?>
                        <button data-url="<?=$this->createUrl('/interest/attach', ['id' => $interest->id])?>" class="btn btn-1mini attach-interest" data-id=<?=$interest->id?>>+</button><?=$comma?', ':''?>
                    <?php
                    elseif($comma):
                        echo ', ';
                    endif;#($user && !$user->hasInterest($interest))
                    ?>
                <?php endforeach;#($post->interests as $interest)?>
            </b>
            <i style="float:right;"><?=Yii()->dateFormatter->format('HH:mm dd MMM yyy', $post->dateSubmitted)?></i>

            <p> <?=$post->htmlContent?></p>
        </div>
        <div class="span1" style="margin-right:0px;line-height:30px;margin-top:-7px;">
            <?php if ($user && $post->User_id != $user->id): ?>

                <a href=""><img src="<?=Yii()->baseUrl?>/img/star_null.png" style="margin-left:4px;"></a><br>

                <?php if ($user->canVote($post)): ?>
                    <a href="<?=$this->createUrl('/post/vote', ['id' => $post->id, 'type' => 'like'])?>" class="btn btn-mini">
                        <i class=" icon-thumbs-up"></i>
                    </a>

                    <a href="<?=$this->createUrl('/post/vote', ['id' => $post->id, 'type' => 'spam'])?>" class="btn btn-mini">
                        <i class=" icon-ban-circle"></i></a>
                    <a href="<?=$this->createUrl('/post/vote', ['id' => $post->id, 'type' => 'abuse'])?>" class="btn btn-mini">
                        <i class="  icon-warning-sign"></i>
                    </a>
                <?php endif;#($user->canVote($post))?>
            <?php endif;#($user)?>
        </div>
    </div>
</div>