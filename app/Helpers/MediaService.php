<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MediaService
{


    public function updatesettings(Request $request, $id) {
       $validatedData  = $request->validate([
            "email" => "required|email|max:40",
            "name" => "required|max:40",
            "image" => "required"
        ]);

        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with(['error' => 'User not found']);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . 'user.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $imageName);
            $relativeImagePath = 'public/images/' . $imageName;
            $imagePath = Storage::url($relativeImagePath);
            $validatedData['image'] = $imagePath;
        }

        // Update only specified fields
        $user->update($validatedData);

        return redirect()->route('users.settings')->with(['update' => 'User data updated successfully']);
    }


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
            $imageName = time() . 'Adopt' . $image->getClientOriginalExtension();
             $image->storeAs('public/posts', $imageName);
            $relativeImagePath = 'public/posts/'.$imageName;
            $imagePath = Storage::url($relativeImagePath);
            $imagePaths[] = $imagePath;
        }

        return $imagePaths;
    }

}
