<?php
/**
 * Date: 11/16/12 11:33 AM
 *
 * @author Dima Chukhai (dipp.dc@gmail.com, dipp@luckyteam.co.uk)
 */

namespace admin\widgets;

class LinkPager extends \CLinkPager
{
    protected function createPageButtons() {
        $buttons = parent::createPageButtons();

        //remove first button
        array_shift($buttons);
        //remove last button
        array_pop($buttons);

        return $buttons;
    }

}