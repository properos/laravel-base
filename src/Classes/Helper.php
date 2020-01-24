<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Properos\Base\Classes;

/**
 * Description of Helper
 *
 * @author Properos
 */
class Helper
{

    public static function isActive($path, $class = 'active')
    {
        return (\Request::is($path)) ? $class : '';
    }

    public static function getValue($array, $key, $return = '')
    {
        $keys = explode('.', $key);
        $count_keys = count($keys);
        if ($count_keys > 1) {
            if (isset($array[$keys[0]])) {
                $n_key = '';
                for ($i = 1; $i < $count_keys; $i++) {
                    $n_key .= $keys[$i] . '.';
                }
                return self::getValue($array[$keys[0]], rtrim($n_key, '.'), $return);
            }
        } elseif ($count_keys > 0) {
            if (isset($array[$keys[0]]) && $array[$keys[0]] != '') {
                return $array[$keys[0]];
            }
        }

        return $return;
    }

    public static function splitFullname($name)
    {
        $fullname = [];
        $name_exploded = explode(" ", $name);
        if (count($name_exploded) > 2) {
            $fullname['first_name'] = $name_exploded[0] . ' ' . $name_exploded[1];
            $fullname['last_name'] = $name_exploded[2];
        } else {
            $fullname['first_name'] = self::getValue($name_exploded, 0, '');
            $fullname['last_name'] = self::getValue($name_exploded, 1, '');
        }

        return $fullname;
    }

    function generate_upc_checkdigit($upc_code)
    {
        $odd_total = 0;
        $even_total = 0;
        for ($i = 0; $i < 11; $i++) {
            if ((($i + 1) % 2) == 0) {
            /* Sum even digits */
                $even_total += $upc_code[$i];
            } else {
            /* Sum odd digits */
                $odd_total += $upc_code[$i];
            }
        }
        $sum = (3 * $odd_total) + $even_total;
    /* Get the remainder MOD 10*/
        $check_digit = $sum % 10;
 
    /* If the result is not zero, subtract the result from ten. */
        return ($check_digit > 0) ? 10 - $check_digit : $check_digit;
    }

    static function create_xml_feed(array $data)
    {
        $xw = xmlwriter_open_memory();
        xmlwriter_set_indent($xw, 1);
        $res = xmlwriter_set_indent_string($xw, ' ');

        xmlwriter_start_document($xw, '1.0', 'UTF-8');

        //AmazonEnvelope element
        xmlwriter_start_element($xw, 'AmazonEnvelope');

        // Attribute 'xmlns:xsi' for element 'AmazonEnvelope'
        xmlwriter_start_attribute($xw, 'xmlns:xsi');
        xmlwriter_text($xw, 'http://www.w3.org/2001/XMLSchema-instance');
        xmlwriter_end_attribute($xw);

        // Attribute 'xmlns:xsi' for element 'AmazonEnvelope'
        xmlwriter_start_attribute($xw, 'xsi:noNamespaceSchemaLocation');
        xmlwriter_text($xw, 'amzn-envelope.xsd');
        xmlwriter_end_attribute($xw);

        //Header element
        xmlwriter_start_element($xw, 'Header');
        xmlwriter_start_element($xw, 'DocumentVersion');
        xmlwriter_write_cdata($xw, "1.01");
        xmlwriter_end_element($xw);
        xmlwriter_end_element($xw);

        //MessageType element
        xmlwriter_start_element($xw, 'MessageType');
        xmlwriter_write_cdata($xw, "Inventory");
        xmlwriter_end_element($xw);

        //MessageType element 
        xmlwriter_start_element($xw, 'Message');
        xmlwriter_start_element($xw, 'MessageID');
        xmlwriter_write_cdata($xw, "1");
        xmlwriter_end_element($xw);
        xmlwriter_start_element($xw, 'OperationType');
        xmlwriter_write_cdata($xw, "Update");
        xmlwriter_end_element($xw);
        xmlwriter_start_element($xw, 'Inventory');
        xmlwriter_start_element($xw, 'SKU');
        xmlwriter_write_cdata($xw, $data['sku']);
        xmlwriter_end_element($xw);
        xmlwriter_start_element($xw, 'Quantity');
        xmlwriter_write_cdata($xw, $data['qty']);
        xmlwriter_end_element($xw);
        xmlwriter_end_element($xw);
        xmlwriter_end_element($xw);

        xmlwriter_end_element($xw);
        return xmlwriter_output_memory($xw); // AmazonEnvelope
    }

    public static function stringToArray($data)
    {
        if (is_string($data)) {
            return json_decode($data, true);
        } else if (!is_array($data)) {
            $data = [];
        }
        return $data;
    }

    public static function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function call($url, $data = [], $method = "POST", $headers = [], $options = [])
    {
        $curl = \curl_init();

        switch (strtoupper($method)) {
            case "POST":
                \curl_setopt($curl, CURLOPT_POST, 1);
                if (count($headers) > 0 && in_array('Content-Type: application/json', $headers)) {
                    \curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                } else {
                    if (count($headers) == 0) {
                        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                    }
                    if (is_array($data) && count($data) > 0) {
                        \curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                    }
                }
                $headers[] = 'Connection: close;';
                break;
            case "PUT":
                \curl_setopt($curl, CURLOPT_PUT, 1);
                if (count($headers) > 0 && in_array('Content-Type: application/json', $headers)) {
                    \curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                } else {
                    if (count($headers) == 0) {
                        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                    }
                    if (is_array($data) && count($data) > 0) {
                        \curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                    }
                }
                $headers[] = 'Connection: close;';
                break;
            case "DELETE":
                \curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                $headers[] = 'Connection: close;';
                break;
            default:
                if (!is_array($data)) {
                    $query_string = $data;
                } elseif (is_array($data) && count($data) > 0) {
                    $query_string = http_build_query($data);
                } else {
                    $query_string = "";
                }
                if (strlen($query_string) > 0) {
                    $url = sprintf("%s?%s", $url, $query_string);
                }
        }

        \curl_setopt($curl, CURLOPT_URL, $url);
        \curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        \curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        if (isset($options['userpwd'])) {
            \curl_setopt($curl, CURLOPT_USERPWD, $options['userpwd']);
            \curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }

        \curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        \curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = \curl_exec($curl);
        return $result;
    }

    public static function base2base($iNum, $iBase, $oBase, $iScale = 0)
    {
        $base2base = new Base2BaseClass();
        return $base2base->base_base2base($iNum, $iBase, $oBase, $iScale);
    }

    public static function shorten_string($string, $wordsreturned)
    /*Returns the first $wordsreturned out of $string.  If string
	contains more words than $wordsreturned, the entire string
	is returned.*/
    {
        $retval = $string;	//	Just in case of a problem
        $array = explode(" ", $string);
	/*  Already short enough, return the whole thing*/
        if (count($array) <= $wordsreturned) {
            $retval = $string;
        }
	/*  Need to chop of some words*/
        else {
            array_splice($array, $wordsreturned);
            $retval = implode(" ", $array) . " ...";
        }
        return $retval . '...';
    }

    public static function gen_invoice_token($id) {
        return (10000000 + $id) . uniqid();
    }


    public static function generate_random_string($length = 8)
    {
        $characters = 'A0B1C2D3E4F5G6H78I9J0K1L2M3N4O5P6Q7R8S9T0U1V2W3X4Y5Z6';
        $string = '';
        $characters_length = strlen($characters) - 1;

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $characters_length)];
        }

        return $string;

    }

    public static function ipAddress()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function round_up($number, $precision = 2)
    {
        $fig = pow(10, $precision);
        return (ceil($number * $fig) / $fig);
    }

}