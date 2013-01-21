<?php
namespace site\models;

class Collection extends \shared\models\Collection
{
    //todo: update to use setStatus() from \shared\models\Collection
    public function uploading(){
        $this->status = static::STATUS_UPLOADING;
        return $this->update(array('status'));
    }


}
