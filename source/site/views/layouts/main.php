<?php
/** @var $this site\components\Controller */
/** @var $content string */

Yii()->clientScript->registerPackage('main');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?=$this->pageTitle?></title>
    <style
        type="text/css">embed[type*="application/x-shockwave-flash"], embed[src*=".swf"], object[type*="application/x-shockwave-flash"], object[codetype*="application/x-shockwave-flash"], object[src*=".swf"], object[codebase*="swflash.cab"], object[classid*="D27CDB6E-AE6D-11cf-96B8-444553540000"], object[classid*="d27cdb6e-ae6d-11cf-96b8-444553540000"], object[classid*="D27CDB6E-AE6D-11cf-96B8-444553540000"] {
            display: none !important;
        }</style>
</head>
<body>

<div class="container"> <!--общий контейнер-->
    <!--        <div class="mess_email">Please, enter your e-mail adress</div>-->
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
                ]
                ]
            );
            ?>
        </div>
        <div class="head_center">
            <img alt="Inposted" src="<?=Yii()->baseUrl?>/img/logo_full.png" title="Inposted">
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
                        ['label' => '<i class="icon-1nat"></i>', 'url' => ['/site/share'],'linkOptions' => ['class' => 'DISABLED']],
                        ['label' => '<i class="icon-1pencil"></i>', 'url' => '#createPost', 'linkOptions' => ['data-toggle' => 'modal']],
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
}
?>
</body>
</html>