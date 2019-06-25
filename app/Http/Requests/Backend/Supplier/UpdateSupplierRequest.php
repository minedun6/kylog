<?php

namespace App\Http\Requests\Backend\Supplier;

use App\Http\Requests\Request;

class UpdateSupplierRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-supplier');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:companies,id,' . $this->get('id'),
            'address' => 'max:255',
            'trn' => 'numeric',
            'customs' => 'numeric',
            'comment' => 'max:255',
            'logo' => ''
        ];
    }
}
