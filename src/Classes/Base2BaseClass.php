<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Properos\Base\Classes;

/**
 * Description of Base2BaseClass
 *
 * @author Roberto Heredia
 */
class Base2BaseClass {
    
    private function base_dec2base($iNum, $iBase, $iScale = 0) { // cope with base 2..62
        $LDEBUG = FALSE;
        $sChars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $sResult = ''; // Store the result
        // special case for Base64 encoding
        if ($iBase == 64)
            $sChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-';
        if ($iBase == 78)
            $sChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-:@~!$&'()*+,;=";

        $sNum = is_integer($iNum) ? "$iNum" : (string) $iNum;
        $iBase = intval($iBase); // incase it is a string or some weird decimal
        // Check to see if we are an integer or real number
        if (strpos($sNum, '.') !== FALSE) {
            list ($sNum, $sReal) = explode('.', $sNum, 2);
            $sReal = '0.' . $sReal;
        } else
            $sReal = '0';

        while ( \bccomp($sNum, 0, $iScale) != 0) { // still data to process
            $sRem = \bcmod($sNum, $iBase); // calc the remainder
            $sNum = \bcdiv(bcsub($sNum, $sRem, $iScale), $iBase, $iScale);
            $sResult = $sChars[$sRem] . $sResult;
        }
        if ($sReal != '0') {
            $sResult .= '.';
            $fraciScale = $iScale;
            while ($fraciScale-- && \bccomp($sReal, 0, $iScale) != 0) { // still data to process
                if ($LDEBUG)
                    print "<br> -> $sReal * $iBase = ";
                $sReal = \bcmul($sReal, $iBase, $iScale); // multiple the float part with the base
                if ($LDEBUG)
                    print "$sReal  => ";
                $sFrac = 0;
                if ( \bccomp($sReal, 1, $iScale) > -1)
                    list($sFrac, $dummy) = explode('.', $sReal, 2); // get the intval
                if ($LDEBUG)
                    print "$sFrac\n";
                $sResult .= $sChars[$sFrac];
                $sReal = \bcsub($sReal, $sFrac, $iScale);
            }
        }

        return $sResult;
    }

    private function base_base2dec($sNum, $iBase = 0, $iScale = 0) {
        $sChars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $sResult = '';

        $iBase = intval($iBase); // incase it is a string or some weird decimal
        // special case for Base64 encoding
        if ($iBase == 64)
            $sChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-';
        if ($iBase == 78)
            $sChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-:@~!$&'()*+,;=";

        // clean up the input string if it uses particular input formats
        switch ($iBase) {
            case 16: // remove 0x from start of string
                if (strtolower(substr($sNum, 0, 2)) == '0x')
                    $sNum = substr($sNum, 2);
                break;
            case 8: // remove the 0 from the start if it exists - not really required
                if (strpos($sNum, '0') === 0)
                    $sNum = substr($sNum, 1);
                break;
            case 2: // remove an 0b from the start if it exists
                if (strtolower(substr($sNum, 0, 2)) == '0b')
                    $sNum = substr($sNum, 2);
                break;
            case 64: // remove padding chars: =
                $sNum = str_replace('=', '', $sNum);
                break;
            default: // Look for numbers in the format base#number,
                // if so split it up and use the base from it
                if (strpos($sNum, '#') !== false) {
                    list ($sBase, $sNum) = explode('#', $sNum, 2);
                    $iBase = intval($sBase);  // take the new base
                }
                if ($iBase == 0) {
                    print("base_base2dec called without a base value and not in base#number format");
                    return '';
                }
                break;
        }

        // Convert string to upper case since base36 or less is case insensitive
        if ($iBase < 37)
            $sNum = strtoupper($sNum);

        // Check to see if we are an integer or real number
        if (strpos($sNum, '.') !== FALSE) {
            list ($sNum, $sReal) = explode('.', $sNum, 2);
            $sReal = '0.' . $sReal;
        } else
            $sReal = '0';


        // By now we know we have a correct base and number
        $iLen = strlen($sNum);

        // Now loop through each digit in the number
        for ($i = $iLen - 1; $i >= 0; $i--) {
            $sChar = $sNum[$i]; // extract the last char from the number
            $iValue = strpos($sChars, $sChar); // get the decimal value
            if ($iValue > $iBase) {
                print("base_base2dec: $sNum is not a valid base $iBase number");
                return '';
            }
            // Now convert the value+position to decimal
            $sResult = \bcadd($sResult, \bcmul($iValue, \bcpow($iBase, ($iLen - $i - 1))));
        }

        // Now append the real part
        if (strcmp($sReal, '0') != 0) {
            $sReal = substr($sReal, 2); // Chop off the '0.' characters
            $iLen = strlen($sReal);
            for ($i = 0; $i < $iLen; $i++) {
                $sChar = $sReal[$i]; // extract the first, second, third, etc char
                $iValue = strpos($sChars, $sChar); // get the decimal value
                if ($iValue > $iBase) {
                    print("base_base2dec: $sNum is not a valid base $iBase number");
                    return '';
                }
                $sResult = \bcadd($sResult, \bcdiv($iValue, \bcpow($iBase, ($i + 1)), $iScale), $iScale);
            }
        }

        return $sResult;
    }

    public function base_base2base($iNum, $iBase, $oBase, $iScale = 0) {
        
        if ($iBase != 10)
            $oNum = $this->base_base2dec($iNum, $iBase, $iScale);
        else
            $oNum = $iNum;
        $oNum = $this->base_dec2base($oNum, $oBase, $iScale);
        return $oNum;
    }
}