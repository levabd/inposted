<div
    class="modal"
    style="z-index: 10002;background:#efefef;"
    id="suggestions"
    tabindex="-1"
    aria-labelledby="suggestionsLabel"
    >
    <div class="modal-header my_modal1">
        <button type="button" class="my_modal2 close" ng-click="close(false)">x</button>
        <h3 id="suggestionsLabel" class="my_modal3">
            <img src="<?= Yii()->baseUrl ?>/img/logo_icon.png" /> Did you know ?
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
        <button class="btn mynext" ng-click="next()">Next &raquo;</button>
        <button class="btn mypre" ng-click="previous()">&laquo; Previous</button>
        <label class="checkbox"><input type="checkbox" ng-click="close(true)"> Don't show </label>
    </div>
</div>