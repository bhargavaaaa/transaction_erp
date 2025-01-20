<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'work_order_number' => ['required', 'unique:orders,work_order_number,'.$this->order],
            'customer' => ['required', 'string', 'max:255'],
            'part_name' => ['required', 'string', 'max:255'],
            'metal' => ['required', 'string', 'max:255'],
            'size' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric'],
            'weight_per_pcs' => ['required', 'numeric'],
            'required_weight' => ['required', 'numeric'],
            'po_no' => ['required', 'string', 'max:255'],
            'po_date' => ['required', 'date'],
            'delivery_date' => ['required', 'date'],
            'remark' => ['nullable', 'string', 'max:255'],
            'updated_by' => ['required', 'numeric'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'updated_by' => getCurrentUserId()
        ]);
    }
}
