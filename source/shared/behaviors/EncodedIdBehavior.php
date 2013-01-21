<?php
namespace shared\behaviors;
class EncodedIdBehavior extends \CActiveRecordBehavior
{
    public $base = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    public $size = 62;
    public $attribute = 'id';
    public $shift = 620;

    public function init() {
        parent::init();

        $this->shift = abs($this->shift);

        $min = 2;
        $max = strlen($this->baseAlpha);
        if ($this->baseSize < 2 || $this->baseSize > $max)
            throw new \CException(\Yii::t('base', 'Incorrect target base size {size}. Min: {min}, max: {max}',
                    array('{min}' => $min, '{max}' => $max, '{size}' => $this->size))
            );
    }

    public function findByEID($eid, $condition = '', $params = array()) {
        return $this->owner->findByPk($this->decodeId($eid), $condition, $params);
    }

    /**
     * @return string Encoded id
     */
    public function getEID() {
        return $this->encodeId($this->owner->{$this->attribute});
    }

    public function encodeId($id) {
        return $this->to($id + $this->shift);
    }

    public function decodeId($eid) {
        return $this->from($eid) - $this->shift;
    }

    public static function abc(){
        return 'abc';
    }

    private function to($N) {
        $base = $this->base;
        $R = $this->size;

        $out = '';
        //A magic here
        while ($N != 0) {
            $out = $base[$N % $R] . $out;
            $N = floor($N / $R);
        }
        return $out;
    }

    private function from($RN) {
        $base = $this->base;
        $R = $this->size;

        $res = 0;
        $len = strlen($RN);
        $RN = strrev($RN);
        for ($i = 0; $i < $len; $i++) {
            $res += strpos($base, $RN[$i]) * pow($R, $i);
        }
        return $res;
    }
}
