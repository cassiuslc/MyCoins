<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Interfaces\Auth\UserInterface;
use Exception;
class CheckUserExistence implements ValidationRule
{
    private UserInterface $userRepositoryInterface;
    
    public function __construct(UserInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $this->userRepositoryInterface->getById($value);
        } catch (Exception $e) {
            $fail('The selected :attribute is invalid.');
        }
    }
}
