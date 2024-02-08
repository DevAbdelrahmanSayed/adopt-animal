<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
class MediaService
{
    public function __construct(protected $model){}

    public function storePhoto($id, $image)
    {
        $imageName = time() . $id . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/images', $imageName);

        $relativeImagePath = 'public/images/' . $imageName;

        $imagePath = Storage::url($relativeImagePath);

        return $imagePath;
    }
    public function deleteOldPhoto($id)
    {
        $item = $this->getById($id);
        if ($item && $item->profile) {
            $oldImagePath = $item->profile;

              if (File::exists(public_path($oldImagePath))) { // Use File facade for file_exists
                File::delete(public_path($oldImagePath)); // Use File facade for file deletion
                return true;
            }
        }
        return false;
    }


    public function getAll()
    {
        return $this->model::all();
    }

    public function getByAttribute($attribute, $value)
    {
        return $this->model::where($attribute, $value)->first();
    }

    public function getById($id)
    {
        return $this->model::findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update($id, array $data)
    {
        $item = $this->getById($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        $item = $this->getById($id);
        $item->delete();
    }
}
