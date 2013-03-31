<?php
/** @var $this site\components\Controller */
/** @var $content string */

Yii()->clientScript->registerPackage('main');
?>
<!DOCTYPE html>
<html lang="en" ng-app="inposted">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?=$this->pageTitle?></title>
    <style
        type="text/css">embed[type*="application/x-shockwave-flash"], embed[src*=".swf"], object[type*="application/x-shockwave-flash"], object[codetype*="application/x-shockwave-flash"], object[src*=".swf"], object[codebase*="swflash.cab"], object[classid*="D27CDB6E-AE6D-11cf-96B8-444553540000"], object[classid*="d27cdb6e-ae6d-11cf-96b8-444553540000"], object[classid*="D27CDB6E-AE6D-11cf-96B8-444553540000"] {
            display: none !important;
        }</style>
</head>
<body ng-controller="inposted.controllers.main">

<div class="container"> <!--общий контейнер-->

    <?php if (!Yii()->user->isGuest && !Yii()->user->model->verified): ?>
        <div class="mess_email">
            <span class="clickable" ng-show="verification.state == 'initial'" ng-click="verification.sendEmail()">
                Please, confirm your e-mail address
            </span>
            <span ng-show="verification.state == 'pending'" in-dots>
                Sending verification link
            </span>
            <span ng-show="verification.state == 'sent'">
                Verification link was sent to {{user.email}}. Please, check your inbox.
            </span>
            <span ng-show="verification.state == 'error'">
                Error while sending verification link. Please try again later.
            </span>
        </div>
    <?php endif;#(!Yii()->user->isGuest && !Yii()->user->model->verified)?>

    <div class="header"> <!--шапка-->
        <div class="head_left">
            <?php
            $this->widget(
                'zii.widgets.CMenu',
                [
                'encodeLabel' => false,
                'items'       => [
                    ['label' => '<b class="icon-1home">Home</b>', 'url' => ['/site/index']],
                    ['label' => '<b class="icon-1me">Me </b>', 'url' => ['/user/view'], 'visible' => !Yii()->user->isGuest],
                    ['label' => 'Messages</a><sub class="unread" ng-show="unreadPmsCount">{{unreadPmsCount}}</sub>',
                     'url'   => ['/pm'], 'visible' => !Yii()->user->isGuest],
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
                        ['label' => '<i class="icon-1nat"></i>', 'url' => ['/site/share'], 'linkOptions' => ['in-disabled' => 'true']],
                        ['label'       => '<i class="icon-1pencil"></i>', 'url' => '#',
                         'linkOptions' => ['ng-click' => 'newPost.active = true; $event.preventDefault()']],
                    ]
                    ]
                );
                ?>
            <?php endif;#(!Yii()->user->isGuest)?>
        </div>
    </div>
    <!-- конец шапка-->
    <div class="empty_block"></div>
    <!--пустой блок-->
    <?=$content?>
</div>
<!--конец общий контейнер-->
<?php
if (!Yii()->user->isGuest) {
    $this->controllerWidget('post/create');
    $this->controllerWidget('pm/widget');
}
?>
</body>
</html>