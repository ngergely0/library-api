<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    /**
     * @api {get} /api/books List all books
     * @apiName GetBooks
     * @apiGroup Books
     * @apiVersion 1.0.0
     *
     * @apiSuccess {Object[]} books              List of books.
     * @apiSuccess {Number}   books.id           Book ID.
     * @apiSuccess {String}   books.name         Book title.
     * @apiSuccess {Number}   books.category_id  Category ID.
     * @apiSuccess {Number}   books.price        Book price.
     * @apiSuccess {Date}     books.publication_date Publication date.
     * @apiSuccess {Number}   books.edition      Edition number.
     * @apiSuccess {Number}   books.author_id    Author ID.
     * @apiSuccess {String}   books.isbn         ISBN number.
     * @apiSuccess {String}   books.cover        Cover image path.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "books": [
     *     {
     *       "id": 1,
     *       "name": "The London Fog",
     *       "category_id": 1,
     *       "price": 20,
     *       "publication_date": "2021-05-10",
     *       "edition": 1,
     *       "author_id": 1,
     *       "isbn": "978-1-00001-001-1",
     *       "cover": "covers/book1.jpg"
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->has('needle')) {
            $needle = $request->input('needle');
            $query->where('name', 'like', "%{$needle}%");
        }

        $books = $query->get();

        return response()->json([
            'books' => $books,
        ]);
    }

    /**
     * @api {post} /api/books Create a new book
     * @apiName CreateBook
     * @apiGroup Books
     * @apiVersion 1.0.0
     *
     * @apiBody {String} name              Book title.
     * @apiBody {Number} category_id       Category ID.
     * @apiBody {Number} price             Book price.
     * @apiBody {Date}   publication_date  Publication date (YYYY-MM-DD).
     * @apiBody {Number} edition           Edition number.
     * @apiBody {Number} author_id         Author ID.
     * @apiBody {String} [isbn]            ISBN number.
     * @apiBody {String} [cover]           Cover image path.
     *
     * @apiSuccess {Object} book           Created book.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *   "book": {
     *     "id": 22,
     *     "name": "New Book Title",
     *     "category_id": 2,
     *     "price": 30,
     *     "publication_date": "2025-10-21",
     *     "edition": 1,
     *     "author_id": 4,
     *     "isbn": "978-1-99999-999-9",
     *     "cover": "covers/newbook.jpg"
     *   }
     * }
     */
    public function store(BookRequest $request)
    {
        $book = Book::create($request->validated());

        return response()->json([
            'book' => $book,
        ], 201);
    }

    /**
     * @api {put} /api/books/:id Update an existing book
     * @apiName UpdateBook
     * @apiGroup Books
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Book’s unique ID.
     *
     * @apiBody {String} [name]              Book title.
     * @apiBody {Number} [category_id]       Category ID.
     * @apiBody {Number} [price]             Book price.
     * @apiBody {Date}   [publication_date]  Publication date.
     * @apiBody {Number} [edition]           Edition number.
     * @apiBody {Number} [author_id]         Author ID.
     * @apiBody {String} [isbn]              ISBN number.
     * @apiBody {String} [cover]             Cover image path.
     *
     * @apiSuccess {Object} book             Updated book.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "book": {
     *     "id": 1,
     *     "name": "Updated Book Title",
     *     "category_id": 2,
     *     "price": 22,
     *     "publication_date": "2023-09-12",
     *     "edition": 2,
     *     "author_id": 3,
     *     "isbn": "978-1-00002-001-0",
     *     "cover": "covers/book_updated.jpg"
     *   }
     * }
     */
    public function update(BookRequest $request, $id)
	{
		$book = Book::find($id);
        
        if (!$book) {
            return response()->json(['message' => 'Not found!'], 404);
        }

        $book->update($request->validated());

 		return response()->json([
 			'book' => $book,
 		]);
 	}

    /**
     * @api {delete} /api/books/:id Delete a book
     * @apiName DeleteBook
     * @apiGroup Books
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Book’s unique ID.
     *
     * @apiSuccess {String} message Success message.
     * @apiSuccess {Number} id Deleted book ID.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 410 Gone
     * {
     *   "message": "Deleted",
     *   "id": 5
     * }
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json(['message' => 'Not found!'], 404);
        }
        
        $book->delete();
        
        return response()->json([
            'message' => 'Deleted',
            'id' => $id
        ], 410);
    }

    /**
     * @api {get} /api/books/:id Get a single book
     * @apiName GetBook
     * @apiGroup Books
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Book’s unique ID.
     *
     * @apiSuccess {Object}   book              Book object.
     * @apiSuccess {Number}   book.id           Book ID.
     * @apiSuccess {String}   book.name         Book title.
     * @apiSuccess {Number}   book.category_id  Category ID.
     * @apiSuccess {Number}   book.price        Book price.
     * @apiSuccess {Date}     book.publication_date Publication date.
     * @apiSuccess {Number}   book.edition      Edition number.
     * @apiSuccess {Number}   book.author_id    Author ID.
     * @apiSuccess {String}   book.isbn         ISBN number.
     * @apiSuccess {String}   book.cover        Cover image path.
     *
     * @apiError BookNotFound Book not found.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "book": {
     *     "id": 1,
     *     "name": "The London Fog",
     *     "category_id": 1,
     *     "price": 20,
     *     "publication_date": "2021-05-10",
     *     "edition": 1,
     *     "author_id": 1,
     *     "isbn": "978-1-00001-001-1",
     *     "cover": "covers/book1.jpg"
     *   }
     * }
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);

        return response()->json([
            'book' => $book,
        ]);
    }
}
