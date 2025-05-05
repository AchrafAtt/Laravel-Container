<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Api\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
       
    }


    public function index(): JsonResponse
    {
        $users = $this->userService->index()->paginate(15);
        return response()->json($users);
    }


    /**
     * Display the specified user.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $user = $this->userService->show($id)->firstOrFail();
        return response()->json($user);
    }

    /**
     * Store a newly created user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = $this->userService->store($request->all());
            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating user',
                'errors' => method_exists($e, 'errors') ? $e->errors() : [$e->getMessage()]
            ], 422);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $user = $this->userService->update($id, $request->all());
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating user',
                'errors' => method_exists($e, 'errors') ? $e->errors() : [$e->getMessage()]
            ], 422);
        }
    }

    //profile
    public function profile(Request $request): JsonResponse
    {
        $user = $this->userService->show($request->user()->id)->firstOrFail();
        return response()->json($user);
    }

}
