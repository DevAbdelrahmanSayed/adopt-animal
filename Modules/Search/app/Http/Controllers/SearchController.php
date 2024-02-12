<?php

namespace Modules\Search\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Post\app\Models\Post;
use Modules\Search\app\Resources\SearchResource;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $searchTerms = $request->input('text');

        $posts = Post::search($searchTerms)->get();
        if ($posts) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Search results', SearchResource::collection($posts));
        }
    }
}
