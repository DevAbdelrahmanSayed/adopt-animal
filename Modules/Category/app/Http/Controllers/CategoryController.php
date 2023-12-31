<?php

namespace Modules\Category\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Category\app\Http\Requests\ShowCategoryRequest;
use Modules\Category\app\Models\Category;
use Modules\Category\app\Resources\CategoryResource;
use Modules\Post\app\Resources\PostsResource;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();

        if ($categories->isNotEmpty()) {
            return ApiResponse::sendResponse(200, 'Categories retrieved successfully',
                CategoryResource::collection($categories)
            );
        }

        return ApiResponse::sendResponse(200, 'No Categories exist');
    }



    public function show(Category $category)
    {
        $posts = $category->posts;

        if ($posts->isEmpty()) {
            return ApiResponse::sendResponse(200, 'No posts exist for this category');
        }

        $postResource = PostsResource::collection($posts);

        return ApiResponse::sendResponse(200, 'Posts retrieved successfully', $postResource);
    }



}
