<?php
/** @var $this site\components\Controller */
/** @var $content string */

Yii()->clientScript->registerPackage('main');
?>
<!DOCTYPE html>
<html lang="en" ng-app="inposted" xmlns:fb="http://ogp.me/ns/fb#" ng-controller="inposted.controllers.main">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="copyright" content="Fordot http://fordot.com.ua/"/>
    <meta name="keywords" content="интересы, актуальность, мировоззрение, короткие сообщения, поиск нужной информации, короткие по интересам, по интересам">
    <meta name="description" content="Вы не можете найти всех людей, которые, дадут вам качественный контент. На Inposted, следуя интересам вы увидите все интересные сообщения. Выбирайте и добавляйте интересы, когда и как вы хотите">
    <title><?= $this->pageTitle ?></title>
    <style
        type="text/css">embed[type*="application/x-shockwave-flash"], embed[src*=".swf"], object[type*="application/x-shockwave-flash"], object[codetype*="application/x-shockwave-flash"], object[src*=".swf"], object[codebase*="swflash.cab"], object[classid*="D27CDB6E-AE6D-11cf-96B8-444553540000"], object[classid*="d27cdb6e-ae6d-11cf-96b8-444553540000"], object[classid*="D27CDB6E-AE6D-11cf-96B8-444553540000"] {
            display: none !important;
        }</style>
</head>
<body>

<div class="container">

    <?php if (!Yii()->user->isGuest && !Yii()->user->model->verified): ?>
        <div class="mess_email">
            <span class="clickable" ng-show="verification.state == 'initial'" ng-click="verification.sendEmail()">
                Пожалуйста, подтвердите Ваш e-mail адрес
            </span>
            <span ng-show="verification.state == 'pending'" in-dots="verification.state == 'pending'">
                Отправляем ссылку для подтверждения
            </span>
            <span ng-show="verification.state == 'sent'" style="font-size: 12px;">
                Ссылка для подтверждения была отправлена на {{user.email}}.
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
                    ['label' => '<b class="icon-1home">Домашняя</b>', 'url' => ['/site/index']],
                    [
                        'label'   => '<b class="icon-1me">Профиль</b>',
                        'url'     => ['/user/view'],
                        'visible' => !Yii()->user->isGuest,
                        'active'  => $this->id == 'user' && $this->getAction()->id == 'view' && empty($_GET['nickname']),
                    ],
                    ['label' => 'Сообщения</a><sub class="unread" ng-show="unreadPmsCount">{{unreadPmsCount}}</sub>',
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

    <?= $content ?>
</div>

<?php
if (!Yii()->user->isGuest) {
    $this->controllerWidget('pm/widget');
}
?>

<script type="text/javascript">
    var reformalOptions = {
        project_id: 139167,
        project_host: "feedback.inposted.com",
        tab_orientation: "left",
        tab_indent: "50%",
        tab_bg_color: "#F05A00",
        tab_border_color: "#FFFFFF",
        tab_image_url: "http://tab.reformal.ru/T9GC0LfRi9Cy0Ysg0Lgg0L%252FRgNC10LTQu9C%252B0LbQtdC90LjRjw==/FFFFFF/2a94cfe6511106e7a48d0af3904e3090/left/1/tab.png",
        tab_border_width: 2
    };

    (function() {
        var script = document.createElement('script');
        script.type = 'text/javascript'; script.async = true;
        script.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'media.reformal.ru/widgets/v3/reformal.js';
        document.getElementsByTagName('head')[0].appendChild(script);
    })();
</script><noscript><a href="http://reformal.ru"><img src="http://media.reformal.ru/reformal.png" /></a><a href="http://feedback.inposted.com">Oтзывы и предложения для Inposted</a></noscript>
<?php $this->widget('ext.widgets.googleAnalytics.EGoogleAnalyticsWidget',
        array('account'=>'UA-36904603-1','domainName'=>'inposted.com')
);?>
</body>
</html>
