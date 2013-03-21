<?php
/** @var $this \site\controllers\PostController */
/** @var $interests array */

/** @var $user \site\models\User */
$user = Yii()->user->model;
?>

<div class="well mini_post_white" id="favorites" data-url="<?= $this->createUrl('/post/favorites') ?>"> <!--фавориты-->
    <div class="well yellow">
        <span class="ref_main"><b>Favorites</b></span>
    </div>
    <br/>
    <ul class="unstyled">
        <li ng-repeat="favorite in favorites">
                <span class="clickable" ng-click="favorite.expanded = !favorite.expanded">
                    <img ng-src="{{favorite.expanded && '<?= Yii()->baseUrl ?>/img/d.png' || '<?= Yii()->baseUrl ?>/img/r.png'}}">
                    <b>{{favorite.interest.name}}</b>
                </span>
            <ul class="unstyled_fav" ng-show="favorite.expanded">
                <li ng-repeat="post in favorite.posts">
                    <a ng-href="{{post.author.url}}"><b>{{post.author.nickname}}</b></a>
                    <span class="clickable" ng-click="toggleFavorite(post, false)">
                        <img ng-src="{{post.isFavorite && '<?= Yii()->baseUrl ?>/img/star_full.png' || '<?= Yii()->baseUrl ?>/img/star_null.png'}}" class="star">
                    </span>
                    <br>
                    <a ng-href="{{post.viewUrl}}" ng-bind-html-unsafe="post.htmlContent"></a>
                </li>
            </ul>
        </li>
</div><!-- конец фавориты-->