<?php

namespace Modules\Post\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Post\app\Http\Requests\PostsRequest;
use Modules\Post\app\Http\Requests\UpdatePostRequest;
use Modules\Post\app\Models\Post;
use Modules\Post\app\Resources\PostsResource;

class PostsController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::latest()->get();
        $postsResource = PostsResource::collection($posts);

        return ApiResponse::sendResponse(200, 'Posts retrieved successfully', $postsResource);
    }

    public function store(PostsRequest $request): JsonResponse
    {
        if ($request->hasFile('pet_photo')) {
            $extension = $request->file('pet_photo')->getClientOriginalExtension();
            $randomName = Str::random(10) . 'abed.' . $extension;

            $request->file('pet_photo')->move(public_path('pet_photos'), $randomName);
            $photoUrl = url('pet_photos/' . $randomName);
        }

        $userId = Auth::id();
        $requestData = array_merge($request->validated(), ['user_id' => $userId, 'pet_photo' => $photoUrl]);
        $post = Post::create($requestData);

        return ApiResponse::sendResponse(201, 'Post created successfully', ['post_id' => $post->id]);
    }

    public function show($id): JsonResponse
    {
        $post = Post::findOrFail($id);
        if (is_null($post)){
            return ApiResponse::sendResponse(200, 'no Post found');
        }

        $postResource = new PostsResource($post);

        return ApiResponse::sendResponse(200, 'Post retrieved successfully', $postResource);
    }

    public function showUserPosts(): JsonResponse
    {
        $user = Auth::user();
        $posts = $user->posts;
        $postResource = PostsResource::collection($posts);

        return ApiResponse::sendResponse(200, 'User posts retrieved successfully', $postResource);
    }
    public function update(UpdatePostRequest $request, $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        if ($request->hasFile('pet_photo')) {

            $extension = $request->file('pet_photo')->getClientOriginalExtension();
            $randomName = Str::random(10) . 'abed.' . $extension;

            $request->file('pet_photo')->move(public_path('pet_photos'), $randomName);
            $photoUrl = url('pet_photos/' . $randomName);

            $post->pet_photo = $photoUrl;
            $post->save();
        } else {
            return ApiResponse::sendResponse(400, 'Invalid pet photo provided.');
        }

        $post->update($request->validated());

        $postData = $post->toArray();
        $postData['pet_photo'] = $photoUrl;

        return ApiResponse::sendResponse(200, 'Post updated successfully', $postData);
    }



    // ... (store method remains the same)




    public function destroy($id): JsonResponse
    {
        $post = Post::findOrFail($id);

        if ($post->pet_photo) {
            $filePath = parse_url($post->pet_photo, PHP_URL_PATH);
            $fileFullPath = public_path($filePath);
            if (file_exists($fileFullPath)) {
                unlink($fileFullPath);
            }
        }

        $post->delete();

        return ApiResponse::sendResponse(200, 'Post deleted successfully');
    }
}
