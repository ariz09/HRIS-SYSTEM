<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    protected $userData;

    public function __construct($userData = [])
    {
        $this->userData = $userData;
    }

    public function passes($attribute, $value)
    {
        // 1. Minimum 8 characters
        if (strlen($value) < 8) return false;
        // 2. Uppercase
        if (!preg_match('/[A-Z]/', $value)) return false;
        // 3. Lowercase
        if (!preg_match('/[a-z]/', $value)) return false;
        // 4. Digit
        if (!preg_match('/[0-9]/', $value)) return false;
        // 5. Special character
        if (!preg_match('/[\W_]/', $value)) return false;
        // 6. No common words
        $common = ['password', '123456', 'admin'];
        foreach ($common as $word) {
            if (stripos($value, $word) !== false) return false;
        }
        // 7. No username/email/birthdate
        foreach ($this->userData as $data) {
            if ($data && stripos($value, $data) !== false) return false;
        }
        return true;
    }

    public function message()
    {
        return 'The password must be at least 8 characters, contain upper and lower case letters, a number, a special character, and must not contain common words or your personal info.';
    }
}
