<?php

namespace App\Http\Controllers;

use App\Entities\Content;
use App\Entities\ContentAutosave;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index($clientId)
    {
        $contents = Content::where('client_id', $clientId)->get();
        return response()->json($contents->toArray());
    }

    /**
     * 記事新規登録
     */
    public function store(Request $request, int $clientId)
    {
        $this->commonValidate($request);

        $this->deleteAutosaveContent($clientId, 0);
        $content = new Content();
        $content->fill($request->all());
        $content->setAttribute('client_id', $clientId);
        $content->save();

        return response()->json(json_decode($content->toJson()), 200);
    }

    public function show(int $clientId, int $contentId)
    {
        $content = Content::firstWhere(['id' => $contentId, 'client_id' => $clientId]);

        if (is_null($content)) {
            return response()->json(null, 404);
        }

        return response()->json($content->toJson(), 200);
    }

    public function update(Request $request, int $clientId, int $contentId)
    {
        $content = Content::firstWhere(['id' => $contentId, 'client_id' => $clientId]);

        if (is_null($content)) {
            return response()->json(false, 404);
        }
        $this->deleteAutosaveContent($clientId, $contentId);

        $content->fill($request->all());
        $content->save();

        return response()->json(true, 200);
    }

    public function delete(int $clientId, int $contentId)
    {
        $content = Content::firstWhere(['id' => $contentId, 'client_id' => $clientId]);

        if (is_null($content)) {
            return response()->json(false, 404);
        }
        $content->delete();

        return response()->json(true, 200);
    }

    private function deleteAutosaveContent(int $clientId, int $contentId): void
    {
        $autoSave = ContentAutosave::firstWhere(['client_id' => $clientId, 'content_id' => $contentId]);
        if ($autoSave) {
            $autoSave->delete();
        }
    }

    private function commonValidate(Request $request)
    {
        $request->validate(([
            'title' => 'required|max:255',
            'description' => 'max:255'
        ]));
    }

    public function edit(int $id)
    {
        $content = Content::find($id);
        return response()->json($content->toJson(), 200);
    }

    public function showAutosave(int $clientId, int $contentId)
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

    public function storeAutosave(Request $request, int $clientId, int $contentId)
    {
        $this->commonValidate($request);

        $key = [
            'client_id' => $clientId,
            'content_id' => $contentId
        ];
        $isUpdatedOrCreated = ContentAutosave::updateOrCreate($key, $request->all());

        return response()->json(true, 200);
    }
}
