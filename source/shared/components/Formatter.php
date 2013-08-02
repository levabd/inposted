<?php
namespace shared\components;

class Formatter extends \CFormatter
{
    public $bytesFormat = array('decimals' => 1, 'unitSystem' => 'si');

    public function formatDate($value) {
        if(is_string($value)){
            $value = strtotime($value);
        }

        return parent::formatDate($value);
    }

    public function formatDateTime($value) {
        if(is_string($value)){
            $value = strtotime($value);
        }

        return parent::formatDateTime($value);
    }


    public function formatTimeSec($value) {
        return $this->formatNumber($value) . ' сек.';
    }

    public function formatList($value) {
        if(is_array($value)){
            return join(', ', $value);
        }
        return $value;
    }

    public function formatDateMysql($timestamp = null, $shortFormat = false) {
        if (is_null($timestamp)) {
            $timestamp = time();
        }
        if (is_string($timestamp)) $timestamp = strtotime($timestamp);


        if ($shortFormat)
            $format = 'Y-m-d';
        else
            $format = 'Y-m-d H:i:s';

        return date($format, $timestamp);
    }

    public function formatBytes($size) {
        $decimals = $this->bytesFormat['decimals'];
        $prefix = $this->bytesFormat['unitSystem'];

        $units = array(
            'si' => array(
                'TB' => 1000000000000,
                'GB' => 1000000000,
                'MB' => 1000000,
                'kB' => 1000,
            ),
            'iec' => array(
                'TiB' => 1099511627776,
                'GiB' => 1073741824,
                'MiB' => 1048576,
                'KiB' => 1024,
            )
        );

        if (!isset($units[$prefix])) {
            throw new \CException('Unsupported unit system ' . $prefix . '. Use one of: si, iec');
        }

        $div = 1;
        $unit = 'B';

        foreach ($units[$prefix] as $u => $m) if ($size >= $m) {
            $div = $m;
            $unit = $u;
            break;
        }

        if($unit == 'B'){
            $decimals = 0;
        }

        return number_format($size / $div, $decimals) . ' ' . $unit;
    }

    public function parseBytes($string) {
        $units = array(
            //iec system
            'tib' => 1099511627776,
            'gib' => 1073741824,
            'mib' => 1048576,
            'kib' => 1024,

            //ci system
            'tb' => 1000000000000,
            'gb' => 1000000000,
            'mb' => 1000000,
            'kb' => 1000,

            //something else
            'g' => 1000000000,
            'm' => 1000000,
            'k' => 1000,
            'b' => 1,
        );
        $reg = '/([\d.]+)\s*(' . join(array_keys($units), '|') . ')/i';
        if (preg_match($reg, $string, $m)) {
            $value = floatval($m[1]);
            $unit = $m[2];
        } else {
            $value = floatval($string);
            $unit = 'B';
        }

        return $value * $units[strtolower($unit)];
    }

}
