<?php

namespace App\Http\Controllers;

use App\Entities\Content;
use App\Entities\ContentAutosave;
use Illuminate\Http\Request;

/**
 * ContentController
 */
class ContentController extends Controller
{

    /**
     * 対象クライアントの記事一覧を取得
     *
     * @param integer $clientId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $clientId)
    {
        $contents = Content::where('client_id', $clientId)->get();
        return response()->json($contents->toArray());
    }

    /**
     * 記事新規登録
     *
     * @param Request $request
     * @param integer $clientId
     * @return \Illuminate\Http\JsonResponse
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

    /**
     * 記事の登録内容を参照
     *
     * @param integer $clientId
     * @param integer $contentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $clientId, int $contentId)
    {
        $content = Content::firstWhere(['id' => $contentId, 'client_id' => $clientId]);

        if (is_null($content)) {
            return response()->json(null, 404);
        }

        return response()->json($content->toJson(), 200);
    }

    /**
     * 記事を更新
     *
     * @param Request $request
     * @param integer $clientId
     * @param integer $contentId
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * 記事を削除
     *
     * @param integer $clientId
     * @param integer $contentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $clientId, int $contentId)
    {
        $content = Content::firstWhere(['id' => $contentId, 'client_id' => $clientId]);

        if (is_null($content)) {
            return response()->json(false, 404);
        }
        $this->deleteAutosaveContent($clientId, $contentId);

        $content->delete();

        return response()->json(true, 200);
    }

        
    /**
     * 自動保存されたレコードを削除
     *
     * @param integer $clientId
     * @param integer $contentId
     * @return void
     */
    private function deleteAutosaveContent(int $clientId, int $contentId): void
    {
        $autoSave = ContentAutosave::firstWhere(['client_id' => $clientId, 'content_id' => $contentId]);
        if ($autoSave) {
            $autoSave->delete();
        }
    }

    /**
     * 登録・更新共通のバリデーション
     *
     * @param Request $request
     * @return void
     */
    private function commonValidate(Request $request)
    {
        $request->validate(([
            'title' => 'required|max:255',
            'description' => 'max:255'
        ]));
    }
    
    /**
     * 自動保存された記事を参照する
     *
     * @param integer $clientId
     * @param integer $contentId
     * @return \Illuminate\Http\JsonResponse
     */
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
            return response()->json(null, 404);
        } else {
            return response()->json($content->toJson());
        }
    }
    
    /**
     * 自動保存用テーブルに記事を保存する
     *
     * @param Request $request
     * @param integer $clientId
     * @param integer $contentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAutosave(Request $request, int $clientId, int $contentId)
    {
        $this->commonValidate($request);

        if (!Content::where(['id' => $contentId, 'client_id' => $clientId])->exists()) {
            return response()->json(false, 404);
        }
        $key = [
            'client_id' => $clientId,
            'content_id' => $contentId
        ];
        ContentAutosave::updateOrCreate($key, $request->all());

        return response()->json(true, 200);
    }
}
