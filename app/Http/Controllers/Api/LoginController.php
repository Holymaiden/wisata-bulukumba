<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected $response, $title;

    public function __construct()
    {
    }

    public function _error($e)
    {
        $this->response = [
            'message' => $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine()
        ];
        return response()->json($this->response, 500);
    }

    public function register(Request $request)
    {
        try {
                $request->validate([
                        'name' => 'required|string',
                        'email' => 'required|string|email|unique:users',
                        'password' => 'required|string'
                ]);
                $user = new User([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'role_id' => '2',
                        'active' => '1',
                ]);

                $user->save();

                $token = $user->createToken('token')->plainTextToken;


                return response()->json([
                        'message' => 'Successfully created user!',
                        'user' => $user,
                        'token' => $token
                ], 201);
                
            } catch (\Exception $e) {
                return $this->_error($e);
            }
    }

    public function getUser(Request $request, $id)
    {
        try {
            $user = User::where('id',$id)->first(); 
            return response()->json($user, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function updateUser(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first(); 
            $user->name = $request->name;
            $user->email = $request->email;
            if($request->password || $request->password != '')
                $user->password = Hash::make($request->password);
            $user->save();
            return response()->json($user, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function login(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            $user = User::where('email', $request->email)->first(); 
            $token = $user->createToken('token')->plainTextToken;
            $response = ['user' => $user,'token' => $token];
            
            return response()->json($response, 201);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }

    public function logout()
    {
        try {
                Auth::user()->tokens()->delete();
                return response()->json(['message' => 'Logged out'], 200);
        } catch (\Exception $e) {
            return $this->_error($e);
        }
    }
}
