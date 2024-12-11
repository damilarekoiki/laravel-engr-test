<?php

namespace App\Http\Requests;

use App\Enums\ClaimPriorityEnum;
use App\Models\Insurer;
use App\Models\Provider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClaimRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation() {

        $this->merge([
            'insurer_id' => Insurer::where('code', $this->insurer_code)->first()?->id,
            'provider_id' => Provider::where('name', $this->provider_name)->first()?->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'insurer_id' => ['required'],
            'specialty_id' => ['required', 'exists:specialties,id'],
            'provider_id' => ['required'],
            'encounter_date' => ['required', 'date'],
            'priority_level' => ['required', Rule::enum(ClaimPriorityEnum::class)],
            'claim_items.*.name' => ['required'],
            'claim_items.*.unit_price' => ['required', 'numeric'],
            'claim_items.*.quantity' => ['required', 'numeric'],
        ];
    }
}
