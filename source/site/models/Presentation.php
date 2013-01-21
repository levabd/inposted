<?php
namespace site\models;

class Presentation extends \shared\models\Presentation implements \shared\interfaces\RestRecord
{
    public function render($mode) {
        switch ($mode) {
            case 'full':
                return $this->renderFull();
            case 'id':
                return $this->renderId();
        }
    }

    public function renderFull() {
        return $this->template->render($this);
    }

    public function renderId() {
        $id = $this->id;
        $src = $this->getSrc();
        $alt = $this->getName();

        return "<a class=\"InpostedPresentation\" rel=\"id:{$id}\" href=\"#\">\n\t<img src=\"{$src}\" alt=\"{$alt}\"/>\n</a>";
    }

    protected function uncamel($string){
        return strtolower(preg_replace('/([a-z])([A-Z])/','$1-$2', $string));
    }

    public function getPublicAttributes() {
        $profile = User()->getAccount()->profile();

        return array(
            'id' => $this->id,
            'status' => $this->status,
            'options' => (object)$this->options,
            'data' =>  (object)$this->data,
            'profile' => (object)$profile->options,
            'dateCreated' =>  strtotime($this->dateCreated) . '000',
        );
    }
}
