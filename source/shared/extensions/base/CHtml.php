<?php
$cacheFile = Yii()->getRuntimePath() . DS . 'yii_core_chtml_' . str_replace('.', '_', Yii::getVersion()) . '.php';
if(!file_exists($cacheFile)){
    $file = Yii::getPathOfAlias('system.web.helpers.CHtml') . '.php';
    $source = str_replace(['<?php', 'self::'], ['','static::'], file_get_contents($file));
    $source = "<?php\nnamespace yii_core;\nuse Yii, CJavaScriptExpression;\n$source";
    file_put_contents($cacheFile, $source);
}
require_once $cacheFile;

class CHtml extends yii_core\CHtml{
    /**
     * Generates input name for a model attribute.
     * Note, the attribute name may be modified after calling this method if the name
     * contains square brackets (mainly used in tabular input) before the real attribute name.
     * @param \base\ActiveRecord|\base\FormModel $model the data model
     * @param string $attribute the attribute
     * @return string the input name
     */
    public static function resolveName($model,&$attribute)
    {
        if(($pos=strpos($attribute,'['))!==false)
        {
            if($pos!==0)  // e.g. name[a][b]
                return $model->formName().'['.substr($attribute,0,$pos).']'.substr($attribute,$pos);
            if(($pos=strrpos($attribute,']'))!==false && $pos!==strlen($attribute)-1)  // e.g. [a][b]name
            {
                $sub=substr($attribute,0,$pos+1);
                $attribute=substr($attribute,$pos+1);
                return $model->formName().$sub.'['.$attribute.']';
            }
            if(preg_match('/\](\w+\[.*)$/',$attribute,$matches))
            {
                $name=$model->formName().'['.str_replace(']','][',trim(strtr($attribute,array(']['=>']','['=>']')),']')).']';
                $attribute=$matches[1];
                return $name;
            }
        }
        return $model->formName().'['.$attribute.']';
    }
}

