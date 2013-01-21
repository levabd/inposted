<?php
/**
 * Date: 11/16/12 11:33 AM
 *
 * @author Dima Chukhai (dipp.dc@gmail.com, dipp@luckyteam.co.uk)
 */

namespace admin\widgets;

\Yii::import('zii.widgets.grid.CGridView');

class GridView extends \CGridView {
    public $cssFile = false;
    public $itemsCssClass = 'table table-hover';
    public $pagerCssClass = 'pagination pagination-small pagination-right';
    public $pager = array(
        'class' => '\admin\widgets\LinkPager',
        'header' => false,
        'cssFile' => false,
        'htmlOptions' => array(
            'class' => false,
        ),
        'selectedPageCssClass' => 'active',
        'hiddenPageCssClass' => 'disabled',

        'prevPageLabel' => '«',
        'nextPageLabel' => '»',
    );
}