<label class="checkbox" ng-repeat="interest in owner.interests">
    <input
        type="checkbox"
        ng-model="interest.checked"
        ng-disabled="isFilterDisabled(interest.id, owner.interests)"
        ng-click="toggleFilter(interest)"
        >
    <b>{{interest.fullName}}</b>
    <button
        class="btn btn-1mini attach-interest"
        ng-click="attachInterest(interest); $event.stopPropagation(); $event.preventDefault()"
        ng-show="!settings.user.isGuest && !hasInterest(interest)"
        title="Добавить интерес"
        ><img src="<?=Yii()->baseUrl?>/img/plus.svg"></button>
</label>