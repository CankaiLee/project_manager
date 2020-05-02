<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $per_page = 10;

    protected function _output_json($message, $data = null, $code = 0, $success = false)
    {
        return response()->json([
            'code' => $code,
            'data' => $data,
            'msg' => $message,
            'success' => $success,
        ]);
    }

    protected function _output_success($message, $data = null, $code = 0)
    {
        return $this->_output_json($message, $data, $code, true);
    }

    protected function _output_error($message, $data = null, $code = 500)
    {
        return $this->_output_json($message, $data, $code, false);
    }

    protected function _output_exception(\Exception $exception, $data = null)
    {
        return $this->_output_json($exception->getMessage(), $data, $exception->getCode());
    }
}
