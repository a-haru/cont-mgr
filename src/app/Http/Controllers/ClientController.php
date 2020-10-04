<?php

namespace App\Http\Controllers;

use App\Entities\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    /**
     * 利用者一覧を表示
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $list = Client::all();
        return response()->json($list);
    }

    public function show(int $id)
    {
        $query = Client::select([
            'id',
            'name',
            'url',
            'contract_activate_at',
            'contract_deactivate_at',
        ]);
        $client = $query->find($id);

        if (is_null($client)) {
            return response()->json(null, 404);
        }

        return response()->json($client);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'url' => 'required|max:255',
            'contract_activate_at' => 'required|date',
            'contract_deactivate_at' => 'required|date'
        ]);

        $client = new Client();
        $client->fill($request->all());
        $client->save();

        return response()->json(true);
    }

    public function update(Request $request, int $clientId)
    {
        $request->validate([
            'name' => 'required|max:255',
            'url' => 'required|max:255',
            'contract_activate_at' => 'required|date',
            'contract_deactivate_at' => 'required|date'
        ]);

        $client = Client::find($clientId);
        if (is_null($client)) {
            return response()->json(false, 404);
        }
        $client->fill($request->all());
        $client->save();
        return response()->json(true, 200);
    }

    public function delete(int $clientId)
    {
        $client = Client::find($clientId);
        $isDeleted = false;
        if (!is_null($client)) {
            $isDeleted = $client->delete();
        }
        $status = $isDeleted ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
        return response()->json($isDeleted, $status);
    }
}
