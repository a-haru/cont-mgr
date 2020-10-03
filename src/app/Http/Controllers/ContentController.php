<?php

namespace App\Http\Controllers;

use App\Entities\Content;
use App\Entities\ContentAutosave;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    //
    public function index()
    {
        return view('index');
    }

    public function list($clientId)
    {
        $contents = Content::where('client_id', $clientId)->get();
        return response()->json($contents->toArray());
    }

    private function commonValidate(Request $request)
    {
        $request->validate(([
            'title' => 'required|max:255',
            'description' => 'max:255'
        ]));
    }

    public function edit(Request $request)
    {
        $request->get('contentId');
    }

    public function store(Request $request, int $clientId)
    {
        $this->commonValidate($request);

        $autoSave = ContentAutosave::firstWhere(['client_id' => $clientId, 'content_id' => 0]);
        if ($autoSave) {
            $autoSave->delete();
        }
        $content = new Content();
        $content->fill($request->all());
        $content->setAttribute('client_id', $clientId);
        $content->save();

        return response()->json(json_decode($content->toJson()), 200);
    }

    public function autosave(Request $request, int $clientId, int $contentId)
    {
        $this->commonValidate($request);
        $key = [
            'client_id' => $clientId,
            'content_id' => $contentId
        ];
        ContentAutosave::updateOrCreate($key, $request->all());
    }

    public function fetchAutosave(int $clientId, int $contentId)
    {
        $query = ContentAutosave::select([
            'id',
            'title',
            'description',
            'text',
            'note'
        ]);
        $content = $query->firstWhere([
            'client_id' => $clientId,
            'content_id' => $contentId
        ]);
        if (is_null($content)) {
            return response()->json(null);
        } else {
            return response()->json($content->toJson());
        }
    }
}
