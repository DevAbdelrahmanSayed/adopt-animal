<?php

namespace Modules\User\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\User\app\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        Cache::forget("user.profile'.{$user->id}");
    }
    public function updated(User $user)
    {
        Cache::forget("user.profile.{$user->id}");
    }

    public function deleted(User $user)
    {
        Cache::forget("user.profile.{$user->id}");
    }
}
