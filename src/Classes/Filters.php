<?php
namespace Properos\Base\Classes;

class Filters
{
    public static function cleanPhone($info)
    {
        $info = explode('x', $info);
        if (isset($info[0])) {
            $info[0] = self::deleteSpecialCharacters($info[0]);
            $phone = preg_replace("/[^0-9]/", "", $info[0]);
        }
        if (isset($info[1])) {
            $info[1] = self::deleteSpecialCharacters($info[1]);
            $phone = $phone . 'x' . preg_replace("/[^0-9,]/", "", $info[1]);
        }
        return $phone;
    }

    public static function cleanString($info, $options = ['strtolower', 'ucwords'])
    {
        $info = preg_replace('[\s+]', ' ', trim($info));
        
        if (in_array('strtolower', $options)) {
            $info = strtolower($info);
        }
        if (in_array('ucwords', $options)) {
            $info = ucwords($info);
        }
        if (in_array('strtoupper', $options)) {
            $info = strtoupper($info);
        }

        return $info;
    }

    public static function cleanAddress($info, $options = ['strtolower'])
    {
        return self::cleanString($info, $options);
    }

    public static function cleanEmail($info, $options = ['strtolower'])
    {
        return self::cleanString($info, $options);
    }

    public static function cleanZipCode($info, $options = ['short', 'large'])
    {
        if (in_array('short', $options)) {
            $info_large = preg_replace("/[^0-9,.]/", "", $info);
            $info = substr($info, 0, 5);
        }
        if (in_array('large', $options)) {
            if (strlen($info_large) == 9) {
                $info = $info . '-' . substr($info_large, 5, 9);
            }
        }
        return $info;
    }

    public static function createIndex($info)
    {
        return preg_replace('[\s+]', '_', self::cleanString($info, ['strtolower']));
    }

    public static function deleteSpecialCharacters($info, $replace = ' ')
    {
        return preg_replace('([^A-Za-z0-9\s])', $replace, $info);
    }

    public static function luminance($hexcolor, $percent)
    {
        if (strlen($hexcolor) < 6) {
            $hexcolor = $hexcolor[0] . $hexcolor[0] . $hexcolor[1] . $hexcolor[1] . $hexcolor[2] . $hexcolor[2];
        }
        $hexcolor = array_map('hexdec', str_split(str_pad(str_replace('#', '', $hexcolor), 6, '0'), 2));

        foreach ($hexcolor as $i => $color) {
            $from = $percent < 0 ? 0 : $color;
            $to = $percent < 0 ? $color : 255;
            $pvalue = ceil(($to - $from) * $percent);
            $hexcolor[$i] = str_pad(dechex($color + $pvalue), 2, '0', STR_PAD_LEFT);
        }

        return '#' . implode($hexcolor);
    }
    public static function hexToRgb($hexcolor, $alpha = 1)
    {
        list($r, $g, $b) = sscanf($hexcolor, "#%02x%02x%02x");
        
        return ['r' => $r, 'g' => $g, 'b' => $b, 'a' => $alpha];
    }
}
