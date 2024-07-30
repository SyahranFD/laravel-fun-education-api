<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function resStoreData($data, $notification = null)
    {
        if ($notification) {
            return response(['data' => $data, 'notification' => $notification], 201);
        }

        return response(['data' => $data], 201);
    }

    public function resInvalidLogin()
    {
        return response(['message' => 'Nama Lengkap or Password Is Invalid'], 409);
    }

    public function resUserNotFound()
    {
        return response(['message' => 'User Not Found'], 404);
    }

    public function resUserNotAdmin()
    {
        return response(['message' => 'User Not Admin'], 403);
    }

    public function resDataNotFound($data)
    {
        return response(['message' => $data.' Not Found'], 404);
    }

    public function resDataDeleted($data)
    {
        return response(['message' => $data.' Deleted'], 200);
    }
}
