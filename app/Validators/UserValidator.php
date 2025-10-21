<?php

namespace App\Validators;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserValidator
{
    /**
     * Sanitize payload
     */
    public static function sanitize(array $data, bool $isUpdate = false): array
    {
        $sanitized = [];

        if (isset($data['name'])) {
            $name = trim(strip_tags((string)$data['name']));
            $name = preg_replace('/[<>"\'&]/', '', $name);
            $name = preg_replace('/\s+/', ' ', $name);
            $sanitized['name'] = $name;
        }

        if (isset($data['email'])) {
            $sanitized['email'] = strtolower(trim((string)$data['email']));
        }

        if (!$isUpdate && isset($data['password'])) {
            $sanitized['password'] = $data['password'];
        }

        if ($isUpdate && isset($data['password']) && !empty($data['password'])) {
            $sanitized['password'] = $data['password'];
        }

        if (isset($data['role'])) {
            $sanitized['role'] = trim(strip_tags($data['role']));
        }

        if (isset($data['is_active'])) {
            $sanitized['is_active'] = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN);
        }

        return $sanitized + $data;
    }

    /**
     * Validation rules for user registration (frontend)
     */
    public static function registrationRules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-Z\s\-\'\.]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'password_confirmation' => ['required', 'same:password'],
        ];
    }

    /**
     * Validation rules for user login
     */
    public static function loginRules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    /**
     * Validation rules for admin creating/updating users
     */
    public static function adminRules(?User $user = null): array
    {
        $emailRule = $user
            ? ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)]
            : ['required', 'string', 'email', 'max:255', 'unique:users,email'];

        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-Z\s\-\'\.]+$/'],
            'email' => $emailRule,
            'role' => ['required', 'string', Rule::in(['admin', 'user'])],
            'is_active' => ['sometimes', 'boolean'],
        ];

        // Password is required for new users, optional for updates
        if (!$user) {
            $rules['password'] = ['required', 'string', 'min:8', 'max:255', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8', 'max:255', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'];
        }

        return $rules;
    }

    /**
     * Custom validation messages
     */
    public static function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'name.min' => 'Name must be at least 2 characters long.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'name.regex' => 'Name can only contain letters, spaces, hyphens, apostrophes, and periods.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'email.exists' => 'No account found with this email address.',
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'password_confirmation.required' => 'Please confirm your password.',
            'password_confirmation.same' => 'Password confirmation does not match.',
            'role.required' => 'Please select a user role.',
            'role.in' => 'The selected role is invalid.',
        ];
    }

    /**
     * Validate user registration
     */
    public static function validateRegistration(array $data)
    {
        return Validator::make($data, self::registrationRules(), self::messages());
    }

    /**
     * Validate user login
     */
    public static function validateLogin(array $data)
    {
        return Validator::make($data, self::loginRules(), self::messages());
    }

    /**
     * Validate admin user creation/update
     */
    public static function validateAdmin(array $data, ?User $user = null)
    {
        return Validator::make($data, self::adminRules($user), self::messages());
    }
}
