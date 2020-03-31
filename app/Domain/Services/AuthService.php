<?php

namespace App\Domain\Services;

use App\Domain\Models\PasswordReset;
use App\Domain\Models\User;
use App\Domain\Models\VerifyUser;
use App\Mail\VerifyMail;
use App\Notifications\PasswordResetRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Passport;

class AuthService
{
    public function signIn(array $inputs)
    {
        $user = User::query()
            ->whereNotNull('email_verified_at')
            ->where('email', $inputs['email'])
            ->first();

        if ($user && Hash::check($inputs['password'], $user->password)) {

            if (array_key_exists('remember_me', $inputs) && $inputs['remember_me']) {
                Passport::refreshTokensExpireIn(now()->addDay(30));
            }

            $user->last_login_at = now();
            $user->save();

            $tokenResult = $user->createToken($user->email);
            $user->tokenResult = $tokenResult;

            return $user;
        }

        throw ValidationException::withMessages(['message' => 'Invalid Credentials.']);
    }

    /**
     * @param array $inputs
     * @return User
     */
    public function signUp(array $inputs)
    {
        $user = new User();
        $user->first_name = $inputs['first_name'];
        $user->last_name = $inputs['last_name'];
        $user->birthday = $inputs['birthday'];
        $user->gender = $inputs['gender'];
        $user->email = $inputs['email'];
        $user->password = bcrypt($inputs['password']);
        $user->type = 2;
        $user->email_verified_at = null;
        $user->save();

        if (env('APP_ENV') !== 'production' && File::exists(app_path('./../front/public/settings.json'))) {
            $data = json_decode(File::get(app_path('./../front/public/settings.json')), true);
            if (isset($data['user_has_to_verify_email_after_register']) && $data['user_has_to_verify_email_after_register'] === 'no') {
                $user->email_verified_at = now();
                $user->save();
            }
        } else if (env('APP_ENV') === 'production' && File::exists(public_path('settings.json'))) {
            $data = json_decode(File::get(public_path('settings.json')), true);
            if (isset($data['user_has_to_verify_email_after_register']) && $data['user_has_to_verify_email_after_register'] === 'no') {
                $user->email_verified_at = now();
                $user->save();
            }
        }

        if (!$user->email_verified_at) {
            $this->createOrSendVerification($user->email);
        }

        return $user;
    }

    /**
     * @param $userEmail
     * @return mixed|string
     */
    public function createOrSendVerification($userEmail)
    {
        $verifyUser = VerifyUser::whereEmail($userEmail)->latest()->first();

        if ($verifyUser) {
            if (Carbon::parse($verifyUser->updated_at)->addMinutes(720)->isPast()) {
                $verifyUser->token = Str::random(70);
                $verifyUser->update();
            }
        } else {
            VerifyUser::whereEmail($userEmail)->delete();
            $verifyUser = new VerifyUser();
            $verifyUser->email = $userEmail;
            $verifyUser->token = Str::random(70);
            $verifyUser->save();
        }

        \Mail::to($userEmail)->send(new VerifyMail($verifyUser->token));
        return $verifyUser->token;
    }

    /**
     * @param array $inputs
     * @return mixed
     * @throws ValidationException
     */
    public function createResetPassword(array $inputs)
    {
        $user = User::where('email', $inputs['email'])->first();

        if (!$user)
            throw ValidationException::withMessages(['message' => 'Credentials does not match with our portal.']);

        $passwordReset = PasswordReset::query()->where('email', $user->email)->first();

        if (!$passwordReset) {
            $passwordReset = new PasswordReset();
            $passwordReset->email = $user->email;
        }

        $passwordReset->token = Str::random(60);
        $passwordReset->created_at = now();
        $passwordReset->save();

        $user->notify(
            new PasswordResetRequest($passwordReset->token)
        );

        return $user;
    }

    /**
     * @param array $inputs
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws ValidationException
     */
    public function resetPassword(array $inputs)
    {
        $passwordReset = PasswordReset::where([
            'token' => $inputs['token'],
            'email' => $inputs['email']
        ])->first();

        if (!$passwordReset)
            throw ValidationException::withMessages(['message' => 'Credentials does not match with our portal.']);

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            throw ValidationException::withMessages(['message' => 'This password reset token is invalid.']);
        }

        $user = User::query()->where('email', $passwordReset->email)->first();

        if (!$user)
            throw ValidationException::withMessages(['message' => 'Credentials does not match with our portal.']);

        $user->email_verified_at = (!$user->email_verified_at) ? now() : $user->email_verified_at;
        $user->password = bcrypt($inputs['password']);
        $user->save();

        PasswordReset::where([
            'token' => $inputs['token'],
            'email' => $inputs['email']
        ])->delete();

//        $user->notify(new PasswordResetSuccess());

        return $user;
    }

    /**
     * @param $token
     * @return bool
     * @throws ValidationException
     */
    public function verification($token)
    {
        $verifyUser = VerifyUser::whereToken($token)->first();

        if (!$verifyUser || ($verifyUser && Carbon::parse($verifyUser->updated_at)->addMinutes(720)->isPast()))
            throw ValidationException::withMessages(['message' => 'Verification link has expired.']);

        $user = User::whereEmail($verifyUser->email)->first();
        $user->email_verified_at = now();
        $user->save();

        VerifyUser::whereToken($token)->delete();
        return true;
    }
}
