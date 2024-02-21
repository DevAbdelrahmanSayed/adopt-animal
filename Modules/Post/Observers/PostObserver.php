<?php

namespace Modules\Post\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Post\app\Models\Post;

class PostObserver
{
    public function created(Post $post)
    {
        Cache::forget('posts.index');
    }

    public function updated(Post $post)
    {
        Cache::forget("post.{$post->id}");
        Cache::forget('posts.index');
    }

    public function deleted(Post $post)
    {
        Cache::forget("post.{$post->id}");
        Cache::forget('posts.index');
    }
}
