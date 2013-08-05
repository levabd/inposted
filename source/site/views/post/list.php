<?php
use site\models\Post;

/** @var $this \site\components\Controller */
/** @var $posts Post[] */
/** @var $sort string */
?>
<div id="posts" in-infinite-posts="loadMorePosts()">

    <div class="block_sort" ng-show="posts.length > 1">
        <b>Сортировать по:</b>
        <a href="#" class="sort_post"
           ng-class="{active: sort.value == 'date'}"
           ng-click="sort.change('date', $event)"
            >
            дате</a>,
        <a href="#" class="sort_post"
           ng-class="{active: sort.value == 'votes'}"
           ng-click="sort.change('votes', $event)"
            >
            популярности
        </a>
    </div>


    <div class="well post" ng-class="{mini_post_ser: !($index%2), mini_post_white: $index%2}" ng-repeat="post in posts" in-hide="!post.isGood">
        <div class="necessarily" ng-show="post.isModerated && !post.thanks && !post.userVote">Пожалуйста, проголосуйте! Нам важно ваше мнение.</div>
        <div class="thanks" ng-show="post.thanks" in-hide="post.thanks">Спасибо за оценку!</div>
        <div class="row-fluid">
            <div class="span1">
                <a ng-href="{{post.author.url}}" class="ref_avat">
                    <b>{{post.author.nickname}}</b>
                </a>

                <div class="avat">
                    <a href="{{post.author.url}}">
                        <img alt="{{post.author.firstName}}" class="face" ng-src="{{post.author.avatarUrls[56]}}" title="{{post.author.firstName}}">
                    </a>
                </div>

            </div>


            <div class="padding_left_20px" ng-class="{span9: !settings.user.isGuest, span11: settings.user.isGuest}">
                <b ng-repeat="interest in post.interests">
                    {{interest.name}}<span ng-show="!$last && (settings.user.isGuest || hasInterest(interest))">,</span>
                    <button
                        class="btn btn-1mini attach-interest"
                        ng-click="attachInterest(interest)"
                        ng-hide="settings.user.isGuest || hasInterest(interest)"
                        title="Добавить интерес"
                        ><img src="<?= Yii()->baseUrl ?>/img/plus.svg"></button>
                    <span ng-hide="$last || settings.user.isGuest || hasInterest(interest)">,</span>
                </b>
                <i class="float_right">{{post.date | date:'HH:mm dd MMM yyyy'}}</i>

                <p ng-bind-html-unsafe="disableCut && post.htmlContent || (post.htmlContent | cut)"></p>
            </div>


            <div class="span2 adm_butt" ng-hide="settings.user.isGuest">
                <span class="clickable" ng-click="toggleFavorite(post, true)">
                    <img
                        ng-src="{{post.isFavorite && '<?= Yii()->baseUrl ?>/img/star_full.svg' || '<?= Yii()->baseUrl ?>/img/star_null.svg'}}"
                        class="star"
                        title="{{post.isFavorite && 'Удалить из избранного' || 'Добавить в избранное'}}"
                        >
                </span>
                <br>

                <div class="adm_butt_left" ng-hide="settings.user.id == post.author.id">
                    <button
                        class="btn btn-mini adm_butt_decor"
                        ng-repeat="(type, text) in {'nonsense': 'Бессмыслица', 'irrelevant': 'Нерелевантные интересы', 'duplicate': 'Уже было'}"

                        ng-class="{'btn-warning': type == post.userVote}"

                        ng-click="!post.userVote && vote(post, type)"
                        ng-show="(!post.userVote && post.isModerated) || type == post.userVote"
                        >
                        {{text}}
                    </button>
                    <br/>
                </div>
                <div class="adm_butt_right">

                    <a
                        ng-href="{{post.viewUrl}}"
                        ng-click="post.visited = true"
                        class="btn btn-mini"
                        ng-class="{'btn-warning' : !post.visited}"
                        ng-hide="settings.page.post"
                        title="Посмотреть пост"
                        >
                        <i class="icon-eye-open"> </i>
                    </a>
                    <button
                        class="btn btn-mini"
                        ng-click="vote(post, 'like')"
                        ng-class="{'btn-success': 'like' == post.userVote, 'disabled': (post.userVote && 'like' != post.userVote) || settings.user.id == post.author.id}"
                        title="Нравится"
                        >
                        <i class="icon-thumbs-up"></i>
                    </button>

                    <div class="arrow_box" ng-show="post.userVote || settings.user.id == post.author.id">{{post.likesCount}}</div>

                    <button
                        class="btn btn-mini"
                        ng-repeat="(type, text) in {'spam': 'Спам', 'abuse': 'Оскорбление'}"
                        ng-click="vote(post, type)"
                        ng-class="{'btn-warning': post.userVote=='spam', 'btn-danger': post.userVote=='abuse'}"
                        ng-show="(!post.userVote || post.userVote == type) && settings.user.id != post.author.id"
                        title="{{text}}"
                        >
                        <i ng-class="{'icon-ban-circle': type=='spam', 'icon-warning-sign': type=='abuse'}"></i>
                    </button>


                    <br>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="wait" ng-show="pager.wait"><img src="<?= Yii()->baseUrl ?>/img/loader.svg"></div>
