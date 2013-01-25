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

                <div class="nav-collapse">
                    <ul class="nav" >
                        <li class="active"><a href="" style="text-decoration: underline;font-size:18px;font-weight:bold;color:#000000;"><i class="icon-1home"></i>Home </a></li>
                        <li><a href="" style="text-decoration: underline;font-size:18px;font-weight:bold;color:#000000;">Title</li>
                    </ul>
                </div>
                <div class="instum">
                    <div class="ins"><a href=""><i class="icon-1share"></i></a></div>
                    <div class="ins"><a href=""><i class="icon-1star-empty"></i></a></div>
                    <div class="ins"><a href=""><i class="icon-1pencil"></i></a></div>
                </div>
            </div>
        </div>
    </div><!-- конец шапка-->

    <div style="min-height:60px; "> </div><!--пустой блок-->
    <?=$content?>
</div>
<!--конец общий контейнер-->
</body>
</html>