<?php
use site\models\Post;

/** @var $this \site\components\Controller */
/** @var $posts Post[] */
/** @var $sort string */
$view = '//post/view';

$odd = 'background:#f4f2e7;';
$even = 'background:#ffffff;';

$get = $_GET;
unset($get['interests']);


?>
<div id="posts" data-url="<?=$this->createUrl('', $get)?>">
    <!--сортировка-->
    <div class="block_sort">
        <b>Sort:</b>
        <a href="<?=$this->createUrl('', ['sort' => 'date'] + $get)?>" class="sort_post <?=Post::SORT_DATE == $sort ? 'active' : ''?>">
            by date</a>,
        <a href="<?=$this->createUrl('', ['sort' => 'votes'] + $get)?>" class="sort_post <?=Post::SORT_VOTES == $sort ? 'active' : ''?>">
            by popularity
        </a>
    </div>
    <!-- конец сортировка-->

    <?php foreach ($posts as $index => $post) {
        $this->renderPartial('//post/view', ['post' => $post, 'style' => $index % 2 ? $even : $odd]);
    }
    ?>
</div>
