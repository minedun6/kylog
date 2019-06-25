<?php

namespace App\Http\Requests\Backend\Product;

use App\Http\Requests\Request;

class UpdateProductRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-product');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'supplier_id' => 'required|exists:companies,id',
            // 'reference' => 'required|unique:products,id,' . $this->get('id'),
            // 'supplier_reference' => 'required|unique:products,id,' . $this->get('id'),
            'reference' => 'required',
            'supplier_reference' => 'required',
            'designation' => 'required',
            'value' => 'numeric',
            'net_weight' => 'numeric',
            'brut_weight' => 'numeric',
            'piece' => '',
            'custom_attributes.*.key' => 'distinct',
            'custom_attributes.*.value' => 'distinct'
        ];
        if ( ! $this->has('piece')) {
            $rules['unit'] = 'required';
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'supplier_id.required' => 'The supplier is required.',
            'supplier_id.exists' => 'The Selected Supplier is invalid',
            'reference.required' => 'The reference is required.',
            'reference.unique' => 'This reference is already taken.',
            'supplier_reference.required' => 'The supplier reference is required.',
            'supplier_reference.unique' => 'This supplier reference is already taken.',
            'sap.required' => 'The SAP is required.',
            'designation.required' => 'The product designation is required',
            'value.numeric' => 'The product value needs to be a number.',
            'net_weight.numeric' => 'The product net weight needs to be a number.',
            'brut_weight.numeric' => 'The product gross weight needs to be a number.',
            'piece' => '',
            'unit.required' => 'The units field is required if "Count by Pieces ?" is not checked.',
            'custom_attributes.*.key.distinct' => 'The product custom attributes has a duplicate key.',
            'custom_attributes.*.value.distinct' => 'The product custom attributes has a duplicate value.'
        ];
    }
}
