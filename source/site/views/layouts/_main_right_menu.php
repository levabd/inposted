<?php return array(
    array('label' => 'Sign in', 'url' => array('/auth/signin'), 'visible' => User()->isGuest),
    array('label' => 'Sign out', 'url' => array('/auth/signout'), 'visible' => !User()->isGuest, 'itemOptions' => array('class' => 'pull-right')),
);