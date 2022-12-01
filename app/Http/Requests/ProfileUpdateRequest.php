<?php
namespace App\Http\Requests;
use App\Models\MasterLogin;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class ProfileUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'company_name' => ['string', 'max:255'],
            'avatar' => ['image','mimes:png,jpg,jpeg'],
            'firstname' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],
            'phone' => ['string', 'max:15'],
            'email' => ['email', 'max:255', Rule::unique(MasterLogin::class)->ignore($this->user()->id)],
        ];
    }
}
