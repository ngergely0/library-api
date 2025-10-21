<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Requests\AuthorRequest;

class AuthorController extends Controller
{
    /**
     * @api {get} /api/authors List all authors
     * @apiName GetAuthors
     * @apiGroup Authors
     * @apiVersion 1.0.0
     *
     * @apiSuccess {Object[]} authors             List of authors.
     * @apiSuccess {Number}   authors.id          Author ID.
     * @apiSuccess {String}   authors.name        Author name.
     * @apiSuccess {String}   authors.nationality Author nationality.
     * @apiSuccess {Number}   authors.age         Author age.
     * @apiSuccess {String}   authors.gender      Author gender.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "authors": [
     *     {
     *       "id": 1,
     *       "name": "Emma Clarke",
     *       "nationality": "British",
     *       "age": 42,
     *       "gender": "female"
     *     },
     *     {
     *       "id": 2,
     *       "name": "John Miller",
     *       "nationality": "American",
     *       "age": 55,
     *       "gender": "male"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $authors = Author::all();
        return response()->json([
            'authors' => $authors,
        ]);
    }

    /**
     * @api {post} /api/authors Create a new author
     * @apiName CreateAuthor
     * @apiGroup Authors
     * @apiVersion 1.0.0
     *
     * @apiBody {String} name          Author name.
     * @apiBody {String} nationality   Author nationality.
     * @apiBody {Number} age           Author age.
     * @apiBody {String} gender        Author gender.
     *
     * @apiSuccess {Object} author     Created author.
     * @apiSuccess {Number} author.id  Author ID.
     * @apiSuccess {String} author.name Author name.
     * @apiSuccess {String} author.nationality Author nationality.
     * @apiSuccess {Number} author.age Author age.
     * @apiSuccess {String} author.gender Author gender.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *   "author": {
     *     "id": 12,
     *     "name": "New Author",
     *     "nationality": "Canadian",
     *     "age": 40,
     *     "gender": "female"
     *   }
     * }
     */
    public function store(AuthorRequest $request)
    {
        $author = Author::create($request->validated());

        return response()->json([
            'author' => $author,
        ], 201);
    }

    /**
     * @api {put} /api/authors/:id Update an existing author
     * @apiName UpdateAuthor
     * @apiGroup Authors
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Author’s unique ID.
     *
     * @apiBody {String} [name]          Author name.
     * @apiBody {String} [nationality]   Author nationality.
     * @apiBody {Number} [age]           Author age.
     * @apiBody {String} [gender]        Author gender.
     *
     * @apiSuccess {Object} author Updated author.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "author": {
     *     "id": 2,
     *     "name": "Updated Author Name",
     *     "nationality": "French",
     *     "age": 56,
     *     "gender": "male"
     *   }
     * }
     */
    public function update(AuthorRequest $request, $id)
    {
        $author = Author::findOrFail($id);
        $author->update($request->validated());

        return response()->json([
            'author' => $author,
        ], 200);
    }

    /**
     * @api {delete} /api/authors/:id Delete an author
     * @apiName DeleteAuthor
     * @apiGroup Authors
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Author’s unique ID.
     *
     * @apiSuccess {String} message Success message.
     * @apiSuccess {Number} id Deleted author ID.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "message": "Author deleted successfully",
     *   "id": 3
     * }
     */
    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        $author->delete();
        return response()->json([
            'message' => 'Author deleted successfully',
            'id' => $id
        ]);
    }

    /**
     * @api {get} /api/authors/:id Get a single author
     * @apiName GetAuthor
     * @apiGroup Authors
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Author’s unique ID.
     *
     * @apiSuccess {Object} author Author details.
     * @apiSuccess {Number} author.id Author ID.
     * @apiSuccess {String} author.name Author name.
     * @apiSuccess {String} author.nationality Author nationality.
     * @apiSuccess {Number} author.age Author age.
     * @apiSuccess {String} author.gender Author gender.
     *
     * @apiError AuthorNotFound Author not found.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "author": {
     *     "id": 1,
     *     "name": "Emma Clarke",
     *     "nationality": "British",
     *     "age": 42,
     *     "gender": "female"
     *   }
     * }
     */
    public function show($id)
    {
        $author = Author::findOrFail($id);

        return response()->json([
            'author' => $author,
        ]);
    }
}
