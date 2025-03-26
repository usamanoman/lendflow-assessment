<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersRequest;
use App\Services\UserService;

class UserController extends Controller
{
    // Register a new user and generate Sanctum token
    public function register(UsersRequest $request, UserService $userService)
    {
        $validatedData = $request->validated(); // Validation rules are defined in UsersRequest

        $data = $request->only(['name', 'email', 'password']);
        $result = $userService->registerUser($data);

        return response()->json($result, 201);
    }

    // Login user and generate Sanctum token
    public function login(UsersRequest $request, UserService $userService)
    {
        $validatedData = $request->validated(); // Validation rules are defined in UsersRequest

        $data = $request->only(['email', 'password']);
        $result = $userService->loginUser($data);

        return response()->json($result);
    }

    // Get authenticated user details
    public function getUser(UsersRequest $request, UserService $userService)
    {
        $user = $userService->getAuthenticatedUser($request);

        return response()->json($user);
    }
}
