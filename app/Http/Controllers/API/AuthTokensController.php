<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;

class AuthTokensController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user_tokens = $request->user()->tokens;
        return Response::json($user_tokens, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
//            'permissions' => 'array',
            'device_name' => 'required',
//            'fcm_token' => 'nullable',
            //'permissions' => 'array',
        ]);

//        $auth_user = Auth::guard('sanctum');

        //Validate User email and password
//        User::where('email', '=', $request->post('email'));
        $user = User::where('email', '=', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)):
            $token = $user->createToken($request->device_name, ['*']);

            //Get Token as String without Hashing
            return Response::json([
                'token' => $token->plainTextToken,
                'user' => $user,
            ], 201);
        endif;

        return response()->json('invalid credentials', 401);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
        $user->tokens()->findOrFail($id)->delete();

        return Response::json([
//                'user_tokens' => $user->tokens,
//                'user_find_token' => $user->tokens()->findOrFail($id),
                'message' => 'Token deleted'
            ]
            , 200);
    }

    public function current_logout(): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
        $current_token = $user->currentAccessToken();
        // Logout from current device
        $current_token->delete();

        return Response::json([
//                'user_tokens' => $user->tokens,
//                'user_find_token' => $user->tokens()->findOrFail($id),
                'message' => 'You delete your current token --Token deleted'
            ]
            , 200);
    }

    /**
     * @return JsonResponse
     */
    public function logout_all(): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
        $user->tokens()->delete();

        return Response::json([
//                'user_tokens' => $user->tokens,
//                'user_find_token' => $user->tokens()->findOrFail($id),
                'message' => 'You delete all tokens --Tokens deleted'
            ]
            , 200);
    }
}
