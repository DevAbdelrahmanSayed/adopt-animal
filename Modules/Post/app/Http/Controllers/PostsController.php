<?php

namespace Modules\Post\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\Post\app\Http\Requests\PostsRequest;
use Modules\Post\app\Http\Requests\UpdatePostRequest;
use Modules\Post\app\Resources\PostsResource;
use Modules\Post\app\Resources\UpdatePostResource;
use Modules\Post\Dto\PostDto;
use Modules\Post\Services\PostService;

class PostsController extends Controller
{
    public function __construct(protected PostService $postService)
    {
    }

    public function index(): JsonResponse
    {

        $posts = Cache::remember('posts.index', now()->addHour(1), function () {
            return $this->postService->getAllPosts();
        });
        $postsResource = PostsResource::collection($posts);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Posts retrieved successfully', $postsResource);

    }

    public function store(PostsRequest $request): JsonResponse
    {
        try {
            $requestData = PostDto::fromPostRequest($request, Auth::id());
            $responseData = $this->postService->store($requestData);

            return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Post created successfully', ['post_id' => $responseData->id]);
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'An error occurred while creating the post');
        }
    }

    public function show($postId): JsonResponse
    {
        try {
            $post = Cache::remember("post.{$postId}", now()->addHour(1), function () use ($postId) {
                return $this->postService->show($postId);
            });
            if ($post) {
                return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Post retrieved successfully', new PostsResource($post));
            }

            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Post not found');
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'An error occurred while retrieving the post');
        }
    }

    public function showUserPosts(): JsonResponse
    {
        try {
            $responseData = $this->postService->showUserPosts(Auth::id());

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'User posts retrieved successfully', PostsResource::collection($responseData));

        } catch (\Exception $e) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'An error occurred while retrieving the user post', $e->getMessage());
        }
    }

    public function update(UpdatePostRequest $request, $postId): JsonResponse
    {
        try {
            $requestData = PostDto::fromUpdatePostRequest($request);
            $responseData = $this->postService->update($requestData, $postId);

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Post updated successfully', new UpdatePostResource($responseData));

        } catch (\Exception $e) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'An error occurred while update the post', $responseData);

        }

    }

    public function destroy($postId): JsonResponse
    {
        try {

            $this->postService->destroy($postId);

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Post deleted successfully');

        } catch (\Exception $e) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'An error occurred while delete the post');

        }

    }
}
