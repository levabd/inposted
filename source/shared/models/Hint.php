<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */

namespace shared\models;


use base\ActiveRecord;

class Hint extends ActiveRecord
{
    public $id;
    public $content;

    public function getRestAttributes() {
        return $this->attributes;
    }
}