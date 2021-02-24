<?php

namespace Properos\Base\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class ApiException extends Exception
{
    protected $errors = [];
    protected $code = '';
    protected $data = [];
    protected $headers;
    protected $httpCode;

    public function __construct($errors = '', $code = '', $data = [], $headers = [], $httpCode = Response::HTTP_OK)
    {
        parent::__construct("Error: ApiException");

        if (is_string($errors)) {
            $errors = [$errors];
        }

        $this->errors = $errors;
        $this->messages = $errors;
        $this->code = $code;
        $this->data = $data;
        $this->headers = $headers;
        $this->httpCode = $httpCode;
        \Log::info(json_encode($this->errors));
    }
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return  response()->json($this->ApiResponse(), $this->httpCode, $this->headers);
    }

    public function messages()
    {
        return $this->messages;
    }
    
    public function message()
    {
        return Arr::get($this->messages, '0', '');
    }

    public function getMessages()
    {
        return $this->message;
    }
    
    public function data()
    {
        return $this->data;
    }
    
    public function code()
    {
        return $this->code;
    }
    
    public function ApiResponse()
    {
        $response = [
            'status' => 0,
            'code' => $this->code,
            'errors' => $this->errors,
        ];
        if (is_array($this->data) && count($this->data) > 0) {
            $response['data'] = $this->data;
        }else if (is_object($this->data) && $this->data) {
            $response['data'] = $this->data;
        }

        return $response;
    }
}
