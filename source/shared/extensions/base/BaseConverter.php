<?php
namespace base;
class BaseConverter extends \CApplicationComponent{
    public $baseAlpha = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    public $baseSize  = 62;

    public function init(){
        parent::init();

        $min = 2;
        $max = strlen($this->baseAlpha);

        if($this->baseSize < 2 || $this->baseSize > $max)
            throw new \CException(\Yii::t('base','Incorrect target base size {size}. Min: {min}, max: {max}',
                array('{min}' => $min, '{max}' => $max, '{size}' => $this->baseSize))
            );
    }

    /**
     * @param int $N Base 10 number
     * @return string Base $R number as string
     */
    public function to($N){
        $base = $this->baseAlpha;
        $R = $this->baseSize;

        $out = '';
        //A magic here
        while ($N != 0) {
            $out = $base[$N%$R].$out;
            $N   = floor($N/$R);
        }
        return $out;
    }

    public function from($RN){
        $base = $this->baseAlpha;
        $R = $this->baseSize;

        $res = 0;
        $len = strlen($RN);
        $RN = strrev($RN);
        for($i = 0; $i < $len; $i++){
            $res += strpos($base, $RN[$i]) * pow($R, $i);
        }
        return $res;
    }

    public function selfTest(){
        $N = mt_rand(0, mt_getrandmax());
        $R = mt_rand(2, self::MAX);
        return $this->from($this->to($N,$R),$R) == $N;
    }
}