<?php
namespace base;

class Randomizr
{
    const ALPHA_EXTENDED = 'extended';
    const ALPHA_MIXEDCASE = 'mixed';
    const ALPHA_SINGLECASE = 'upper';

    public static function generateRandomString($length, $alphabeth = null) {
        switch ($alphabeth) {
            case self::ALPHA_SINGLECASE:
                $alphabeth = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                break;
            default:
            case self::ALPHA_MIXEDCASE:
                $alphabeth = 'abcdefghigklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                break;
            case self::ALPHA_EXTENDED:
                $alphabeth = 'abcdefghigklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=+';
                break;
        }

        $alphalen = strlen($alphabeth);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $alphabeth[mt_rand(0, $alphalen - 1)];
        }
        return $string;
    }

    public static function hashPassword($password, $salt = '') {
        $salt = $salt
                ? :
            // SHA-256 hash
            // http://php.net/manual/en/function.crypt.php
                '$5$rounds=5000$' . self::generateRandomString(16) . '$';

        return crypt($password, $salt);
    }

    public static function generateUniqueAttribute($class, $attribute, $length, $alphabeth = null) {
        $value = self::generateRandomString($length, $alphabeth);

        if (self::checkUniqueAttribute($class, $attribute, $value)) {
            return $value;
        }

        return self::generateUniqueAttribute($class, $attribute, $length);
    }

    private static function checkUniqueAttribute($class, $attribute, $value) {
        $model = call_user_func(array($class, 'model'));
        return !$model->findByAttributes(array($attribute => $value));
    }

}
