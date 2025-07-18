<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Enums\Role;
class AuthController extends Controller
{
    // Registering normal users
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role' => Role::USER->value,// always has user role
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token]);
    }
//////////////////////////////////////////////////////////////////////
// Registering Doctors
public function DoctorRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role' => Role::DOCTOR->value,// always has doctor role
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token]);
    }
///////////////////////////////////////////////////////////////////////////
    // Registering Admins
    public function Adminregister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role' => Role::ADMIN->value,// always has admin role
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token]);
    }
////////////////////////////////////////////////////////////////////


    //update role
    public function updateRole(Request $request, $id)
    {
        // Validate the incoming role
    $validated = $request->validate(['role' => 'required|string|in:' . implode(',', Role::values()),]);
    
    $Cuser = auth()->user();
    if ($id == $Cuser->id) {// this make sure the login admin never change thier role
    return response()->json(['error' => 'Forbidden'], 403);
    }

        $user = User::find($id);

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        

        $user->role = Role::from($validated['role'])->value;
        $user->save();

        return response()->json([
            'message' => 'User role updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ]
        ]);
    }
///////////////////////////////////////////////////////////////////////////////



    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(['token' => $token]);
    }
////////////////////////////////////////////////////////////////////////////
    // Logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }
////////////////////////////////////////////////////////////////////////////
    // Get user
    public function me()
    {
        return response()->json(auth()->user());
    }
}