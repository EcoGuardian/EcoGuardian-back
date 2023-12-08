<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            "name" => ['required', 'max:255'],
            "email" => ['required', 'email', 'max:255', 'unique:users'],
            "password" => ['required', 'confirmed', 'min:8', 'max:255']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error!', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role()->associate(Role::where('name', 'Default')->first());

        $user->save();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $token = $user->createToken(Str::random(40))->plainTextToken;

            return $this->sendResponse(["token" => $token], 'Register success!');

        } else {
            $this->sendError('Invalid email or password','', Response::HTTP_UNAUTHORIZED);
        }
    }

    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            "email" => ['required', 'email', 'max:255'],
            "password" => ['required']
        ]);

        if ($validator->fails()) {
            return  $this->sendError('Validation error!', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();

            $token = $user->createToken(Str::random(40))->plainTextToken;

            $user->update();

            return $this->sendResponse(["token" => $token], 'Login success!');


        } else {
            return $this->sendError('','Invalid email or password!', Response::HTTP_UNAUTHORIZED);
        }
    }
}
