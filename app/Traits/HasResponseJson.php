<?php

namespace App\Traits;

trait HasResponseJson
{
    public function response(int $status, string $message = "Sukses", mixed $data = null)
    {
        $success = ($status >= 200 && $status <=299);
        if($success){
            return response()->json(compact("success","message","data"), $status);
        }
        $errors = $data;
        return response()->json(compact("success","message","errors"), $status);
    }
}
