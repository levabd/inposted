<?php
namespace site\models;

class Domain extends \shared\models\Domain implements \shared\interfaces\RestRecord
{
    public function getPublicAttributes() {
        return $this->getAttributes();
    }
}
