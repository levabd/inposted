<?php
/**
 * @author Yurko Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace base\context;
class LogFilter extends \CComponent implements \ILogFilter
{
    protected $_context;

    /**
     * This method should be implemented to perform actual filtering of log messages
     * by working on the array given as the first parameter.
     * Implementation might reformat, remove or add information to logged messages.
     *
     * @param array $logs list of messages. Each array element represents one message
     *                    with the following structure:
     *                    array(
     *   [0] => message (string)
     *   [1] => level (string)
     *   [2] => category (string)
     *   [3] => timestamp (float, obtained by microtime(true));
     */
    public function filter(&$logs) {
        if($prefix = $this->getContextString()){
            foreach($logs as &$log){
                $log[0] = "[$prefix] {$log[0]}";
            }
        }
    }

    protected function getContextString(){
        if($context = $this->getContext()->toArray()){
            $context = array_flatten($context, '#');
            $data = array();
            foreach($context as $key => $value){
                if(null === $value){
                    $value = 'null';
                }
                elseif(0 === $value){
                    $value = '0';
                }
                elseif(is_object($value)){
                    if(method_exists($value, '__toString')){
                        $value = $value->__toString();
                    }
                    elseif(method_exists($value, 'getAttributes')){
                        $value = $value->getAttributes();
                    }
                    else{
                        $value = \CJSON::encode($value);
                    }
                }
                $data[] = is_numeric($key) ? $value : "$key=$value";
            }
            return implode(',', $data);
        }
        return '';
    }

    protected function getContext(){
        $context = Yii()->context;
        if($this->_context){
            foreach(explode('.', $this->_context) as $path){
                $context = $context->$path;
            }
        }

        return $context;
    }

    protected function setContext($context){
        $this->_context = $context;
    }

}
