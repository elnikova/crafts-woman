<?php

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleCallbackRequest;
use App\Http\Requests\Auth\GoogleRedirectRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SocialLoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialUser;
use Throwable;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): UserResource
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => Role::Buyer,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return UserResource::make($user);
    }

    public function login(LoginRequest $request): UserResource|JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 422);
        }

        $request->session()->regenerate();

        return UserResource::make($request->user());
    }

    public function me(Request $request): UserResource
    {
        return UserResource::make($request->user());
    }

    public function socialLogin(SocialLoginRequest $request): UserResource|JsonResponse
    {
        $validated = $request->validated();

        try {
            $socialUser = Socialite::driver($validated['provider'])
                ->stateless()
                ->userFromToken($validated['access_token']);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => 'Unable to authenticate with provider.',
            ], 422);
        }

        if (!$socialUser->getEmail()) {
            return response()->json([
                'message' => 'Provider account does not have an email address.',
            ], 422);
        }

        $user = $this->findOrCreateSocialUser($socialUser, $validated['provider']);

        Auth::login($user);
        $request->session()->regenerate();

        return UserResource::make($user);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink($request->validated());

        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => __($status),
            ], 422);
        }

        return response()->json([
            'message' => __($status),
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->validated(),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json([
                'message' => __($status),
            ], 422);
        }

        return response()->json([
            'message' => __($status),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out.']);
    }

    public function googleRedirect(GoogleRedirectRequest $request): JsonResponse
    {
        $driver = Socialite::driver('google')->stateless();

        if ($request->filled('redirect_uri')) {
            $driver->redirectUrl($request->string('redirect_uri')->toString());
        }

        $url = $driver->redirect()->getTargetUrl();

        return response()->json(['url' => $url]);
    }

    public function googleCallback(GoogleCallbackRequest $request): UserResource|JsonResponse
    {
        if ($request->filled('error')) {
            return response()->json([
                'message' => $request->string('error')->toString(),
            ], 422);
        }

        try {
            $socialUser = Socialite::driver('google')->stateless()->user();
        } catch (Throwable $exception) {
            return response()->json([
                'message' => 'Unable to authenticate with Google.',
            ], 422);
        }

        if (!$socialUser->getEmail()) {
            return response()->json([
                'message' => 'Google account does not have an email address.',
            ], 422);
        }

        $user = $this->findOrCreateSocialUser($socialUser, 'google');

        Auth::login($user);
        $request->session()->regenerate();

        return UserResource::make($user);
    }

    private function findOrCreateSocialUser(SocialUser $socialUser, string $provider): User
    {
        $user = User::query()
            ->where('oauth_provider', $provider)
            ->where('oauth_provider_id', $socialUser->getId())
            ->first();

        if (!$user) {
            $email = $socialUser->getEmail();
            if ($email) {
                $user = User::where('email', $email)->first();
            }
        }

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: 'User',
                'email' => $socialUser->getEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(32)),
                'role' => Role::Buyer,
                'oauth_provider' => $provider,
                'oauth_provider_id' => $socialUser->getId(),
            ]);
        } else {
            $user->forceFill([
                'oauth_provider' => $provider,
                'oauth_provider_id' => $socialUser->getId(),
            ])->save();
        }

        return $user;
    }
}
