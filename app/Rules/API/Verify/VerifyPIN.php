<?php

declare(strict_types=1);

namespace App\Rules\API\Verify;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class VerifyPIN implements Rule
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function passes($attribute, $value): bool
    {
        if (! $this->user->pin) {
            return false;
        }

        if (\Hash::check($value, $this->user->pin)) {
            return true;
        }

        return false;
    }

    public function message()
    {
        return trans('messages.pin_does_not_matched');
    }
}
