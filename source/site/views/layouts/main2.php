<?php
Yii()->clientScript->registerPackage('main');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <!-- Twitter Bootstrap CSS 2.0 -->
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="#"><?=CHtml::encode(Yii()->name);?></a>
            <?php $this->widget('zii.widgets.CMenu', array(
            'encodeLabel' => false,
            'items' => require_once '_main_menu.php',
            'htmlOptions' => array(
                'class' => 'nav',
            )
        )); ?>


            <?php $this->widget('zii.widgets.CMenu', array(
            'items' => require_once '_main_right_menu.php',
            'htmlOptions' => array(
                'class' => 'nav pull-right',
            )
        )); ?>
        <?php if(($u = User()) && !$u->getIsGuest()): ?>
            <span class="navbar-text pull-right"><?=$u->account->firstName?> (<?=$u->account->email?>)</span>
        <?php endif?>
        </div>
    </div>
</div>
<div class="container">
    <?php if(($u = User())): ?>
        <?php if($msg = $u->getSuccess()):?><p class="alert alert-success"><?=$msg?></p><?php endif?>
        <?php if($msg = $u->getInfo()):?><p class="alert alert-info"><?=$msg?></p><?php endif?>
        <?php if($msg = $u->getError()):?><p class="alert alert-error"><?=$msg?></p><?php endif?>
    <?php endif?>

    <?php if (isset($this->breadcrumbs)): ?>
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => $this->breadcrumbs,
    )); ?><!-- breadcrumbs -->
    <?php endif?>

    <?php echo $content; ?>
</div>

</body>
</html>