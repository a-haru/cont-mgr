<?php

namespace App\Http\Controllers;

use App\Entities\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    //
    public function list()
    {
        $list = Client::all();
        return response()->json($list);
    }

    public function getClient(Request $request)
    {
        $id = $request->get('id');

        if (is_null($id)) {
            $data = null;
        } else {
            $data = Client::find($id);
        }

        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $id = $request->get('id');
        $client = Client::find($id);
        $isDeleted = false;
        if (!is_null($client)) {
            $isDeleted = $client->delete();
        }
        $status = $isDeleted ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
        return response()->json($isDeleted, $status);
    }
}
