<?php

namespace Modules\Post\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Post\app\Http\Requests\PostsRequest;
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

        if ($request->hasFile('photo')) {
            $originalName = $request->file('photo')->getClientOriginalName();
            $extension = $request->file('photo')->getClientOriginalExtension();
            $randomName = Str::random(10) . 'abed.' . $extension; // Generate a random name

            $photoPath = $request->file('photo')->storeAs('public/photos', $randomName);
            $photoPath = Storage::url($photoPath);
        } else {
            // Set a default photo path
            $photoPath = asset('assets/default.jpg');
        }

        // Create the post
        $userId = auth()->id();
        $requestData = array_merge($request->validated(), ['user_id' => $userId, 'photo' => $photoPath]);
        $post = Post::create($requestData);

        // Transform and send the response
        $postResource = new PostsResource($post);

        return ApiResponse::sendResponse(201, 'Post created successfully', $postResource);
    }


    public function show($id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $postResource = new PostsResource($post);

        return ApiResponse::sendResponse(200, 'Post retrieved successfully', $postResource);
    }


    public function update(PostsRequest $request, $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        if ($request->hasFile('photo')) {

        $originalName = $request->file('photo')->getClientOriginalName();
        $extension = $request->file('photo')->getClientOriginalExtension();
        $randomName = Str::random(20) . '.' . $extension;

        $photoPath = $request->file('photo')->storeAs('public/photos', $randomName);
        $photoPath = Storage::url($photoPath);


        $post->update(array_merge($request->validated(), ['photo' => $photoPath]));
    } else {
            $photoPath = asset('assets/default.jpg');
        // If no new photo is provided, update with existing photo
            $post->update(array_merge($request->validated(), ['photo' => $photoPath]));
    }

        $postResource = new PostsResource($post);

        return ApiResponse::sendResponse(200, 'Post updated successfully', $postResource);
    }

    public function destroy($id): JsonResponse
    {
        $post = Post::findOrFail($id);
        if ($post->photo) {
            Storage::delete($post->photo);
        }
        $post->delete();

        return ApiResponse::sendResponse(200, 'Post deleted successfully');
    }
}
