<?php
namespace App\Http\Requests;
use App\Models\MasterLogin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UserStoreRequest extends FormRequest
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
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required','string', 'max:255'],
            'password' => ['required', 'string', 'min:9'],
            'email' => ['required', 'email', 'max:255', Rule::unique(MasterLogin::class)->ignore($this->user()->id)],
            'user_role' => ['required'],
            'status' => ['required'],
        ];
    }
}
