<?php return array(
    array('label' => 'Sign in', 'url' => array('https:site:auth/signin'), 'visible' => User()->isGuest),
    array('label' => 'Sign out', 'url' => array('https:site:auth/signout'), 'visible' => !User()->isGuest, 'itemOptions' => array('class' => 'pull-right')),
);