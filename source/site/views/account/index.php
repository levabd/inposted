<?php
$baseurl = Yii()->getBaseUrl();
$apiId = User()->getModel()->apiId;
$apiUrl = $this->createUrl('http(s):api:/') . "/{$apiId}/inposted.js";

if(!User()->getModel()->verified){
    User()->setFlash('email-not-verified',"<strong>Warning!</strong><br>Your email is not verified. <a href='{$this->createUrl('/auth/verify')}'>Send me verification link</a>");
}

$config = array(
    'base' => array(
        'url' => $baseurl,
    ),
    'inposted' => array(
        'url' => $apiUrl,
        'id' => $apiId,
        'options' => array(
            'autostart' => false,
        ),
    ),
    'alerts' => User()->getFlashes(),
);

/** @var $cs CClientScript */
$cs = Yii()->clientScript;
$cs->registerPackage('app/account');
$cs->registerScript('config', 'window.config=' . CJSON::encode($config) . ';', CClientScript::POS_HEAD);

//$cs->registerScriptFile($apiUrl);
//$cs->registerScript('inposted', 'window.Inposted.options=' . CJSON::encode($inposted) . ';', CClientScript::POS_HEAD);


?>
<div class="row" ng-app="App" ng-controller="MainController">
    <div class="span2">
        <ul class="well nav nav-list">
            <!--            <li><a href="#/integration">Integration</a></li>-->
            <li ng-class="{active:page=='items.html'}"><a href="#/items">Products</a></li>
            <li class="nav-header">FTP</li>
            <li ng-class="{active:page=='ftp.html'}"><a href="#/ftp">Uploads</a></li>
            <li class="nav-header">Settings</li>
            <li ng-class="{active:page=='settings.html'}"><a href="#/settings">Preferences</a></li>
            <li ng-class="{active:page=='domains.html'}"><a href="#/settings/domains">Domains</a></li>
            <li class="nav-header">Other</li>
            <li ng-class="{active:page=='integration.html'}"><a href="#/integration">Integration</a></li>
            <!--            <li><a href="#/settings/domains">Magic 360 &copy; (tbd)</a></li>-->
        </ul>
    </div>
    <div class="span10">
        <div class="row">
            <div class="span10" ng-show="alerts.length">
                <div class="alert {{a.class}}" ng-repeat="a in alerts | limitTo:1">
                    <button type="button" class="close" ng-click="dismiss(a)">&times;</button>
                    <span ng-bind-html-unsafe="a.message"></span>
                </div>
            </div>
            <div class="span10" ng-view=""></div>
        </div>
    </div>
</div>
