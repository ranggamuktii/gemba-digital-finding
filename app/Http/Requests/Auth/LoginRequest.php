<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Check Mock LDAP (Employees table)
        $employee = \App\Models\Employee::where('username', $this->username)->first();

        if ($employee && \Illuminate\Support\Facades\Hash::check($this->password, $employee->password)) {
            // Mock LDAP: Sync employee to local users table
            $user = \App\Models\User::updateOrCreate(
                ['email' => $employee->email],
                [
                    'name' => $employee->name,
                    'password' => $employee->password,
                    'department_id' => $employee->department_id,
                ]
            );

            // Assign a default role if newly created or if we want to reset
            if (! $user->hasAnyRole(['Super Admin', 'Manager', 'PIC', 'Verifier', 'Viewer'])) {
                $user->assignRole('PIC'); // default fallback role for LDAP users
            }

            Auth::login($user, $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // 2. Check Local DB (Users table fallback for Super Admin / Seeded Users)
        // Check by email (if they typed email) or name
        $user = \App\Models\User::where('email', $this->username)->orWhere('name', $this->username)->first();
        if ($user && \Illuminate\Support\Facades\Hash::check($this->password, $user->password)) {
            Auth::login($user, $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // If both failed:
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('username')).'|'.$this->ip());
    }
}
