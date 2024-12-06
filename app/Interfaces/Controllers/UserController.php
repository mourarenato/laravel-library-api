<?php

namespace App\Interfaces\Controllers;

use App\Application\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception|Throwable
     */
    public function signup(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'max:50'],
            'name' => ['required', 'string', 'min:3', 'max:100'],
        ]);

        $service = app(UserService::class);

        $service->signup();

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function signin(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'max:50']
        ]);

        $service = app(UserService::class);

        $token = $service->signin();

        return response()->json([
            'success' => true,
            'token' => $token,
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function signout(Request $request): JsonResponse
    {
        $service = app(UserService::class);

        $service->signout();

        return response()->json([
            'success' => true,
            'message' => 'User has been logged out'
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getUser(Request $request): JsonResponse
    {
        $service = app(UserService::class);

        $user = $service->getUser();

        return response()->json([
            'success' => true,
            'user' => $user
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception|Throwable
     */
    public function updateUserName(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'min:3', 'max:100'],
        ]);

        $service = app(UserService::class);

        $service->updateUserName();

        return response()->json([
            'success' => true,
            'message' => 'User updated with success'
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception|Throwable
     */
    public function deleteUser(Request $request): JsonResponse
    {
        $this->validate($request, [
            'password' => ['required', 'string', 'min:6', 'max:50'],
        ]);

        $service = app(UserService::class);

        $service->deleteUser();

        return response()->json([
            'success' => true,
            'message' => 'User deleted with success'
        ], Response::HTTP_OK);
    }
}
