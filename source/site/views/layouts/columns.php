<?php
/** @var $this site\components\Controller */
/** @var $content string */

$baseUrl = Yii()->baseUrl;

$this->beginContent('//layouts/main');
?>
<div class="row-fluid">
<div class="span9"> <!--левая часть контента-->
    <?=$content?>
</div><!-- конец левая часть контента-->

<div class="span3"> <!--правая часть контента-->
    <div class="well" style="background:#ffffff;">
        <div class="well" style="background:#fffd74;margin:-19px -19px -10px -19px ; border: 1px solid #fffd74;">
            <a href=""style="color:#54211d;font-size:18px;text-decoration: underline;"><b>bender</b></a>
        </div><br/>
        <div class="row-fluid">
            <div class="span5">
                <div class="avat">
                    <img alt="bender" src="<?=$baseUrl?>/img/avatar.png" title="bender"align="middle">
                </div>
            </div>
            <div class="span7">
                Reputation: 34<br/>
                Level: 4<br/>
                <a href=""><img alt="bender"  src="<?=$baseUrl?>/img/uk.jpg" title="Ukraine"></a><br/>
            </div>
        </div>
    </div>


    <div class="well"style="background:#ffffff;">
        <div class="well" style="background:#fffd74;margin:-19px -19px -10px -19px ; border: 1px solid #fffd74;">
            <a href=""style="color:#54211d;font-size:18px;text-decoration: underline;"><b>Interests</b></a>
        </div><br/>
        <label class="checkbox"><input type="checkbox"> <b>coding  </b>
            <button class="btn btn-1mini">x</button>
        </label>

        <label class="checkbox"><input type="checkbox"> <b>Football  </b>
            <button class="btn btn-1mini">x</button>
        </label>
        <label class="checkbox"><input type="checkbox"><b> Jazz  </b>
            <button class="btn btn-1mini">x</button>
        </label><br/>
        <div class="poisk">

            <form><!--форма поиска-->
                <input id="quicksearch"type="text" style="width:75%;"class="input "placeholder="Search" /><input id="go" type="submit" />	</form>

        </div>

        <b style="line-height:25px;" >Програмування  </b><button class="btn btn-1mini">+</button><br/>
        <b style="line-height:25px;">Производство  </b><button class="btn btn-1mini">+</button><br/>
        <b style="line-height:25px;">Прокрастинация  </b><button class="btn btn-1mini">+</button> <br/>

        <input type="text" class="input-medium "style="width:80%;" id="input01" placeholder="Create a new category">   <button class="btn btn-1mini"style="margin-top:-10px;">+</button>
    </div>

    <div class="well"style="background:#ffffff;">
        <div class="well" style="background:#fffd74;margin:-19px -19px -10px -19px ; border: 1px solid #fffd74;">
            <a href=""style="color:#54211d;font-size:18px;text-decoration: underline;"><b>Favorites</b></a>
        </div><br/>
        <ul class="unstyled">
            <li>
                <a href=""><img  src="<?=$baseUrl?>/img/r.png" ></a> <b style="line-height:30px;"> coding</b>
            </li>
            <li>
                <a href=""><img  src="<?=$baseUrl?>/img/r.png" ></a> <b style="line-height:30px;">football </b>
            </li>
            <li>
                <a href=""><img  src="<?=$baseUrl?>/img/d.png" ></a> <b style="line-height:30px;">Jazz</b>
                <ul class="unstyled " style="margin-left:20px;">



                    <li style="padding-top:15px;"><b style="color:#54211d;padding-left:10px">Amasfera </b><a href=""><img  src="<?=$baseUrl?>/img/star_full.png"style="float:right;" > </a><br/>
                        <a href="" style="color:#214821;text-decoration: underline;">По многочисленым просьбам, мырешили провести для вас концерт Cosmojazz</a>
                    </li>
                    <li style="padding-top:15px;"><b style="color:#54211d;padding-left:10px">Amasfera </b><a href=""><img  src="<?=$baseUrl?>/img/star_full.png"style="float:right;" > </a><br/>
                        <a href="" style="color:#214821;text-decoration: underline;">По многочисленым просьбам, мырешили провести для вас концерт Cosmojazz</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href=""><img  src="<?=$baseUrl?>/img/r.png" ></a><b> Music </b>
            </li>
            <li>
                <a href=""><img  src="<?=$baseUrl?>/img/r.png" ></a><b> Java</b>
            </li>
            <li>
                <a href=""><img  src="<?=$baseUrl?>/img/r.png" ></a><b> Програмування</b>
            </li>
        </ul>
    </div>
    <div class="well"style="background:#ffffff;">
        <div class="well" style="background:#fffd74;margin:-19px -19px -10px -19px ; border: 1px solid #fffd74;">
            <a href=""style="color:#54211d;font-size:18px;text-decoration: underline;"><b>About</b></a>
        </div><br/>

        (c)2012 Copyright
    </div>
</div> <!--конец правая часть контента-->
</div>
<?php $this->endContent()?>