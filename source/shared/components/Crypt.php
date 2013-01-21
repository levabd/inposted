<?php
/**
 * Date: 8/13/12 5:36 PM
 *
 * @author Dima Chukhai (dipp.dc@gmail.com, dipp@luckyteam.co.uk)
 */
namespace shared\components;

class Crypt extends \CApplicationComponent
{
    public $key;

    public function encrypt($str)
    {
        $block = mcrypt_get_block_size(MCRYPT_BLOWFISH, 'ecb');
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);

        return mcrypt_encrypt(MCRYPT_BLOWFISH, $this->key, $str, MCRYPT_MODE_ECB);
    }

    public function decrypt($str)
    {
        $str = mcrypt_decrypt(MCRYPT_BLOWFISH, $this->key, $str, MCRYPT_MODE_ECB);

        $pad = ord($str[($len = strlen($str)) - 1]);
        return substr($str, 0, strlen($str) - $pad);
    }
}

