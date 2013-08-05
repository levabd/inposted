<?php
use site\models\Vote;

/** @var $this \site\controllers\PostController */
/** @var $post \site\models\Post */

/** @var $user \site\models\User */
$user = Yii()->user->model;
$interests = $post->interests;

isset($necessarily) || ($necessarily = null);
isset($thanks) || ($thanks = null);

if ($user) {
//favorite
    $favorite = [
        'state'       => $user->isFavorite($post) ? 'удалить' : 'добавить',
        'stateChange' => $user->isFavorite($post) ? 'добавить' : 'удалить',
        'refresh'     => true,

        'add'         => [
            'image' => Yii()->baseUrl . '/img/star_null.svg',
            'url'   => Yii()->createUrl('/post/addFavorite', ['id' => $post->id]),
        ],
        'delete'      => [
            'image' => Yii()->baseUrl . '/img/star_full.svg',
            'url'   => Yii()->createUrl('/post/deleteFavorite', ['id' => $post->id]),
        ],
    ];
}
?>

<div class="well mini_post_ser post" data-id="<?=$post->id?>"><!--РјРёРЅРё-РїРѕСЃС‚-->
    <?php if ($necessarily): ?>
        <div class="necessarily"><?=$necessarily?></div>
    <?php endif;#($necessarily)?>

    <?php if ($thanks): ?>
        <div class="thanks"><?=$thanks?></div>
    <?php endif;#($thanks)?>



    <div class="row-fluid">
        <?php if (!$this->author && ($author = $post->author)): ?>
            <div class="span1">
                <a href="<?=Yii()->createProfileUrl($author)?>" class="ref_avat">
                    <b><?=$author->nickname?></b>
                </a>

                <div class="avat">
                    <a href="<?=Yii()->createProfileUrl($author)?>">
                        <img alt="<?=$author->nickname?>" class="face" src="<?=$author->getAvatarUrl(56)?>" title="<?=$author->nickname?>">
                    </a>
                </div>
            </div>
        <?php endif;#($this->author)?>

        <div class="<?=$this->author ? 'span10' /*TODO:???*/ : 'span9'?> padding_left_20px">
            <b>
                <?php foreach ($interests as $index => $interest): ?>
                    <?php
                    $comma = isset($interests[$index + 1]);
                    echo $interest;
                    if ($user && !$user->hasInterest($interest)):
                        ?>
                    <button data-url="<?=$this->createUrl('/interest/attach', ['id' => $interest->id])?>" class="btn btn-1mini attach-interest"
                            data-id=<?=$interest->id?>><img src="<?=Yii()->baseUrl?>/img/plus.svg"></button><?= $comma ? ', ' : '' ?>
                    <?php elseif ($comma):
                        echo ', ';
                    endif;#($user && !$user->hasInterest($interest))
                    ?>
                <?php endforeach;#($post->interests as $interest)?>
            </b>
            <i class="float_right"><?=Yii()->dateFormatter->format('HH:mm dd MMM yyy', $post->dateSubmitted)?></i>

            <p><?=$post->htmlContent?></p>
        </div>
    

        <div class="span2 adm_butt">
            <?php if ($user && $post->User_id != $user->id): ?>
                <a href="<?=$favorite[$favorite['state']]['url']?>" data-favorite='<?=CJSON::encode($favorite)?>' class="favorite-star">
                    <img src="<?=$favorite[$favorite['state']]['image']?>" class="star">
                </a>
                <br>

                <?php if (!($vote = $user->getVote($post))): ?>
                    <div class="adm_butt_left">
                        <?php //TODO: THIS IS DEMO, not actual logic?>
                        <?php if ($post->id % 2): ?>

                            <a href="<?=$this->createUrl('/post/vote', ['id' => $post->id, 'type' => 'nonsense'])?>" class="btn btn-mini adm_butt_decor">
                                No sense
                            </a>
                            <br/>
                            <a href="<?=$this->createUrl('/post/vote', ['id' => $post->id, 'type' => 'irrelevant'])?>" class="btn btn-mini adm_butt_decor">
                                Wrong tags
                            </a>
                            <br/>
                            <a href="<?=$this->createUrl('/post/vote', ['id' => $post->id, 'type' => 'duplicate'])?>" class="btn btn-mini adm_butt_decor">
                                Duplication
                            </a>
                            <br/>
                        <?php endif;#?>
                    </div>
                    <div class="adm_butt_right">
                        <a href="<?=$this->createUrl('/post/vote', ['id' => $post->id, 'type' => 'like'])?>" class="btn btn-mini">
                             <img src="<?= Yii()->baseUrl ?>/img/like.svg">
                        </a>
                        <br/>

                        <a href="<?=$this->createUrl('/post/vote', ['id' => $post->id, 'type' => 'spam'])?>" class="btn btn-mini">
                            <img src="<?= Yii()->baseUrl ?>/img/awesome.svg">
                        </a>
                        <br/>

                        <a href="<?=$this->createUrl('/post/vote', ['id' => $post->id, 'type' => 'abuse'])?>" class="btn btn-mini">
                           <img src="<?= Yii()->baseUrl ?>/img/warning.svg">
                        </a>
                        <br/>
                    </div>
                <?php else: ?>
                    <div class="adm_butt_left">
                        <?php
                        $votes = [
                            Vote::TYPE_IRRELEVANT => 'Нерелевантные интересы',
                            Vote::TYPE_NONSENSE   => 'Бессмыслица',
                            Vote::TYPE_DUPLICATE  => 'Уже было',
                        ];
                        if (in_array($vote->type, array_keys($votes))):
                            ?>
                            <button class="btn btn-mini adm_butt_decor btn-warning"><?=$votes[$vote->type]?></button><br>
                        <?php endif;?>
                    </div>
                    <div class="adm_butt_right">
                        <?php if ($vote->type == Vote::TYPE_LIKE): ?>
                            <button class="btn btn-mini btn-success"><i class="icon-thumbs-up"></i></button>
                            <div class="arrow_box"><?=$post->likesCount?></div>
                        <?php else: ?>
                            <button class="btn btn-mini disabled"><i class=" icon-thumbs-up"></i></button>
                            <div class="arrow_box"><?=$post->likesCount?></div>
                            <?php if ($vote->type == Vote::TYPE_ABUSE): ?>
                                <button class="btn btn-mini btn-warning"><i class="icon-warning-sign"></i></button>
                            <?php elseif ($vote->type == Vote::TYPE_SPAM): ?>
                                <button class="btn btn-mini btn-danger"><i class="icon-ban-circle"></i></button>
                            <?php endif; ?>
                        <?php endif;#($vote->type == Vote::TYPE_LIKE)?>
                    </div>
                <?php endif;#(!($vote = $user->getVote($post)))?>


            <?php endif;#($user && $post->User_id != $user->id)?>
        </div>
       
    </div>
</div>