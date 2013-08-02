<div class="well well-large">
    <p>Thank you. We will reply to:
        <strong><?=$model->email?></strong>
    </p>

    <p>Если Ваше письмо не верно набрано, <a href="<?=$this->contactUrl?>">пожалуйста, вернитесь и исправьте</a>.</p>

    <p>
        Сейчас: <b><?=date('g:ia')?></b> <?=date('(T)')?><br/>
        Рабочие часы (Пон-Пят): <b>9:00am - 5:00pm</b> (GMT)<br/>
        Среднее время ответа: в течении <b><?=Contact::getAverageReplyTime()?> часов</b>
    </p>
</div>