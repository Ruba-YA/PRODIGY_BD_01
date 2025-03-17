<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return response()->json($this->userService->getAllUsers());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'age' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = $this->userService->createUser($validator->validated());
        return response()->json($user, 201);
    }

    public function show(string $id)
    {
        try {
            return response()->json($this->userService->getUserById($id));
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|max:255',
            'age' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $user = $this->userService->updateUser($id, $validator->validated());
            return response()->json($user);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->userService->deleteUser($id);
            return response()->json(null, 204);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
