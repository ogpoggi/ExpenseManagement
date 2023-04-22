<?php

namespace App\Models\Validators;

use App\Models\Company;
use Illuminate\Validation\Rule;

class CompanyValidator
{
    public function validate(Company $company, array $attributes): array
    {
        return validator($attributes,
            [
                'title' => [Rule::when($company->exists, 'sometimes'), 'required', 'string'],
                'description' => [Rule::when($company->exists, 'sometimes'), 'required', 'string'],
                'lat' => [Rule::when($company->exists, 'sometimes'), 'required', 'numeric'],
                'lng' => [Rule::when($company->exists, 'sometimes'), 'required', 'numeric'],
                'address_line1' => [Rule::when($company->exists, 'sometimes'), 'required', 'string'],
                'address_line2' => ['string'],
                'price_per_day' => [Rule::when($company->exists, 'sometimes'), 'required', 'integer', 'min:100'],


                'featured_image_id' => [Rule::exists('images', 'id')->where('resource_type', 'office')->where('resource_id', $company->id)],
                'hidden' => ['bool'],
                'monthly_discount' => ['integer', 'min:0', 'max:90'],

                'tags' => ['array'],
                'tags.*' => ['integer', Rule::exists('tags', 'id')]
            ]
        )->validate();
    }
}
