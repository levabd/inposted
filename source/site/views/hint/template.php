<div
    class="modal"
    style="z-index: 10002;background:#f4f2e7;"
    id="suggestions"
    tabindex="-1"
    aria-labelledby="suggestionsLabel"
    >
    <div class="modal-header my_modal1">
        <button type="button" class="my_modal2 close" ng-click="close(false)">x</button>
        <h3 id="suggestionsLabel" class="my_modal3">
            <img src="<?= Yii()->baseUrl ?>/img/logo_icon.png" /> Знаете ли Вы...
        </h3>
    </div>
    <div class="modal-body mini_post_ser">
        <div class="row-fluid">
            <div class="span12">
                <div class="well mini_post_white">
                    {{hint.content}}
                </div>
            </div>
        </div>
        <button class="btn mynext" ng-click="next()">След. &raquo;</button>
        <button class="btn mypre" ng-click="previous()">&laquo; Пред.</button>
        <label class="checkbox"><input type="checkbox" ng-click="close(true)"> Не показывать </label>
    </div>
</div>