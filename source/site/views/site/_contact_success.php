<div class="well well-large">
    <p>Thank you. We will reply to:
        <strong><?=$model->email?></strong>
    </p>

    <p>���� ���� ������ �� ����� �������, <a href="<?=$this->contactUrl?>">����������, ��������� � ���������</a>.</p>

    <p>
        ������: <b><?=date('g:ia')?></b> <?=date('(T)')?><br/>
        ������� ���� (���-���): <b>9:00am - 5:00pm</b> (GMT)<br/>
        ������� ����� ������: � ������� <b><?=Contact::getAverageReplyTime()?> �����</b>
    </p>
</div>