<?php
namespace site\models;

class Profile extends \shared\models\Profile implements \shared\interfaces\RestRecord
{
    public function getPublicAttributes() {
        return array(
            'name' => $this->name,
            'options' => $this->options,
        );
    }

}
