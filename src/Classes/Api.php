<?php

namespace Properos\Base\Classes;

/*
 * API
 */

class Api
{
    /*
     * Response Success Status
     * @param String $message
     * @param Array $data
     * @param Array $pagination
     *
     * @return Array
     */

    public static function success($message = "", $data = [], $pagination = [])
    {
        $response = [
            'status' => 1,
            'message' => $message,
            'data' => []
        ];

        if (is_array($data) && count($data) > 0) {
            $response['data'] = $data;
        } elseif ($data) {
            $response['data'] = $data;
        }
        
        if (count($pagination) > 0) {
            $response['pagination'] = $pagination;
        }
        return $response;
    }
    /*
     * Response Fail Status
     *
     * @param String $code
     * @param Array|String $errors
     * @param Array $data
     *
     * @return Array
     */

    public static function error($code = '', $errors = [], $data = [])
    {
        if (is_string($errors)) {
            $errors = [$errors];
        }
        $response = [
            'status' => 0,
            'code' => $code,
            'errors' => $errors,
        ];
        if (is_array($data) && count($data) > 0) {
            $response['data'] = $data;
        } elseif ($data) {
            $response['data'] = $data;
        }

        return $response;
    }
    
    public static function call($url, $data = [], $method = "POST", $headers = [])
    {
        $curl = curl_init();
        switch (strtoupper($method)) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if (count($headers) > 0 && in_array('Content-Type: application/json', $headers)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                } else {
                    if (count($headers) == 0) {
                        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                    }
                    if (is_array($data) && count($data) > 0) {
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                    }
                }
                $headers[] = 'Connection: close;';
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                if (count($headers) > 0 && in_array('Content-Type: application/json', $headers)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                } else {
                    if (count($headers) == 0) {
                        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                    }
                    if (is_array($data) && count($data) > 0) {
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                    }
                }
                $headers[] = 'Connection: close;';
                break;
            default:
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        return curl_exec($curl);
    }
}
