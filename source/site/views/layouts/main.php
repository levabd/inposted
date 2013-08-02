<?php
/** @var $this site\components\Controller */
/** @var $content string */

Yii()->clientScript->registerPackage('main');
?>
<!DOCTYPE html>
<html lang="en" ng-app="inposted" xmlns:fb="http://ogp.me/ns/fb#" ng-controller="inposted.controllers.main">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="copyright" content="Fordot http://fordot.com.ua/" />
    <title><?=$this->pageTitle?></title>
    <style
        type="text/css">embed[type*="application/x-shockwave-flash"], embed[src*=".swf"], object[type*="application/x-shockwave-flash"], object[codetype*="application/x-shockwave-flash"], object[src*=".swf"], object[codebase*="swflash.cab"], object[classid*="D27CDB6E-AE6D-11cf-96B8-444553540000"], object[classid*="d27cdb6e-ae6d-11cf-96b8-444553540000"], object[classid*="D27CDB6E-AE6D-11cf-96B8-444553540000"] {
            display: none !important;
        }</style>
</head>
<body >

<div class="container"> 

    <?php if (!Yii()->user->isGuest && !Yii()->user->model->verified): ?>
        <div class="mess_email">
            <span class="clickable" ng-show="verification.state == 'initial'" ng-click="verification.sendEmail()">
                Пожалуйста, подтвердите Ваш e-mail адрес
            </span>
            <span ng-show="verification.state == 'pending'" in-dots="verification.state == 'pending'">
                Отправляем ссылку для подтверждения
            </span>
            <span ng-show="verification.state == 'sent'">
                Ссылка для подтверждения была отправлена на {{user.email}}.
                <br/>
                Пожалуйста, проверьте Ваш почтовый адрес (включая папку со спамом).
            </span>
            <span ng-show="verification.state == 'error'">
                Произошла ошибка во время отправки ссылки для подтверждения. Пожалуйста, попробуйте позже.
            </span>
        </div>
    <?php endif;#(!Yii()->user->isGuest && !Yii()->user->model->verified)?>

    <div class="header">
        <div class="head_left">
            <?php
            $this->widget(
                'zii.widgets.CMenu',
                [
                'encodeLabel' => false,
                'items'       => [
                    ['label' => '<b class="icon-1home">Home</b>', 'url' => ['/site/index']],
                    [
                        'label' => '<b class="icon-1me">Me </b>',
                        'url' => ['/user/view'],
                        'visible' => !Yii()->user->isGuest,
                        'active' => $this->id == 'user' && $this->getAction()->id == 'view' && empty($_GET['nickname']),
                    ],
                    ['label' => 'Messages</a><sub class="unread" ng-show="unreadPmsCount">{{unreadPmsCount}}</sub>',
                     'url'   => ['/pm/index'], 'visible' => !Yii()->user->isGuest],
                ]
                ]
            );
            ?>
        </div>
        <div class="head_center">
            <img alt="Inposted" src="<?= Yii()->baseUrl ?>/img/logo_full.png" title="Inposted">
        </div>
        <div class="head_right">
            <?php if (!Yii()->user->isGuest): ?>
                <?php
                $this->widget(
                    'zii.widgets.CMenu',
                    [
                    'encodeLabel'  => false,
                    'itemCssClass' => 'ins',
                    'items'        => [
                        ['label' => '<i class="icon-1share"></i>', 'url' => ['/auth/signout']],
                        ['label' => '<i class="icon-1star-empty"></i>', 'url' => ['/user/settings']],
                        ['label' => '<i class="icon-1nat"></i>', 'url' => ['/site/share']],
                        ['label'       => '<i class="icon-1pencil"></i>', 'url' => '#',
                         'linkOptions' => ['ng-click' => 'createNewPost(); $event.preventDefault()']],
                    ]
                    ]
                );
                ?>
            <?php endif;#(!Yii()->user->isGuest)?>
        </div>
    </div>
   
    <div class="empty_block"></div>
  
    <?=$content?>
</div>

<?php
if (!Yii()->user->isGuest) {
    $this->controllerWidget('pm/widget');
}
?>

</body>
</html>
