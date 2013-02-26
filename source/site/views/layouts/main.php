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
</head>
<body style="background:#efefef;">

<div class="container"> <!--общий контейнер-->
    <div class="navbar navbar-fixed-top"> <!--шапка-->
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="instum">
                    <?php if (!Yii()->user->isGuest): ?>
                        <div class="ins"><a href="<?=$this->createUrl('/auth/signout')?>"><i class="icon-1share"></i></a></div>
                        <div class="ins"><a href="<?=$this->createUrl('/user/settings')?>"><i class="icon-1star-empty"></i></a></div>
                        <div class="ins"><a href="#createPost" data-toggle="modal"><i class="icon-1pencil"></i></a></div>
                    <?php endif;#(!Yii()->user->isGuest)?>

                </div>
                <div class="nav-collapse">
                    <ul class="nav">
                        <li class="<?=$this->id == 'site' && $this->action->id == 'index' ? 'active' : ''?>">
                            <a href="<?=$this->createUrl('/site/index')?>" style="text-decoration: underline;font-size:18px;font-weight:bold;color:#000000;">
                                <i class="icon-1home"></i>Home
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
    <!-- конец шапка-->
    <div style="min-height:60px; "></div>
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