<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\validators;
class Password extends \CValidator
{
    public $maxSameChars = 3;
    /**
     * Validates a single attribute.
     * This method should be overridden by child classes.
     *
     * @param \CModel $object    the data object being validated
     * @param string  $attribute the name of the attribute to be validated.
     */
    protected function validateAttribute($object, $attribute) {
        if (!$object->$attribute) return;

        $password = $object->$attribute;
        $encoding = Yii()->charset;
        $length = mb_strlen($password, $encoding);

        $error = null;

        if (mb_strtolower($password, $encoding) == $password || mb_strtoupper($password, $encoding) == $password) {
            $error = '{attribute} должен содержать буквы разного регистра';
        } else {
            $charCounts = [];
            for ($i = 0; $i < $length; $i++) {
                $char = mb_substr($password, $i, 1, $encoding);
                if (!isset($charCounts[$char])) {
                    $charCounts[$char] = 1;
                } else {
                    $charCounts[$char]++;
                    if ($charCounts[$char] > $this->maxSameChars) {
                        $error = '{attribute} не может содержать более {num} одинаковых символов.';
                        break;
                    }
                }
            }
        }

        if ($error) {
            $this->addError(
                $object,
                $attribute,
                \Yii::t(
                    'inposted', $error,
                    [
                    '{attribute}' => \Yii::t('inposted', $object->getAttributeLabel($attribute)),
                    '{num}'       => $this->maxSameChars,
                    ]
                )
            );
        }
    }

}
