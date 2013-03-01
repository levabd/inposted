<?php
use site\models\Post;
/** @var $this \site\components\Controller */
/** @var $posts Post[] */
/** @var $sort string  */
$view = '//post/view';

$odd = 'background:#f4f2e7;';
$even = 'background:#ffffff;';

$get = $_GET;
unset($get['interests']);


?>
<div id="posts" data-url="<?=$this->createUrl('', $get)?>">
    <!--сортировка-->
    <b style="padding-left:30px;">Sort:</b>
    <a href="<?=$this->createUrl('', ['sort' => 'date']+ $get)?>" class="sort-post <?=Post::SORT_DATE == $sort ? 'active' : 'inactive'?>">by date</a> ,
    <a href="<?=$this->createUrl('', ['sort' => 'votes'] + $get)?>" class="sort-post <?=Post::SORT_VOTES == $sort ? 'active' : 'inactive'?>">by popularity</a>
    <!-- конец сортировка-->

    <?php foreach ($posts as $index => $post) {
        $this->renderPartial('//post/view', ['post' => $post, 'style' => $index % 2 ? $even : $odd]);
    }
    ?>
</div>
