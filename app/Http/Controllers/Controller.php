<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $code = 200;

    private $msg = 'OK';

    private $data = [];

    public function success($msg, $data = [])
    {
        return $this->setData($data)->setMsg($msg)->buildJsonResponse();
    }

    public function failure($msg, $code = 500)
    {
        return $this->setMsg($msg)->setCode($code)->buildJsonResponse();
    }

    /**
     * @param int $code
     * @return void
     */
    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string $msg
     * @return $this
     */
    public function setMsg(string $msg): self
    {
        $this->msg = $msg;
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setDataBatch($data): self
    {
        if (is_array($data)) {
            $this->data = array_merge_recursive($this->data, $data);
        } elseif ($data instanceof Collection) {
            $this->data = $data->mergeRecursive($this->data);
        }
        return $this;
    }

    public function buildJsonResponse()
    {
        $body = ['code' => $this->code, 'msg' => $this->msg, 'data' => $this->data];
        return Response::json($body);
    }
}
