<?php
/** @var $this \site\controllers\PostController */
/** @var $interests array */

/** @var $user \site\models\User */
$user = Yii()->user->model;
?>

<div class="well mini_post_white" id="favorites" data-url="<?= $this->createUrl('/post/favorites') ?>"> 
    <div class="well yellow">
        <span class="ref_main"><b>Избранное</b></span>
    </div>
    <br/>
    <ul class="unstyled">
        <li ng-repeat="favorite in favorites">
                <span class="clickable" ng-click="favorite.expanded = !favorite.expanded">
                    <img ng-src="{{favorite.expanded && '<?= Yii()->baseUrl ?>/img/d.svg' || '<?= Yii()->baseUrl ?>/img/r.svg'}}">
                    <b>{{favorite.interest.name}}</b>
                </span>
            <ul class="unstyled_fav" ng-show="favorite.expanded">
                <li ng-repeat="post in favorite.posts">
                    <a ng-href="{{post.author.url}}"><b>{{post.author.nickname}}</b></a>
                    <span class="clickable" ng-click="toggleFavorite(post, false)">
                        <img
                            ng-src="{{post.isFavorite && '<?= Yii()->baseUrl ?>/img/star_full.svg' || '<?= Yii()->baseUrl ?>/img/star_null.svg'}}"
                            class="star"
                            title="{{post.isFavorite && 'Удалить из избранного' || 'Добавить в избранное'}}"
                            >
                    </span>
                    <br>
                    <a ng-href="{{post.viewUrl}}" ng-bind-html-unsafe="post.htmlContent"></a>
                </li>
            </ul>
        </li>
</div>