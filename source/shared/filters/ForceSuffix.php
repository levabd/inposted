<?php
namespace shared\filters;

class ForceSuffix extends \CFilter {
    public $suffix = '/';
    protected function preFilter( $filterChain ) {
        $r = Yii()->getRequest();

        $s = preg_quote($this->suffix,'/');
        $uri = preg_replace("/({$s})+$/", $this->suffix, $r->requestUri . $this->suffix);

        if($uri == $r->requestUri && substr($uri, -strlen($this->suffix)) == $this->suffix){
            $filterChain->run();
            return;
        }

        $r->redirect($uri);
    }
}
