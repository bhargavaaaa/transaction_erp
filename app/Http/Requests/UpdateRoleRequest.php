<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', "unique:roles,name,{$this->role},id"],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['required', 'exists:permissions,id']
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => getCurrentUserId(),
            'updated_by' => auth()->user()->id,
            'status' => $this->boolean('status')
        ]);
    }
}
