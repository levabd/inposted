<?php
/** @var $this \site\controllers\InterestController */
/** @var $searchWidth int */
?>
<label class="checkbox" ng-repeat="interest in interests">
    <input
        type="checkbox"
        ng-model="interest.checked"
        ng-disabled="isFilterDisabled(interest.id, interests)"
        ng-click="toggleFilter(interest); $event.stopPropagation()"
        >
    <b>{{interest.fullName}}</b>
    <button
        class="btn btn-1mini"
        ng-click="detachInterest(interest); $event.stopPropagation()"
        title="Delete interest"
        >
        <img src="<?=Yii()->baseUrl?>/img/x.svg">
    </button>
</label>

<br>
<div class="poisk">
	
    <div class="search_block" in-search <?php if($searchWidth):?>style="width: <?=$searchWidth?>px;"<?php endif;?>>
        <span class="bit-box" ng-show="suggestions.parents.length > 1" ng-click="suggestions.popParent()">
            <img src="<?= Yii()->baseUrl ?>/img/back.png">
        </span>
        <span class="bit-box" ng-show="suggestions.parents.length" ng-click="suggestions.popParent()">
            {{suggestions.parents[suggestions.parents.length - 1].name}}
            <a href="#" class="closebutt"><sup>x</sup></a>
        </span>
			
		<input ng-model="search.term" ng-change="search()" class="searchh input"  type="text"/>
            
	   <button
            class="btn btn-2mini"
            ng-click="createInterest(); $event.stopPropagation()"
            ng-show="search.term && search.term.length >= 3 && !existsInterest"
            title="Create interest"
            ><img src="<?=Yii()->baseUrl?>/img/plus.svg">
        </button>
		
    </div>
	<span class="addon"><img src="/img/search.svg"></span>

    <div class="result_search" ng-show="suggestions.main.length">
        <ul>
            <li
                ng-repeat="interest in suggestions.main"
                ng-class="{active_res: interest.active}"

                ng-click="suggestions.pushParent(interest); $event.stopPropagation()"
                in-suggest
                class="suggestion"
                >

                <button
                    class="btn btn-2mini"
                    ng-hide="hasInterest(interest)"
                    ng-click="attachInterest(interest); $event.stopPropagation()"
                    title="Add interest"
                    ><img src="<?=Yii()->baseUrl?>/img/plus.svg">
                </button>
                {{interest.name}}
                <button
                    class="btn btn-3mini"
                    class="but_sear"
                    ng-click="showAdditionalSuggestions(interest); $event.stopPropagation()"
                    title="Show additional suggestions"
                    >
                    <img src="<?= Yii()->baseUrl ?>/img/sear.svg">
                </button>
            </li>

            <li class="fixing_res" ng-show="suggestions.additional.length">{{suggestions.getActive().name}}</li>

            <li ng-repeat="interest in suggestions.additional" ng-click="suggestions.pushParent(interest); $event.stopPropagation()" class="suggestion">
                <button
                    class="btn btn-2mini"
                    ng-hide="hasInterest(interest)"
                    ng-click="attachInterest(interest); $event.stopPropagation()"><img src="<?=Yii()->baseUrl?>/img/plus.svg">
                </button>
                {{interest.name}}
            </li>
        </ul>
    </div>
</div><!--конец форма поиска-->