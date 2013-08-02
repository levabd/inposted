<div class="modal hide" style="z-index: 10002;background:#f4f2e7;" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="modalMessageLabel"
     aria-hidden="true">
    <div class="modal-header my_modal1">
        <button type="button" class="close my_modal2" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="modalMessageLabel" class="my_modal3"><img src="<?= Yii()->baseUrl ?>/img/logo_icon.png">
            Сообщение {{pm.to.firstName}}
        </h3>
    </div>
    <div class="modal-body" id="mini_post_ser">
        <input type="text" style="width:50%;" class="input" placeholder="Тема" ng-model="pm.topic"/>
        <span class="text-error" ng-show="pm.errors.topic">{{pm.errors.body}}</span>
        <textarea style="width:97%;" rows="10" placeholder="Сообщение" ng-model="pm.body"></textarea>
        <br/>
        <button class="btn" id="mypre" ng-click="sendPM()">Отправить</button>
        <span class="text-error" ng-show="pm.errors.body || pm.error">{{pm.errors.body}}{{pm.error}}</span>
    </div>

</div>
