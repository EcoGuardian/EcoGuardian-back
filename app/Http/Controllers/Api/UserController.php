<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Psy\Util\Json;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();

        return $this->sendResponse(UserResource::collection($user), 'Users fetched successfuly!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if(!$user) {
            return new JsonResponse('User not found!', 404);
        }

        return $this->sendResponse(new UserResource($user), 'User fetched successfuly!');
    }

    public function currentUser(Request $request)
    {
        $user = User::find($request->user()->id);

        return $this->sendResponse(new UserResource($user), 'User fetched successfuly!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "email" => ['email'],
            "password" => ['confirmed', 'min:8', 'max:255']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error!', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::find($request->user()->id);
        $user->update($request->toArray());

        return $this->sendResponse('', 'User updated successfuly!');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user = User::find($request->user()->id);
        $user->delete();

        return $this->sendResponse('', 'User deleted successfuly!');
    }
}
