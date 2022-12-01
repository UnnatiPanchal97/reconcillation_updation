<?php
namespace App\Http\Requests;
use App\Rules\UniqueEmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
class CompanyCreateRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $validationRule = [
            'company_name' => 'required|min:2|max:200',
            'firstname' => 'required|min:2|max:50',
            'lastname' => 'required|min:2|max:50',
            'email' => ['required', 'email', 'max:100', new UniqueEmail($request->email, '')],
            'password' => 'sometimes | confirmed | min:3',
        ];
        if ($request->id) {
            unset($validationRule['email']);
            if (empty($request->password)) {
                unset($validationRule['password']);
            }
        }
        return $validationRule;
    }
    public function messages($id = '')
    {
        return [
            'password . confirmed' => 'Password and confirm password don\'t match.',
            'email.unique' => 'The email address is already in use.',
            'firstname.required' => 'Please enter first name.',
            'firstname.min' => 'The first name must be at least 2 characters.',
            'lastname.required' => 'Please enter last name.',
            'lastname.min' => 'The last name must be at least 2 characters.',
            'email.required' => 'Please enter valid email address.',
        ];
    }
}
