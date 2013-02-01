<?php
/** @var $this \site\controllers\UserController */
/** @var $posts site\models\Post[] */

$view = '//post/view';

$odd = 'background:#f4f2e7;';
$even = 'background:#ffffff;';
foreach ($posts as $index => $post) {
    $this->renderPartial($view, ['post' => $post, 'style' => $index % 2 ? $even : $odd]);
}
