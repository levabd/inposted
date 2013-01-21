<h2>Accounts</h2>
<hr>
<?php
/** @var $accountProvider \CActiveDataProvider */
$accountProvider->getPagination()->setPageSize(25);
$grid = $this->widget('\admin\widgets\GridView', array(
    'dataProvider'=>$accountProvider,
    'rowCssClassExpression' => function($row, $data, $widget){
        $usage = $data->ftpUploadUsage / $data->ftpUploadQuota;
        if($usage >= 0.9){
            return 'error';
        }
        if($usage >= 0.8){
            return 'warning';
        }
    },
    'columns' => array(
        'id',
        'email',
        'fullName',
        'apiId',
        'company',
        'ftpUploadUsage:bytes:FTP Usage',
        'ftpUploadQuota:bytes:FTP Quota',
//        'dateCreated:date',
//        'dateAccessed:datetime',
        'roles:list',
//        'note',
        array(
            'class' => 'CButtonColumn',
            'template' => '{login}',
            'buttons' => array(
                'login' => array(
                    'label' => '<i class="icon-user"></i>',
                    'options' => array('title' => 'Login'),
                    'url' => 'array("login", "id" => $data->id)',
                    'visible' => 'User()->id != $data->id',
                )
            )
        )
    )
));

