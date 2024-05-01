<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Interfaces\Auth\UserInterface;
use Exception;

class IsNotMerchant implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    private UserInterface $userRepositoryInterface;
    
    public function __construct(UserInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $user = $this->userRepositoryInterface->getById($value);
            if($user && $user->type === 'merchant'){
                $fail('The :attribute must be a merchant.');
            }
        } catch (Exception $e) {
            $fail('The selected :attribute is invalid.');
        }
    }
}
