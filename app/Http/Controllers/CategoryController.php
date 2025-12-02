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
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('needle')) {
            $needle = $request->input('needle');
            $query->where('name', 'like', "%{$needle}%");
        }

        $categories = $query->get();

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
        ], 201);
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
        $category = Category::find($id);
        
        if (!$category) {
            return response()->json(['message' => 'Not found!'], 404);
        }

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
     * HTTP/1.1 410 Gone
     * {
     *   "message": "Deleted",
     *   "id": 3
     * }
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            return response()->json(['message' => 'Not found!'], 404);
        }
        
        $category->delete();
        
        return response()->json([
            'message' => 'Deleted',
            'id' => $id
        ], 410);
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

     /**
     * @api {get} /api/categories/:id/books Get all books in a category
     * @apiName GetCategoryBooks
     * @apiGroup Categories
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Category’s unique ID.
     *
     * @apiSuccess {Object} category Category information.
     * @apiSuccess {Number} category.id Category ID.
     * @apiSuccess {String} category.name Category name.
     *
     * @apiSuccess {Object[]} books List of books in the category.
     * @apiSuccess {Number} books.id Book ID.
     * @apiSuccess {String} books.title Book title.
     * @apiSuccess {String} books.isbn Book ISBN.
     * @apiSuccess {String} books.description Book description.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "category": {
     *     "id": 5,
     *     "name": "Fantasy"
     *   },
     *   "books": [
     *     {
     *       "id": 12,
     *       "title": "The Dragon's Path",
     *       "isbn": "9781234567890",
     *       "description": "Epic high fantasy adventure."
     *     },
     *     {
     *       "id": 18,
     *       "title": "Shadow of the Mountain",
     *       "isbn": "9780987654321",
     *       "description": "A tale of magic and destiny."
     *     }
     *   ]
     * }
     */

    public function books($id)
    {
        $category = Category::findOrFail($id);
        $books = $category->books;

        return response()->json([
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
            'books' => $books,
        ]);
    }

     /**
     * @api {delete} /api/categories/:id/books/:book_id Remove a book from a category
     * @apiName DeleteCategoryBook
     * @apiGroup Categories
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Category’s unique ID.
     * @apiParam {Number} book_id Book ID to remove.
     *
     * @apiSuccess {String} message Success message.
     * @apiSuccess {Number} category_id Category ID.
     * @apiSuccess {Number} book_id Removed book ID.
     *
     * @apiError BookNotFound Book not found within this category.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "message": "Book removed from category successfully",
     *   "category_id": 5,
     *   "book_id": 12
     * }
     *
     * @apiErrorExample {json} Error-Response:
     * HTTP/1.1 404 Not Found
     * {
     *   "error": "Book not found for this category"
     * }
     */

       public function deleteBook($id, $book_id)
    {
        $category = Category::findOrFail($id);
        $book = $category->books()->where('id', $book_id)->first();

        if (!$book) {
            return response()->json([
                'error' => 'Book not found for this author',
            ], 404);
        }
    }
}
