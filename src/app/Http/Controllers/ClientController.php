<?php

namespace App\Http\Controllers;

use App\Entities\Client;
use Illuminate\Http\Request;

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
        $id = $request->get('id', null);

        if (is_null($id)) {
            $data = null;
        } else {
            $data = Client::find($id);
        }

        return response()->json($data);
    }
}
