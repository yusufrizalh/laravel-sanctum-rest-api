<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Article;
use App\Http\Resources\Article as ArticleResource;
use Illuminate\Support\Facades\Validator;


class ArticleController extends BaseController
{
    public function index()
    {
        $articles = Article::all();
        return $this->sendResponse(ArticleResource::collection($articles), 'Posts fetched.');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $article = Article::create($input);
        return $this->sendResponse(new ArticleResource($article), 'Post created.');
    }

    public function show($id)
    {
        $Article = Article::find($id);
        if (is_null($Article)) {
            return $this->sendError('Post does not exist.');
        }
        return $this->sendResponse(new ArticleResource($Article), 'Post fetched.');
    }

    public function update(Request $request, Article $article)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $article->title = $input['title'];
        $article->description = $input['description'];
        $article->save();

        return $this->sendResponse(new ArticleResource($article), 'Post updated.');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return $this->sendResponse([], 'Post deleted.');
    }
}
