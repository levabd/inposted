<div class="row">
    <div class="span6">
        <h2>My Account</h2>
<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => User(),
    'attributes' =>
        array(
            'account.fullname:text:Name',
            'account.email:text:Email',
            'account.company:text:Company',
            'account.apiId:text:InpostedID',
        ),
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed'
    )
));?>
    </div>
    <div class="span6">
        <h2>FTP</h2>
        <p>Use your account email and password to access FTP at <code>main.inposted.com</code></p>
    </div>
</div>