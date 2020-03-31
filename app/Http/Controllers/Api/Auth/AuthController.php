<?php

namespace App\Http\Controllers\Api\Auth;

use App\Domain\Services\AuthService;
use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public $service;

    public function __construct(AuthService $authService)
    {
        $this->service = $authService;

    }

    /**
     * @param Request $request
     * @return AuthResource|\Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function postSignIn(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required',
            'remember_me' => 'boolean'
        ]);

        try {
            $userWithToken = $this->service->signIn($request->all());
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => [
                    'email' => ['Invalid Credentials']
                ]
            ], 200);
        }

        return new AuthResource($userWithToken);
    }

    /**
     * @param Request $request
     * @return AuthResource
     * @throws ValidationException
     */
    public function postSignUp(Request $request)
    {
        $validated = $this->validate($request, [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
            'birthday' => ['required', 'date', 'before:today'],
            'gender' => ['required'],
        ]);

        $validated['type'] = 2;
        $user = $this->service->signUp($validated);

        return new AuthResource($user);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * @param Request $request
     * @return AuthResource
     */
    public function getUser(Request $request)
    {
        $user = $request->user();

        return new AuthResource($user);
    }

    /**
     * Create token password reset
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function postForgotPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
        ]);

        try {
            $this->service->createResetPassword($request->all());
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => [
                    'email' => [$e->getMessage()]
                ]
            ], 422);
        }

        return response()->json([
            'message' => 'We have e-mailed your password reset link!'
        ]);
    }

    /**
     * Reset
     *
     * @param Request $request
     * @return AuthResource|\Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function postResetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'min:5', 'exists:users'],
            'password' => ['required', 'min:6', 'confirmed'],
            'token' =>  ['required', 'string'],
        ]);

        try {
            $user = $this->service->resetPassword($request->all());
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => [
                    'email' => [$e->getMessage()]
                ]
            ], 422);
        }

        return response()->json([
            'message' => 'Password has been reset successfully.'
        ]);
    }

    public function postVerification(Request $request)
    {
        try {
            $this->service->verification($request->get('token'));
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => [
                    'email' => [$e->getMessage()]
                ]
            ], 422);
        }

        return response()->json([
            'message' => 'Account Verified!'
        ]);
    }

    public function postResendVerification(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'min:5', 'exists:users'],
        ]);

        $this->service->createOrSendVerification($request->input('email', null));

        return response()->json([
            'message' => 'We have e-mailed your verification link!'
        ]);
    }

}
