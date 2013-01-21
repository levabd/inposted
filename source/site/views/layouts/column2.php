<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
    <div class="span3">
        <ul class="well nav nav-list">
            <li class="nav-header">Settings</li>
            <li class="active"><a href="#/settings/domains">Domains</a></li>
            <li><a href="#/settings/360">Magic 360</a></li>
        </ul>
    </div>
    <div class="span9">
        <?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>