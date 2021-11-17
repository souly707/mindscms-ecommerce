<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST': {
                    return [
                        'name' => 'required|string|max:255',
                        'description' => 'required|string',
                        'price' => 'required|numeric',
                        'quantity' => 'required|numeric',
                        'product_category_id' => 'required|numeric',
                        'tags.*' => 'required',
                        'featured' => 'required',
                        'status' => 'required',
                        'images' => 'required',
                        'images.*' => 'mimes:png,jpg,jpeg,gif|max:3000'
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'name' => 'required|string|max:255',
                        'description' => 'required|string',
                        'price' => 'required|numeric',
                        'quantity' => 'required|numeric',
                        'product_category_id' => 'required|numeric',
                        'tags.*' => 'required',
                        'featured' => 'required',
                        'status' => 'required',
                        'images' => 'nullable',
                        'images.*' => 'mimes:png,jpg,jpeg,gif|max:3000'
                    ];
                }
            default:
                break;
        }
    }
}