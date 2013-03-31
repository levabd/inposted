

<div ng-repeat="pm in pms" class="well" ng-class="{'mini_post_ser' : !$index%2, 'mini_post_white' : $index%2}"><!--мини-пост-->
    <div class="row-fluid">
        <div class="span1"><!--имя пользователя и аватарка-->
            <a href="" class="ref_avat"><b>{{pm.from.nickname}}</b></a>

            <div class="avat">
                <img alt="bender" class="face" ng-src="{{pm.from.avatarUrls[56]}}" title="{{pm.from.nickname}}">
            </div>
            <button class="btn reply" ng-click="showPM(pm.from, 'RE: ' + pm.topic)">Reply</button>
        </div>
        <!--конец имя пользователя и аватарка-->
        <div class="span11 padding_left_20px">
            <b>{{pm.topic || '&nbsp;'}}</b>
            <i class="float_right">{{pm.date | date:'HH:mm dd MMM yyyy'}}</i>

            <p> {{pm.body}}</p>
        </div>

    </div>
</div>
<!--конец мини-пост-->