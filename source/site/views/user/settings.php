<?php
/** @var $this \site\controllers\UserController */
/** @var $user \site\models\User */

/** @var $form CActiveForm */

$countries = site\models\Country::model()->listData();

//$timezoneTable = array(
//    "-12"  => "(GMT -12:00) Eniwetok, Kwajalein",
//    "-11"  => "(GMT -11:00) Midway Island, Samoa",
//    "-10"  => "(GMT -10:00) Hawaii",
//    "-9"   => "(GMT -9:00) Alaska",
//    "-8"   => "(GMT -8:00) Pacific Time (US &amp; Canada)",
//    "-7"   => "(GMT -7:00) Mountain Time (US &amp; Canada)",
//    "-6"   => "(GMT -6:00) Central Time (US &amp; Canada), Mexico City",
//    "-5"   => "(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima",
//    "-4"   => "(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz",
//    "-3.5" => "(GMT -3:30) Newfoundland",
//    "-3"   => "(GMT -3:00) Brazil, Buenos Aires, Georgetown",
//    "-2"   => "(GMT -2:00) Mid-Atlantic",
//    "-1"   => "(GMT -1:00 hour) Azores, Cape Verde Islands",
//    "0"    => "(GMT) Western Europe Time, London, Lisbon, Casablanca",
//    '1'    => "(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris",
//    "2"    => "(GMT +2:00) Kaliningrad, South Africa",
//    "3"    => "(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg",
//    "3.5"  => "(GMT +3:30) Tehran",
//    "4"    => "(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi",
//    "4.5"  => "(GMT +4:30) Kabul",
//    "5"    => "(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent",
//    "5.5"  => "(GMT +5:30) Bombay, Calcutta, Madras, New Delhi",
//    "6"    => "(GMT +6:00) Almaty, Dhaka, Colombo",
//    "7"    => "(GMT +7:00) Bangkok, Hanoi, Jakarta",
//    "8"    => "(GMT +8:00) Beijing, Perth, Singapore, Hong Kong",
//    "9"    => "(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk",
//    "9.5"  => "(GMT +9:30) Adelaide, Darwin",
//    "10"   => "(GMT +10:00) Eastern Australia, Guam, Vladivostok",
//    "11"   => "(GMT +11:00) Magadan, Solomon Islands, New Caledonia",
//    "12"   => "(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka"
//);


$form = $this->beginWidget(
    'CActiveForm',
    [
    'errorMessageCssClass' => 'text-error',
    'htmlOptions'          => ['enctype' => 'multipart/form-data'],
    ]
);
?>
    <div class="span12">
        <div class="well mini_post_white">
            <div class="info_title ">
                <h3 class="my_modal3"><img src="<?=Yii()->baseUrl?>/img/logo_icon.png"> Личное</h3></div>
            <div class="row-fluid">
                <div class="span6">
                    <?=$form->textField($user, 'email', ['class' => 'span12'])?><br>
                    <?=$form->error($user, 'email')?>
                    <?=$form->textField($user, 'name', ['class' => 'span12'])?><br>
                    <?=$form->error($user, 'name')?>

                    <?=$form->dropDownList(
                        $user, 'Country_id', $countries,
                        [
                        'class'  => 'span12',
                        'prompt' => '' . $user->getAttributeLabel('Country_id') . '...'
                        ]
                    )?>
                    <?=$form->error($user, 'Country_id')?>

                    <div class="row-fluid">
                        <div class="span6" style="border: 2px solid #e3e3e3; text-align: center">
                            <img src="<?=$user->getAvatarUrl(210)?>" alt="<?=$user->name?>">
                        </div>
                        <div class="span6" style="padding-top: 95px">
                            <?=$form->fileField($user, 'avatarUpload');?>
                        </div>
                    </div>
                    <br>

                    <?=$form->passwordField($user, 'password', ['class' => 'span6'])?>
                    <?=$form->error($user, 'password')?>
                    <br>
                    <?=$form->passwordField($user, 'newPassword', ['class' => 'span6'])?>
                    <?=CHtml::submitButton('Подтвердить', ['class' => 'but_conf'])?>
                    <?=$form->error($user, 'newPassword')?>
                </div>
                <div class="span6">
                    <?=$form->textField($user, 'nickname', ['class' => 'span6'])?><br>
                    <?=$form->error($user, 'nickname')?>
                    <?=$form->textField($user, 'homepage', ['class' => 'span6'])?><br>
                    <?=$form->error($user, 'homepage')?>
                    <?=$form->textField($user, 'birthYear', ['class' => 'span6'])?><br>
                    <?=$form->error($user, 'birthYear')?>
                    <?=$form->dropDownList($user, 'gender', ['male' => 'Мужской', 'female' => 'Женский'], ['class' => 'span6', 'prompt' => 'Ваш пол...'])?><br>
                    <?=$form->error($user, 'gender')?>
                    <?//$form->dropDownList(
//                        $user, 'timezone', $timezoneTable,
//                        [
//                        'class'  => 'span12',
//                        'prompt' => 'Select ' . $user->getAttributeLabel('timezone') . '...',
//                        ]
                    //)?>
                    <?=$form->error($user, 'timezone')?>
                    <?=$form->textArea($user, 'info', ['class' => 'span12', 'rows' => '10'])?>
                    <?=$form->error($user, 'info')?>
                    <label class="checkbox">
                        <?=$form->checkBox($user, 'enabledHints')?> <?=$user->getAttributeLabel('enabledHints')?>
                    </label>
                    <?=CHtml::submitButton('Применить', ['class' => 'but_apl'])?>
                </div>
            </div>
        </div>
    </div>
    <div class="span12">
        <div class="row">
            <div class="span6">
                <div class="well mini_post_white">
                    <div class="info_title">
                        <h3 class="my_modal3"><img src="<?=Yii()->baseUrl?>/img/logo_icon.png"> Уведомления по e-mail</h3>
                    </div>
                    <?=CHtml::submitButton('Применить', ['class' => 'btn mypre'])?>
                    <label class="checkbox">
                        <?=$form->checkBox($user, 'enabledNotifications')?> <?=$user->getAttributeLabel('enabledNotifications')?>
                    </label>
                </div>

            </div>
            <div class="span6"></div>
        </div>
    </div>
<?php $this->endWidget();