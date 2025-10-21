<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * @api {get} /api/categories List all categories
     * @apiName GetCategories
     * @apiGroup Categories
     * @apiVersion 1.0.0
     *
     * @apiSuccess {Object[]} categories            List of categories.
     * @apiSuccess {Number}   categories.id         Category ID.
     * @apiSuccess {String}   categories.name       Category name.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "categories": [
     *     {
     *       "id": 1,
     *       "name": "Historical Fiction"
     *     },
     *     {
     *       "id": 2,
     *       "name": "Science Fiction"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'categories' => $categories,
        ]);
    }

    /**
     * @api {post} /api/categories Create a new category
     * @apiName CreateCategory
     * @apiGroup Categories
     * @apiVersion 1.0.0
     *
     * @apiBody {String} name Category name.
     *
     * @apiSuccess {Object} category Category created successfully.
     * @apiSuccess {Number} category.id Category ID.
     * @apiSuccess {String} category.name Category name.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *   "category": {
     *     "id": 12,
     *     "name": "Romance"
     *   }
     * }
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return response()->json([
            'category' => $category,
        ]);
    }

    /**
     * @api {put} /api/categories/:id Update an existing category
     * @apiName UpdateCategory
     * @apiGroup Categories
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Category’s unique ID.
     * @apiBody {String} name Category name.
     *
     * @apiSuccess {Object} category Updated category.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "category": {
     *     "id": 2,
     *     "name": "Updated Category Name"
     *   }
     * }
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());

        return response()->json([
            'category' => $category,
        ]);
    }

    /**
     * @api {delete} /api/categories/:id Delete a category
     * @apiName DeleteCategory
     * @apiGroup Categories
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Category’s unique ID.
     *
     * @apiSuccess {String} message Success message.
     * @apiSuccess {Number} id Deleted category ID.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "message": "Category deleted successfully",
     *   "id": 3
     * }
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            'message' => 'Category deleted successfully',
            'id' => $id
        ]);
    }

    /**
     * @api {get} /api/categories/:id Get a single category
     * @apiName GetCategory
     * @apiGroup Categories
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Category’s unique ID.
     *
     * @apiSuccess {Object} category Category details.
     * @apiSuccess {Number} category.id Category ID.
     * @apiSuccess {String} category.name Category name.
     *
     * @apiError CategoryNotFound Category not found.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "category": {
     *     "id": 2,
     *     "name": "Science Fiction"
     *   }
     * }
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return response()->json([
            'category' => $category,
        ]);
    }
}
