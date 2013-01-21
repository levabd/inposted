<?php return array(
    array('label' => 'Home', 'url' => array('/site/index')),
    array('label' => 'Account<b class="caret"></b>', 'url' => '#',
        'itemOptions' => array('class' => 'dropdown'),
        'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
        'submenuOptions' => array('class' => 'dropdown-menu'),
        'items' => array(
            array('label' => 'My Account', 'url' => array('account/index')),
            array('label' => 'My Studios', 'url' => array('account/studios'), 'visible' => !User()->getIsStudio())
        ),
    ),
);