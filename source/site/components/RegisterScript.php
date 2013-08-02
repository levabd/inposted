<?php
namespace site\components;
/**
 * @author Yurko Fedoriv <yurko.fedoriv@gmail.com>
 *
 * Widget to move inline scripts in views to the right place.
 * F.I. styles should always be placed withing <head></head> tags and good place for JavaScript pieces is ready function.
 * You may wrap <style></style> and <script></script> blocks with this widget and their content will appear where it supposed to be.
 */
class RegisterScript extends \CWidget
{
    const TYPE_CSS = 'css';
    const TYPE_SCRIPT = 'script';

    /**
     * @var string Default type to use if source was not wrapped with tag.
     */
    public $type = self::TYPE_CSS;

    /**
     * @var int Position to place script while processing JavaScript piece
     */
    public $position = \CClientScript::POS_READY;

    /**
     * @var int Media to use while processing CSS piece
     */
    public $media = '';

    /**
     * Starts recording script
     */
    public function init() {
        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * Ends recording script
     * This method will register content in appropriate place
     * @throws \CException On incorrect type.
     */
    public function run() {
        $data = trim(ob_get_clean());
        if (substr($data, 0, strlen('<style')) == '<style') {
            $type = self::TYPE_CSS;
        } elseif (substr($data, 0, strlen('<script')) == '<script') {
            $type = self::TYPE_SCRIPT;
        }
        else {
            $type = $this->type;
        }

        $data = $this->removeTags($data);
        if ($type == self::TYPE_CSS) {
            Yii()->clientScript->registerCss(__CLASS__ . "#css#$this->id", $data, $this->media);
        } elseif ($type == self::TYPE_SCRIPT) {
            Yii()->clientScript->registerScript(__CLASS__ . "#js#$this->id", $data, $this->position);
        }
        else {
            throw new \CException(__CLASS__ . ": Неизвестный тип скрипта $type.");
        }
    }

    private function removeTags($data){
        if(preg_match('/<(?:script|style).*?>(.*)<\/(?:script|style)>/is', $data, $matches)){
            return trim($matches[1]);
        }
        else return $data;
    }
}
