<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'mobile_no' => 'required|digits:10|unique:users,mobile_no',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required',
            'profile_photo' => 'required|mimes:jpeg,png,jpg,gif|max:20480',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $profilePhotoPath = null;

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $directory = 'profile_photos';
            $file->move(public_path($directory), $filename);
            $profilePhotoPath = $directory . '/' . $filename;
        }

        $user = Register::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'gender' => $request->gender,
            'profile_photo' => $profilePhotoPath,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'data' => [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'mobile_no' => $user->mobile_no,
                'email' => $user->email,
                'gender' => $user->gender,
                'profile_photo' => $user->profile_photo,
                'password' => $user->password,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 201);
    }
}
