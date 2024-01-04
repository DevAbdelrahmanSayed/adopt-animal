<?php

namespace Modules\Favorite\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Post\app\Models\Post;
use Modules\Post\app\Resources\PostsResource;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $posts = $user->favorites()->get(); // Corrected method call
        $postResource = PostsResource::collection($posts);
        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Favorites retrieved successfully', $postResource);
    }

    public function addFavorite(Post $post)
    {
        $user = Auth::user();
        if ($user->favorites()->where('post_id', $post->id)->exists()) {

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Error: Post already favorited');
        }
        $user->favorites()->syncWithoutDetaching([$post->id]);
        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Favorite added successfully', ['post_id' => $post->id]);
    }

    public function removeFavorite(Post $post)
    {
        $user = Auth::user();
        $user->favorites()->detach([$post->id]);
        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Favorite deleted successfully', ['post_id' => $post->id]);
    }
}
