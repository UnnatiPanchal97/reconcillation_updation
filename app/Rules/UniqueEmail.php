<?php

namespace App\Rules;

use App\Models\MasterLogin;
use Illuminate\Contracts\Validation\Rule;

class UniqueEmail implements Rule
{
    private $email, $id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($email, $id = null)
    {
        $this->email = $email;
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $admins = MasterLogin::where('email', '=', $this->email)->first();
        if (!empty($this->id) && !empty($admins)) {
            //if data belongs to same user then ignore.
            $admins->child_id == $this->id ? $admins = null : $admins;
        }
        if ($admins === null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The email address is already in use.';
    }
}
