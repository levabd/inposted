<?php
/** @var $this \site\components\Controller */
/** @var $posts site\models\Post[] */

$view = '//post/view';

$odd = 'background:#f4f2e7;';
$even = 'background:#ffffff;';

$get = $_GET;
unset($get['interests']);

echo "<div id='posts' data-url='{$this->createUrl('', $get)}'>";
foreach ($posts as $index => $post) {
    $this->renderPartial('//post/view', ['post' => $post, 'style' => $index % 2 ? $even : $odd]);
}
echo '</div>';
