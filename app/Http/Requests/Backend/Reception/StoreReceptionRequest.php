<?php

namespace App\Http\Requests\Backend\Reception;

use App\Http\Requests\Request;

class StoreReceptionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-reception');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier' => 'required|exists:companies,id',
            'client' => 'required|exists:companies,id',
            'po' => '',
            'invoice_number' => 'required',
            'invoice_date' => 'required|date_format:"d-m-Y"',
            'reception_date' => 'date_format:"d-m-Y"',
            'planned_arrival_date' => 'date_format:"d-m-Y"',
            'status' => 'in:1,2',
            'returns' => '',
            'reservations' => 'min:5|max:255',
            'type' => 'in:1,2,3',
            'declaration_type' => 'max:255',
            'declaration_number' => 'max:255',
            'declaration_date' => 'date_format:"d-m-Y"',
            'container_number' => '',
            'driver' => 'max:255',
            'registration_number' => 'max:10',
            'other' => ''
        ];
    }

}
