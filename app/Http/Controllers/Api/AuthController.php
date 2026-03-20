<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DonorProfile;
use App\Models\OrganizationProfile;
use App\Models\PartnerKitchenProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function registerDonor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_initial' => 'nullable|string|max:10',
            'suffix' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:Male,Female,Other',
            'house_no' => 'required|string|max:50',
            'street' => 'required|string|max:100',
            'barangay' => 'required|string|max:100',
            'city_municipality' => 'required|string|max:100',
            'province_region' => 'required|string|max:100',
            'postal_zip_code' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'required|regex:/^[0-9+\-\s]{10,15}$/',
            'password' => 'required|min:8|same:password_confirmation',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'role' => 'donor',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_verified' => false
        ]);

        DonorProfile::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial,
            'suffix' => $request->suffix,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'house_no' => $request->house_no,
            'street' => $request->street,
            'barangay' => $request->barangay,
            'city_municipality' => $request->city_municipality,
            'province_region' => $request->province_region,
            'postal_zip_code' => $request->postal_zip_code,
            'contact_number' => $request->contact_number,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Donor registered successfully.'
        ], 201);
    }

    public function registerOrganization(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organization_name' => 'required|string|max:150',
            'website_url' => 'nullable|url',
            'industry_sector' => 'required|string|max:100',
            'organization_type' => 'required|string|max:100',
            'contact_person' => 'required|string|max:100',
            'position_role' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'required|regex:/^[0-9+\-\s]{10,15}$/',
            'password' => 'required|min:8|same:password_confirmation',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'role' => 'organization',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_verified' => false
        ]);

        OrganizationProfile::create([
            'user_id' => $user->id,
            'organization_name' => $request->organization_name,
            'website_url' => $request->website_url,
            'industry_sector' => $request->industry_sector,
            'organization_type' => $request->organization_type,
            'contact_person' => $request->contact_person,
            'position_role' => $request->position_role,
            'contact_number' => $request->contact_number,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Organization registered successfully.'
        ], 201);
    }

    public function registerPartnerKitchen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kitchen_name' => 'required|string|max:150',
            'website_url' => 'nullable|url',
            'contact_person' => 'required|string|max:100',
            'position_role' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'required|regex:/^[0-9+\-\s]{10,15}$/',
            'password' => 'required|min:8|same:password_confirmation',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'role' => 'partner_kitchen',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_verified' => false
        ]);

        PartnerKitchenProfile::create([
            'user_id' => $user->id,
            'kitchen_name' => $request->kitchen_name,
            'website_url' => $request->website_url,
            'contact_person' => $request->contact_person,
            'position_role' => $request->position_role,
            'contact_number' => $request->contact_number,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Partner Kitchen registered successfully.'
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        $token = $user->createToken('savr_mobile_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'is_verified' => $user->is_verified
            ]
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.'
        ]);
    }
}