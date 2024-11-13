<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();

            // Attempt to authenticate using the provided credentials
            if (!Auth::attempt($data)) {
                return $this->response(400, "Credentials are invalid");
            }

            // Generate the token
            $user = auth()->user();
            $token = $user->createToken('access_token')->plainTextToken;

            // Set token expiration (e.g., 1 hour from now)
            $expiration = now()->addDay(); // Adjust the expiration time as needed
            $expired_at = $expiration->toDateTimeString();

            // Respond with token and expiration date
            return $this->response(200, "Login Success", compact("token", "expired_at"));
        } catch (Exception $th) {
            return $this->response(500, $th->getMessage());
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);
            return $this->response(200,"Login Success",compact("user"));
        } catch (Exception $th) {
            return $this->response(500, $th->getMessage());
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            return $this->response(200,"Logout Success");
        } catch (\Throwable $th) {
            return $this->response(500,$th->getMessage());
        }
    }


}
