<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * @api {post} /login Login User
 * @apiName Login
 * @apiGroup User
 * @apiVersion 1.0.0
 *
 * @apiDescription Authenticates a user using email and password.  
 * Returns a new Sanctum access token.
 *
 * @apiBody {String} email User email address.
 * @apiBody {String} password User password.
 *
 * @apiSuccess {Object} user User object with access token.
 * @apiSuccess {Number} user.id User ID.
 * @apiSuccess {String} user.name User name.
 * @apiSuccess {String} user.email User email.
 * @apiSuccess {String} user.token Newly issued access token.
 *
 * @apiSuccessExample {json} Success Response:
 *  HTTP/1.1 200 OK
 *  {
 *      "user": {
 *          "id": 1,
 *          "name": "John Doe",
 *          "email": "john@example.com",
 *          "token": "2|XyzAbc123..."
 *      }
 *  }
 *
 * @apiError (401) Unauthorized Invalid email or password.
 *
 * @apiErrorExample {json} Error Response:
 *  HTTP/1.1 401 Unauthorized
 *  {
 *      "message": "Invalid email or password"
 *  }
 */


/**
 * @api {get} /users Get All Users
 * @apiName GetUsers
 * @apiGroup User
 * @apiVersion 1.0.0
 *
 * @apiDescription Fetches a list of all registered users.
 *
 * @apiSuccess {Object[]} users List of users.
 * @apiSuccess {Number} users.id User ID.
 * @apiSuccess {String} users.name User name.
 * @apiSuccess {String} users.email User email.
 *
 * @apiSuccessExample {json} Success Response:
 *  HTTP/1.1 200 OK
 *  {
 *      "users": [
 *          {
 *              "id": 1,
 *              "name": "John Doe",
 *              "email": "john@example.com"
 *          },
 *          {
 *              "id": 2,
 *              "name": "Jane Doe",
 *              "email": "jane@example.com"
 *          }
 *      ]
 *  }
 */


class UserController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $password ? $user->password : '')) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401); 
        }

        $user->tokens()->delete();

        $user->token = $user->createToken('access')->plainTextToken;

        return response()->json([
            'user' => $user,
        ]);
    }

    public function index(Request $request)
    {
        $users = User::all();
        return response()->json([
            'users' => $users,
        ]);
    }
}
