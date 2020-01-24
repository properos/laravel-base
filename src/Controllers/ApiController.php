<?php

namespace Properos\Base\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Properos\Base\Classes\Api;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected $httpCode = Response::HTTP_OK;
    protected $pagination = [];

    public function getHttpCode()
    {
        return $this->httpCode;
    }

    public function getCode() {
        return $this->myCode;
    }
    
    public function setHttpCode($statusCode)
    {
        $this->httpCode = $statusCode;
        return $this;
    }
    
    public function setCode($myCode) {
        $this->myCode = $myCode;
        return $this;
    }
    
    public function respondErrors($code = '000', $errors = [], $header = [], $data = []) {
        return $this->respond(Api::error($code, $errors, $data), $header);
    }
    
    public function respondWithErrors($errors = [], $header = [], $data = []) {
        return $this->respond(Api::error($this->getCode(), $errors, $data), $header);
    }

    public function respond($data, $header = [])
    {
        return \Response::json($data, $this->getHttpCode(), $header);
    }
    
    public function setPagination($pagination) {
        $this->pagination = $pagination;
        return $this;
    }
    
    public function respondInvalidParams($message = 'InvalidParams') {
        return $this->setCode('006')->respondWithErrors([$message]);
    }
    
     public function respondData($message = "", $data = [], $header = []) {
        return  $this->respond(Api::success($message, $data, $this->pagination), $header);
    }
}
