<?php
/** @var $this site\components\Controller */
/** @var $content string */

$baseUrl = Yii()->baseUrl;

$this->beginContent('//layouts/main');
?>
    <div class="row-fluid">
        <div class="span9"> <!--левая часть контента-->
            <?=$content?>
        </div>
        <!-- конец левая часть контента-->

        <div class="span3"> <!--правая часть контента-->
            <?php if (Yii()->user->isGuest): ?>
                <?php $this->widget('site\controllers\AuthController', ['action' => 'signin']) ?>
                <?php $this->widget('site\controllers\AuthController', ['action' => 'signup']) ?>
            <?php else: ?>
                <?php $this->widget('site\widgets\user\User') ?>
                <?php $this->widget('site\controllers\InterestController') ?>

                <div class="well" style="background:#ffffff;">
                    <div class="well" style="background:#fffd74;margin:-19px -19px -10px -19px ; border: 1px solid #fffd74;">
                        <a href="" style="color:#54211d;font-size:18px;text-decoration: underline;"><b>Favorites</b></a>
                    </div>
                    <br/>
                    <ul class="unstyled">
                        <li>
                            <a href=""><img src="<?=$baseUrl?>/img/r.png"></a> <b style="line-height:30px;"> coding</b>
                        </li>
                        <li>
                            <a href=""><img src="<?=$baseUrl?>/img/r.png"></a> <b style="line-height:30px;">football </b>
                        </li>
                        <li>
                            <a href=""><img src="<?=$baseUrl?>/img/d.png"></a> <b style="line-height:30px;">Jazz</b>
                            <ul class="unstyled " style="margin-left:20px;">


                                <li style="padding-top:15px;"><b style="color:#54211d;padding-left:10px">Amasfera </b><a href=""><img
                                            src="<?=$baseUrl?>/img/star_full.png" style="float:right;"> </a><br/>
                                    <a href="" style="color:#214821;text-decoration: underline;">По многочисленым просьбам, мырешили провести для вас концерт
                                        Cosmojazz</a>
                                </li>
                                <li style="padding-top:15px;"><b style="color:#54211d;padding-left:10px">Amasfera </b><a href=""><img
                                            src="<?=$baseUrl?>/img/star_full.png" style="float:right;"> </a><br/>
                                    <a href="" style="color:#214821;text-decoration: underline;">По многочисленым просьбам, мырешили провести для вас концерт
                                        Cosmojazz</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href=""><img src="<?=$baseUrl?>/img/r.png"></a><b> Music </b>
                        </li>
                        <li>
                            <a href=""><img src="<?=$baseUrl?>/img/r.png"></a><b> Java</b>
                        </li>
                        <li>
                            <a href=""><img src="<?=$baseUrl?>/img/r.png"></a><b> Програмування</b>
                        </li>
                    </ul>
                </div>
            <?php endif;#(Yii()->user->isGuest)?>


            <div class="well" style="background:#ffffff;">
                <div class="well" style="background:#fffd74;margin:-19px -19px -10px -19px ; border: 1px solid #fffd74;">
                    <a href="" style="color:#54211d;font-size:18px;text-decoration: underline;"><b>About</b></a>
                </div>
                <br/>

                (c)2012 Copyright
            </div>
        </div>
        <!--конец правая часть контента-->
    </div>
<?php $this->endContent() ?>