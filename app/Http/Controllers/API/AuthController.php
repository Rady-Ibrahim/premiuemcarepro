<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'uuid' => [
                'required',
                Rule::exists(User::class, 'uuid'),
            ],
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError('validation_error', $validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        /** @var User */
        $user = User::where('uuid',  $request->uuid)->first();

        if ($user) {
            if (! $user->active) {
                return $this->sendError('inactive_account', ['error' => 'user is inactive'], JsonResponse::HTTP_UNAUTHORIZED);
            }
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return $this->sendError('credentials_error', ['error' => 'There is an error credentials'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            $success['token'] = $user->createToken('MyAppToken')->plainTextToken;
            $success['user'] =  $user;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Handle password change request
     */
    public function changePassword(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return $this->sendError('validation_error', $validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        /** @var \App\Models\User $user */
        $user = $request->user(); // Get the authenticated user

        // Check if current password is correct
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return $this->sendError('credentials_error', ['error' => 'There is an error credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Update password
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return $this->sendResponse(true, 'Password changed successfully');
    }
}
