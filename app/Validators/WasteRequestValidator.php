<?php

namespace App\Validators;

use App\Models\User;
use App\Models\WasteRequest;
use App\Helpers\TunisiaStates;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WasteRequestValidator
{
    /**
     * Sanitize payload
     */
    public static function sanitize(array $data, bool $isUpdate = false): array
    {
        $sanitized = [];

        if (isset($data['user_id'])) {
            $sanitized['user_id'] = filter_var($data['user_id'], FILTER_VALIDATE_INT);
        }
        if (isset($data['waste_type'])) {
            $sanitized['waste_type'] = trim(strip_tags($data['waste_type']));
        }
        if (isset($data['quantity'])) {
            $q = preg_replace('/[^0-9.]/', '', (string)$data['quantity']);
            $parts = explode('.', $q);
            if (count($parts) > 2) {
                $q = $parts[0] . '.' . implode('', array_slice($parts, 1));
            }
            if (isset($parts[1]) && strlen($parts[1]) > 2) {
                $q = $parts[0] . '.' . substr($parts[1], 0, 2);
            }
            $sanitized['quantity'] = $q;
        }
        if (isset($data['state'])) {
            $sanitized['state'] = trim(strip_tags($data['state']));
        }
        if (isset($data['address'])) {
            $addr = trim(strip_tags((string)$data['address']));
            $addr = preg_replace('/[<>"\'&]/', '', $addr);
            $addr = preg_replace('/\s+/', ' ', $addr);
            $sanitized['address'] = $addr;
        }
        if (isset($data['description'])) {
            $desc = trim(strip_tags((string)$data['description']));
            $desc = preg_replace('/[<>"\'&]/', '', $desc);
            $desc = preg_replace('/\s+/', ' ', $desc);
            $sanitized['description'] = $desc ?: null;
        }
        if (isset($data['collector_id'])) {
            $sanitized['collector_id'] = $data['collector_id'] ? filter_var($data['collector_id'], FILTER_VALIDATE_INT) : null;
        }
        if ($isUpdate && isset($data['status'])) {
            $sanitized['status'] = trim(strip_tags($data['status']));
        }

        return $sanitized + $data; // preserve other keys if any
    }

    /**
     * Base rules and messages for store/update
     */
    public static function rules(?WasteRequest $wasteRequest = null): array
    {
        $rules = [
            'user_id' => [
                'required', 'integer', 'exists:users,id',
                function ($attr, $value, $fail) {
                    $user = User::find($value);
                    if ($user && $user->role === 'admin') {
                        $fail('Admin users cannot be assigned to waste requests.');
                    }
                    if ($user && !$user->is_active) {
                        $fail('Selected user is not active.');
                    }
                }
            ],
            'waste_type' => ['required', 'string', Rule::in(array_keys(WasteRequest::getWasteTypes()))],
            'quantity' => ['required', 'numeric', 'min:0.1', 'max:999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'state' => ['required', 'string', Rule::in(TunisiaStates::getStateValues())],
            'address' => ['required', 'string', 'min:10', 'max:1000', 'regex:/^[a-zA-Z0-9\s\.,\-\#\/]+$/'],
            'description' => ['nullable', 'string', 'max:2000', 'regex:/^[a-zA-Z0-9\s\.,\-\!\?\(\)]*$/'],
            'collector_id' => [
                'nullable', 'integer', 'exists:users,id',
                function ($attr, $value, $fail) {
                    if ($value) {
                        $collector = User::find($value);
                        if ($collector && $collector->role !== 'collector') {
                            $fail('Selected user is not a collector.');
                        }
                        if ($collector && !$collector->is_active) {
                            $fail('Selected collector is not active.');
                        }
                    }
                }
            ],
        ];

        if ($wasteRequest) {
            $rules['status'] = [
                'required', 'string', Rule::in(array_keys(WasteRequest::getStatuses())),
                function ($attr, $value, $fail) use ($wasteRequest) {
                    $current = $wasteRequest->status;
                    if ($current === 'collected' && $value !== 'collected') {
                        $fail('Cannot change status from collected to another status.');
                    }
                    if ($current === 'cancelled' && in_array($value, ['accepted', 'collected'])) {
                        $fail('Cannot change status from cancelled to ' . $value . '.');
                    }
                    if ($value === 'collected' && !$wasteRequest->collector_id) {
                        $fail('Cannot mark as collected without assigning a collector.');
                    }
                }
            ];
        }

        return $rules;
    }

    public static function messages(): array
    {
        return [
            'user_id.required' => 'Please select a customer.',
            'user_id.exists' => 'The selected customer does not exist.',
            'waste_type.required' => 'Please select a waste type.',
            'waste_type.in' => 'The selected waste type is invalid.',
            'quantity.required' => 'Please enter the quantity.',
            'quantity.numeric' => 'Quantity must be a valid number.',
            'quantity.min' => 'Quantity must be at least 0.1 kg.',
            'quantity.max' => 'Quantity cannot exceed 999,999.99 kg.',
            'quantity.regex' => 'Quantity can have maximum 2 decimal places.',
            'state.required' => 'Please select a governorate/state.',
            'state.in' => 'The selected governorate/state is invalid.',
            'address.required' => 'Please enter the pickup address.',
            'address.min' => 'Address must be at least 10 characters long.',
            'address.max' => 'Address cannot exceed 1000 characters.',
            'address.regex' => 'Address contains invalid characters.',
            'description.max' => 'Description cannot exceed 2000 characters.',
            'description.regex' => 'Description contains invalid characters.',
            'collector_id.exists' => 'The selected collector does not exist.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The selected status is invalid.',
        ];
    }

    /**
     * Validate for store
     */
    public static function validateStore(array $data)
    {
        return Validator::make($data, self::rules(), self::messages());
    }

    /**
     * Validate for update
     */
    public static function validateUpdate(array $data, WasteRequest $wasteRequest)
    {
        return Validator::make($data, self::rules($wasteRequest), self::messages());
    }

    /**
     * Validate assigning a collector
     */
    public static function validateAssign(array $data)
    {
        return Validator::make($data, [
            'collector_id' => ['required', 'integer', 'exists:users,id', function ($attr, $value, $fail) {
                $collector = User::find($value);
                if (!$collector || $collector->role !== 'collector') {
                    $fail('Selected user is not a collector.');
                }
                if ($collector && !$collector->is_active) {
                    $fail('Selected collector is not active.');
                }
            }],
        ], [
            'collector_id.required' => 'Please select a collector.',
            'collector_id.exists' => 'The selected collector does not exist.',
        ]);
    }

    /**
     * Validate updating status with business rules
     */
    public static function validateStatus(array $data, WasteRequest $wasteRequest)
    {
        return Validator::make($data, [
            'status' => ['required', 'string', Rule::in(array_keys(WasteRequest::getStatuses())), function ($attr, $value, $fail) use ($wasteRequest) {
                $current = $wasteRequest->status;
                if ($current === 'collected' && $value !== 'collected') {
                    $fail('Cannot change status from collected to another status.');
                }
                if ($current === 'cancelled' && in_array($value, ['accepted', 'collected'])) {
                    $fail('Cannot change status from cancelled to ' . $value . '.');
                }
                if ($value === 'collected' && !$wasteRequest->collector_id) {
                    $fail('Cannot mark as collected without assigning a collector.');
                }
            }]
        ], [
            'status.required' => 'Please select a status.',
            'status.in' => 'The selected status is invalid.',
        ]);
    }
}
