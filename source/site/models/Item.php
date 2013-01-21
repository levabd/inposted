<?php
namespace site\models;

class Item extends \shared\models\Item implements \shared\interfaces\RestRecord
{
    public function behaviors() {
        return array(
            array('class' => 'shared\behaviors\SignedUrlBehavior')
        );
    }

    public function createPreviewUrl() {
        return $this->createSignedUrl('/account/preview', array('sku' => $this->code, 'accountId' => $this->accountId));
    }

    public function getPublicAttributes() {
        $filter = array('id', 'accountId');
        $attrs = array_diff_key($this->getAttributes(), array_combine($filter, $filter));

        $presentation = $this->presentation;

        $attrs['deleted'] = (bool)$this->deleted;
        $attrs['status'] = $this->getStatus();
        $attrs['dateCreated'] = strtotime($this->dateCreated) . '000';

        $attrs['permalink'] = $this->createPreviewUrl();

        if($presentation){
            $attrs['presentation'] = $presentation->getPublicAttributes();
        } else {
            $attrs['presentation'] = false;
        }



        return $attrs;
    }
}
