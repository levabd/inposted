<?php
/** @var $this \site\controllers\InterestController */
?>
<label class="checkbox" ng-repeat="interest in interests">
    <input
        type="checkbox"
        ng-model="interest.checked"
        ng-disabled="isFilterDisabled(interest.id)"
        ng-click="toggleFilter(interest.id); $event.stopPropagation()"
        >
    <b>{{interest.fullName}}</b>
    <button
        class="btn btn-1mini"
        ng-click="detachInterest(interest); $event.stopPropagation()"
        >
        x
    </button>
</label>

<br>
<div class="poisk"> <!--форма поиска-->
    <div class="search_block">
        <span class="bit-box" ng-show="suggestions.parents.length > 1" ng-click="suggestions.popParent()">
            <img src="<?=Yii()->baseUrl?>/img/back.png">
        </span>
        <span class="bit-box" ng-show="suggestions.parents.length" ng-click="suggestions.popParent()">
            {{suggestions.parents[suggestions.parents.length - 1].name}}
            <a href="#" class="closebutt"><sup>x</sup></a>
        </span>
        <input ng-model="search.term" ng-change="search()" class="searchh input" type="text" />
        <button
            class="btn btn-2mini"
            ng-click="createInterest(); $event.stopPropagation()"
            ng-show="search.term && search.term.length >= 3 && !existsInterest"
            >+</button>
    </div>

    <div class="result_search" ng-show="suggestions.main.length">
        <ul>
            <li
                ng-repeat="interest in suggestions.main"
                ng-class="{active_res: interest.active}"

                ng-click="suggestions.pushParent(interest); $event.stopPropagation()"
                class="suggestion"
                >

                <button
                    class="btn btn-2mini"
                    ng-hide="hasInterest(interest)"
                    ng-click="attachInterest(interest); $event.stopPropagation()">+</button>
                {{interest.name}}
                <button
                    class="btn btn-2mini"
                    class="but_sear"
                    ng-click="showAdditionalSuggestions(interest); $event.stopPropagation()">
                    <img src="<?=Yii()->baseUrl?>/img/sear.png">
                </button>
            </li>

            <li class="fixing_res" ng-show="suggestions.additional.length">{{suggestions.getActive().name}}</li>

            <li ng-repeat="interest in suggestions.additional" ng-click="suggestions.pushParent(interest); $event.stopPropagation()" class="suggestion">
                <button
                    class="btn btn-2mini"
                    ng-hide="hasInterest(interest)"
                    ng-click="attachInterest(interest); $event.stopPropagation()">+</button>
                {{interest.name}}
            </li>
        </ul>
    </div>
</div><!--конец форма поиска-->
<?php $this->beginWidget('site\components\RegisterScript') ?>
<script type="text/javascript">
    $(function () {
        $('.search_block')
            .click(function () {
                $(this).find('input').focus();
            })

            .find('input')
            .focus(function () {
                $(this).closest('.search_block').addClass('with-focus')
            })
            .blur(function () {
                $(this).closest('.search_block').removeClass('with-focus')
            })
    });
</script>
<?php $this->endWidget() ?>
