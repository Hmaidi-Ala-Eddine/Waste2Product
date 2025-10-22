<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductValidator
{
    /**
     * Validation rules for product creation
     */
    public static function getCreateRules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-Z0-9\s\-_.,!?()]+$/'
            ],
            'description' => [
                'nullable',
                'string',
                'min:10',
                'max:2000'
            ],
            'category' => [
                'required',
                'string',
                Rule::in(['furniture', 'electronics', 'plastic', 'textile', 'metal', 'organic', 'glass', 'paper'])
            ],
            'condition' => [
                'nullable',
                'string',
                Rule::in(['excellent', 'good', 'fair', 'poor'])
            ],
            'price' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'stock' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999'
            ],
            'status' => [
                'required',
                'string',
                Rule::in(['available', 'sold', 'donated', 'reserved', 'removed'])
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'
            ]
        ];
    }

    /**
     * Validation rules for product update
     */
    public static function getUpdateRules($productId = null)
    {
        $rules = self::getCreateRules();
        
        // For updates, make some fields optional
        $rules['name'][0] = 'sometimes';
        $rules['category'][0] = 'sometimes';
        $rules['status'][0] = 'sometimes';
        
        return $rules;
    }

    /**
     * Custom validation messages
     */
    public static function getMessages()
    {
        return [
            'name.required' => 'Le nom du produit est obligatoire.',
            'name.min' => 'Le nom du produit doit contenir au moins 3 caractères.',
            'name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',
            'name.regex' => 'Le nom du produit contient des caractères non autorisés.',
            
            'description.min' => 'La description doit contenir au moins 10 caractères.',
            'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
            
            'category.required' => 'La catégorie est obligatoire.',
            'category.in' => 'La catégorie sélectionnée n\'est pas valide.',
            
            'condition.in' => 'L\'état sélectionné n\'est pas valide.',
            
            'price.numeric' => 'Le prix doit être un nombre valide.',
            'price.min' => 'Le prix ne peut pas être négatif.',
            'price.max' => 'Le prix ne peut pas dépasser 999,999.99 DT.',
            'price.regex' => 'Le prix doit avoir au maximum 2 décimales.',
            
            'stock.integer' => 'Le stock doit être un nombre entier.',
            'stock.min' => 'Le stock ne peut pas être négatif.',
            'stock.max' => 'Le stock ne peut pas dépasser 9999.',
            
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
            
            'image.image' => 'Le fichier doit être une image valide.',
            'image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG, GIF ou WebP.',
            'image.max' => 'L\'image ne peut pas dépasser 2MB.',
            'image.dimensions' => 'L\'image doit faire entre 100x100 et 4000x4000 pixels.'
        ];
    }

    /**
     * Validate product data for creation
     */
    public static function validateCreate($data)
    {
        return Validator::make($data, self::getCreateRules(), self::getMessages());
    }

    /**
     * Validate product data for update
     */
    public static function validateUpdate($data, $productId = null)
    {
        return Validator::make($data, self::getUpdateRules($productId), self::getMessages());
    }

    /**
     * Validate individual field
     */
    public static function validateField($field, $value, $isUpdate = false)
    {
        $rules = $isUpdate ? self::getUpdateRules() : self::getCreateRules();
        $fieldRules = $rules[$field] ?? [];
        
        if (empty($fieldRules)) {
            return ['valid' => true, 'message' => ''];
        }

        $validator = Validator::make([$field => $value], [$field => $fieldRules], self::getMessages());
        
        if ($validator->fails()) {
            return [
                'valid' => false,
                'message' => $validator->errors()->first($field)
            ];
        }

        return ['valid' => true, 'message' => ''];
    }

    /**
     * Get field-specific validation rules for frontend
     */
    public static function getFieldRules($field)
    {
        $rules = self::getCreateRules();
        return $rules[$field] ?? [];
    }

    /**
     * Sanitize product data
     */
    public static function sanitize($data)
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'name':
                    $sanitized[$key] = trim(strip_tags($value));
                    break;
                case 'description':
                    $sanitized[$key] = trim($value);
                    break;
                case 'price':
                    $sanitized[$key] = is_numeric($value) ? round((float)$value, 2) : null;
                    break;
                case 'stock':
                    $sanitized[$key] = is_numeric($value) ? (int)$value : null;
                    break;
                default:
                    $sanitized[$key] = $value;
                    break;
            }
        }
        
        return $sanitized;
    }
}

