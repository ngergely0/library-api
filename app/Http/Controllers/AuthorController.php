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
    public function index(Request $request)
    {
        $query = Author::query();

        if ($request->has('needle')) {
            $needle = $request->input('needle');
            $query->where('name', 'like', "%{$needle}%");
        }

        $authors = $query->get();
        
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
     * @api {get} /api/authors/:id/books Get all books of an author
     * @apiName GetAuthorBooks
     * @apiGroup Authors
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Author’s unique ID.
     *
     * @apiSuccess {Object} author Author information.
     * @apiSuccess {Number} author.id Author ID.
     * @apiSuccess {String} author.name Author name.
     *
     * @apiSuccess {Object[]} books List of books written by the author.
     * @apiSuccess {Number} books.id Book ID.
     * @apiSuccess {String} books.title Book title.
     * @apiSuccess {String} books.isbn Book ISBN.
     * @apiSuccess {String} books.description Book description.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "author": {
     *     "id": 4,
     *     "name": "J. R. R. Tolkien"
     *   },
     *   "books": [
     *     {
     *       "id": 10,
     *       "title": "The Hobbit",
     *       "isbn": "978000000001",
     *       "description": "Fantasy adventure novel."
     *     },
     *     {
     *       "id": 11,
     *       "title": "The Lord of the Rings",
     *       "isbn": "978000000002",
     *       "description": "Epic fantasy trilogy."
     *     }
     *   ]
     * }
     */


    public function books($id)
    {
        $author = Author::findOrFail($id);
        $books = $author->books;

        return response()->json([
            'author' => [
                'id' => $author->id,
                'name' => $author->name,
            ],
            'books' => $books,
        ]);
    }

    /**
     * @api {delete} /api/authors/:id/books/:book_id Delete a book of an author
     * @apiName DeleteAuthorBook
     * @apiGroup Authors
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Author’s unique ID.
     * @apiParam {Number} book_id Book’s unique ID.
     *
     * @apiSuccess {String} message Success message.
     * @apiSuccess {Number} book_id Deleted book ID.
     *
     * @apiError BookNotFound Book not found for this author.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "message": "Book deleted successfully",
     *   "book_id": 15
     * }
     *
     * @apiErrorExample {json} Error-Response:
     * HTTP/1.1 404 Not Found
     * {
     *   "error": "Book not found for this author"
     * }
     */

       public function deleteBook($id, $book_id)
    {
        $author = Author::findOrFail($id);
        $book = $author->books()->where('id', $book_id)->first();

        if (!$book) {
            return response()->json([
                'error' => 'Book not found for this author',
            ], 404);
        }

        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully',
            'book_id' => $book_id,
        ]);
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
        $author = Author::find($id);
        
        if (!$author) {
            return response()->json(['message' => 'Not found!'], 404);
        }

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
     * HTTP/1.1 410 Gone
     * {
     *   "message": "Deleted",
     *   "id": 3
     * }
     */
    public function destroy($id)
    {
        $author = Author::find($id);
        
        if (!$author) {
             return response()->json(['message' => 'Not found!'], 404);
        }
        
        $author->delete();
        
        return response()->json([
            'message' => 'Deleted',
            'id' => $id
        ], 410);
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
