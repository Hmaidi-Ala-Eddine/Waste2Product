<?php

namespace App\Validators;

use App\Models\User;
use App\Models\Collector;
use App\Helpers\TunisiaStates;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CollectorValidator
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
        
        if (isset($data['company_name'])) {
            $name = trim(strip_tags((string)$data['company_name']));
            $name = preg_replace('/[<>"\'&]/', '', $name);
            $name = preg_replace('/\s+/', ' ', $name);
            $sanitized['company_name'] = $name ?: null;
        }
        
        if (isset($data['vehicle_type'])) {
            $sanitized['vehicle_type'] = trim(strip_tags($data['vehicle_type']));
        }
        
        if (isset($data['service_areas'])) {
            if (is_array($data['service_areas'])) {
                // Sanitize array of selected governorates
                $areas = array_map(function($area) {
                    return trim(strip_tags((string)$area));
                }, $data['service_areas']);
                $areas = array_filter($areas);
                $sanitized['service_areas'] = array_values($areas);
            }
        }
        
        if (isset($data['capacity_kg'])) {
            $cap = preg_replace('/[^0-9.]/', '', (string)$data['capacity_kg']);
            $parts = explode('.', $cap);
            if (count($parts) > 2) {
                $cap = $parts[0] . '.' . implode('', array_slice($parts, 1));
            }
            if (isset($parts[1]) && strlen($parts[1]) > 2) {
                $cap = $parts[0] . '.' . substr($parts[1], 0, 2);
            }
            $sanitized['capacity_kg'] = $cap;
        }
        
        if (isset($data['availability_schedule'])) {
            if (is_string($data['availability_schedule'])) {
                $sanitized['availability_schedule'] = json_decode($data['availability_schedule'], true) ?? [];
            } elseif (is_array($data['availability_schedule'])) {
                $sanitized['availability_schedule'] = $data['availability_schedule'];
            }
        }
        
        if (isset($data['bio'])) {
            $bio = trim(strip_tags((string)$data['bio']));
            $bio = preg_replace('/[<>"\'&]/', '', $bio);
            $bio = preg_replace('/\s+/', ' ', $bio);
            $sanitized['bio'] = $bio ?: null;
        }
        
        if ($isUpdate && isset($data['verification_status'])) {
            $sanitized['verification_status'] = trim(strip_tags($data['verification_status']));
        }

        return $sanitized + $data; // preserve other keys if any
    }

    /**
     * Base rules and messages for store/update
     */
    public static function rules(?Collector $collector = null): array
    {
        // user_id removed - admin creates for themselves (set in controller)
        $rules = [
            'company_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\.\,\-\&]+$/'],
            'vehicle_type' => ['required', 'string', Rule::in(array_keys(Collector::getVehicleTypes()))],
            'service_areas' => ['required', 'array', 'min:1', 'max:24'],
            'service_areas.*' => ['string', Rule::in(TunisiaStates::getStateValues())],
            'capacity_kg' => ['required', 'numeric', 'min:1', 'max:99999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'availability_schedule' => ['nullable', 'array'],
            'bio' => ['nullable', 'string', 'max:1000', 'regex:/^[a-zA-Z0-9\s\.\,\-\!\?\(\)]*$/'],
        ];

        if ($collector) {
            $rules['verification_status'] = ['required', 'string', Rule::in(array_keys(Collector::getVerificationStatuses()))];
        }

        return $rules;
    }

    public static function messages(): array
    {
        return [
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'The selected user does not exist.',
            'user_id.unique' => 'This user already has a collector profile.',
            'company_name.max' => 'Company name cannot exceed 255 characters.',
            'company_name.regex' => 'Company name contains invalid characters.',
            'vehicle_type.required' => 'Please select a vehicle type.',
            'vehicle_type.in' => 'The selected vehicle type is invalid.',
            'service_areas.required' => 'Please select at least one governorate.',
            'service_areas.array' => 'Service areas must be selected.',
            'service_areas.min' => 'Please select at least one governorate.',
            'service_areas.max' => 'Cannot select more than 24 governorates.',
            'service_areas.*.in' => 'One or more selected governorates are invalid.',
            'capacity_kg.required' => 'Please enter the collection capacity.',
            'capacity_kg.numeric' => 'Capacity must be a valid number.',
            'capacity_kg.min' => 'Capacity must be at least 1 kg.',
            'capacity_kg.max' => 'Capacity cannot exceed 99,999.99 kg.',
            'capacity_kg.regex' => 'Capacity can have maximum 2 decimal places.',
            'bio.max' => 'Bio cannot exceed 1000 characters.',
            'bio.regex' => 'Bio contains invalid characters.',
            'verification_status.required' => 'Please select a verification status.',
            'verification_status.in' => 'The selected verification status is invalid.',
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
    public static function validateUpdate(array $data, Collector $collector)
    {
        return Validator::make($data, self::rules($collector), self::messages());
    }

    /**
     * Validate verification status update
     */
    public static function validateStatus(array $data)
    {
        return Validator::make($data, [
            'verification_status' => ['required', 'string', Rule::in(array_keys(Collector::getVerificationStatuses()))],
        ], [
            'verification_status.required' => 'Please select a verification status.',
            'verification_status.in' => 'The selected verification status is invalid.',
        ]);
    }

    /**
     * Validate frontend application (simplified for users)
     */
    public static function validateFrontendApplication(array $data, $userId)
    {
        return Validator::make($data, [
            'company_name' => ['nullable', 'string', 'max:255'],
            'vehicle_type' => ['required', 'string', Rule::in(array_keys(Collector::getVehicleTypes()))],
            'service_areas' => ['required', 'array', 'min:1', 'max:24'],
            'service_areas.*' => ['string', Rule::in(TunisiaStates::getStateValues())],
            'capacity_kg' => ['required', 'numeric', 'min:1', 'max:99999.99'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ], [
            'vehicle_type.required' => 'Please select your vehicle type.',
            'service_areas.required' => 'Please select at least one governorate.',
            'service_areas.min' => 'Please select at least one governorate where you provide service.',
            'service_areas.max' => 'Cannot select more than 24 governorates.',
            'service_areas.*.in' => 'One or more selected governorates are invalid.',
            'capacity_kg.required' => 'Please enter your collection capacity.',
            'capacity_kg.min' => 'Capacity must be at least 1 kg.',
        ]);
    }
}
