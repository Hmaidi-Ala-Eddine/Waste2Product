<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Validators\ProductValidator;
use Illuminate\Http\Request;

class ProductValidationController extends Controller
{
    /**
     * Validate individual product field
     */
    public function validateField(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');
        $isUpdate = $request->boolean('is_update', false);
        
        if (!$field) {
            return response()->json([
                'valid' => false,
                'message' => 'Field name is required'
            ], 400);
        }
        
        $result = ProductValidator::validateField($field, $value, $isUpdate);
        
        return response()->json($result);
    }
    
    /**
     * Validate multiple fields at once
     */
    public function validateFields(Request $request)
    {
        $fields = $request->input('fields', []);
        $isUpdate = $request->boolean('is_update', false);
        
        $results = [];
        $allValid = true;
        
        foreach ($fields as $field => $value) {
            $result = ProductValidator::validateField($field, $value, $isUpdate);
            $results[$field] = $result;
            
            if (!$result['valid']) {
                $allValid = false;
            }
        }
        
        return response()->json([
            'valid' => $allValid,
            'fields' => $results
        ]);
    }
    
    /**
     * Get validation rules for a specific field
     */
    public function getFieldRules(Request $request)
    {
        $field = $request->input('field');
        
        if (!$field) {
            return response()->json([
                'error' => 'Field name is required'
            ], 400);
        }
        
        $rules = ProductValidator::getFieldRules($field);
        
        return response()->json([
            'field' => $field,
            'rules' => $rules
        ]);
    }
    
    /**
     * Sanitize field value
     */
    public function sanitizeField(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');
        
        if (!$field) {
            return response()->json([
                'error' => 'Field name is required'
            ], 400);
        }
        
        $sanitized = ProductValidator::sanitize([$field => $value]);
        
        return response()->json([
            'field' => $field,
            'original' => $value,
            'sanitized' => $sanitized[$field] ?? $value
        ]);
    }
}

