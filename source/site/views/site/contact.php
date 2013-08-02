<div class="row">
    <div class="span6">
        <h3 style="margin-left: 180px">Contact us</h3>
        <?php $this->renderPartial($success ? '_contact_success' : '_contact_form', compact('model'))?>
    </div>
    <div class="span6">
        <h3>Служба поддержки</h3>

        <p><a href="mailto:support@inposted.com">support@inposted.com</a></p>
        <br/>
    </div>
    <div class="clearfix"></div>
</div>