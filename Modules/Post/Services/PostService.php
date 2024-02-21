<?php

namespace Modules\Post\Services;

use Modules\Post\app\Models\Post;
use Modules\Post\Dto\PostDto;

class PostService
{
    public function getAllPosts()
    {
        return Post::with('user')->latest()->paginate(40);

    }

    public function store(PostDto $postDto)
    {

        return Post::create($postDto->toArray());
    }

    public function show($postId)
    {
        $post = Post::findOrFail($postId);
        if (! is_null($post)) {
            return $post;

        }

        return false;
    }

    public function showUserPosts($user)
    {
        return Post::where('user_id', $user)->with('user')->latest()->get();
    }

    public function update(PostDto $postDto, $postId)
    {
        $post = Post::findOrFail($postId);
        // Filter out null values from the DTO array
        $updatedData = array_filter($postDto->toUpdateArray());
        $post->update($updatedData);

        return $post;
    }

    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);
        $post->delete();
    }
}
