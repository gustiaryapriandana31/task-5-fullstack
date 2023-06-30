<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        // Make a validator for the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
        ]);

        // Check if the validator fails and return errors
        if($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        // Fetch all the request data and Hash the password
        $createData = $request->all();
        $createData['password'] = bcrypt($createData['password']);

        // Create user
        $user = User::create($createData);
        $success['token'] = $user->createToken('task-5-fullstack')->accessToken;
        $success['name'] = $user->name;

        // Return success response in json format
        return response()->json(['success'=>$success], 200);   
    }

    public function login(Request $request) {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('task-5-fullstack')->accessToken;
            $success['name'] = $user->name;
            
            return response()->json(['success' => $success], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}