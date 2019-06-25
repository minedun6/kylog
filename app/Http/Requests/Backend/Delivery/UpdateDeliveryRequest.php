<?php

namespace App\Http\Requests\Backend\Delivery;

use App\Http\Requests\Request;

class UpdateDeliveryRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-delivery');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier' => 'required|exists:companies,id|array',
            'client' => 'required|exists:companies,id',
            'delivery_order_date' => 'required|date_format:"d-m-Y"',
            'delivery_preparation_date' => 'required|date_format:"d-m-Y"',
            'final_destination' => 'required',
            'po' => '',
            'bl_date' => 'date_format:"d-m-Y"',
            'destination' => 'required|in:1,2,3',
            'delivery_outside_working_hours' => ''
        ];
    }
}
