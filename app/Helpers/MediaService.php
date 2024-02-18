<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MediaService
{
    public function __construct(protected $model)
    {
    }

    public static function storePhoto($id, $image)
    {
        $imageName = time().$id.'.'.$image->getClientOriginalExtension();
        $image->storeAs('public/images', $imageName);

        $relativeImagePath = 'public/images/'.$imageName;

        $imagePath = Storage::url($relativeImagePath);

        return $imagePath;
    }

    public static function deleteOldPhoto($id, $model)
    {
        $item = $model::findOrFail($id);
        if ($item && $item->profile) {
            $oldImagePath = $item->profile;

            if (File::exists(public_path($oldImagePath))) {
                File::delete(public_path($oldImagePath));

                return true;
            }
        }

        return false;
    }


    public static function storeMultiplePhotos($images)
    {
        $imagePaths = [];

        foreach ($images as $image) {
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('posts'), $imageName); // Move the image to the public directory
            $imagePaths[] = 'posts/' . $imageName; // Store the relative path
        }

        return $imagePaths;
    }

}
