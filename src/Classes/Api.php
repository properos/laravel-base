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
                if (is_string($data)) {
                    $query_string = $data;
                } elseif (is_array($data) && count($data) > 0) {
                    $query_string = http_build_query($data);
                } elseif (is_object($data)) {
                    $data = json_decode(json_encode($data), true);
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

        if (isset($options['locations'])) {
            \curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $options['locations']);
            \curl_setopt($curl, CURLOPT_MAXREDIRS, 20);
        }

        if (isset($options['return_headers']) && $options['return_headers']) {
            \curl_setopt($curl, CURLOPT_HEADER, 1);
        }

        if (isset($options['userpwd'])) {
            \curl_setopt($curl, CURLOPT_USERPWD, $options['userpwd']);
            \curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }

        \curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        \curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = \curl_exec($curl);

        if (isset($options['return_headers']) && $options['return_headers']) {
            // Then, after your curl_exec call:
            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);

            $headers = array();

            foreach (explode("\r\n", $header) as $i => $line) {
                if ($i === 0) {
                    $headers['http_code'] = $line;
                } else {
                    $_header = explode(': ', $line);
                    if (count($_header) > 1) {
                        $headers[$_header[0]] = $_header[1];
                    }
                }
            }

            if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json' && Helper::isJson($body)) {
                $body = json_decode($body, true);
            }

            return [
                'code' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
                'headers' => $headers,
                'body' => $body,
            ];
        } else {
            return $response;
        }
    }
}
