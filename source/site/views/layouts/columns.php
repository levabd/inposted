<?php
/** @var $this site\components\Controller */
/** @var $content string */

$baseUrl = Yii()->baseUrl;

$this->beginContent('//layouts/main');
?>
<?php if ($this->author) {
    $this->widget('site\widgets\user\User', ['user' => $this->author, 'view' => 'user-info']);
} ?>

    <div class="row-fluid">
        <div class="span9"> 
            <?=$content?>
        </div>
      

        <div class="span3"> 
            <?php
            if (Yii()->user->isGuest) {
                $this->controllerWidget('auth/signin');
            }
            if ($this->author) {
                if(!($this->id == 'pm' && $this->getAction()->id == 'index')){
                    $this->controllerWidget('interest/ofUser', ['actionParams' => ['id' => $this->author->id]]);
                }
            } elseif (!Yii()->user->isGuest) {
                $this->widget('site\widgets\user\User');
                $this->controllerWidget('interest/own', ['widgetId' => 'sidebar-interests', 'actionParams' => ['filter' => true]]);
                $this->controllerWidget('post/favorites', ['widgetId' => 'favorites']);
            }
            ?>

            <div class="well mini_post_white">
                <div class="well yellow">
                    <span class="ref_main"><b>О нас</b></span>
                </div>
                <br/>
                &copy; Inposted <br/>  
				Inposted <a href="http://inposted.info/" class="ablack" >Блог</a>  <br/>         
                создан <a href="http://fordot.com.ua/"class="ablack" >Fordot</a>  <br/>  
				<a href="mailto:info@inposted.com"class="ablack" >Свяжитесь с нами</a> 				
            </div>
      
        </div>
   
    </div>
<?php $this->endContent() ?>