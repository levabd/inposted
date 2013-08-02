<?php
/** @var $this site\controllers\SiteController * */
/** @var $link string */
$this->layout = 'main';
$cs = Yii()->clientScript;

$cs->registerScriptFile('http://platform.tumblr.com/v1/share.js', CClientScript::POS_END);
$cs->registerScript('addThis.config', sprintf('var addthis_config = %s;', CJavaScript::encode(['data_track_addressbar' => false])), CClientScript::POS_BEGIN);
$cs->registerScriptFile('//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515eae64690ce2f2', CClientScript::POS_END);

$config = Yii()->params['share'];

$tumblrUrl = 'http://www.tumblr.com/share/link?url=' . urlencode($config['url']) . '&name=' . $config['title'] . '&description=' . $config['description'];
?>

<div class="row-fluid" ng-controller="inposted.controllers.share">
    <div class="span1"></div>
    <div class="span10">
        <div class="well" class="mini_post_white">
            <div class="info_title ">
                <h3 id="myModalLabel" class="my_modal3"><img src="<?= Yii()->baseUrl ?>/img/logo_icon.png"> И наконец...</h3>
            </div>
            <div class="row-fluid">
                <div class="span1"></div>
                <div class="span10">
                    <div class="centering">
                        <p>Поздравляем, Вы - один из первых!</p>

                        <p>За Ваше терпение Вы получите дополнительные возможности!</p>

                        <p>Сейчас Вы можете пригласить друзей на этот сайт. </p>

                        <p>И следите за новостями на нашем <a href="http://inposted.info/" class="ref_mess">блоге</a></p>

                        <p>

                        <div class="span2"></div>
                        <!-- AddThis Button BEGIN -->
                        <div
                            class="addthis_toolbox addthis_default_style "
                            addthis:url="<?= $config['url'] ?>"
                            addthis:title="<?= $config['title'] ?>"
                            addthis:description="<?= $config['description'] ?>"
                            >
                            <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
                            <a class="addthis_button_facebook_send"></a>
                            <a class="addthis_button_linkedin_counter"></a>

                            <a
                                href="<?= $tumblrUrl ?>"
                                title="Share on Tumblr"
                                style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url('http://platform.tumblr.com/v1/share_1.png') top left no-repeat transparent; float:left;"
                                >
                                Share on Tumblr
                            </a>

                            <a class="addthis_button_tweet"></a>
                        </div>
                        <!-- AddThis Button END -->
                        </p>

                    </div>
                    <div style="clear:both;"></div>
                    <div class="well" id="block_share">
                        Share by e-mail:
                        <input
                            type="text"
                            style="margin-left:30px;width:74%;"
                            class="input"
                            placeholder="Введите e-mail адреса (разделенные запятыми)"
                            ng-model="share.emails"
                            required
                            >

                        <textarea
                            style="width:97%;"
                            rows="10"
                            placeholder="ВВедите сообщение"
                            ng-model="share.message"
                            ></textarea>
                        Share link:<span style="margin-left:30px;width:50%; margin-bottom: 0" class="uneditable-input input"><?= $link ?></span>
                        <button class="btn" class="mypre" ng-click="send()">Отправить приглашение</button>
                        <div class="well" style="margin-top: 20px; margin-bottom: 0" ng-show="state">
                            <div class="text-success" ng-show="state == 'success'">
                                Your invitations was sent.
                            </div>
                            <div class="text-info" ng-show="state == 'pending'">
                                Sending email...
                            </div>
                            <div class="text-error" ng-show="state == 'error'">
                                {{error}}
                                <div ng-show="errorsLength()">
                                    Произошла ошибка во время отправки приглашений
                                    <div ng-repeat="(email, error) in errors">
                                        {{email}}: {{error}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </div>
    <div class="span1"></div>
</div>