<div class="well well-large">
    <p>Thank you. We will reply to:
        <strong><?=$model->email?></strong>
    </p>

    <p>If your email is mistyped, <a href="<?=$this->contactUrl?>">please go back to correct it</a>.</p>

    <p>
        Time now: <b><?=date('g:ia')?></b> <?=date('(T)')?><br/>
        Working hours (Mon-Fri): <b>9:00am - 5:00pm</b> (GMT)<br/>
        Expected reply time: within <b><?=Contact::getAverageReplyTime()?> hours</b>
    </p>
</div>