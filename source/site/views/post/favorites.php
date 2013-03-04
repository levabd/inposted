<?php
/** @var $this \site\controllers\PostController */
/** @var $interests array */

/** @var $user \site\models\User */
$user = Yii()->user->model;
?>

<div class="well mini_post_white"> <!--фавориты-->
    <div class="well yellow">
        <span class="ref_main"><b>Favorites</b></span>
    </div>
    <br/>
    <ul class="unstyled">
        <?php foreach ($interests as $data): ?>
            <li>
                <a href="#" class="favorites-group">
                    <img src="<?=Yii()->baseUrl?>/img/r.png" data-collapsed="<?=Yii()->baseUrl?>/img/r.png" data-expanded="<?=Yii()->baseUrl?>/img/d.png">
                    <b><?=$data['interest']?></b>
                </a>
                <ul class="unstyled_fav hide">
                    <?php foreach ($data['posts'] as $post): ?>
                        <?php
                        $favorite = [
                            'state'       => 'delete',
                            'stateChange' => 'add',
                            'confirm'     => 'delete',
                            'refresh'     => false,

                            'add'         => [
                                'image' => Yii()->baseUrl . '/img/star_null.png',
                                'url'   => Yii()->createUrl('/post/addFavorite', ['id' => $post->id]),
                            ],
                            'delete'      => [
                                'image' => Yii()->baseUrl . '/img/star_full.png',
                                'url'   => Yii()->createUrl('/post/deleteFavorite', ['id' => $post->id]),
                            ],
                        ];
                        ?>
                        <li>
                            <a href="<?=Yii()->createProfileUrl($post->author)?>"><b><?=$post->author->nickname?></b></a>
                            <a href="<?=$favorite[$favorite['state']]['url']?>" data-favorite='<?=CJSON::encode($favorite)?>' class="favorite-star">
                                <img src="<?=$favorite[$favorite['state']]['image']?>">
                            </a>
                            <br>
                            <a href="<?=$this->createUrl('/post/view', ['id' => $post->id])?>">
                                <?=$post->htmlContent?>
                            </a>
                        </li>
                    <?php endforeach;#($data['posts' as $post])?>
                </ul>
            </li>
        <?php endforeach;#($interests as $data)?>
</div><!-- конец фавориты-->