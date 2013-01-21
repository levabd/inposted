<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Inposted</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" href="<?=$this->staticUrl?>/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
          href="<?=$this->staticUrl?>/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
          href="<?=$this->staticUrl?>/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
          href="<?=$this->staticUrl?>/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?=$this->staticUrl?>/ico/apple-touch-icon-57-precomposed.png">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="crlBrd clearfix"><span class="c1"></span><span class="c2"></span></div>
    <div id="container">
        <div id="header">
            <a id="logo" href="<?=Yii()->getBaseUrl()?>/" tabindex="-1">Inposted.com</a>
            <?php if($this->showTopMenu):?>
            <ul id="menu">
                <li><a href="<?=$this->createUrl('/site/page',array('view'=>'pricing'))?>">Pricing</a></li>
                <li><a href="<?=$this->createUrl('/site/page',array('view'=>'features'))?>">Features</a></li>
                <li><a href="<?=$this->createUrl('/site/contact')?>">Contact</a></li>
            </ul>
            <?php endif?>
            <div class="pull-right">
                <?php if (User()->getIsGuest()): ?>
                <?=
                CHtml::link(
                    '<i class="icon-user icon-white"></i> Sign up', array('/auth/signup'),
                    array('class' => 'btn btn-primary')
                ) ?>
                <?=
                CHtml::link(
                    '<i class="icon-lock icon-white"></i> Sign in', array('/auth/signin'),
                    array('class' => 'btn btn-primary')
                ) ?>
                <?php else: ?>

                <div class="btn-group">
                    <a class="btn" href="<?=$this->createUrl('/account/index')?>">
                        <i class="icon-user"></i> <?=User()->getAccount()->getFullname()?>
                    </a>
                    <button class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <!--<li><a href="#"><i class="icon-pencil"></i> Edit profile</a></li>
                        <li class="divider"></li>-->
                        <?php if (User()->getIsAdmin() || User()->getWasAdmin()): ?>
                        <li><a class="danger" href="<?=$this->createUrl('admin:admin/back')?>"><i class="icon-user"></i>
                            Back to admin</a></li>
                        <li class="divider"></li>
                        <?php endif?>
                        <li><a href="<?=$this->createUrl('/auth/signout')?>"><i class="icon-off"></i> Log out</a></li>

                    </ul>
                </div>

                <?php endif?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div id="content">
            <?=$content?>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div id="footer">

            <!--<div class="pull-right">
                <a class="fIco facebook" href="https://www.facebook.com/pages/Inposted/263371297006519">Inposted on Facebook</a>
                <a class="fIco twitter" href="https://twitter.com/weinposted">Inposted on Twitter</a>
            </div>-->


            <ul class="pull-left">
                <li><b>Inposted</b></li>
                <li><a href="#">How it works</a></li>
                <li><a href="#">Examples</a></li>
                <li><a href="<?=$this->createUrl('/site/page',array('view'=>'pricing'))?>">Pricing</a></li>
                <li><a href="#">About us</a></li>
            </ul>
            <ul class="pull-left">
                <li><b>Community</b></li>
                <li><a href="#">Referrals</a></li>
                <li><a href="https://twitter.com/weinposted">Twitter</a></li>
                <li><a href="https://www.facebook.com/pages/Inposted/263371297006519">Facebook</a></li>
            </ul>
            <ul class="pull-left">
                <li><b>Support</b></li>
                <li><a href="<?=$this->createUrl('/site/contact')?>">Contact us</a></li>
                <li><a href="<?=$this->createUrl('/site/page',array('view'=>'terms'))?>">Privacy &amp; Terms</a></li>
            </ul>

            <div class="clearfix"></div>
        </div>
    </div>
    <div class="crlBrd clearfix sm"><span class="c1"></span><span class="c2"></span></div>
</div>
</body>
</html>