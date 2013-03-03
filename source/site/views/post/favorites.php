<?php
/** @var $this \site\controllers\PostController */
/** @var $interests array */

/** @var $user \site\models\User */
$user = Yii()->user->model;
?>
<div class="well" style="background:#ffffff;" id='favorites' data-url="<?=$this->createUrl('/post/favorites')?>"> <!--фавориты-->
    <div class="well" style="background:#fffd74;margin:-19px -19px -10px -19px ; border: 1px solid #fffd74;">
        <span style="color:#54211d;font-size:18px;text-decoration: underline;"><b>Favorites</b></span>
    </div>
    <br>
    <ul class="unstyled">
        <?php foreach ($interests as $data): ?>
            <li>
                <a href="#" class="favorites-group">
                    <img src="<?=Yii()->baseUrl?>/img/r.png" data-collapsed="<?=Yii()->baseUrl?>/img/r.png" data-expanded="<?=Yii()->baseUrl?>/img/d.png">
                    <b style="line-height:30px;"><?=$data['interest']?></b>
                </a>
                <ul class="unstyled hide" style="margin-left:20px;">
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
                        <li style="padding-top:15px;">
                            <a href="<?=Yii()->createProfileUrl($post->author)?>" style="color:#54211d;padding-left:10px"><?=$post->author->nickname?></a>
                            <a href="<?=$favorite[$favorite['state']]['url']?>" data-favorite='<?=CJSON::encode($favorite)?>' class="favorite-star">
                                <img src="<?=$favorite[$favorite['state']]['image']?>" style="float: right">
                            </a>
                            <br>
                            <a href="<?=$this->createUrl('/post/view', ['id' => $post->id])?>" style="color:#214821;text-decoration: underline;">
                                <?=$post->htmlContent?>
                            </a>
                        </li>
                    <?php endforeach;#($data['posts' as $post])?>
                </ul>
            </li>
        <?php endforeach;#($interests as $data)?>

        <!--        <li>-->
        <!--            <a href=""><img src="./img/r.png"></a><b> Jazz</b>-->
        <!--        </li>-->
    </ul>
</div>